<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\DriverDetail;
use App\Models\ExperiencePreference;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Nette\Utils\Random;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678'),
            'user_type' => 'admin',
            'status' => 'verified',
            'is_verified' => true,
            'terms_and_conditions' => true,
            'email_verified_at' => $now,
            'verified_at' => $now,
        ]);

        // Vehicle types and service areas for random pick
        $vehicleTypes = ['semi_truck', 'box_truck', 'flatbed', 'refrigerated'];
        $serviceAreas = ['regional', 'international', 'national'];

        // 10 Trucker Users
        for ($i = 1; $i <= 10; $i++) {
            $trucker = User::create([
                'name' => "Trucker $i",
                'email' => "trucker$i@example.com",
                'password' => Hash::make('12345678'),
                'user_type' => 'trucker',
                'company_name' => Random::generate(20),
                'status' => 'verified',
                'is_verified' => true,
                'terms_and_conditions' => true,
                'email_verified_at' => $now,
                'verified_at' => $now,
            ]);

            // Driver Detail
            DriverDetail::create([
                'user_id' => $trucker->id,
                'driver_license' => Random::generate(10),
                'license_number' => strtoupper(Random::generate(8, 'A-Z0-9')),
                'state_of_issue' => 'TX',
                'expiration_date' => now()->addYears(3)->toDateString(),
            ]);

            // Experience Preference
            ExperiencePreference::create([
                'user_id' => $trucker->id,
                'experience' => rand(1, 30) . ' years',
                'vehicle_type' => $vehicleTypes[array_rand($vehicleTypes)],
                'service_area' => $serviceAreas[array_rand($serviceAreas)],
                'additional_information' => 'Available for night shifts',
            ]);
        }

        // 10 Shipper Users
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => "Shipper $i",
                'email' => "shipper$i@example.com",
                'password' => Hash::make('12345678'),
                'user_type' => 'shipper',
                'company_name' => Random::generate(20),
                'status' => 'verified',
                'is_verified' => true,
                'terms_and_conditions' => true,
                'email_verified_at' => $now,
                'verified_at' => $now,
            ]);
        }
    }
}
