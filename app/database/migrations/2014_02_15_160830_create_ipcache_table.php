<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIpcacheTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ipcache', function($table)
		{
		    $table->increments('id');
		    $table->string('ipaddress');
		    $table->string('longitude')->nullable();
		    $table->string('latitude')->nullable();
		    $table->string('city')->nullable();
		    $table->string('state')->nullable();
		    $table->string('postalcode')->nullable();
		    $table->string('country')->nullable();
		    $table->unique('ipaddress');
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
		Schema::dropIfExists('ipcache');
	}

}
