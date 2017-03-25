<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNewsletterSchedulesCustomDataFields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('newsletter_schedules', function($table)
        {
            $table->string('schedule_name');
            $table->string('subject_line');
            $table->text('intro_paragraph');
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
            $table->dropColumn('schedule_name');
            $table->dropColumn('subject_line');
            $table->dropColumn('intro_paragraph');
        });
    }

}
