<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserImpressionsVehicleModel extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_impressions', function($table)
        {
            $table->integer('vehicle_model_id');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user_impressions', function($table)
        {
            $table->dropColumn('vehicle_model_id');
        });
	}

}
