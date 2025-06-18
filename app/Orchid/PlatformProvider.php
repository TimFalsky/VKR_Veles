<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Dashboard $dashboard
     *
     * @return void
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * Register the application menu.
     *
     * @return Menu[]
     */
    public function menu(): array
    {
        return [
            Menu::make('Заказ-наряд')
                ->icon('bs.card-checklist')
                ->title('Навигация')
                ->route('platform.main')
                ->permission('order'),
            Menu::make('Реестр заказов')
                ->icon('bs.card-checklist')
                ->route('platform.reestr')
                ->permission('reestr'),
            Menu::make('Услуги')
                ->icon('bs.boxes')
                ->route('platform.services')
                ->permission('services'),
            Menu::make('Автомобили')
                ->icon('bs.car-front-fill')
                ->route('platform.cars')
                ->permission('cars'),
            Menu::make('Справочник работ')
                ->icon('bs.wrench')
                ->route('platform.reference-operations')
                ->permission('ref-operations'),
            Menu::make('Работы')
                ->icon('bs.wrench')
                ->route('platform.operations')
                ->permission('operations'),
            Menu::make('Детали')
                ->icon('bs.tools')
                ->route('platform.details')
                ->permission('details')
                ->divider(),

            Menu::make('Пользователи')
                ->icon('bs.people')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title('Управление доступом'),

            Menu::make('Роли')
                ->icon('bs.shield')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles'),
        ];
    }

    /**
     * Register permissions for the application.
     *
     * @return ItemPermission[]
     */
    public function permissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),
        ];
    }
}
