<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVehicleTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//DB::statement("ALTER TABLE quotes CHANGE project_tag_name project_tag_slug VARCHAR(255)");

		Schema::table('merchants', function($table)
        {
        	$table->integer('old_id');
        	$table->string('vendor');
        });

        Schema::table('used_vehicles', function($table)
        {
        	$table->integer('merchant_id');
        	$table->integer('location_id');
        	$table->double('latm');
			$table->double('lngm');
        	$table->integer('old_dealer_id');
        	$table->string('vendor');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('merchants', function($table)
        {
        	$table->dropColumn('old_id');
        	$table->dropColumn('vendor');
        });

        Schema::table('used_vehicles', function($table)
        {
        	$table->dropColumn('merchant_id');
        	$table->dropColumn('location_id');
        	$table->dropColumn('latm');
			$table->dropColumn('lngm');
        	$table->dropColumn('old_dealer_id');
        	$table->dropColumn('vendor');
        });
	}

}
