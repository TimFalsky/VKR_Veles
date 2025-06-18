<?php

namespace App\Orchid\Screens;

use App\Exports\ReestrExport;
use App\Exports\VelesExport;
use App\Models\Car;
use App\Models\Detail;
use App\Models\Order;
use App\Models\Service;
use App\Orchid\Filters\ReestrFilter;
use App\Orchid\Layouts\OrderListener;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class OrdersScreen extends Screen
{
    const MODAL_NAME = 'reestrModal';

    public function permission(): ?iterable
    {
        return [
            'reestr',
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
            'orders' => Order::query()
                ->filters([ReestrFilter::class])
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
        return 'Заказ-наряды реестр';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Экспорт')
                ->icon('download')
                ->method('export')
                ->type(Color::SUCCESS)
                ->download(),
        ];
    }

    public function export(): BinaryFileResponse
    {
        return Excel::download(new ReestrExport, 'reestr.xlsx');
    }

    public function exportVeles(Order $order): BinaryFileResponse
    {
        return (new VelesExport($order))->export();
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::selection([
                ReestrFilter::class,
            ]),
            Layout::table('orders', [
                TD::make('id', '№')->sort(),
                TD::make('uuid', 'Номер')->defaultHidden()->sort(),
                TD::make('operations', 'Операции')->class('align-top')
                    ->render(function (Order $order) {
                        $table = '<ul class="list-group list-group-flush">%s</ul>';
                        $rows = [];
                        $tr = '<li class="list-group-item p-1" style="font-size: 9px">%s</li>';
                        foreach ($order->services as $service) {
                            foreach ($service->operations as $operation) {
                                $rows[] = sprintf($tr, $operation->refOperation->name);
                            }
                        }
                        $rowsString = implode("\n", $rows);
                        return sprintf($table, $rowsString);
                    }),
                TD::make('details', 'Детали')->class('align-top')
                    ->render(function (Order $order) {
                        $table = '<ul class="list-group list-group-flush">%s</ul>';
                        $rows = [];
                        $tr = '<li class="list-group-item p-1" style="font-size: 9px">%s</li>';
                        foreach ($order->services as $service) {
                            foreach ($service->details as $detail) {
                                $rows[] = sprintf($tr, "$detail->name кол: {$detail->pivot->quantity}");
                            }
                        }
                        $rowsString = implode("\n", $rows);
                        return sprintf($table, $rowsString);
                    }),
                TD::make('car', 'Машина')
                    ->render(function (Order $order) {
                        return "{$order->car->car_brand}<br/>{$order->car->car_model}";
                    }),
                TD::make('vin_code', 'VIN')->defaultHidden(),
                TD::make('mileage', 'Пробег')->defaultHidden(),
                TD::make('gos_number', 'Гос Номер'),
                TD::make('details_cost', 'Стоймость деталей')->defaultHidden(),
                TD::make('operations_cost', 'Стоймость операций')->defaultHidden(),
                TD::make('total_cost', 'ИТОГО'),
                TD::make('created_at', 'Дата создания')->sort(),
                TD::make('export', 'Экспорт')->render(function (Order $order) {
                    return Button::make('Экспорт')
                        ->icon('download')
                        ->method('exportVeles', ['order' => $order->id])
                        ->type(Color::SUCCESS)
                        ->download();
                }),
            ]),
        ];
    }
}
