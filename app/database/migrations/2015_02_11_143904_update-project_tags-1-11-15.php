<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProjectTags11115 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('project_tags', function($table)
        {
            $table->softDeletes();
        });

		DB::table('project_tags')->insert(array(
            array('name' => 'Bathroom Reglazing', 'slug' => 'bathroom-reglazing'),
            array('name' => 'Brick Paver (New & Repair)', 'slug' => 'brick-paver-new-repair'),
            array('name' => 'Carpet Cleaning', 'slug' => 'carpet-cleaning'),
            array('name' => 'Chimney & Fireplace (New & Repair)', 'slug' => 'chimney-fireplace-new-repair'),
            array('name' => 'Concrete/Brick Masonry (New & Repair)', 'slug' => 'concrete-brick-masonry-new-repair'),
            array('name' => 'Decks (New & Repair)', 'slug' => 'decks-new-repair'),
            array('name' => 'Garage Door (New & Opener)', 'slug' => 'garage-door-new-opener'),
            array('name' => 'Garage Floor Coating', 'slug' => 'garage-floor-coating'),
            array('name' => 'Heating/Cooling (New & Repair)', 'slug' => 'heating-cooling-new-repair'),
            array('name' => 'Insurance', 'slug' => 'insurance'),
            array('name' => 'Junk Removal', 'slug' => 'junk-removal'),
            array('name' => 'Maid Service', 'slug' => 'maid-service'),
            array('name' => 'Pools/Hot Tubs (New & Repair)', 'slug' => 'pools-hot-tubs-new-repair'),
            array('name' => 'Remodeling (General)', 'slug' => 'remodeling-general'),
            array('name' => 'Sport Courts', 'slug' => 'sport-courts')
        ));

        $home_remodel = DB::table('project_tags')->where('name', 'Home Remodeling')->first();
        $remodel = DB::table('project_tags')->where('name', 'Remodeling (General)')->first();
        DB::table('project_tag_relation')->where('parent_id', $home_remodel->id)
            ->update(array(
                'parent_id' => $remodel->id
            ));

        DB::table('franchise_project_tag')->where('project_tag_id', $home_remodel->id)
            ->update(array(
                'project_tag_id' => $remodel->id
            ));

        DB::table('project_tags')->where('id', $home_remodel->id)->update(array('deleted_at' => date('Y-m-d H:i:s')));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('project_tags', function($table)
        {
            $table->dropColumn('deleted_at');
        });
	}

}
