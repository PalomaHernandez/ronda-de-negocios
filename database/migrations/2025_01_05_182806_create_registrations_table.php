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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participant_id')->constrained('users')->restrictOnDelete()->cascadeOnUpdate();;
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete()->cascadeOnUpdate();;
            $table->timestamp('inscription_date');
            $table->string('interests')->nullable();
            $table->string('products_services')->nullable();
            $table->integer('remaining_meetings')->nullable();
            $table->timestamps();

            $table->unique(['participant_id', 'event_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
