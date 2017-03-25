<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertStPaulZipcodes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::table('zipcodes')->insert(array(
			'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'zipcode' => '55124',
            'zipcodetype' => 'STANDARD',
            'city' => 'ST PAUL',
            'state' => 'MN',
            'locationtype' => 'ACCEPTABLE',
            'latitude' => '44.94',
            'longitude' => '-93.10',
            'estimatedpopulation' => '44823',
            'latm' => '4994317.02',
            'lngm' => '-7323725.15115'
        ));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::table('zipcodes')->where('city', '=', 'ST PAUL')->where('state', '=', 'MN')->delete();
	}

}