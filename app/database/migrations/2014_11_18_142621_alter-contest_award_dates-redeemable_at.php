<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContestAwardDatesRedeemableAt extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('contest_award_dates', function($table)
        {
            $table->string('redeemable_at');
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
            $table->dropColumn('redeemable_at');
        });
    }

}
