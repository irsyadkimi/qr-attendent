<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('contacts', function (Blueprint $table) {
            if (!Schema::hasColumn('contacts', 'is_member')) {
                $table->boolean('is_member')->default(false)->after('address');
            }
        });

        Schema::table('event_participants', function (Blueprint $table) {
            if (!Schema::hasColumn('event_participants', 'contact_id')) {
                // Pakai foreignUuid karena tabel contacts pakai UUID
                $table->foreignUuid('contact_id')->nullable()->after('event_id')->constrained('contacts')->onDelete('set null');
            }
        });
    }

    public function down() {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn('is_member');
        });
        Schema::table('event_participants', function (Blueprint $table) {
            $table->dropForeign(['contact_id']);
            $table->dropColumn('contact_id');
        });
    }
};
