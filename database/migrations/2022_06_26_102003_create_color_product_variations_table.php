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
        Schema::create('color_product_variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('color_id')->constrained();
            $table->foreignId('product_variation_id')->constrained();
            $table->unique(['color_id', 'product_variation_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('color_product_variations');
    }
};
