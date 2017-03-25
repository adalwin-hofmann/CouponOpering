<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCategoriesHomeImprovement extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        DB::table('categories')->where('name', '=', 'Home Improvement')->update(array('name' => 'Home Improvement - Old', 'slug' => 'home-improvement-old'));
        DB::table('categories')->where('slug', '=', 'home-services')->update(array('name' => 'Home Improvement', 'slug' => 'home-improvement'));
        DB::table('entities')->where('category_id', '=', '231')->update(array('category_slug' => 'home-improvement'));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        DB::table('entities')->where('category_id', '=', '231')->update(array('category_slug' => 'home-services'));
        DB::table('categories')->where('slug', '=', 'home-improvement')->update(array('name' => 'Home Services', 'slug' => 'home-services'));
		DB::table('categories')->where('name', '=', 'Home Improvement - Old')->update(array('name' => 'Home Improvement', 'slug' => 'home-improvement'));
	}

}
