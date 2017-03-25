<?php

use Illuminate\Database\Migrations\Migration;

class AlterUserLocations extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_locations', function($table)
        {
            $table->boolean('is_deleted');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user_locations', function($table)
        {
            $table->dropColumn('is_deleted');
        });
	}

}