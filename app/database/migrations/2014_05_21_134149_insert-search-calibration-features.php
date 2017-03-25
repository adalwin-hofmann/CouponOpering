<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertSearchCalibrationFeatures extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'search',
            'name' => 'search_about_weight',
            'value' => '2.38',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'description' => 'How heavily should this attribute affect search results?'
        ));

        DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'search',
            'name' => 'search_category_weight',
            'value' => '1.37',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'description' => 'How heavily should this attribute affect search results?'
        ));

        DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'search',
            'name' => 'search_city_weight',
            'value' => '1.27',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'description' => 'How heavily should this attribute affect search results?'
        ));

        DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'search',
            'name' => 'search_name_weight',
            'value' => '3.94',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'description' => 'WHow heavily should this attribute affect search results'
        ));

        DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'search',
            'name' => 'search_subcategory_weight',
            'value' => '1.7',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'description' => 'How heavily should this attribute affect search results?'
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'search')->where('name', '=', 'search_about_weight')->delete();
        DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'search')->where('name', '=', 'search_category_weight')->delete();
        DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'search')->where('name', '=', 'search_city_weight')->delete();
        DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'search')->where('name', '=', 'search_name_weight')->delete();
        DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'search')->where('name', '=', 'search_subcategory_weight')->delete();
    }

}
