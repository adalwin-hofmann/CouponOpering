<?php

use Illuminate\Database\Migrations\Migration;

class AlterUserLocationsAddNonmemberId extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('user_locations', function($table)
        {
            $table->integer('nonmember_id');
        });
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_locations', function($table)
        {
            $table->dropColumn('nonmember_id');
        });
    }

}