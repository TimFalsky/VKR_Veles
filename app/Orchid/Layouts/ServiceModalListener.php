<?php

namespace App\Orchid\Layouts;

use App\Models\Car;
use App\Models\Detail;
use App\Models\Operation;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Listener;
use Orchid\Screen\Repository;
use Orchid\Support\Facades\Layout;

class ServiceModalListener extends Listener
{
    /**
     * Список имен полей, значения которых будут отслеживаться.
     *
     * @var string[]
     */
    protected $targets = [
        'service.car_id',
    ];

    /**
     * @return Layout[]
     */
    protected function layouts(): iterable
    {
        $aa = $this->query->get('service');
        return [
            Layout::rows([
                Select::make('service.operations.')
                    ->fromQuery(Operation::query()->selectRaw("operations.*, operations.id as operations_id, operations.id || ' ' || ro.name as truename")
                        ->join('reference_operations as ro', 'ro.id','=', 'operations.ref_operation_id')
                        ->where('car_id', $this->query->get('service.car_id', 1)),
                        'truename',
                        'operations_id'
                    )
                    ->multiple()
                    ->title('Операции'),
                Matrix::make('service.detailsPivot')
                    ->title('Детали и количество')
                    ->columns([
                        'Деталь' => 'detail_id',
                        'Количество' => 'quantity',
                    ])
                    ->fields([
                        'detail_id' => Select::make()
                            ->fromQuery(Detail::query()->where('car_id', $this->query->get('service.car_id')), 'name'),
                        'quantity' => Input::make()->type('number')->min(1),
                    ]),
            ])
        ];
    }

    /**
     * @param \Orchid\Screen\Repository $repository
     * @param \Illuminate\Http\Request  $request
     *
     * @return \Orchid\Screen\Repository
     */
    public function handle(Repository $repository, Request $request): Repository
    {
        $service = $request->input('service');
        $service['detailsPivot'] = [];
        $service['operations'] = [];

        return $repository->set('service', $service);
    }
}
