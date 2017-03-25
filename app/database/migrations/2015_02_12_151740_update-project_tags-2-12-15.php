<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProjectTags21215 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

        // Revert Home Remodeling Changes
		$home = \DB::table('project_tags')->where('name', 'Home Remodeling')->first();
        \DB::table('project_tags')->where('name', 'Home Remodeling')->update(array('deleted_at' => \DB::raw('NULL')));

        $remodel = \DB::table('project_tags')->where('name', 'Remodeling (General)')->first();

        \DB::table('project_tag_relation')->where('parent_id', $remodel->id)->update(array('parent_id' => $home->id));

        \DB::table('franchise_project_tag')->where('project_tag_id', $remodel->id)->update(array('project_tag_id' => $home->id));

        $services = \DB::table('project_tags')->where('name', 'Home Services')->first();
        $inside = \DB::table('project_tags')->where('name', 'Inside Your Home')->first();
        $outside = \DB::table('project_tags')->where('name', 'Outside Your Home')->first();
        $yard = \DB::table('project_tags')->where('name', 'In Your Yard')->first();

        // Add New Categories *************************************
        \DB::table('project_tag_relation')->insert(array(
            'parent_id' => $services->id,
            'child_id' => $remodel->id
        ));
        \DB::table('project_tag_relation')->insert(array(
            'parent_id' => $inside->id,
            'child_id' => $remodel->id
        ));
        \DB::table('project_tag_relation')->insert(array(
            'parent_id' => $outside->id,
            'child_id' => $remodel->id
        ));
        \DB::table('project_tag_relation')->insert(array(
            'parent_id' => $home->id,
            'child_id' => $remodel->id
        ));

        $heating = \DB::table('project_tags')->where('name', 'Heating/Cooling (Repair)')->first();
        \DB::table('project_tag_relation')->insert(array(
            'parent_id' => $services->id,
            'child_id' => $heating->id
        ));

        $insurance = \DB::table('project_tags')->where('name', 'Insurance')->first();
        \DB::table('project_tag_relation')->insert(array(
            'parent_id' => $services->id,
            'child_id' => $insurance->id
        ));

        $junk = \DB::table('project_tags')->where('name', 'Junk Removal')->first();
        \DB::table('project_tag_relation')->insert(array(
            'parent_id' => $services->id,
            'child_id' => $junk->id
        ));

        $brickNew = \DB::table('project_tags')->where('name', 'Brick Paver (New)')->first();
        \DB::table('project_tag_relation')->insert(array(
            'parent_id' => $yard->id,
            'child_id' => $brickNew->id
        ));

        $brickRepair = \DB::table('project_tags')->where('name', 'Brick Paver (Repair)')->first();
        \DB::table('project_tag_relation')->insert(array(
            'parent_id' => $yard->id,
            'child_id' => $brickRepair->id
        ));

        $masonry = \DB::table('project_tags')->where('name', 'Concrete/Brick Masonry (Repair)')->first();
        \DB::table('project_tag_relation')->insert(array(
            'parent_id' => $yard->id,
            'child_id' => $masonry->id
        ));

        $decks = \DB::table('project_tags')->where('name', 'Decks (Repair)')->first();
        \DB::table('project_tag_relation')->insert(array(
            'parent_id' => $yard->id,
            'child_id' => $decks->id
        ));

        \DB::table('project_tag_relation')->insert(array(
            'parent_id' => $yard->id,
            'child_id' => $junk->id
        ));

        $sport = \DB::table('project_tags')->where('name', 'Sport Courts')->first();
        \DB::table('project_tag_relation')->insert(array(
            'parent_id' => $yard->id,
            'child_id' => $sport->id
        ));

        $reglazing = \DB::table('project_tags')->where('name', 'Bathroom Reglazing')->first();
        \DB::table('project_tag_relation')->insert(array(
            'parent_id' => $inside->id,
            'child_id' => $reglazing->id
        ));

        $carpet = \DB::table('project_tags')->where('name', 'Carpet Cleaning')->first();
        \DB::table('project_tag_relation')->insert(array(
            'parent_id' => $inside->id,
            'child_id' => $carpet->id
        ));

        $chimney = \DB::table('project_tags')->where('name', 'Chimney & Fireplace (Repair)')->first();
        \DB::table('project_tag_relation')->insert(array(
            'parent_id' => $inside->id,
            'child_id' => $chimney->id
        ));

        \DB::table('project_tag_relation')->insert(array(
            'parent_id' => $inside->id,
            'child_id' => $heating->id
        ));

        $poolsNew = \DB::table('project_tags')->where('name', 'Pools/Hot Tubs (New)')->first();
        \DB::table('project_tag_relation')->insert(array(
            'parent_id' => $inside->id,
            'child_id' => $poolsNew->id
        ));

        $poolsRepair = \DB::table('project_tags')->where('name', 'Pools/Hot Tubs (Repair)')->first();
        \DB::table('project_tag_relation')->insert(array(
            'parent_id' => $inside->id,
            'child_id' => $poolsRepair->id
        ));

        $additions = \DB::table('project_tags')->where('name', 'Additions')->first();
        \DB::table('project_tag_relation')->insert(array(
            'parent_id' => $outside->id,
            'child_id' => $additions->id
        ));

        \DB::table('project_tag_relation')->insert(array(
            'parent_id' => $outside->id,
            'child_id' => $brickRepair->id
        ));

        \DB::table('project_tag_relation')->insert(array(
            'parent_id' => $outside->id,
            'child_id' => $chimney->id
        ));

        \DB::table('project_tag_relation')->insert(array(
            'parent_id' => $outside->id,
            'child_id' => $masonry->id
        ));

        \DB::table('project_tag_relation')->insert(array(
            'parent_id' => $outside->id,
            'child_id' => $decks->id
        ));

        $garage = \DB::table('project_tags')->where('name', 'Garage Door (Opener)')->first();
        \DB::table('project_tag_relation')->insert(array(
            'parent_id' => $outside->id,
            'child_id' => $garage->id
        ));

        $garageFloor = \DB::table('project_tags')->where('name', 'Garage Floor Coating')->first();
        \DB::table('project_tag_relation')->insert(array(
            'parent_id' => $outside->id,
            'child_id' => $garageFloor->id
        ));

        \DB::table('project_tag_relation')->insert(array(
            'parent_id' => $outside->id,
            'child_id' => $poolsRepair->id
        ));

        \DB::table('project_tag_relation')->insert(array(
            'parent_id' => $outside->id,
            'child_id' => $sport->id
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
