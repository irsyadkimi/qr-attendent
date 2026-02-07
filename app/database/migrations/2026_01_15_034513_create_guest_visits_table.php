<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guest_visits', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('full_name');
            $table->string('phone', 20);
            $table->string('organization_name')->nullable();
            $table->string('organization_type')->nullable();
            $table->string('agenda')->nullable();
            $table->string('location')->nullable();
            $table->integer('group_count')->default(1);
            $table->text('notes')->nullable();
            $table->timestamp('visited_at');
            $table->timestamps();
            
            $table->index(['visited_at', 'organization_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guest_visits');
    }
};
