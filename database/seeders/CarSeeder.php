<?php

namespace Database\Seeders;

use App\Models\Car;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cars = [
            ['car_brand' => 'Volkswagen', 'car_model' => 'Polo sedan'],
            ['car_brand' => 'Hyundai', 'car_model' => 'Solaris'],
            ['car_brand' => 'Kia', 'car_model' => 'Rio'],
        ];
        foreach ($cars as $car) {
            Car::query()->create($car);
        }
    }
}
