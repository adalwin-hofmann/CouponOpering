<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersNonmembersRankedAt extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('users', function($table)
        {
            $table->dateTime('ranked_at');
        });
        Schema::table('nonmembers', function($table)
        {
            $table->dateTime('ranked_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function($table)
        {
            $table->dropColumn('ranked_at');
        });
        Schema::table('nonmembers', function($table)
        {
            $table->dropColumn('ranked_at');
        });
    }

}
