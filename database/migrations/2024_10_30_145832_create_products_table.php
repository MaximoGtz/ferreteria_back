<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_category');
            $table->string('name', 100);
            $table->string('brand', 100);
            $table->decimal('sell_price', 10, 2);
            $table->decimal('buy_price', 10, 2);
            $table->bigInteger('bar_code');
            $table->integer('stock');
            $table->longText('description')->nullable();
            $table->enum('estado', ['INACTIVO', 'ACTIVO'])->default('ACTIVO');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
