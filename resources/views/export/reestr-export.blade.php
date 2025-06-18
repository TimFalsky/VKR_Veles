<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Машина</th>
        <th>VIN</th>
        <th>Гос Номер</th>
        <th>Стоймость операций</th>
        <th>Стоймость деталей</th>
        <th>ИТОГО</th>
        <th>Дата создания</th>
    </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
        <tr>
            <td>{{$order->id}}</td>
            <td>{{"{$order->car->car_brand} {$order->car->car_model}"}}</td>
            <td>{{$order->vin_code}}</td>
            <td>{{$order->gos_number}}</td>
            <td>{{$order->operations_cost}}</td>
            <td>{{$order->details_cost}}</td>
            <td>{{$order->total_cost}}</td>
            <td>{{$order->created_at}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
