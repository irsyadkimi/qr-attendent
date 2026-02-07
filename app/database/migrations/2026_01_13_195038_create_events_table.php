<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('event_code', 8)->unique();
            $table->string('event_name');
            $table->enum('event_type', ['pengajian', 'rekreasi', 'kunjungan', 'rapat', 'custom'])->default('custom');
            $table->text('agenda_topic');
            $table->string('location');
            $table->date('date_start');
            $table->date('date_end')->nullable();
            $table->integer('capacity_expected')->nullable();
            $table->boolean('allow_manual_entry')->default(true);
            $table->boolean('allow_preloaded_select')->default(true);
            $table->foreignUuid('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index('event_code');
            $table->index('event_type');
            $table->index('date_start');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
