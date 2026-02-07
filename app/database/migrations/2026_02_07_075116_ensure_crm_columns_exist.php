<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        if (Schema::hasTable('contacts')) {
            Schema::table('contacts', function (Blueprint $table) {
                if (!Schema::hasColumn('contacts', 'address')) $table->text('address')->nullable();
                if (!Schema::hasColumn('contacts', 'is_member')) $table->boolean('is_member')->default(false);
            });
        }
        if (Schema::hasTable('event_participants')) {
            Schema::table('event_participants', function (Blueprint $table) {
                if (!Schema::hasColumn('event_participants', 'contact_id')) {
                    $table->foreignUuid('contact_id')->nullable()->constrained('contacts')->nullOnDelete();
                }
            });
        }
    }
    public function down() {
        Schema::table('event_participants', function (Blueprint $table) { $table->dropColumn('contact_id'); });
        Schema::table('contacts', function (Blueprint $table) { $table->dropColumn(['address', 'is_member']); });
    }
};
