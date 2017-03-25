<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertFinancingSubcategory extends Migration {

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
            'name' => 'Financing',
            'slug' => 'financing',
            'tags' => '',
            'parent_id' => '10',
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
        DB::table('categories')->where('slug', 'financing')->delete();
    }

}
