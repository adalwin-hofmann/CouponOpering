<?php

use Illuminate\Database\Migrations\Migration;

class AlterContestApplicationsAddEmailAddress extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('contest_applications', function($table)
        {
            $table->string('address');
            $table->string('email');
        });
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contest_applications', function($table)
        {
            $table->dropColumn('address');
            $table->dropColumn('email');
        });
    }

}