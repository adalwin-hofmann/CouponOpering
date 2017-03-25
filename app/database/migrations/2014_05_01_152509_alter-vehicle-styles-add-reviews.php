<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVehicleStylesAddReviews extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('vehicle_styles', function($table)
        {
        	$table->float('rating');
        	$table->float('rating_count');
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
        	$table->dropColumn('rating');
        	$table->dropColumn('rating_count');
        });
	}

}
