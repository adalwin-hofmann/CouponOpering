<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectTagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('project_tags', function($table)
        {
            $table->increments('id');
            $table->string('name');
        });

        DB::table('project_tags')->insert(array(
            array('name' => 'Additions'),
            array('name' => 'Air Duct'),
            array('name' => 'Asphalt/Seal Coating'),
            array('name' => 'Awnings'),
            array('name' => 'Basement Waterproofing'),
            array('name' => 'Basement Remodeling'),
            array('name' => 'Bathtub Fitters'),
            array('name' => 'Bathroom Remodeling'),
            array('name' => 'Brick Paver'),
            array('name' => 'Carpet/Flooring'),
            array('name' => 'Chimney & Fireplace'),
            array('name' => 'Closet Remodeling'),
            array('name' => 'Concrete/Brick Masonry'),
            array('name' => 'Decks'),
            array('name' => 'Door Service/Doors'),
            array('name' => 'Drapery/Blinds'),
            array('name' => 'Electrical'),
            array('name' => 'Fencing'),
            array('name' => 'Flooring'),
            array('name' => 'Garage Door'),
            array('name' => 'Glass Block'),
            array('name' => 'Glass - Custom/Specialty'),
            array('name' => 'Granite Countertops'),
            array('name' => 'Grout'),
            array('name' => 'Handyman'),
            array('name' => 'Heating/Cooling'),
            array('name' => 'Insulation'),
            array('name' => 'Kitchen Remodeling'),
            array('name' => 'Landscape'),
            array('name' => 'Lawn Care'),
            array('name' => 'Painting'),
            array('name' => 'Pools/Hot Tubs'),
            array('name' => 'Plumbing'),
            array('name' => 'Roofing'),
            array('name' => 'Security Systems'),
            array('name' => 'Siding/Gutters'),
            array('name' => 'Sunrooms'),
            array('name' => 'Tree Services'),
            array('name' => 'Water Treatment'),
            array('name' => 'Windows'),
            array('name' => 'Wood Floor Refinishing')
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_tags');
    }

}
