<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pandit_profile_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('day_of_week'); // 0=Sunday, 1=Monday ... 6=Saturday
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });

        // Blocked dates — pandit can manually block specific dates
        Schema::create('blocked_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pandit_profile_id')->constrained()->cascadeOnDelete();
            $table->date('blocked_date');
            $table->string('reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blocked_dates');
        Schema::dropIfExists('availabilities');
    }
};
