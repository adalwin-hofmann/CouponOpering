<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersTypeDefault extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        DB::table('users')->where('type', '=', '')->update(array('type' => 'Member'));
        DB::statement("ALTER TABLE users CHANGE type type VARBINARY(255) NOT NULL DEFAULT 'Member'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE users CHANGE type type VARBINARY(255) NOT NULL");
    }

}
