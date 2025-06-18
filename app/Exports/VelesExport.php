<?php

namespace App\Exports;

use App\Models\Detail;
use App\Models\Operation;
use App\Models\Order;
use App\Models\Service;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class VelesExport
{
    public array $data;
    public function __construct(Order $order)
    {
        $this->data = $this->getData($order);
    }

    public function getData(Order $order): array
    {
        $services = $order->services;
        return [
            'order' => $order,
            'car' => $order->car,
            'details' => $services->reduce(function ($detailsCollection, Service $service) {
                return $detailsCollection->merge($service->details()->withPivot('quantity')->get());
            }, collect()),
            'operations' => $services->reduce(function (Collection $result, Service $service) {
                return $result->merge($service->operations()->with('refOperation')->get());
            }, collect()),
        ];
    }

    public function export() {
        // Загружаем шаблон
        $templatePath = Storage::disk('excel')->path('template_veles.xlsx');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();

        $order_numb = str_pad(data_get($this->data, 'order.id'), 3, '0', STR_PAD_LEFT);
        $order_date = data_get($this->data, 'order.created_at')->format("d.m.Y");
        $car = data_get($this->data, 'car.car_brand') . " " . data_get($this->data, 'car.car_model');
        $operations_cost = data_get($this->data, 'order.operations_cost');
        $details_cost = data_get($this->data, 'order.details_cost');
        $gos_number = data_get($this->data, 'order.gos_number');
        $vin_code = data_get($this->data, 'order.vin_code');
        $mileage = data_get($this->data, 'order.mileage');
        // Заполняем ячейки

        $sheet->setCellValue('C4', "Заказ-наряд Кузов № $order_numb от $order_date");
        $sheet->setCellValue('B7', "Автомобиль : $car гос. номер: $gos_number VIN: $vin_code");
        $sheet->setCellValue('G7', "Пробег: $mileage км");

        $row = 11;
        $operations = data_get($this->data, 'operations');
        if ($operations->count()) {
            $sheet->setCellValue("C$row", "Ремонтные работы");
            $row++;
            $sheet->setCellValue("B$row", "№");
            $sheet->setCellValue("C$row", "Наименование");
            $sheet->setCellValue("D$row", "Кол.оп.");
            $sheet->setCellValue("E$row", "Кол-во");
            $sheet->setCellValue("F$row", "Ед.изм.");
            $sheet->setCellValue("G$row", "Цена");
            $sheet->setCellValue("H$row", "Всего");
            $row++;
            $i = 1;
            /** @var Operation $operation */
            foreach ($operations as $operation) {
                $sheet->setCellValue("B$row", $i++);
                $sheet->setCellValue("C$row", $operation->refOperation->name);
                $sheet->setCellValue("D$row", $operation->index);
                $sheet->setCellValue("E$row", '');
                $sheet->setCellValue("F$row", is_null($operation->refOperation->price)
                    ? "Н.Ч." : 'руб.');
                $sheet->setCellValue("G$row", $operation->refOperation->price ?? config('app.default_price'));
                $sheet->setCellValue("H$row", is_null($operation->refOperation->price)
                    ? $operation->index * config('app.default_price')
                    : $operation->refOperation->price * $operation->index);
                $row++;
            }
            $sheet->setCellValue("G$row", "ИТОГО: ");
            $sheet->setCellValue("H$row", "$operations_cost");
            $row++;
            $row++;
        }
        $details = data_get($this->data, 'details');
        if ($details->count()) {
            $sheet->setCellValue("C$row", "Расходная накладная к заказ-наряду");
            $row++;
            $sheet->setCellValue("B$row", "№");
            $sheet->setCellValue("C$row", "Наименование");
            $sheet->setCellValue("D$row", "№ по каталогу");
            $sheet->setCellValue("E$row", "Кол-во");
            $sheet->setCellValue("F$row", "Ед.изм.");
            $sheet->setCellValue("G$row", "Цена");
            $sheet->setCellValue("H$row", "Всего");
            $row++;
            $i = 1;
            /** @var Detail $detail */
            foreach ($details as $detail) {
                $sheet->setCellValue("B$row", $i++);
                $sheet->setCellValue("C$row", $detail->name);
                $sheet->setCellValue("D$row", $detail->article);
                $sheet->setCellValue("E$row", $detail->pivot->quantity);
                $sheet->setCellValue("F$row", $detail->type_units);
                $sheet->setCellValue("G$row", $detail->price);
                $sheet->setCellValue("H$row", $detail->pivot->quantity * $detail->price);
                $row++;
            }
            $total_cost = $details_cost + $operations_cost;
            $sheet->setCellValue("G$row", "ИТОГО: ");
            $sheet->setCellValue("H$row", "$details_cost");
            $row++;
            $sheet->setCellValue("G$row", "Всего: ");
            $sheet->setCellValue("H$row", "$total_cost");	
        }


//        $sheet->setCellValue('B3', $request->input('value2'));

        // Создаем временный файл
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $tempPath = tempnam(sys_get_temp_dir(), 'laravel-excel');
        $writer->save($tempPath);

        // Отправляем файл пользователю
        return response()->download($tempPath, "ЗН №$order_numb от $order_date.xlsx")->deleteFileAfterSend(true);
    }
}
