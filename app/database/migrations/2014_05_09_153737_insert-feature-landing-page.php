<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertFeatureLandingPage extends Migration {

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
            'name' => 'special_landing_page',
            'value' => '0',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'description' => 'Show or hide the special landing page.'
        ));

        DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'save',
            'name' => 'featured_city_states',
            'value' => 'MI, IL, MN',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'description' => 'Determine what states will NOT see the special landing page.'
        ));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'special_landing_page')->delete();
		DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'featured_city_states')->delete();
	}

}
