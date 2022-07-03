<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_image');
            $table->string('product_id')->unique();
            $table->string('product_type');
            $table->string('product_name');
            // $table->string('product_department_id');
            $table->unsignedInteger('product_stock');
            $table->unsignedInteger('product_price');
            $table->unsignedInteger('product_rating');
            $table->unsignedInteger('product_sales');
            $table->unsignedBigInteger('product_parent_id')->nullable();
            $table->foreignId('category_id')->constrained();
            $table->foreignId('material_id')->constrained();
            $table->foreignId('color_id')->constrained();
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreign('product_parent_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
