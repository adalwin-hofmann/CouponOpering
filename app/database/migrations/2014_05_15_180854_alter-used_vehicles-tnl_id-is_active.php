<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsedVehiclesTnlIdIsActive extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('used_vehicles', function($table)
        {
            $table->dropUnique('used_vehicles_vin_merchant_id_unique');
            $table->boolean('is_active')->default(true);
            $table->index('is_active', 'is_active_index');
        });
        DB::statement("ALTER TABLE used_vehicles CHANGE tnl_id tnl_id VARCHAR(255)");
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
            $table->dropIndex('is_active_index');
            $table->dropColumn('is_active');
            $table->unique(array('vin', 'merchant_id'), 'used_vehicles_vin_merchant_id_unique');
        });
        DB::statement("ALTER TABLE used_vehicles CHANGE tnl_id tnl_id INT(11)");
    }

}
