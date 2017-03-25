<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersTypeVarbinary extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        DB::table('users')->where(DB::raw("BINARY type"), '=', 'MEMBER')->update(array('type' => 'Member'));
        DB::statement("ALTER TABLE users CHANGE type type VARBINARY(255) NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE users CHANGE type type VARCHAR(255) NOT NULL");
    }

}
