<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContestsGeolocation extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('contests', function($table)
        {
            $table->float('latitude');
            $table->float('longitude');
            $table->integer('radius');
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
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
            $table->dropColumn('radius');
        });
    }

}
