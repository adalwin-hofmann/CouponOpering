<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVehicleStylesYearAssetIndexes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('vehicle_styles', function($table)
        {
            $table->index('year', 'year_index');
        });

        Schema::table('vehicle_assets', function($table)
        {
            $table->index(array('style_id', 'shot_type'), 'style_shot_index');
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
            $table->dropIndex('year_index');
        });

        Schema::table('vehicle_assets', function($table)
        {
            $table->dropIndex('style_shot_index');
        });
    }

}
