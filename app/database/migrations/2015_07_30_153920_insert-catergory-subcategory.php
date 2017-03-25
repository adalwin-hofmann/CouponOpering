<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertCatergorySubcategory extends Migration {

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
            'name' => 'Nutrition',
            'slug' => 'nutrition',
            'tags' => '',
            'parent_id' => '11',
            'above_heading' => '',
            'footer_heading' => '',
            'title' => '',
            'description' => ''
        ));

        DB::table('categories')->insert(array(
            'created_at' => DB::raw('NOW()'),
            'updated_at' => DB::raw('NOW()'),
            'name' => 'Arts & Crafts Classes',
            'slug' => 'arts-crafts-classes',
            'tags' => '',
            'parent_id' => '5',
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
		DB::table('categories')->where('slug', 'nutrition')->delete();
		DB::table('categories')->where('slug', 'arts-crafts-classes')->delete();
	}

}
