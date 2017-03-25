<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertCategoryAdvertsing extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::table('categories')->insert(array(
            'created_at' => DB::raw('NOW()'),
            'updated_at' => DB::raw('NOW()'),
            'name' => 'Advertising',
            'slug' => 'advertising',
            'tags' => '',
            'parent_id' => '9',
            'above_heading' => '',
            'footer_heading' => '',
            'title' => '',
            'description' => ''
        ));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::table('categories')->where('slug', 'advertising')->delete();
	}

}
