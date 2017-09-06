<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLookupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//doc_type
//prod_type_code
//prod_status_code
//prod_attrib_code
//uom_class

        Schema::create('lookups', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string("type",30);
            $table->string("code",15);
            $table->string("meaning",100);            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *++++++++++++++++++ ++
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lookups');
    }
}
