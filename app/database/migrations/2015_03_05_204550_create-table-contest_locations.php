<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableContestLocations extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('contest_locations', function($table)
        {
            $table->increments('id');
            $table->integer('contest_id');
            $table->integer('zipcode');
            $table->string('state');
            $table->decimal('latitude',8,6);
            $table->decimal('longitude',8,6);
            $table->double('latm');
            $table->double('lngm');
            $table->integer('service_radius');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contest_locations');
    }

}
