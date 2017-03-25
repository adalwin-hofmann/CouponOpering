<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyEventsTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('company_events', function($table)
        {
        	$table->timestamps();
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->dateTime('date');
            $table->text('description');
            $table->text('path');
        });

        Schema::create('company_event_attendees', function($table)
        {
        	$table->timestamps();
            $table->increments('id');
            $table->string('name');
            $table->string('company');
            $table->string('email');
            $table->integer('company_event_id');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('company_events');
		Schema::dropIfExists('company_event_attendees');
	}

}
