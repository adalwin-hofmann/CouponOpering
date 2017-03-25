<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContestsFollowUp extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('contests', function($table)
        {
            $table->integer('follow_up_id');
            $table->string('follow_up_type');
            $table->text('follow_up_text');
            $table->dateTime('follow_up_sent_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contests', function($table)
        {
            $table->dropColumn('follow_up_id');
            $table->dropColumn('follow_up_type');
            $table->dropColumn('follow_up_text');
            $table->dropColumn('follow_up_sent_at');
        });
    }

}
