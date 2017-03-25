<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContestWinnerDisclaimersAddEmail extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('contest_winners', function($table)
        {
            $table->string('email');
            $table->string('address');
            $table->string('zip');
        });
        Schema::table('contest_disclaimers', function($table)
        {
            $table->string('email');
            $table->integer('contest_winner_id');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('contest_winners', function($table)
        {
            $table->dropColumn('email');
            $table->dropColumn('address');
            $table->dropColumn('zip');
        });
        Schema::table('contest_disclaimers', function($table)
        {
            $table->dropColumn('email');
            $table->dropColumn('contest_winner_id');
        });
	}

}
