<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'cps_total')) {
                $table->unsignedInteger('cps_total')->default(0)->after('rejected_reason');
                $table->index('cps_total');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'cps_total')) {
                $table->dropIndex(['cps_total']);
                $table->dropColumn('cps_total');
            }
        });
    }
};
