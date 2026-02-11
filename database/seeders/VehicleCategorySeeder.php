<?php

namespace Database\Seeders;

use App\Models\VehicleCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Private',
                'slug' => 'private',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Commercial',
                'slug' => 'commercial',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($categories as $category) {

            VehicleCategory::create([
                'name' => $category['name'],
                'slug' => $category['slug'],
                'is_active' => $category['is_active'],
                'created_at' => $category['created_at'],
                'updated_at' => $category['updated_at']
            ]);
        }
    }
}
