<?php

namespace Database\Seeders;

use App\Models\Detail;
use Illuminate\Database\Seeder;

class DetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $operations = [
            ['car_id' => 1, 'name' => 'Бампер перед', 'article' => '6RU807221', 'type_units' => 'шт.', 'price' => 3100],
            ['car_id' => 1, 'name' => 'Бампер зад', 'article' => '6RU807421', 'type_units' => 'шт.', 'price' => 3000],
            ['car_id' => 1, 'name' => 'Дверь перед лев', 'article' => '6RU831055', 'type_units' => 'шт.', 'price' => 16000],
            ['car_id' => 1, 'name' => 'Дверь перед прав', 'article' => '6RU831056', 'type_units' => 'шт.', 'price' => 15000],
            ['car_id' => 1, 'name' => 'Дверь зад лев', 'article' => '6RU833055', 'type_units' => 'шт.', 'price' => 12000],
            ['car_id' => 1, 'name' => 'Дверь зад прав', 'article' => '6RU833056', 'type_units' => 'шт.', 'price' => 12000],
            ['car_id' => 1, 'name' => 'Крыло перед лев', 'article' => '6RU821105', 'type_units' => 'шт.', 'price' => 3500],
            ['car_id' => 1, 'name' => 'Крыло перед прав', 'article' => '6RU821106', 'type_units' => 'шт.', 'price' => 3500],
            ['car_id' => 1, 'name' => 'Капот', 'article' => '6R0823031', 'type_units' => 'шт.', 'price' => 10000],
            ['car_id' => 1, 'name' => 'Крышка багажника', 'article' => '6RU827025', 'type_units' => 'шт.', 'price' => 15000],
            ['car_id' => 2, 'name' => 'Бампер перед', 'article' => '86511H5000', 'type_units' => 'шт.', 'price' => 4100],
            ['car_id' => 2, 'name' => 'Бампер зад', 'article' => '86611H5000', 'type_units' => 'шт.', 'price' => 4000],
            ['car_id' => 2, 'name' => 'Дверь перед лев', 'article' => '76003H5000', 'type_units' => 'шт.', 'price' => 21000],
            ['car_id' => 2, 'name' => 'Дверь перед прав', 'article' => '76004H5000', 'type_units' => 'шт.', 'price' => 19000],
            ['car_id' => 2, 'name' => 'Дверь зад лев', 'article' => '77003H5000', 'type_units' => 'шт.', 'price' => 15000],
            ['car_id' => 2, 'name' => 'Дверь зад прав', 'article' => '77004H5000', 'type_units' => 'шт.', 'price' => 15000],
            ['car_id' => 2, 'name' => 'Крыло перед лев', 'article' => '66311F9000', 'type_units' => 'шт.', 'price' => 2500],
            ['car_id' => 2, 'name' => 'Крыло перед прав', 'article' => '66321F9000', 'type_units' => 'шт.', 'price' => 2500],
            ['car_id' => 2, 'name' => 'Капот', 'article' => '66400H5000', 'type_units' => 'шт.', 'price' => 14000],
            ['car_id' => 2, 'name' => 'Крышка багажника', 'article' => '69200H5000', 'type_units' => 'шт.', 'price' => 19000],
            ['car_id' => 3, 'name' => 'Бампер перед', 'article' => '865114Y000', 'type_units' => 'шт.', 'price' => 3600],
            ['car_id' => 3, 'name' => 'Бампер зад', 'article' => '866114Y000', 'type_units' => 'шт.', 'price' => 3500],
            ['car_id' => 3, 'name' => 'Дверь перед лев', 'article' => '760034Y000', 'type_units' => 'шт.', 'price' => 15000],
            ['car_id' => 3, 'name' => 'Дверь перед прав', 'article' => '760044Y000', 'type_units' => 'шт.', 'price' => 14000],
            ['car_id' => 3, 'name' => 'Дверь зад лев', 'article' => '770034Y000', 'type_units' => 'шт.', 'price' => 12000],
            ['car_id' => 3, 'name' => 'Дверь зад прав', 'article' => '770044Y000', 'type_units' => 'шт.', 'price' => 12000],
            ['car_id' => 3, 'name' => 'Крыло перед лев', 'article' => '663114Y000', 'type_units' => 'шт.', 'price' => 4100],
            ['car_id' => 3, 'name' => 'Крыло перед прав', 'article' => '663214Y000', 'type_units' => 'шт.', 'price' => 4100],
            ['car_id' => 3, 'name' => 'Капот', 'article' => '664004Y000', 'type_units' => 'шт.', 'price' => 12000],
            ['car_id' => 3, 'name' => 'Крышка багажника', 'article' => '692004Y000', 'type_units' => 'шт.', 'price' => 15000],


        ];
        foreach ($operations as $operation) {
            Detail::query()->create($operation);
        }
    }
}
