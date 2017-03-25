<?php

use Illuminate\Database\Migrations\Migration;

class AlterUsersEntitiesLocationsUserLocationsCartesian extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('users', function($table)
        {
            $table->double('latm');
            $table->double('lngm');
        });

        Schema::table('entities', function($table)
        {
            $table->double('latm');
            $table->double('lngm');
        });

        Schema::table('locations', function($table)
        {
            $table->double('latm');
            $table->double('lngm');
        });

        Schema::table('user_locations', function($table)
        {
            $table->double('latm');
            $table->double('lngm');
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
            $table->dropColumn('latm');
            $table->dropColumn('lngm');
        });

        Schema::table('entities', function($table)
        {
            $table->dropColumn('latm');
            $table->dropColumn('lngm');
        });

        Schema::table('locations', function($table)
        {
            $table->dropColumn('latm');
            $table->dropColumn('lngm');
        });

        Schema::table('user_locations', function($table)
        {
            $table->dropColumn('latm');
            $table->dropColumn('lngm');
        });
    }

}