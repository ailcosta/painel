<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePEAdvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Product_exhibition_advertisements', function (Blueprint $table) {
            $table->engine = 'InnoDB';            
            $table->increments('id');
            
            $table->integer('product_exhibition_id')
                        ->unsigned();
            $table->foreign('product_exhibition_id')
                        ->references('id')
                        ->on('product_exhibitions');

            $table->integer('category_id')
                        ->unsigned();
            $table->foreign('category_id')
                        ->references('id')
                        ->on('categories');

            $table->string("marketplace_adv_id",80);
            $table->string("marketplace_adv_url",255);
            $table->timestamp('verifyed_at')->nullable();

            $table->string("prod_exhib_status_code",15);            
            $table->double("price");
            $table->boolean("price_updatable")->default(true);
            $table->timestamp('price_update_required_at')->nullable();
            $table->timestamp('price_updated_at')->nullable();

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
        Schema::dropIfExists('Product_exhibition_advertisements');
    }
}
