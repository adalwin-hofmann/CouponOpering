<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertNewCarYearFeatures extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'soct',
            'name' => 'new_car_start_year',
            'value' => '2013',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'description' => 'What is the lowest year to consider a new car?'
        ));

        DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'soct',
            'name' => 'new_car_end_year',
            'value' => '2015',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'description' => 'What is the highest year to consider a new car?'
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'soct')->where('name', '=', 'new_car_start_year')->delete();
        DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'soct')->where('name', '=', 'new_car_end_year')->delete();
    }

}
