<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('experience_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor (User::class)->constrained();
            $table->string('experience')->comment ('1 to 30 years');
            $table->enum('vehicle_type',['semi_truck','box_truck','flatbed','refrigerated']);
            $table->enum('service_area',['regional','international','national']);
            $table->string('additional_information')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experience_preferences');
    }
};
