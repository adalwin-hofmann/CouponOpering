<?php

use Illuminate\Database\Migrations\Migration;

class AlterUsersNonmembersCategoryRankings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('users', function($table)
        {
            $table->float('rank_food_dining');
            $table->float('rank_home_services');
            $table->float('rank_health_beauty');
            $table->float('rank_auto_transportation');
            $table->float('rank_travel_entertainment');
            $table->float('rank_retail_fashion');
            $table->float('rank_special_services');
        });

        Schema::table('nonmembers', function($table)
        {
            $table->float('rank_food_dining');
            $table->float('rank_home_services');
            $table->float('rank_health_beauty');
            $table->float('rank_auto_transportation');
            $table->float('rank_travel_entertainment');
            $table->float('rank_retail_fashion');
            $table->float('rank_special_services');
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
            $table->dropColumn('rank_food_dining');
            $table->dropColumn('rank_home_services');
            $table->dropColumn('rank_health_beauty');
            $table->dropColumn('rank_auto_transportation');
            $table->dropColumn('rank_travel_entertainment');
            $table->dropColumn('rank_retail_fashion');
            $table->dropColumn('rank_special_services');
        });

        Schema::table('nonmembers', function($table)
        {
            $table->dropColumn('rank_food_dining');
            $table->dropColumn('rank_home_services');
            $table->dropColumn('rank_health_beauty');
            $table->dropColumn('rank_auto_transportation');
            $table->dropColumn('rank_travel_entertainment');
            $table->dropColumn('rank_retail_fashion');
            $table->dropColumn('rank_special_services');
        });
    }

}