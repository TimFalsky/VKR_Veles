<?php

namespace App\Orchid\Screens;

use App\Models\Car;
use App\Models\Operation;
use App\Models\ReferenceOperation;
use App\Orchid\Filters\RefOperationNameFilter;
use App\Traits\ModalTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class OperationScreen extends Screen
{
    use ModalTrait;
    const MODAL_NAME = 'operationModal';

    public function permission(): ?iterable
    {
        return [
            'operations',
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
            'operations' => Operation::filters([RefOperationNameFilter::class])
                ->defaultSort('created_at', 'desc')
                ->with(['refOperation', 'car'])
                ->paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Операции';
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
            $this->generateModal(self::MODAL_NAME, [
                Relation::make('operation.car_id')
                    ->required()
                    ->title('Машина')
                    ->fromModel(Car::class, 'car_brand', 'id')
                    ->displayAppend('name')
                    ->searchColumns('car_brand', 'car_model'),
                Relation::make('operation.ref_operation_id')
                    ->title('Опрация из справочника')
                    ->required()
                    ->fromModel(ReferenceOperation::class, 'name', 'id'),
                Input::make('operation.index')
                    ->type('float')
                    ->required()
                    ->title('Коэффициент')

            ]),
            Layout::selection([
                RefOperationNameFilter::class,
            ]),
            Layout::table('operations', [
                TD::make('id', '№')->sort(),
                TD::make('refOperation.name', 'Название операции'),
                TD::make('index', 'Коэффициент')->sort(),
                TD::make('car', 'Машина')
                    ->render(function (Operation $operation) {
                        return "{$operation->car->car_brand}<br/>{$operation->car->car_model}";
                    }),
                TD::make('created_at', 'Дата создания')->sort(),
                TD::make('Действия')
                    ->alignRight()
                    ->render(fn(Operation $operation) => $this->generateEditBtn(
                        self::MODAL_NAME,
                        ['operation' => $operation->id]
                    )),
            ]),

        ];
    }

    public function asyncData(Operation $operation): array
    {
        return [
            'operation' => $operation,
        ];
    }


    public function createOrUpdate(Operation $operation, Request $request): RedirectResponse
    {
        $data = $request->input('operation');

        $operation->fill($data)->save();

        Toast::info('Операция успешно сохранена');

        return redirect()->route('platform.operations');
    }
}
