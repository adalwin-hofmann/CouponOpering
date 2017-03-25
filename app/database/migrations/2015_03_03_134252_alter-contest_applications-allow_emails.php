<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContestApplicationsAllowEmails extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('contest_applications', function($table)
        {
            $table->boolean('allow_emails')->default(false);
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contest_applications', function($table)
        {
            $table->dropColumn('allow_emails');
        });
    }

}
