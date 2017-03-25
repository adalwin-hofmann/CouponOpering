<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertSohiCategoryElevatorsStairlifts extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		\DB::table('project_tags')->insert(array('name' => 'Stairlifts/Elevators', 'slug' => 'stairlifts-elevators'));
        $elevators =\DB::table('project_tags')->where('name', 'Stairlifts/Elevators')->first(); 
        $services = \DB::table('project_tags')->where('name', 'Home Services')->first();
        $inside = \DB::table('project_tags')->where('name', 'Inside Your Home')->first();
        \DB::table('project_tag_relation')->insert(array(
            'parent_id' => $services->id,
            'child_id' => $elevators->id
        ));
        \DB::table('project_tag_relation')->insert(array(
            'parent_id' => $inside->id,
            'child_id' => $elevators->id
        ));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::table('project_tags')->where('name', 'Stairlifts/Elevators')->update(array('deleted_at' => date('Y-m-d H:i:s')));
	}

}
