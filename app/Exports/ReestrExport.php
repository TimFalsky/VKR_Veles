<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class ReestrExport implements FromView
{

    public function view(): View
    {
        return view('export.reestr-export', [
           'orders' => Order::query()
               ->with(['car', 'services', 'services.operations', 'services.operations.refOperation'])
            ->get()
        ]);
    }
}
