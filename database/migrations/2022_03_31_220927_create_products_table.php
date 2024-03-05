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
            $table->increments('id');
            $table->longText('store')->nullable();
            $table->longText('post_title')->nullable();
            $table->longText('post_name')->nullable();
            $table->integer('hmr_id')->nullable();
            $table->longText('post_excerpt')->nullable();
            $table->longText('post_content')->nullable();
            $table->string('post_status')->nullable();
            $table->dateTime('post_date')->nullable();
            $table->text('sku')->nullable();
            $table->integer('stock')->nullable();
            $table->decimal('regular_price',13,3)->default(0)->nullable();
            $table->decimal('sale_price',13,3)->default(0)->nullable();
            $table->string('weight')->nullable();
            $table->string('length')->nullable();
            $table->string('width')->nullable();
            $table->string('height')->nullable();
            $table->string('stock_status')->nullable();
            $table->string('backorders')->nullable();
            $table->string('manage_stock')->nullable();
            $table->string('tax_status')->nullable();
            $table->json('images')->nullable();
            $table->longText('tax_product_cat')->nullable();
            $table->longText('tax_product_tag')->nullable();
            $table->longText('tax_product_shipping_class')->nullable();
            $table->integer('store_id')->nullable();
            $table->integer('store_reference_id')->nullable();
            $table->json('categories')->nullable();
            $table->json('sub_categories')->nullable();
            $table->json('tags')->nullable();
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
