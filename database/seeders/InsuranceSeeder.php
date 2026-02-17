<?php

namespace Database\Seeders;

use App\Models\InsuranceProvider;
use App\Models\RenewalType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InsuranceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $insurance_providers = [
            [
                'name' => 'Himalayan Everest Insurance',
                'address' => 'Thapagaun, Kathmandu',
                'email' => 'ktm@hei.com.np',
                'phone_no' => '014444717, 014444718',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'IGI Prudential Insurance Company Limited',
                'address' => 'Naxal, Kathmandu',
                'email' => 'info@igiprudential.com',
                'phone_no' => '014511510, 4511520, 4525508, 4525509',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'National Insurance Co. Ltd.',
                'address' => 'Tripureshwor, Kathmandu',
                'email' => 'info@nicnepal.com.np',
                'phone_no' => '014260366,4250710,4254045',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'NECO Insurance Company Limited',
                'address' => 'Gyaneshwor, Kathmandu',
                'email' => 'info@neco.com.np',
                'phone_no' => '014542263, 014526595, 014531462',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Nepal insurance Company Limited',
                'address' => 'Kamaladi, Kathmandu',
                'email' => 'nic@nepalinsurance.com.np',
                'phone_no' => '5321353,5328690,5345565,5345568',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Nepal Micro Insurance Company Limited',
                'address' => 'Bharatpur, Chitwan',
                'email' => 'info@nepalmicro.com',
                'phone_no' => '056494327,014529362,014529363',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'NLG Insurance Company Limited',
                'address' => 'Lazimpath, Kathmandu',
                'email' => 'info@nlgi.com.np',
                'phone_no' => '014542646,014006648',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Prabhu Insurance Company Limited ',
                'address' => 'Tinkune, Kathmandu',
                'email' => 'info@prabhuinsurance.com',
                'phone_no' => '015199220,015199226',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Rastriya Beema Compay Limited',
                'address' => 'Ramshahpath, Kathmandu',
                'email' => 'info@rbcl.gov.np',
                'phone_no' => '014258866, 014259374, 014260352, 014215536',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Shikhar Insurance Company Limited',
                'address' => 'Thapathali, Kathmandu',
                'email' => 'shikharins@mos.com.np',
                'phone_no' => '015346101, 015346102, 015346107',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Siddhartha Premier Insurance Limited',
                'address' => 'Babarmahal, Kathmandu',
                'email' => 'siddharthapremier@spil.com.np',
                'phone_no' => '015705766, 015707190, 015705447',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Sanima GIC Insurance Limited',
                'address' => 'Tangal Marg, Kathmandu',
                'email' => 'info@sgic.com.np',
                'phone_no' => '014527170, 014527171, 014527172, 014527101',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($insurance_providers as $provider) {

            InsuranceProvider::create([
                'name' => $provider['name'],
                'address' => $provider['address'] ?? null,
                'email' => $provider['email'] ?? null,
                'phone_no' => $provider['phone_no'] ?? null,
                'is_active' => $provider['is_active'] ?? true,
                'created_at' => $provider['created_at'] ?? now(),
                'updated_at' => $provider['updated_at'] ?? now()
            ]);
        }
    }
}
