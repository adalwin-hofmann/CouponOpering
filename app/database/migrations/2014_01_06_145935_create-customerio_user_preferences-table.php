<?php

use Illuminate\Database\Migrations\Migration;

class CreateCustomerioUserPreferencesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('customerio_user_preferences', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->integer('customerio_user_id');
            $table->integer('customerio_preference_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('customerio_user_preferences');
    }

}