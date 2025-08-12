<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up (): void
    {
        Schema::create ('job_posts', function (Blueprint $table) {
            $table->id ();
            $table->string ('job_id')->unique ();
            $table->foreignIdFor (User::class)->constrained ()->onDelete ('cascade');
            // Basic information
            $table->string ('package_name');
            $table->string ('shipment_type');
            $table->enum ('priority', ['Standard', 'Express', 'Urgent'])->default ('Standard');
            // Pickup location
            $table->string ('pickup_address')->nullable ();
            $table->string ('pickup_city');
            $table->string ('pickup_state');
            $table->string ('pickup_zip');
            $table->float ('pickup_latitude');
            $table->float ('pickup_longitude');
            // Delivery location
            $table->string ('delivery_address');
            $table->string ('delivery_city');
            $table->string ('delivery_state');
            $table->string ('delivery_zip');
            // Cargo details
            $table->string ('cargo_type');
            $table->decimal ('weight', 8, 2);
            $table->string ('weight_type');
            $table->integer ('quantity');
            $table->decimal ('length', 8, 2);
            $table->decimal ('width', 8, 2);
            $table->decimal ('height', 8, 2);
            // Schedule
            $table->date ('pickup_date');
            $table->time ('pickup_time');
            $table->date ('delivery_date');
            $table->time ('delivery_time');
            // Additional options
            $table->boolean ('is_urgent_shipment')->default (false)->comment ('additional fees may apply for urgent shipment');
            $table->boolean ('flexible_with_pickup')->default (false);
            // Special requirements
            $table->boolean ('temperature_controlled')->default (false);
            $table->boolean ('fragile_handling')->default (false);
            $table->boolean ('hazardous_materials')->default (false);
            $table->text ('additional_instructions')->nullable ();
            $table->decimal ('budget_amount', 10, 2);
            $table->string ('currency');
            $table->enum ('delivery_status', ['Pending', 'Delayed', 'Complete', 'In_Transport'])->default ('Pending');
            $table->enum ('tracking_time', [
                'Customs Clearance',
                'Departed from Port',
                'In Transit',
                'Arrived at Port',
            ])->nullable ();
            $table->timestamps ();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down (): void
    {
        Schema::dropIfExists ('job_posts');
    }
};
