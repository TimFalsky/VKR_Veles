<?php

namespace App\Orchid\Layouts;

use App\Models\Car;
use App\Models\Detail;
use App\Models\Operation;
use App\Models\Service;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Listener;
use Orchid\Screen\Repository;
use Orchid\Screen\Sight;
use Orchid\Screen\TD;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class OrderListener extends Listener
{
    /**
     * Список имен полей, значения которых будут отслеживаться.
     *
     * @var string[]
     */
    protected $targets = [
        'order.car_id',
        'order.services.',
    ];

    /**
     * @return Layout[]
     */
    protected function layouts(): iterable
    {
        $order = $this->query->get('order');
        is_null(data_get($order, 'car_id')) && $this->query->set('order', ['car_id' => $this->query->get('car.id')]);
        return [
                Layout::rows([
                    Input::make('car.id')->type('hidden'),
                    Relation::make('order.car_id')
                        ->required()
                        ->title('Машина')
                        ->fromModel(Car::class, 'car_brand', 'id')
                        ->searchColumns('car_brand', 'car_model')
                        ->displayAppend('name'),
                    Relation::make('order.services.')
                        ->title('Услуга')
                        ->fromModel(Service::class, 'name')
                        ->searchColumns('name')
                        ->applyScope('filterServiceByCar', $this->query->get('order.car_id'))
                        ->multiple(),
                    Input::make('order.vin_code')
                        ->title('VIN код'),
                    Input::make('order.gos_number')
                        ->title('Гос номер'),
                    Input::make('order.mileage')
                        ->title('Пробег')
                        ->type('number'),
                    Input::make('order.total_cost')
                        ->type('hidden'),
                    Input::make('order.details_cost')
                        ->type('hidden'),
                    Input::make('order.operations_cost')
                        ->type('hidden'),
                    Button::make('Создать заказ наряд')
                        ->type(Color::PRIMARY)
                        ->method('createOrder'),
                ]),
//            ])->ratio('60/40'),
            Layout::view('orchid.order'),
            Layout::table('details', [
                TD::make('id')->sort(),
                TD::make('name', 'Наименование детали'),
                TD::make('article', 'Артикль'),
                TD::make('type_units', 'Ед.изм.'),
                TD::make('pivot.quantity', 'Количество'),
                TD::make('price', 'Цена'),
                TD::make('car', 'Машина')
                    ->render(function (Detail $detail) {
                        return "{$detail->car->car_brand}<br/>{$detail->car->car_model}";
                    }),
                TD::make('created_at', 'Дата создания'),
            ]),
            Layout::table('operations', [
                TD::make('id', '№')->sort(),
                TD::make('refOperation.name', 'Название операции'),
                TD::make('index', 'Коэффициент')->sort(),
                TD::make('refOperation.price', 'Стоймость услуги')->render(function (Operation $operation) {
                    return $operation->refOperation->price ?? "Н.Ч. " . config('app.default_price');
                }),
                TD::make('car', 'Машина')
                    ->render(function (Operation $operation) {
                        return "{$operation->car->car_brand}<br/>{$operation->car->car_model}";
                    }),
                TD::make('created_at')->sort(),
            ]),
        ];
    }

    /**
     * @param \Orchid\Screen\Repository $repository
     * @param \Illuminate\Http\Request $request
     *
     * @return \Orchid\Screen\Repository
     * @throws \Exception
     */
    public function handle(Repository $repository, Request $request): Repository
    {
        $order = $request->input('order');
        $prevCarId = $request->input('car.id');
        $carId = data_get($order, 'car_id');
        if (!is_null($carId)) {
            $repository->set('car', Car::query()->find($carId));
        }
        if ($prevCarId !== $carId) {
            $order['services'] = [];
        }
        $serviceIds = data_get($order, 'services');
        if (!is_null($serviceIds)) {
            try {
                $services = Service::query()->whereIn('id', $serviceIds)->get();
                $details = $services->reduce(function ($detailsCollection, Service $service) {
                    return $detailsCollection->merge($service->details()->withPivot('quantity')->get());
                }, new Collection([]));
                $operations = Operation::query()->with('refOperation')->whereHas('services', function ($query) use ($serviceIds) {
                    $query->whereIn('id', $serviceIds);
                })->get();

                $repository->set('services', $services);
                $repository->set('details', $details);
                $repository->set('operations', $operations);
                $order['details_cost'] = $details->reduce(function ($result, Detail $detail) use ($serviceIds) {
                    $quantity = $detail->pivot->quantity;
                    $result += $detail->price * $detail->pivot->quantity;
                    return $result;
                }, 0);
                $order['operations_cost'] = $operations->reduce(function ($result, Operation $operation) {
                    $result += is_null($operation->refOperation->price)
                        ? $operation->index * config('app.default_price')
                        : $operation->refOperation->price * $operation->index;
                    return $result;
                }, 0);
                $order['total_cost'] = $order['operations_cost'] + $order['details_cost'];
            } catch (\Exception $exception) {
                throw new $exception;
            }
        }

        return $repository
            ->set('order', $order);
    }
}
