<?php

namespace Database\Seeders;

use App\Models\JobPost;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JobPostSeeder extends Seeder
{
    public function run(): void
    {
        $shippers = User::where('user_type', 'shipper')->take(10)->get();
        $trackers = User::where('user_type', 'tracker')->take(10)->get();
        $year = now()->year;

        $lastJob = JobPost::whereYear('created_at', $year)
            ->orderByDesc('created_at')
            ->first();

        if ($lastJob) {
            preg_match('/JOB-\d{4}-(\d{3})-?/', $lastJob->job_id, $matches);
            $lastSequence = isset($matches[1]) ? (int)$matches[1] : 0;
        } else {
            $lastSequence = 0;
        }

        $jobPosts = [];
        foreach ($shippers as $index => $shipper) {
            $newSequence = $lastSequence + $index + 1;
            $sequence = str_pad($newSequence, 3, '0', STR_PAD_LEFT);

            $jobPosts[] = JobPost::create([
                'job_id' => "JOB-{$year}-{$sequence}",
                'user_id' => $shipper->id,
                'package_name' => 'Sample Package',
                'shipment_type' => 'Air',
                'priority' => 'Standard',
                'pickup_address' => '123 Pickup St.',
                'pickup_city' => 'Dhaka',
                'pickup_state' => 'Dhaka',
                'pickup_zip' => '1200',
                'delivery_address' => '456 Delivery Rd.',
                'delivery_city' => 'Chittagong',
                'delivery_state' => 'Chittagong',
                'delivery_zip' => '4000',
                'pickup_latitude' => 23.8103,
                'pickup_longitude' => 90.4125,
                'cargo_type' => 'Electronics',
                'weight' => 50.75,
                'weight_type' => 'kg',
                'quantity' => 10,
                'length' => 100,
                'width' => 50,
                'height' => 60,
                'pickup_date' => now()->addDays(2)->toDateString(),
                'pickup_time' => '10:00:00',
                'delivery_date' => now()->addDays(5)->toDateString(),
                'delivery_time' => '15:00:00',
                'is_urgent_shipment' => false,
                'flexible_with_pickup' => true,
                'temperature_controlled' => false,
                'fragile_handling' => true,
                'hazardous_materials' => false,
                'additional_instructions' => 'Handle with care.',
                'budget_amount' => 5000,
                'currency' => 'USD',
                'delivery_status' => 'Pending',
            ]);
        }

        // Trackers you want to specifically assign jobs to:
        $tracker1 = User::find(2);
        $tracker2 = User::find(3);

        if (!$tracker1 || !$tracker2) {
            $this->command->error('Tracker with ID 1 or 2 not found!');
            return;
        }

        // Assign at least 3 jobs to tracker1
        for ($i = 0; $i < 3; $i++) {
            DB::table('job_applications')->insert([
                'user_id' => $tracker1->id,
                'job_post_id' => $jobPosts[$i]->id,
                'assigned_at' => now(),
                'status' => 'accepted',
                'rejection_reason' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Assign at least 5 jobs to tracker2 (jobs from index 3 to 7)
        for ($i = 3; $i < 8; $i++) {
            DB::table('job_applications')->insert([
                'user_id' => $tracker2->id,
                'job_post_id' => $jobPosts[$i]->id,
                'assigned_at' => now(),
                'status' => 'accepted',
                'rejection_reason' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // For remaining jobs (if any), assign randomly from other trackers
        $otherTrackers = $trackers->whereNotIn('id', [1, 2])->values();

        for ($i = 8; $i < count($jobPosts); $i++) {
            if ($otherTrackers->isEmpty()) break;

            $tracker = $otherTrackers->random();

            DB::table('job_applications')->insert([
                'user_id' => $tracker->id,
                'job_post_id' => $jobPosts[$i]->id,
                'assigned_at' => now(),
                'status' => 'accepted',
                'rejection_reason' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
