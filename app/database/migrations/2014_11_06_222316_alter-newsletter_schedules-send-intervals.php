<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNewsletterSchedulesSendIntervals extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('newsletter_schedules', function($table)
        {
            $table->integer('send_interval');
            $table->integer('send_hour');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('newsletter_schedules', function($table)
        {
            $table->dropColumn('send_interval');
            $table->dropColumn('send_hour');
        });
    }

}
