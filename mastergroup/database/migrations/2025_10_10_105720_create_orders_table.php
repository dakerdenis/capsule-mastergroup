<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('number', 32)->unique();
            $table->unsignedInteger('total_cps')->default(0);

            // ordered | completed | cancelled
            $table->string('status', 20)->default('ordered');
            $table->timestamp('executed_at')->nullable();

            $table->timestamps();
            $table->index(['user_id','status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};