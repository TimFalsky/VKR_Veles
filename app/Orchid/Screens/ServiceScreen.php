<?php

namespace App\Orchid\Screens;

use App\Models\Car;
use App\Models\Detail;
use App\Models\Operation;
use App\Models\Service;
use App\Orchid\Filters\ServiceFilter;
use App\Orchid\Layouts\ServiceModalListener;
use App\Traits\ModalTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ServiceScreen extends Screen
{
    use ModalTrait;

    public $car_id = null;
    const MODAL_NAME = 'serviceModal';

    public function permission(): ?iterable
    {
        return [
            'services',
        ];
    }

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'services' => Service::query()
                ->filters([ServiceFilter::class])
                ->with([
                    'operations',
                    'operations.refOperation',
                    'details',
                ])
                ->defaultSort('id', 'desc')
                ->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Услуги';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            $this->generateCreateBtn(self::MODAL_NAME),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::modal(
                self::MODAL_NAME,
                [
                    Layout::rows([
                        Relation::make('service.car_id')
                            ->title('Машина')
                            ->fromModel(Car::class, 'car_brand', 'id')
                            ->displayAppend('name')
                            ->searchColumns('car_brand', 'car_model'),
                        Input::make('service.name')
                            ->title('Наименование Услуги')
                            ->required(),
                    ]),
                    ServiceModalListener::class
                ]
            )
                ->title('Создать')
                ->async('asyncData')
                ->closeButton('Отменить')
                ->applyButton('Сохранить'),
            Layout::selection([
                ServiceFilter::class,
            ]),
            Layout::table('services', [
                TD::make('id')->class('align-top')->sort(),
                TD::make('name', 'Наименование услуги')->class('align-top'),
                TD::make('operations', 'Операции')->class('align-top')
                    ->render(function (Service $service) {
                        $table = '<ul class="list-group list-group-flush">%s</ul>';
                        $rows = [];
                        $tr = '<li class="list-group-item p-1" style="font-size: 9px">%s</li>';
                        foreach ($service->operations as $operation) {
                            $rows[] = sprintf($tr, $operation->refOperation->name);
                        }
                        $rowsString = implode("\n", $rows);
                        return sprintf($table, $rowsString);
                    }),
                TD::make('details', 'Детали')->class('align-top')
                    ->render(function (Service $service) {
                        $table = '<ul class="list-group list-group-flush">%s</ul>';
                        $rows = [];
                        $tr = '<li class="list-group-item p-1" style="font-size: 9px">%s</li>';
                        foreach ($service->details as $detail) {
                            $rows[] = sprintf($tr, "$detail->name кол: {$detail->pivot->quantity}");
                        }
                        $rowsString = implode("\n", $rows);
                        return sprintf($table, $rowsString);
                    }),
                TD::make('car', 'Машина')->class('align-top')
                    ->render(function (Service $service) {
                        return "{$service->car->car_brand}<br/>{$service->car->car_model}";
                    }),
                TD::make('created_at', 'Дата создания')->class('align-top'),
                TD::make('Действия')->class('align-top')
                    ->alignRight()
                    ->render(fn(Service $service) => $this->generateEditBtn(
                        self::MODAL_NAME,
                        ['service' => $service->id]
                    )),
            ]),
        ];
    }

    public function asyncData(Service $service): array
    {
        $service->load('operations', 'details');
        return [
            'service' => $service,
        ];
    }


    /**
     * @throws \Exception
     */
    public function createOrUpdate(Service $service, Request $request): RedirectResponse
    {
        $data = $request->input('service');

        DB::beginTransaction();
        try {
            $service->fill($data);
            $service->save();
            $service->operations()->sync(data_get($data, 'operations', []));
            $service->details()->sync(data_get($data, 'detailsPivot', []));
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage(), $exception->getCode(), $exception);
        } finally {
            DB::commit();
        }

        Toast::info('Услуга успешно сохранена');

        return redirect()->route('platform.services');
    }
}
