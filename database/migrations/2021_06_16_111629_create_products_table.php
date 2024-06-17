<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
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

            $table->string('name');

            $table->foreignId('brand_id');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');

            $table->foreignId('catogory_id');
            $table->foreign('catogory_id')->references('id')->on('categories')->onDelete('cascade');

            $table->string('primary_image');
            $table->text('description');
            $table->unsignedInteger('price')->default(0);
            $table->unsignedInteger('quantity')->default(0);
            $table->unsignedInteger('delivery_amount')->default(0);
            $table->softDeletes();

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
        Schema::dropIfExists('products');
    }
}
