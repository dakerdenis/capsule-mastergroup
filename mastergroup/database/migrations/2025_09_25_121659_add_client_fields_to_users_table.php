<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // тип клиента: individual | company
            $table->string('client_type', 20)->default('individual')->after('id'); 
            // ФИО в одном поле
            $table->string('full_name')->after('client_type');

            // общий набор
            $table->date('birth_date')->nullable()->after('full_name');
            $table->enum('gender', ['male','female','other'])->nullable()->after('birth_date');
            $table->string('country', 100)->nullable()->after('gender');
            $table->string('phone', 50)->nullable()->after('country');

            // медиа (пути к файлам в storage/public)
            $table->string('profile_photo_path')->nullable()->after('phone');       // оба типа: обязат. валидацией
            $table->string('identity_photo_path')->nullable()->after('profile_photo_path'); // только для individual (обяз. валидацией)
            $table->string('company_logo_path')->nullable()->after('identity_photo_path');  // только для company (обяз. валидацией)

            // доп. поля по типам
            $table->string('workplace')->nullable()->after('company_logo_path');     // individual: обязат. валидацией
            $table->string('instagram')->nullable()->after('workplace');            // company: необязательно

            // индексы
            $table->index('client_type');
            $table->index('phone');
            $table->index('country');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['client_type']);
            $table->dropIndex(['phone']);
            $table->dropIndex(['country']);

            $table->dropColumn([
                'client_type',
                'full_name',
                'birth_date',
                'gender',
                'country',
                'phone',
                'profile_photo_path',
                'identity_photo_path',
                'company_logo_path',
                'workplace',
                'instagram',
            ]);
        });
    }
};
