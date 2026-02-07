<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guest_visits', function (Blueprint $table) {
            $table->date('visit_end_date')->nullable()->after('visited_at');
            $table->integer('duration_days')->nullable()->after('visit_end_date');
        });
    }

    public function down(): void
    {
        Schema::table('guest_visits', function (Blueprint $table) {
            $table->dropColumn(['visit_end_date', 'duration_days']);
        });
    }
};
