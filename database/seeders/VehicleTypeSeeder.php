<?php

namespace Database\Seeders;

use App\Models\VehicleType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $renewalTypes = [
            [
                'name' => 'Mini Truck',
                'slug' => 'mini-truck',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Mini Tripper',
                'slug' => 'mini-tripper',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Truck',
                'slug' => 'truck',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Tripper',
                'slug' => 'tripper',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Motorcycle',
                'slug' => 'motorcycle',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Car',
                'slug' => 'car',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Tanker',
                'slug' => 'tanker',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Excavator',
                'slug' => 'excavator',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Backhoe Loader',
                'slug' => 'backhoe-loader',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($renewalTypes as $renewal) {

            VehicleType::create([
                'name' => $renewal['name'],
                'slug' => $renewal['slug'],
                'is_active' => $renewal['is_active'],
                'created_at' => $renewal['created_at'],
                'updated_at' => $renewal['updated_at']
            ]);
        }
    }
}
