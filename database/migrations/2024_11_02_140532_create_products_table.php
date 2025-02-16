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
            $table->bigInteger('category_id')->unsigned();
            $table->bigInteger('brand_id')->unsigned();
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->string('name', 100);
            $table->decimal('sell_price', 10, 2);
            $table->decimal('wholesale_price', 10, 2)->nullable();
            $table->decimal('buy_price', 10, 2);
            $table->bigInteger('bar_code');
            $table->integer('stock');
            $table->longText('description')->nullable();
            $table->enum('state', ['INACTIVO', 'ACTIVO'])->default('ACTIVO');
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
