<?php

use App\Patterns\State\Event\EventStatus;
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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('image_path')->nullable();
            $table->timestamp('date');
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->time('meeting_duration');
            $table->time('time_between_meetings');
            $table->timestamp('inscription_end_date');
            $table->timestamp('promotion_end_date');
            $table->enum('state', [
                EventStatus::Registration->value,
                EventStatus::Promotion->value,
                EventStatus::Matching->value,
            ])->default(EventStatus::Registration->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
