<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVehiceEntitiesIndexes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('vehicle_entities', function($table)
        {
            $table->index(array('state', 'internet_price', 'latm', 'lngm'));
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
            $table->dropIndex('vehicle_entities_state_internet_price_image_latm_lngm_index');
        });
    }

}
