<?php

namespace Database\Seeders;

use App\Models\JobPost;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JobPostSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Get all shippers and truckers
        $shippers = User::where('user_type', 'shipper')->get();
        $truckers = User::where('user_type', 'trucker')->get();

        // Example cities for pickup/delivery
        $cities = [
            ['city' => 'Houston', 'state' => 'TX', 'lat' => 29.7604, 'lng' => -95.3698],
            ['city' => 'Dallas', 'state' => 'TX', 'lat' => 32.7767, 'lng' => -96.7970],
            ['city' => 'Austin', 'state' => 'TX', 'lat' => 30.2672, 'lng' => -97.7431],
        ];

        $shipmentTypes = ['Full Truckload', 'Less than Truckload', 'Parcel'];
        $cargoTypes = ['Electronics', 'Furniture', 'Food', 'Clothing'];
        $statuses = ['Pending', 'Accepted', 'Rejected'];

        foreach ($shippers as $shipper) {
            // 1 job per shipper
            $pickup = $cities[array_rand($cities)];
            $delivery = $cities[array_rand($cities)];

            $job = JobPost::create([
                'job_id' => 'JOB-' . strtoupper(Str::random(6)),
                'user_id' => $shipper->id,
                'package_name' => 'Package for ' . $shipper->name,
                'shipment_type' => $shipmentTypes[array_rand($shipmentTypes)],
                'priority' => ['Standard', 'Express', 'Urgent'][array_rand(['Standard', 'Express', 'Urgent'])],
                'pickup_address' => Str::random(15) . ' Street',
                'pickup_city' => $pickup['city'],
                'pickup_state' => $pickup['state'],
                'pickup_zip' => rand(10000, 99999),
                'pickup_latitude' => $pickup['lat'],
                'pickup_longitude' => $pickup['lng'],
                'delivery_address' => Str::random(15) . ' Avenue',
                'delivery_city' => $delivery['city'],
                'delivery_state' => $delivery['state'],
                'delivery_zip' => rand(10000, 99999),
                'delivery_latitude' => $delivery['lat'],
                'delivery_longitude' => $delivery['lng'],
                'cargo_type' => $cargoTypes[array_rand($cargoTypes)],
                'weight' => rand(10, 1000),
                'weight_type' => 'kg',
                'quantity' => rand(1, 50),
                'length' => rand(1, 10),
                'width' => rand(1, 10),
                'height' => rand(1, 10),
                'pickup_date' => $now->addDays(rand(1, 5))->toDateString(),
                'pickup_time' => $now->addHours(rand(1, 24))->toTimeString(),
                'delivery_date' => $now->addDays(rand(6, 10))->toDateString(),
                'delivery_time' => $now->addHours(rand(25, 48))->toTimeString(),
                'is_urgent_shipment' => rand(0, 1),
                'flexible_with_pickup' => rand(0, 1),
                'temperature_controlled' => rand(0, 1),
                'fragile_handling' => rand(0, 1),
                'hazardous_materials' => rand(0, 1),
                'additional_instructions' => 'Handle with care',
                'budget_amount' => rand(100, 5000),
                'currency' => 'USD',
                'delivery_status' => ['Pending', 'Delayed', 'Complete', 'In_Transport'][array_rand(['Pending', 'Delayed', 'Complete', 'In_Transport'])],
                'tracking_status' => null,
                'tracking_location' => null,
                'tracking_date' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // -----------------------------
            // Assign job applications automatically
            // -----------------------------
            // Assign job applications randomly
            $applyingTruckers = $truckers->random(rand(1, $truckers->count()));
            foreach ($applyingTruckers as $trucker) {
                JobApplication::create([
                    'job_post_id' => $job->id,
                    'user_id' => $trucker->id,
                    'status' => $statuses[array_rand($statuses)],
                    'assigned_at' => now()->subDays(rand(1,30)),
                ]);
            }
        }
    }
}
