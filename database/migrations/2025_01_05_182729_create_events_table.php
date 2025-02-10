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
            $table->foreignId('responsible_id')->constrained('users')->restrictOnDelete()->cascadeOnUpdate();;
            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->string('location')->nullable();
            $table->string('logo_path')->nullable();
            $table->date('date');
            $table->time('starts_at')->nullable();
            $table->time('ends_at')->nullable();
            $table->integer('meeting_duration')->unsigned()->nullable();
            $table->integer('time_between_meetings')->unsigned()->nullable();
            $table->date('inscription_end_date')->nullable();
            $table->date('matching_end_date')->nullable();
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
