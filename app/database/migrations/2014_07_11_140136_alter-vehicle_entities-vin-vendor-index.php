<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVehicleEntitiesVinVendorIndex extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('vehicle_entities', function($table)
        {
            $table->index(array('vin', 'vendor'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicle_entities', function($table)
        {
            $table->dropIndex('vehicle_entities_vin_vendor_index');
        });
    }

}
