<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('codes', function (Blueprint $table) {
            if (!Schema::hasColumn('codes', 'bonus_cps')) {
                $table->unsignedInteger('bonus_cps')->nullable()->after('type')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('codes', function (Blueprint $table) {
            if (Schema::hasColumn('codes', 'bonus_cps')) {
                $table->dropIndex(['bonus_cps']);
                $table->dropColumn('bonus_cps');
            }
        });
    }
};
