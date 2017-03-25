<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsedVehiclesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('used_vehicles', function($table)
        {
        	$table->string('name');
        	$table->integer('style_id');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('used_vehicles', function($table)
        {
        	$table->dropColumn('name');
        	$table->dropColumn('style_id');
        });
	}

}
