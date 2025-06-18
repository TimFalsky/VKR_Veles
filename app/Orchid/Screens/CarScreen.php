<?php

namespace App\Orchid\Screens;

use App\Models\Car;
use App\Traits\ModalTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\TD;
use Orchid\Screen\Fields\Input;

class CarScreen extends Screen
{
    use ModalTrait;

    const MODAL_NAME = 'carModal';

    public function permission(): ?iterable
    {
        return [
            'cars',
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
            'cars' => Car::query()->filters()->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Машины';
    }

    public function description(): ?string
    {
        return 'List and manage cars';
    }

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
    public function layout(): array
    {
        return [
            $this->generateModal(self::MODAL_NAME,[
                Input::make('car.car_brand')
                    ->title('Брэнд')
                    ->required(),
                Input::make('car.car_model')
                    ->title('Модель')
                    ->required(),
            ]),

            Layout::table('cars', [
                TD::make('id')->sort(),
                TD::make('car_brand', 'Брэнд')->filter(),
                TD::make('car_model', 'Модель')->filter(),
                TD::make('created_at', 'Дата создания'),
                TD::make('Действия')
                    ->alignRight()
                    ->render(fn(Car $car) => $this->generateEditBtn(
                        self::MODAL_NAME,
                        ['car' => $car->id]
                    )),
            ]),
        ];
    }

    public function asyncData(Car $car): array
    {
        return [
            'car' => $car,
        ];
    }


    public function createOrUpdate(Car $car, Request $request): RedirectResponse
    {
        $data = $request->input('car');

        $car->fill($data)->save();

        Toast::info('Машина успешно сохранена');

        return redirect()->route('platform.cars');
    }
}
