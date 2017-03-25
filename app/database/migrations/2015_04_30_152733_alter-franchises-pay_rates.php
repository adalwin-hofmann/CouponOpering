<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFranchisesPayRates extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('franchises', function($table)
        {
            $table->integer('click_pay_rate');
            $table->integer('impression_pay_rate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('franchises', function($table)
        {
            $table->dropColumn('click_pay_rate');
            $table->dropColumn('impression_pay_rate');
        });
    }

}
