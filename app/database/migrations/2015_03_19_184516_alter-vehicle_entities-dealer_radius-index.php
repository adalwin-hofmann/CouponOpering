<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVehicleEntitiesDealerRadiusIndex extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::connection('mysql-used')->table('vehicle_entities', function($table)
        {
            $table->index(array('state', 'internet_price', 'latm', 'lngm', 'dealer_radius'), 'vehicle_entities_dealer_radius_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql-used')->table('vehicle_entities', function($table)
        {
            $table->dropIndex('vehicle_entities_dealer_radius_index');
        });
    }

}
