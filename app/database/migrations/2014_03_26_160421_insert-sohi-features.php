<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertSohiFeatures extends Migration {

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
            'name' => 'generic_quote',
            'value' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'description' => 'Can a user get a generic quote?'
        ));

        DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'save',
            'name' => 'top_city_filter',
            'value' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'description' => 'Filter top cities on search.'
        ));

        DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'save',
            'name' => 'top_ten_filter',
            'value' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'description' => 'Filter top ten of categories and cities on search.'
        ));

        DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'save',
            'name' => 'banner_radius',
            'value' => 20,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'description' => 'Radius of Banner.'
        ));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'generic_quote')->delete();
		DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'top_city_filter')->delete();
		DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'top_ten_filter')->delete();
		DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'banner_radius')->delete();
	}

}
