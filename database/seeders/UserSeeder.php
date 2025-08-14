<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\DriverDetail;
use App\Models\ExperiencePreference;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        $now = now();

        // -------------------------
        // Admin User
        // -------------------------
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

        // Vehicle types and service areas for truckers
        $vehicleTypes = ['semi_truck', 'box_truck', 'flatbed', 'refrigerated'];
        $serviceAreas = ['regional', 'international', 'national'];

        // -------------------------
        // 3 Trucker Users
        // -------------------------
        for ($i = 1; $i <= 3; $i++) {
            $trucker = User::create([
                'name' => "Trucker $i",
                'email' => "trucker$i@gmail.com",
                'password' => Hash::make('12345678'),
                'user_type' => 'trucker',
                'company_name' => $faker->company,
                'status' => 'verified',
                'is_verified' => true,
                'terms_and_conditions' => true,
                'email_verified_at' => $now,
                'verified_at' => $now,
            ]);

            // Driver details
            DriverDetail::create([
                'user_id' => $trucker->id,
                'driver_license' => Str::random(10),
                'license_number' => strtoupper(Str::random(8)),
                'state_of_issue' => 'TX',
                'expiration_date' => now()->addYears(3)->toDateString(),
            ]);

            // Experience preference
            ExperiencePreference::create([
                'user_id' => $trucker->id,
                'experience' => rand(1, 30) . ' years',
                'vehicle_type' => $vehicleTypes[array_rand($vehicleTypes)],
                'service_area' => $serviceAreas[array_rand($serviceAreas)],
                'additional_information' => 'Available for night shifts',
            ]);
        }

        // -------------------------
        // 3 Shipper Users
        // -------------------------
        for ($i = 1; $i <= 3; $i++) {
            User::create([
                'name' => "Shipper $i",
                'email' => "shipper$i@gmail.com",
                'password' => Hash::make('12345678'),
                'user_type' => 'shipper',
                'company_name' => $faker->company,
                'status' => 'verified',
                'is_verified' => true,
                'terms_and_conditions' => true,
                'email_verified_at' => $now,
                'verified_at' => $now,
            ]);
        }
    }
}
