<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductExhibitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_exhibitions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('product_id')
                        ->unsigned();
            $table->foreign('product_id')
                        ->references('id')
                        ->on('products');
            $table->integer('marketplace_id')
                        ->unsigned();
            $table->foreign('marketplace_id')
                        ->references('id')
                        ->on('marketplaces');
            $table->string("prod_exhib_status_code",15);

            $table->timestamp('image_update_required_at');
            $table->timestamp('image_updated_at');
            $table->timestamp('attribute_update_required_at');
            $table->timestamp('attribute_updated_at');
            $table->timestamp('product_update_required_at');
            $table->timestamp('product_updated_at');

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
        Schema::dropIfExists('product_exhibitions');
    }
}
