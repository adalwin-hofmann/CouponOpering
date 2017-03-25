<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEntitiesOffersContestsStuffingPriority extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('entities', function($table)
        {
            $table->integer('stuffing_priority');
        });
        Schema::table('offers', function($table)
        {
            $table->integer('stuffing_priority');
        });
        Schema::table('contests', function($table)
        {
            $table->integer('stuffing_priority');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entities', function($table)
        {
            $table->dropColumn('stuffing_priority');
        });
        Schema::table('offers', function($table)
        {
            $table->dropColumn('stuffing_priority');
        });
        Schema::table('contests', function($table)
        {
            $table->dropColumn('stuffing_priority');
        });
    }

}
