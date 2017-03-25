<?php

use Illuminate\Database\Migrations\Migration;

class CreateSweepstakesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sweepstakes', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->string('randomnum', 20);
            $table->dateTime('date');
            $table->integer('entity_id');
            $table->integer('user_id');
            $table->string('firstname', 50);
            $table->string('lastname', 50);
            $table->string('address1', 100);
            $table->string('address2', 100);
            $table->string('city', 50);
            $table->string('state', 2);
            $table->string('zipcode', 50);
            $table->string('email');
            $table->string('phone', 50);
            $table->string('magazinenum', 50);
            $table->dateTime('issuedate');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sweepstakes');
	}

}