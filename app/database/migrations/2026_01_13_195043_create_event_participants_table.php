<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_participants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('event_id')->constrained('events')->onDelete('cascade');
            $table->string('full_name');
            $table->string('phone', 20)->nullable();
            $table->enum('payment_status', ['unpaid', 'dp', 'paid', 'refund', 'na'])->nullable()->default('na');
            $table->string('seat_number', 20)->nullable();
            $table->string('group_label', 50)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['event_id', 'full_name']);
            $table->unique(['event_id', 'seat_number'], 'unique_event_seat');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_participants');
    }
};
