<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\Dashboard;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * @param Dashboard $dashboard
     */
    public function boot(Dashboard $dashboard)
    {
        $permissions = ItemPermission::group('Экраны')
            ->addPermission('order', 'Заказ-наряд')
            ->addPermission('reestr', 'Реестр заказов')
            ->addPermission('services', 'Услуги')
            ->addPermission('cars', 'Автомобили')
            ->addPermission('ref-operations', 'Справочник работ')
            ->addPermission('operations', 'Операции')
            ->addPermission('details', 'Детали');

        $dashboard->registerPermissions($permissions);
    }
}
