<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->integer('variation_id')
                        ->unsigned()
                        ->nullable();
            $table->foreign('variation_id')
                        ->references('id')
                        ->on('products'); 

            $table->integer('brand_id')
                        ->unsigned()
                        ->nullable();
            $table->foreign('brand_id')
                        ->references('id')
                        ->on('brands');      

            $table->integer('supplier_id')
                        ->unsigned()
                        ->nullable();
            $table->foreign('supplier_id')
                        ->references('id')
                        ->on('suppliers');

            $table->integer('category_id')
                        ->unsigned()
                        ->nullable();
            $table->foreign('category_id')
                        ->references('id')
                        ->on('categories');


            $table->string("prod_type_code",15);
            $table->string("prod_status_code",15);
            $table->string("sku",45);
            $table->string("sku_supplier",45)->nullable();
            $table->string("name",120);
            $table->string("title",120);
            $table->string("bigTitle",240);
            $table->text("description");
            $table->text("bigDescription");
            $table->boolean("description_updatable")->default(true);
            $table->string("ean",13)->nullable();
            $table->string("ncm",8)->nullable();
            $table->string("isbn",13)->nullable();
            $table->string('uom_code',15);            
            $table->double('internal_qty')->nullable();
            $table->double('external_qty')->nullable();
            $table->double('minimum_quantity')->nullable();
            $table->integer('multiplicity')->nullable();
            $table->double('lowest_price');
            $table->double("base_price");
            $table->integer('lead_time')->nullable();
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
