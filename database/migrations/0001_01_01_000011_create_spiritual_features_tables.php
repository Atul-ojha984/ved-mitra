<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kundlis
        Schema::create('kundlis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('full_name');
            $table->date('dob');
            $table->time('birth_time');
            $table->string('birth_place');
            $table->json('kundli_data')->nullable();
            $table->timestamps();
        });

        // E-Books
        Schema::create('ebooks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category'); // aarti, katha, festival, puja_vidhi
            $table->text('description')->nullable();
            $table->text('content')->nullable(); // full text content for inline reading
            $table->string('file_url')->nullable();
            $table->string('cover_image')->nullable();
            $table->boolean('is_published')->default(true);
            $table->integer('views')->default(0);
            $table->timestamps();
        });

        // Festivals
        Schema::create('festivals', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('tithi')->nullable();
            $table->date('festival_date');
            $table->text('description')->nullable();
            $table->string('category')->nullable(); // vrat, festival, eclipse, tithi
            $table->string('image')->nullable();
            $table->boolean('is_major')->default(false);
            $table->timestamps();
        });

        // Rashis (Zodiac)
        Schema::create('rashis', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Aries, Taurus etc
            $table->string('hindi_name');
            $table->string('symbol');
            $table->string('element');
            $table->text('daily_prediction')->nullable();
            $table->text('weekly_prediction')->nullable();
            $table->text('monthly_prediction')->nullable();
            $table->string('lucky_color')->nullable();
            $table->string('lucky_number')->nullable();
            $table->text('career_prediction')->nullable();
            $table->text('health_prediction')->nullable();
            $table->text('relationship_prediction')->nullable();
            $table->date('prediction_date')->nullable();
            $table->timestamps();
        });

        // Chat Messages
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('receiver_id')->constrained('users')->cascadeOnDelete();
            $table->text('message');
            $table->string('attachment')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        // Samagri Items
        Schema::create('samagri_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->string('item_name');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });

        // Add google_id to users
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_id')->nullable()->after('avatar');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('google_id');
        });
        Schema::dropIfExists('samagri_items');
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('rashis');
        Schema::dropIfExists('festivals');
        Schema::dropIfExists('ebooks');
        Schema::dropIfExists('kundlis');
    }
};
