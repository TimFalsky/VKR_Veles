<?php

namespace App\Orchid\Screens;

use App\Models\ReferenceOperation;
use App\Orchid\Filters\OriginRefOperationNameFilter;
use App\Traits\ModalTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class ReferenceOperationScreen extends Screen
{
    use ModalTrait;

    const MODAL_NAME = 'referenceOperationModal';

    public function permission(): ?iterable
    {
        return [
            'ref-operations',
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
            'operations' => ReferenceOperation::query()
                ->filters([OriginRefOperationNameFilter::class])
                ->defaultSort('id', 'desc')
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
        return 'Справочник операций';
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
                Input::make('operation.name')
                    ->required()
                    ->title('Название операции'),
                Input::make('operation.price')
                    ->type('number')
                    ->step('0.1')
                    ->title('Стоймость операции')
            ]),

            Layout::selection([
                OriginRefOperationNameFilter::class,
            ]),

            Layout::table('operations', [
                TD::make('id', '№')->sort(),
                TD::make('name', 'Название операции')->sort(),
                TD::make('price', 'Цена операции')->render(fn(ReferenceOperation $operation) => is_null($operation->price)
                    ? 'Н.Ч. ' . config('app.default_price')
                    : $operation->price),
                TD::make('created_at', 'Дата создания')->sort(),
                TD::make('Действия')
                    ->alignRight()
                    ->render(fn(ReferenceOperation $operation) => $this->generateEditBtn(
                        self::MODAL_NAME,
                        ['operation' => $operation->id]
                    )),
            ]),
        ];
    }

    public function asyncData(ReferenceOperation $operation): array
    {
        return [
            'operation' => $operation,
        ];
    }


    public function createOrUpdate(ReferenceOperation $operation, Request $request): RedirectResponse
    {
        $data = $request->input('operation');

        $operation->fill($data)->save();

        Toast::info('Car saved successfully.');

        return redirect()->route('platform.reference-operations');
    }
}
