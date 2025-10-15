<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('codes', function (Blueprint $table) {
            $table->id();

            // сам код (уникальный, вручную вводится админом)
            $table->string('code', 128)->unique();

            // тип кода (5 типов; значения можно поменять позже)
            $table->enum('type', ['welcome','promo','gift','compensation','referral'])->index();

            // статус кода
            $table->enum('status', ['new','activated'])->default('new')->index();

            // кто и когда активировал (после активации не меняется)
            $table->foreignId('activated_by_user_id')->nullable()->constrained('users')->nullOnDelete()->index();
            $table->timestamp('activated_at')->nullable()->index();

            $table->timestamps();

            // быстрый поиск по коду (LIKE)
            $table->index('code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('codes');
    }
};
