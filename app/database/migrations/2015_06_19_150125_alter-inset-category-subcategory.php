<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInsetCategorySubcategory extends Migration {

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
            'name' => 'Community Centers',
            'slug' => 'community-centers',
            'tags' => '',
            'parent_id' => '9',
            'above_heading' => '',
            'footer_heading' => '',
            'title' => '',
            'description' => ''
        ));

        DB::table('categories')->insert(array(
            'created_at' => DB::raw('NOW()'),
            'updated_at' => DB::raw('NOW()'),
            'name' => 'Event Centers',
            'slug' => 'event-centers',
            'tags' => '',
            'parent_id' => '9',
            'above_heading' => '',
            'footer_heading' => '',
            'title' => '',
            'description' => ''
        ));

        DB::table('categories')->insert(array(
            'created_at' => DB::raw('NOW()'),
            'updated_at' => DB::raw('NOW()'),
            'name' => 'Financial Services',
            'slug' => 'financial-services',
            'tags' => '',
            'parent_id' => '9',
            'above_heading' => '',
            'footer_heading' => '',
            'title' => '',
            'description' => ''
        ));

        // Redorder Categories
        DB::table('categories')->where('id', '9')->update(array(
            'category_order' => '10'
        ));
        DB::table('categories')->where('id', '5')->update(array(
            'category_order' => '9'
        ));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::table('categories')->where('slug', 'community-centers')->delete();
		DB::table('categories')->where('slug', 'event-centers')->delete();
		DB::table('categories')->where('slug', 'financial-services')->delete();

		DB::table('categories')->where('id', '9')->update(array(
            'category_order' => '9'
        ));
        DB::table('categories')->where('id', '5')->update(array(
            'category_order' => '10'
        ));
	}

}
