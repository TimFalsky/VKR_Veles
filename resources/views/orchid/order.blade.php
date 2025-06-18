<div class="card p-4 bg-gradient my-4" style="background: white; box-shadow: 0.5px 0.5px 4px #ccc">
    <h5 class="card-title">Заказ-наряд</h5>
    <div class="card-body">
        <table class="table">
            <tbody>
            <tr>
                <td>Стоймость работ</td>
                <td>{{$order['operations_cost'] ?? 0}}</td>
            </tr>
            <tr>
                <td>Стоймость деталей</td>
                <td>{{$order['details_cost'] ?? 0}}</td>
            </tr>
            </tbody>
            <tr>
                <td class="fw-bold">ИТОГО</td>
                <td class="fw-bold">{{$order['total_cost'] ?? 0}}</td>
            </tr>
        </table>
    </div>
</div>
