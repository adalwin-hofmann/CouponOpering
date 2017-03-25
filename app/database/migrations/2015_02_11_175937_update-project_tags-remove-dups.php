<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProjectTagsRemoveDups extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        DB::table('project_tags')->where('name', 'Brick Paver (New & Repair)')->update(array(
            'name' => 'Brick Paver (Repair)',
            'slug' => 'brick-paver-repair'
        ));
        DB::table('project_tags')->where('name', 'Chimney & Fireplace (New & Repair)')->update(array(
            'name' => 'Chimney & Fireplace (Repair)',
            'slug' => 'chimney-fireplace-repair'
        ));
        DB::table('project_tags')->where('name', 'Concrete/Brick Masonry (New & Repair)')->update(array(
            'name' => 'Concrete/Brick Masonry (Repair)',
            'slug' => 'concrete-brick-masonry-repair'
        ));
        DB::table('project_tags')->where('name', 'Decks (New & Repair)')->update(array(
            'name' => 'Decks (Repair)',
            'slug' => 'decks-repair'
        ));
        DB::table('project_tags')->where('name', 'Garage Door (New & Opener)')->update(array(
            'name' => 'Garage Door (Opener)',
            'slug' => 'garage-door-opener'
        ));
        DB::table('project_tags')->where('name', 'Heating/Cooling (New & Repair)')->update(array(
            'name' => 'Heating/Cooling (Repair)',
            'slug' => 'heating-cooling-repair'
        ));
        DB::table('project_tags')->where('name', 'Pools/Hot Tubs (New & Repair)')->update(array(
            'name' => 'Pools/Hot Tubs (Repair)',
            'slug' => 'pools-hot-tubs-repair'
        ));

        DB::table('project_tags')->where('name', 'Brick Paver')->update(array(
            'name' => 'Brick Paver (New)',
            'slug' => 'brick-paver-new'
        ));
        DB::table('project_tags')->where('name', 'Chimney & Fireplace')->update(array(
            'name' => 'Chimney & Fireplace (New)',
            'slug' => 'chimney-fireplace-new'
        ));
        DB::table('project_tags')->where('name', 'Concrete/Brick Masonry')->update(array(
            'name' => 'Concrete/Brick Masonry (New)',
            'slug' => 'concrete-brick-masonry-new'
        ));
        DB::table('project_tags')->where('name', 'Decks')->update(array(
            'name' => 'Decks (New)',
            'slug' => 'decks-new'
        ));
        DB::table('project_tags')->where('name', 'Garage Door')->update(array(
            'name' => 'Garage Door (New)',
            'slug' => 'garage-door-new'
        ));
        DB::table('project_tags')->where('name', 'Heating/Cooling')->update(array(
            'name' => 'Heating/Cooling (New)',
            'slug' => 'heating-cooling-new'
        ));
        DB::table('project_tags')->where('name', 'Pools/Hot Tubs')->update(array(
            'name' => 'Pools/Hot Tubs (New)',
            'slug' => 'pools-hot-tubs-new'
        ));
    }

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
