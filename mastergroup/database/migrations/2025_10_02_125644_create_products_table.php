<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // связь с категориями (обязательная)
            $table->foreignId('category_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();

            $table->string('name');
            $table->string('code')->unique();       // уникальный код товара
            $table->string('slug')->unique();       // SEO-URL
            $table->string('type')->index();        // произвольный тип/серия/линейка
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2)->default(0);

            $table->timestamps();

            $table->index(['category_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
