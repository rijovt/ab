<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTableMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('product_category_id');
            $table->unsignedDecimal('price', 10, 2);
            $table->unsignedDecimal('stock', 10, 2);
            $table->unsignedinteger('stock_defective')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('product_category_id')->references('id')->on('product_categories');
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
