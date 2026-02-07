<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignUuid('event_participant_id')->nullable()->constrained('event_participants')->onDelete('set null');
            
            // Manual entry fields (jika tidak dari preloaded roster)
            $table->string('manual_name')->nullable();
            $table->string('manual_phone', 20)->nullable();
            $table->string('manual_origin')->nullable(); // Untuk kunjungan tamu
            $table->string('manual_org_type', 50)->nullable(); // PRM/PCM/PDM/etc
            $table->string('manual_org_name')->nullable();
            $table->integer('group_count')->nullable(); // Jumlah rombongan
            
            // Additional data (JSON untuk custom fields)
            $table->jsonb('answers_json')->nullable();
            
            // Timestamp check-in
            $table->timestamp('checked_in_at')->useCurrent();
            $table->timestamps();
            
            // Indexes
            $table->index(['event_id', 'checked_in_at']);
            $table->index('event_participant_id');
            
            // Prevent double check-in
            $table->unique(['event_id', 'event_participant_id'], 'unique_event_participant_checkin');
            // Unique untuk manual entry berdasarkan phone (kalau ada)
            // Note: PostgreSQL unique constraint with nullable doesn't work perfectly, 
            // we'll handle this in application logic
        });
        
        // Add partial unique index for manual phone (exclude nulls)
        DB::statement('CREATE UNIQUE INDEX unique_event_manual_phone ON attendances (event_id, manual_phone) WHERE manual_phone IS NOT NULL');
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS unique_event_manual_phone');
        Schema::dropIfExists('attendances');
    }
};
