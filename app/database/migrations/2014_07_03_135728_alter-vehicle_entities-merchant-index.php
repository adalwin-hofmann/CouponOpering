<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVehicleEntitiesMerchantIndex extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('vehicle_entities', function($table)
        {
            $table->index(array('merchant_id', 'make', 'model'));
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
            $table->dropIndex('vehicle_entities_merchant_id_make_model_index');
        });
    }

}
