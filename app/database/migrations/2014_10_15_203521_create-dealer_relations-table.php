<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDealerRelationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('dealer_relations', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->string('dealer_id');
            $table->string('dealer_id_type');
            $table->string('dealer_name');
            $table->string('dealer_address');
            $table->string('dealer_city');
            $table->string('dealer_state');
            $table->string('dealer_zipcode');
            $table->string('dealer_phone');
            $table->integer('franchise_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dealer_relations');
    }

}
