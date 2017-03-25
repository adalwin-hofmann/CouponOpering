<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFranchiseUsersUserIdType extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('franchise_users', function($table)
        {
            $table->integer('user_id');
            $table->string('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('franchise_users', function($table)
        {
            $table->dropColumn('user_id');
            $table->dropColumn('type');
        });
    }

}
