<?php

use Illuminate\Database\Migrations\Migration;

class AlterUsersPreferenceDefault extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("ALTER TABLE users CHANGE `food_dining_preference` `food_dining_preference` TINYINT(1) NOT NULL DEFAULT 1");
        DB::statement("ALTER TABLE users CHANGE `home_services_preference` `home_services_preference` TINYINT(1) NOT NULL DEFAULT 1");
        DB::statement("ALTER TABLE users CHANGE `health_beauty_preference` `health_beauty_preference` TINYINT(1) NOT NULL DEFAULT 1");
        DB::statement("ALTER TABLE users CHANGE `auto_transportation_preference` `auto_transportation_preference` TINYINT(1) NOT NULL DEFAULT 1");
        DB::statement("ALTER TABLE users CHANGE `travel_entertainment_preference` `travel_entertainment_preference` TINYINT(1) NOT NULL DEFAULT 1");
        DB::statement("ALTER TABLE users CHANGE `retail_fashion_preference` `retail_fashion_preference` TINYINT(1) NOT NULL DEFAULT 1");
        DB::statement("ALTER TABLE users CHANGE `special_services_preference` `special_services_preference` TINYINT(1) NOT NULL DEFAULT 1");

        DB::table('users')->update(array(
            'food_dining_preference' => 1,
            'home_services_preference' => 1,
            'health_beauty_preference' => 1,
            'auto_transportation_preference' => 1,
            'travel_entertainment_preference' => 1,
            'retail_fashion_preference' => 1,
            'special_services_preference' => 1,
        ));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement("ALTER TABLE users CHANGE `food_dining_preference` `food_dining_preference` TINYINT(1) NOT NULL DEFAULT 0");
        DB::statement("ALTER TABLE users CHANGE `home_services_preference` `home_services_preference` TINYINT(1) NOT NULL DEFAULT 0");
        DB::statement("ALTER TABLE users CHANGE `health_beauty_preference` `health_beauty_preference` TINYINT(1) NOT NULL DEFAULT 0");
        DB::statement("ALTER TABLE users CHANGE `auto_transportation_preference` `auto_transportation_preference` TINYINT(1) NOT NULL DEFAULT 0");
        DB::statement("ALTER TABLE users CHANGE `travel_entertainment_preference` `travel_entertainment_preference` TINYINT(1) NOT NULL DEFAULT 0");
        DB::statement("ALTER TABLE users CHANGE `retail_fashion_preference` `retail_fashion_preference` TINYINT(1) NOT NULL DEFAULT 0");
        DB::statement("ALTER TABLE users CHANGE `special_services_preference` `special_services_preference` TINYINT(1) NOT NULL DEFAULT 0");
	}

}