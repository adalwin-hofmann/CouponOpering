<?php

use Illuminate\Database\Migrations\Migration;

class CreateRecoveryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('recovery', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->integer('user_id');
            $table->string('email');
            $table->string('recovery_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('recovery');
    }

}