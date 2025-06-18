<?php

namespace App\Traits;

use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Modal;
use Orchid\Support\Facades\Layout;

trait ModalTrait
{
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Создать')
                ->modal('editModal')
                ->method('createOrUpdate')
                ->modalTitle('Создать')
                ->icon('plus'),
        ];
    }

    public function generateCreateBtn(string $name): ModalToggle
    {
        return ModalToggle::make('Создать')
            ->modal($name)
            ->method('createOrUpdate')
            ->modalTitle('Создать')
            ->icon('plus');
    }

    public function generateModal(string $name, array $fields): Modal
    {
        return Layout::modal($name,
            Layout::rows($fields)
        )->title('Создать')
            ->async('asyncData')
            ->closeButton('Отменить')
            ->applyButton('Сохранить');
    }

    public function generateEditBtn(string $name, array $data): ModalToggle
    {
        return ModalToggle::make('Изменить')
            ->modal($name)
            ->method('createOrUpdate')
            ->modalTitle('Редактировать')
            ->asyncParameters($data)
            ->icon('pencil');
    }
}
