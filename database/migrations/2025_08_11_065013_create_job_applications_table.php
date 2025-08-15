<?php

use App\Models\JobPost;
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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor (User::class)->constrained ()->onDelete ('cascade');
            $table->foreignIdFor (JobPost::class)->constrained ()->onDelete ('cascade');
            $table->timestamp('assigned_at')->nullable();
            $table->enum ('status', ['applied', 'accepted', 'rejected'])->default('applied');
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
