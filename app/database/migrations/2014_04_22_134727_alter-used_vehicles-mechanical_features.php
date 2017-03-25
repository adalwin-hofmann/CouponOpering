<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsedVehiclesMechanicalFeatures extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('used_vehicles', function($table)
        {
            $table->text('standard_mechanical_features');
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
            $table->dropColumn('standard_mechanical_features');
        });
    }

}
