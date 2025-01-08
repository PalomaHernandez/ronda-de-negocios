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
            $table->date('date');
            $table->time('starts_at')->nullable();
            $table->time('ends_at')->nullable();
            $table->time('meeting_duration')->nullable();
            $table->time('time_between_meetings')->nullable();
            $table->timestamp('inscription_end_date')->nullable();
            $table->timestamp('matching_end_date')->nullable();
            $table->enum('status', [
                EventStatus::Registration->value,
                EventStatus::Matching->value,
                EventStatus::Ended->value,
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
