<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        // Если таблица уже есть — выходим (ничего не делаем)
        if (Schema::hasTable('auth_activity_logs')) {
            return;
        }

        Schema::create('auth_activity_logs', function (Blueprint $table) {
            $table->id();

            // Отдельное поле + индекс с явным именем
            $table->unsignedBigInteger('user_id')->nullable();
            $table->index('user_id', 'auth_logs_user_id_idx');

            $table->string('guard', 32)->index();
            $table->enum('event', ['login','logout','login_failed'])->index();
            $table->string('email')->nullable()->index();
            $table->string('ip', 45)->nullable()->index();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent()->index();

            // ЯВНОЕ имя FK (при создании)
            $table->foreign('user_id', 'fk_auth_logs_user')
                ->references('id')->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void {
        // Если таблицы нет — выходим
        if (!Schema::hasTable('auth_activity_logs')) {
            return;
        }

        // 1) Снять FOREIGN KEY, как бы он ни назывался
        try {
            // Попробуем найти реальное имя внешнего ключа через INFORMATION_SCHEMA
            $dbName = DB::getDatabaseName();
            $fkRows = DB::select("
                SELECT CONSTRAINT_NAME
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'auth_activity_logs' AND REFERENCED_TABLE_NAME = 'users'
            ", [$dbName]);

            foreach ($fkRows as $row) {
                $fkName = $row->CONSTRAINT_NAME ?? null;
                if ($fkName) {
                    // Снять FK сырой командой — работает даже если имя другое
                    DB::statement("ALTER TABLE `auth_activity_logs` DROP FOREIGN KEY `{$fkName}`");
                }
            }
        } catch (\Throwable $e) {
            // тихо игнорируем, чтобы откат не падал
        }

        // 2) Снять индекс, если он есть
        try {
            $idx = DB::select("
                SHOW INDEX FROM `auth_activity_logs` WHERE Key_name = 'auth_logs_user_id_idx'
            ");
            if (!empty($idx)) {
                Schema::table('auth_activity_logs', function (Blueprint $table) {
                    $table->dropIndex('auth_logs_user_id_idx');
                });
            }
        } catch (\Throwable $e) {
            // игнор
        }

        // 3) Дропнуть таблицу
        Schema::dropIfExists('auth_activity_logs');
    }
};
