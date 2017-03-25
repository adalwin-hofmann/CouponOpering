<?php

use Illuminate\Database\Migrations\Migration;

class AlterUsersPreferences extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('users', function($table)
        {
            $table->boolean('food_dining_preference');
            $table->boolean('home_services_preference');
            $table->boolean('health_beauty_preference');
            $table->boolean('auto_transportation_preference');
            $table->boolean('travel_entertainment_preference');
            $table->boolean('retail_fashion_preference');
            $table->boolean('special_services_preference');
        });
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function($table)
        {
            $table->dropColumn('food_dining_preference');
            $table->dropColumn('home_services_preference');
            $table->dropColumn('health_beauty_preference');
            $table->dropColumn('auto_transportation_preference');
            $table->dropColumn('travel_entertainment_preference');
            $table->dropColumn('retail_fashion_preference');
            $table->dropColumn('special_services_preference');
        });
    }

}