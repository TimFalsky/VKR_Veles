<?php

namespace App\Orchid\Screens;

use App\Models\Car;
use App\Models\Detail;
use App\Models\Operation;
use App\Orchid\Filters\DetailFilter;
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

class DetailScreen extends Screen
{
    use ModalTrait;

    const MODAL_NAME = 'detailModal';

    public function permission(): ?iterable
    {
        return [
            'details',
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
            'details' => Detail::query()
                ->filters([DetailFilter::class])
                ->with(['car'])
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
        return 'Детали';
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
                Relation::make('detail.car_id')
                    ->required()
                    ->title('Машина')
                    ->fromModel(Car::class, 'car_brand', 'id')
                    ->searchColumns('car_brand', 'car_model')
                    ->displayAppend('name'),
                Input::make('detail.name')
                    ->required()
                    ->title('Наименование детали'),
                Select::make('detail.type_units')
                    ->required()
                    ->title('Опрация из справочника')
                    ->options([
                        'шт.' => 'шт.',
                        'л-р' => 'л-р',
                        'кг.' => 'кг.',
                    ]),
                Input::make('detail.price')
                    ->type('number')
                    ->step('0.1')
                    ->required()
                    ->title('Стоймость'),
            ]),

            Layout::selection([
                DetailFilter::class,
            ]),
            Layout::table('details', [
                TD::make('id')->sort(),
                TD::make('name', 'Наименование детали'),
                TD::make('article', 'Артикль'),
                TD::make('type_units', 'Ед.изм.'),
                TD::make('price', 'Цена'),
                TD::make('car', 'Машина')
                    ->render(function (Detail $detail) {
                        return "{$detail->car->car_brand}<br/>{$detail->car->car_model}";
                    }),
                TD::make('created_at', 'Дата создания'),
                TD::make('Действия')
                    ->alignRight()
                    ->render(fn(Detail $detail) => $this->generateEditBtn(
                        self::MODAL_NAME,
                        ['detail' => $detail->id]
                    )),
            ]),
        ];
    }

    public function asyncData(Detail $detail): array
    {
        return [
            'detail' => $detail,
        ];
    }


    public function createOrUpdate(Detail $detail, Request $request): RedirectResponse
    {
        $data = $request->input('detail');
        is_null(data_get($detail, 'article')) && $data['article'] = strtoupper(fake()->bothify("#RU######"));

        $detail->fill($data)->save();

        Toast::info('Машина успешно сохранена');

        return redirect()->route('platform.details');
    }
}
