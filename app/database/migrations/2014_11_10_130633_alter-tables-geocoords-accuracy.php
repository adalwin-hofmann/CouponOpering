<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablesGeocoordsAccuracy extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        set_time_limit(60*120); // 2 hours
        ini_set('memory_limit', '4096M');

        DB::statement("ALTER TABLE city_images CHANGE latitude latitude DECIMAL(8,6)");
        DB::statement("ALTER TABLE city_images CHANGE longitude longitude DECIMAL(8,6)");

        DB::statement("ALTER TABLE companies CHANGE latitude latitude DECIMAL(8,6)");
        DB::statement("ALTER TABLE companies CHANGE longitude longitude DECIMAL(8,6)");

        DB::statement("ALTER TABLE contests CHANGE latitude latitude DECIMAL(8,6)");
        DB::statement("ALTER TABLE contests CHANGE longitude longitude DECIMAL(8,6)");

        DB::statement("ALTER TABLE entities CHANGE latitude latitude DECIMAL(8,6)");
        DB::statement("ALTER TABLE entities CHANGE longitude longitude DECIMAL(8,6)");

        DB::statement("ALTER TABLE locations CHANGE latitude latitude DECIMAL(8,6)");
        DB::statement("ALTER TABLE locations CHANGE longitude longitude DECIMAL(8,6)");

        DB::statement("ALTER TABLE user_locations CHANGE latitude latitude DECIMAL(8,6)");
        DB::statement("ALTER TABLE user_locations CHANGE longitude longitude DECIMAL(8,6)");

        DB::statement("ALTER TABLE user_searches CHANGE latitude latitude DECIMAL(8,6)");
        DB::statement("ALTER TABLE user_searches CHANGE longitude longitude DECIMAL(8,6)");

        DB::statement("ALTER TABLE users CHANGE latitude latitude DECIMAL(8,6)");
        DB::statement("ALTER TABLE users CHANGE longitude longitude DECIMAL(8,6)");

        DB::statement("ALTER TABLE zipcodes CHANGE latitude latitude DECIMAL(8,6)");
        DB::statement("ALTER TABLE zipcodes CHANGE longitude longitude DECIMAL(8,6)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        set_time_limit(60*120); // 2 hours
        ini_set('memory_limit', '4096M');

        DB::statement("ALTER TABLE city_images CHANGE latitude latitude FLOAT(8,2)");
        DB::statement("ALTER TABLE city_images CHANGE longitude longitude FLOAT(8,2)");

        DB::statement("ALTER TABLE companies CHANGE latitude latitude FLOAT(8,2)");
        DB::statement("ALTER TABLE companies CHANGE longitude longitude FLOAT(8,2)");

        DB::statement("ALTER TABLE contests CHANGE latitude latitude FLOAT(8,2)");
        DB::statement("ALTER TABLE contests CHANGE longitude longitude FLOAT(8,2)");

        DB::statement("ALTER TABLE entities CHANGE latitude latitude FLOAT(8,2)");
        DB::statement("ALTER TABLE entities CHANGE longitude longitude FLOAT(8,2)");

        DB::statement("ALTER TABLE locations CHANGE latitude latitude FLOAT(8,2)");
        DB::statement("ALTER TABLE locations CHANGE longitude longitude FLOAT(8,2)");

        DB::statement("ALTER TABLE user_locations CHANGE latitude latitude FLOAT(8,2)");
        DB::statement("ALTER TABLE user_locations CHANGE longitude longitude FLOAT(8,2)");

        DB::statement("ALTER TABLE user_searches CHANGE latitude latitude FLOAT(8,2)");
        DB::statement("ALTER TABLE user_searches CHANGE longitude longitude FLOAT(8,2)");

        DB::statement("ALTER TABLE users CHANGE latitude latitude FLOAT(8,2)");
        DB::statement("ALTER TABLE users CHANGE longitude longitude FLOAT(8,2)");

        DB::statement("ALTER TABLE zipcodes CHANGE latitude latitude FLOAT(8,2)");
        DB::statement("ALTER TABLE zipcodes CHANGE longitude longitude FLOAT(8,2)");
    }

}
