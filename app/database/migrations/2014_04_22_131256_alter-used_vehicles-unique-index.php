<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsedVehiclesUniqueIndex extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('used_vehicles', function($table)
        {
            $table->unique(array('vin', 'merchant_id'));
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
            $table->dropUnique('used_vehicles_vin_merchant_id_unique');
        });
    }

}
