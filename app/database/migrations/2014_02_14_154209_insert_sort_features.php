<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertSortFeatures extends Migration {

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
            'name' => 'search_page_sorting',
            'value' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ));

        DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'save',
            'name' => 'category_page_sorting',
            'value' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'search_page_sorting')->delete();
		DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'category_page_sorting')->delete();
	}

}
