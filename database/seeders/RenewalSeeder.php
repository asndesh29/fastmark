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
                'private_validity_value' => 12,
                'private_validity_unit' => 'months',
                'commercial_validity_value' => 12,
                'commercial_validity_unit' => 'months',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Vehicle Pass',
                'commercial_validity_value' => 6,
                'commercial_validity_unit' => 'months',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Insurance',
                'private_validity_value' => 12,
                'private_validity_unit' => 'months',
                'commercial_validity_value' => 12,
                'commercial_validity_unit' => 'months',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Pollution',
                'commercial_validity_value' => 12,
                'commercial_validity_unit' => 'months',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Road Permit',
                'commercial_validity_value' => 6,
                'commercial_validity_unit' => 'months',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Vehicle Tax',
                'private_validity_value' => 12,
                'private_validity_unit' => 'months',
                'commercial_validity_value' => 12,
                'commercial_validity_unit' => 'months',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'License',
                'private_validity_value' => 12,
                'private_validity_unit' => 'months',
                'commercial_validity_value' => 12,
                'commercial_validity_unit' => 'months',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($renewalTypes as $renewal) {

            RenewalType::create([
                'name' => $renewal['name'],
                'private_validity_value' => $renewal['private_validity_value'] ?? null,
                'private_validity_unit' => $renewal['private_validity_unit'] ?? null,
                'commercial_validity_value' => $renewal['commercial_validity_value'] ?? null,
                'commercial_validity_unit' => $renewal['commercial_validity_unit'] ?? null,
                'is_active' => $renewal['is_active'] ?? true,
                'created_at' => $renewal['created_at'] ?? now(),
                'updated_at' => $renewal['updated_at'] ?? now()
            ]);
        }
    }
}
