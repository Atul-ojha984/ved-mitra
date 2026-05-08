<?php

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
        Schema::create('pandit_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Professional Details
            $table->text('bio')->nullable();
            $table->integer('experience_years')->default(0);
            $table->string('qualification')->nullable();
            $table->string('specialization')->nullable();
            $table->string('languages')->nullable(); // comma-separated
            $table->decimal('consultation_fee', 10, 2)->nullable();
            $table->string('available_timings')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('alternate_phone')->nullable();

            // Location
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode', 10)->nullable();
            $table->decimal('location_lat', 10, 8)->nullable();
            $table->decimal('location_lng', 11, 8)->nullable();

            // Documents
            $table->string('aadhaar_document')->nullable();
            $table->string('pan_document')->nullable();
            $table->string('certificate_document')->nullable();
            $table->string('profile_photo')->nullable();

            // Social Links
            $table->string('website_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('facebook_url')->nullable();

            // Approval System
            $table->boolean('verified')->default(false);
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pandit_profiles');
    }
};
