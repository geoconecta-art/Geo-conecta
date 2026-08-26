<?php

namespace Database\Seeders;

use App\Models\Weather;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WeatherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Weather::create([
            'weather' => '12.21',
            'icon' => '04d',
            'description' => 'nubes',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
