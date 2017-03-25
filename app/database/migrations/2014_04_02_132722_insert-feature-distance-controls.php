<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertFeatureDistanceControls extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'save',
            'name' => 'search_dist',
            'value' => 60000,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'description' => 'Search radius in meters.'
        ));

        DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'save',
            'name' => 'category_dist',
            'value' => 60000,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'description' => 'Category search radius in meters.'
        ));

        DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'save',
            'name' => 'recommendation_dist',
            'value' => 60000,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'description' => 'Recommendation search radius in meters.'
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'search_dist')->delete();
        DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'category_dist')->delete();
        DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'recommendation_dist')->delete();
    }

}
