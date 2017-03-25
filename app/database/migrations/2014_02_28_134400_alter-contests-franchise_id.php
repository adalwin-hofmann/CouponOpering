<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContestsFranchiseId extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('contests', function($table)
        {
            $table->integer('franchise_id');
        });
        DB::table('contests')->join('franchises', 'contests.merchant_id', '=', 'franchises.merchant_id')
                            ->where('contests.merchant_id', '!=', '0')
                            ->update(array('contests.franchise_id' => DB::raw('`franchises`.`id`')));
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
            $table->dropColumn('franchise_id');
        });
    }

}
