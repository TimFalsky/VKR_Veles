<?php

namespace Database\Seeders;

use App\Models\Operation;
use App\Models\ReferenceOperation;
use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReferenceOperationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $operations = [
            ['name' => 'Снятие/установка бампера перед', 'price' => null],
            ['name' => 'Переборка бампера перед', 'price' => null],
            ['name' => 'Подготовка к окрашиванию бампера перед', 'price' => null],
            ['name' => 'Окрашивание бампера перед', 'price' => null],
            ['name' => 'Снятие/установка бампера зад', 'price' => null],
            ['name' => 'Переборка бампера зад', 'price' => null],
            ['name' => 'Подготовка к окрашиванию бампера зад', 'price' => null],
            ['name' => 'Окрашивание бампера зад', 'price' => null],
            ['name' => 'Снятие/установка двери пер лев', 'price' => null],
            ['name' => 'Переборка двери пер лев', 'price' => null],
            ['name' => 'Подготовка к окрашиванию двери пер лев', 'price' => null],
            ['name' => 'Окрашивание двери пер лев', 'price' => null],
            ['name' => 'Снятие/установка двери пер прав', 'price' => null],
            ['name' => 'Переборка двери пер прав', 'price' => null],
            ['name' => 'Подготовка к окрашиванию двери пер прав', 'price' => null],
            ['name' => 'Окрашивание двери пер прав', 'price' => null],
            ['name' => 'Снятие/установка двери зад лев', 'price' => null],
            ['name' => 'Переборка двери зад лев', 'price' => null],
            ['name' => 'Подготовка к окрашиванию двери зад лев', 'price' => null],
            ['name' => 'Окрашивание двери зад лев', 'price' => null],
            ['name' => 'Снятие/установка двери зад прав', 'price' => null],
            ['name' => 'Переборка двери зад прав', 'price' => null],
            ['name' => 'Подготовка к окрашиванию двери зад прав', 'price' => null],
            ['name' => 'Окрашивание двери зад прав', 'price' => null],
            ['name' => 'Снятие/установка крыло перед лев', 'price' => null],
            ['name' => 'Переборка крыло перед лев', 'price' => null],
            ['name' => 'Подготовка к окрашиванию крыло перед лев', 'price' => null],
            ['name' => 'Окрашивание крыло перед лев', 'price' => null],
            ['name' => 'Снятие/установка крыло перед прав', 'price' => null],
            ['name' => 'Переборка крыло перед прав', 'price' => null],
            ['name' => 'Подготовка к окрашиванию крыло перед прав', 'price' => null],
            ['name' => 'Окрашивание крыло перед прав', 'price' => null],
            ['name' => 'Снятие/установка капот', 'price' => null],
            ['name' => 'Переборка капот', 'price' => null],
            ['name' => 'Подготовка к окрашиванию капот', 'price' => null],
            ['name' => 'Окрашивание капот', 'price' => null],
            ['name' => 'Снятие/установка крышка багажника', 'price' => null],
            ['name' => 'Переборка крышка багажника', 'price' => null],
            ['name' => 'Подготовка к окрашиванию крышка багажника', 'price' => null],
            ['name' => 'Окрашивание крышка багажника', 'price' => null],
            ['name' => 'Ремонт порог лев', 'price' => null],
            ['name' => 'Подготовка к окрашиванию порог лев', 'price' => null],
            ['name' => 'Окрашивание порог лев', 'price' => null],
            ['name' => 'Ремонт порог прав', 'price' => null],
            ['name' => 'Подготовка к окрашиванию порог прав', 'price' => null],
            ['name' => 'Окрашивание порог прав', 'price' => null],
            ['name' => 'Ремонт крыло зад лев', 'price' => null],
            ['name' => 'Подготовка к окрашиванию крыло зад лев', 'price' => null],
            ['name' => 'Окрашивание крыло зад лев', 'price' => null],
            ['name' => 'Ремонт крыло зад прав', 'price' => null],
            ['name' => 'Подготовка к окрашиванию крыло зад прав', 'price' => null],
            ['name' => 'Окрашивание крыло зад прав', 'price' => null],
            ['name' => 'Ремонт крыша', 'price' => null],
            ['name' => 'Переборка крыша', 'price' => null],
            ['name' => 'Подготовка к окрашиванию крыша', 'price' => null],
            ['name' => 'Окрашивание крыша', 'price' => null],
            ['name' => 'Ремонт кузов', 'price' => null],
            ['name' => 'Подготовка к окрашиванию кузов', 'price' => null],
            ['name' => 'Окрашивание кузов', 'price' => null],
            ['name' => 'Техническая мойка', 'price' => '350'],
        ];
        foreach ($operations as $operation) {
            ReferenceOperation::query()->create($operation);
        }
    }
}
