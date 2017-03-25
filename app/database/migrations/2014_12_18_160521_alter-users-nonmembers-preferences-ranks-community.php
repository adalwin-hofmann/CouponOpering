<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersNonmembersPreferencesRanksCommunity extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('users', function($table)
        {
            $table->float('rank_community');
            $table->boolean('community_preference')->default(true);
        });

        Schema::table('nonmembers', function($table)
        {
            $table->float('rank_community');
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
            $table->dropColumn('rank_community');
            $table->dropColumn('community_preference');
        });

        Schema::table('nonmembers', function($table)
        {
            $table->dropColumn('rank_community');
        });
    }

}
