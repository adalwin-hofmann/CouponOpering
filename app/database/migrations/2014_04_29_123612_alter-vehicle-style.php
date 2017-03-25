<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVehicleStyle extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('vehicle_styles', function($table)
        {
        	$table->string('slug');
        	$table->string('make_slug');
        	$table->string('model_slug');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('vehicle_styles', function($table)
        {
        	$table->dropColumn('slug');
        	$table->dropColumn('make_slug');
        	$table->dropColumn('model_slug');
        });
	}

}
