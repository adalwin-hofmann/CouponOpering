<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContentAwardDatesAddPrizeName extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('contest_award_dates', function($table)
        {
            $table->string('prize_name');
            $table->dropColumn('prize_company');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('contest_award_dates', function($table)
        {
            $table->dropColumn('prize_name');
            $table->string('prize_company');
        });
	}

}
