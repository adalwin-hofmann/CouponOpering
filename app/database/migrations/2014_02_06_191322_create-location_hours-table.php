<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationHoursTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('location_hours', function($table)
        {
            $table->timestamps();
            $table->increments('id');
            $table->string('weekday', 25);
            $table->string('start_time', 10);
            $table->string('start_ampm', 10);
            $table->string('end_time', 10);
            $table->string('end_ampm', 10);
            $table->integer('location_id');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
    {
        Schema::drop('location_hours');
    }

}
