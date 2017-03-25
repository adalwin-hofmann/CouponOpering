<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVehicleStylesPopularityScores extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('vehicle_styles', function($table)
        {
            $table->integer('popularity_score');
            $table->index('model_id', 'vehicle_styles_model_id_index');
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
            $table->dropColumn('popularity_score');
            $table->dropIndex('vehicle_styles_model_id_index');
        });
    }

}
