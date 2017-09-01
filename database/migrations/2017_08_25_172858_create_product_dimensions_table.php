<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductDimensionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_dimensions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products');      
            $table->double('weight')->nullable();
            $table->double('width')->nullable();
            $table->double('height')->nullable();
            $table->double('length')->nullable();
            $table->double('diameter')->nullable();
            $table->double('volume')->nullable();
            $table->double('cube',15)->nullable();
            $table->string('weight_uom',15)->nullable();
            $table->string('width_uom',15)->nullable();
            $table->string('height_uom',15)->nullable();
            $table->string('length_uom',15)->nullable();
            $table->string('diameter_uom',15)->nullable();
            $table->string('volume_uom',15)->nullable();
            $table->string('cube_uom',15)->nullable();
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
        Schema::dropIfExists('product_dimensions');
    }
}
