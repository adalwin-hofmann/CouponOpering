<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContestTablesPrizes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        DB::statement("ALTER TABLE contest_award_dates CHANGE verify_by verify_by DATETIME");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE contest_award_dates CHANGE verify_by verify_by DATETIME NOT NULL");
    }

}
