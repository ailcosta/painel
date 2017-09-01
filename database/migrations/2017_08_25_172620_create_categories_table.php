<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string("name",120);
            $table->string("id_marketplace",120);
            $table->string("categ_status_code",15);

            $table->integer('parent_category_id')
                        ->unsigned()
                        ->nullable();
            $table->foreign('parent_category_id')
                        ->references('id')
                        ->on('categories');

            $table->integer('marketplace_id')
                        ->unsigned();
            $table->foreign('marketplace_id')
                        ->references('id')
                        ->on('marketplaces');

            $table->integer('internal_category_id')
                        ->unsigned()
                        ->nullable();
            $table->foreign('internal_category_id')
                        ->references('id')
                        ->on('categories');

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
        Schema::dropIfExists('categories');
    }
}
