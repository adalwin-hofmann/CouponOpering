<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContestsSweepstakesNumbers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('contests', function($table)
        {
            $table->integer('winning_number_min');
            $table->integer('winning_number_max');
            $table->string('winning_number_type');      // alpha, alphanum, num
            $table->integer('winning_number_length');
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
            $table->dropColumn('winning_number_min');
            $table->dropColumn('winning_number_max');
            $table->dropColumn('winning_number_type');
            $table->dropColumn('winning_number_length');
        });
    }

}
