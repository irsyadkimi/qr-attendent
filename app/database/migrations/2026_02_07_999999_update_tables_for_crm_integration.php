<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            if (! Schema::hasColumn('contacts', 'address')) {
                $table->text('address')->nullable()->after('position');
            }

            if (! Schema::hasColumn('contacts', 'is_member')) {
                $table->boolean('is_member')->default(false)->after('address');
            }
        });

        Schema::table('event_participants', function (Blueprint $table) {
            if (! Schema::hasColumn('event_participants', 'contact_id')) {
                $table->foreignUuid('contact_id')
                    ->nullable()
                    ->after('event_id')
                    ->constrained('contacts')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('event_participants', function (Blueprint $table) {
            if (Schema::hasColumn('event_participants', 'contact_id')) {
                $table->dropForeign(['contact_id']);
                $table->dropColumn('contact_id');
            }
        });

        Schema::table('contacts', function (Blueprint $table) {
            if (Schema::hasColumn('contacts', 'is_member')) {
                $table->dropColumn('is_member');
            }

            if (Schema::hasColumn('contacts', 'address')) {
                $table->dropColumn('address');
            }
        });
    }
};
