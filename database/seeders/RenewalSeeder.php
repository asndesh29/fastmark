<?php

namespace Database\Seeders;

use App\Models\RenewalType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RenewalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $renewalTypes = [
            [
                'name' => 'Bluebook',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Jach Pass',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Insurance',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Pollution',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Road Permit',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Vehicle Tax',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($renewalTypes as $renewal) {

            RenewalType::create([
                'name' => $renewal['name'],
                'is_active' => $renewal['is_active'],
                'created_at' => $renewal['created_at'],
                'updated_at' => $renewal['updated_at']
            ]);
        }
    }
}
