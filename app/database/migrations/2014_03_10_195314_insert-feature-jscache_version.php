<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertFeatureJscacheVersion extends Migration {

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
            'name' => 'js_cache_version',
            'value' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ));
        DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'save',
            'name' => 'canmodels_cache_version',
            'value' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ));
        DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'save',
            'name' => 'css_cache_version',
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
        DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'js_cache_version')->delete();
        DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'canmodels_cache_version')->delete();
        DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'css_cache_version')->delete();
    }

}
