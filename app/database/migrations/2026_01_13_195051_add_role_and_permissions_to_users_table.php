<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'operator'])->default('operator')->after('email');
            $table->boolean('can_delete')->default(false)->after('role');
            $table->boolean('can_create_event')->default(true)->after('can_delete');
            $table->boolean('can_manage_users')->default(false)->after('can_create_event');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'can_delete', 'can_create_event', 'can_manage_users']);
        });
    }
};
