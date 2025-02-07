<?php

use App\Patterns\Role\RequesterRole;
use App\Patterns\State\Meeting\MeetingStatus;
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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('requester_id')->constrained('users')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('receiver_id')->constrained('users')->restrictOnDelete()->cascadeOnUpdate();
            $table->text('reason')->nullable();
            $table->enum('requester_role', [
                RequesterRole::Supplier->value,
                RequesterRole::Buyer->value,
                RequesterRole::Both->value,
            ])->nullable();
            $table->enum('status',[
                MeetingStatus::Accepted->value,
                MeetingStatus::Rejected->value,
                MeetingStatus::Pending->value,
            ])->default(MeetingStatus::Pending->value);
            $table->integer('assigned_table')->nullable();
            $table->time('time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
