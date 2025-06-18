<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            ['car_id' => 1, 'name' => 'Замена бампера пер'],
            ['car_id' => 1, 'name' => 'Замена бампера зад'],
            ['car_id' => 1, 'name' => 'Замена двери пер лев'],
            ['car_id' => 1, 'name' => 'Замена двери пер прав'],
            ['car_id' => 1, 'name' => 'Замена двери зад лев'],
            ['car_id' => 1, 'name' => 'Замена двери зад прав'],
            ['car_id' => 1, 'name' => 'Замена крыло пер лев'],
            ['car_id' => 1, 'name' => 'Замена крыло пер прав'],
            ['car_id' => 1, 'name' => 'Замена капот'],
            ['car_id' => 1, 'name' => 'Замена крышка багажника'],
            ['car_id' => 1, 'name' => 'Ремонт порог прав'],
            ['car_id' => 1, 'name' => 'Ремонт порог лев'],
            ['car_id' => 1, 'name' => 'Ремонт крыло зад лев'],
            ['car_id' => 1, 'name' => 'Ремонт крыло зад прав'],
            ['car_id' => 1, 'name' => 'Ремонт крыша'],
            ['car_id' => 1, 'name' => 'Восстановление геометрии кузова'],
        ];
        foreach ($services as $service) {
            Service::query()->create($service);
        }
    }
}
