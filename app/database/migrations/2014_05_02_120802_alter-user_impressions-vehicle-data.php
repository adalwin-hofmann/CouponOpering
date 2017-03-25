<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserImpressionsVehicleData extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('user_impressions', function($table)
        {
            $table->integer('used_vehicle_id');
            $table->integer('vehicle_style_id');
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
            $table->dropColumn('used_vehicle_id');
            $table->dropColumn('vehicle_style_id');
        });
    }

}
