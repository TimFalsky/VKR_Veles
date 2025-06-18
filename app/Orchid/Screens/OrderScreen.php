<?php

namespace App\Orchid\Screens;

use App\Models\Car;
use App\Models\Order;
use App\Orchid\Layouts\OrderListener;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class OrderScreen extends Screen
{
    public function permission(): ?iterable
    {
        return [
            'order',
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
            'car' => Car::query()->first(),
            'services' => collect(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Заказ-наряд';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            OrderListener::class
        ];
    }

    public function createOrder(Request $request)
    {
        $data = $request->all();
        $data['order']['uuid'] = Str::uuid()->toString();
        DB::transaction(function () use ($data) {
            $order = new Order();
            $order->fill($data['order']);
            $order->user()->associate(auth()->user());
            $order->save();

            $order->services()->sync($data['order']['services']);
        });

        return redirect()->route('platform.main');
    }
}
