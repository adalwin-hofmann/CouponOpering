<?php

use Illuminate\Database\Migrations\Migration;

class CreateCustomerioPreferencesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('customerio_preferences', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->string('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('customerio_preferences');
    }

}