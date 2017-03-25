<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertEmployeeMembers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		\DB::table('users')
            ->where('type', 'LIKE', '%Employee%')
            ->where('type', 'NOT LIKE', '%Member%')
            ->update(array('type' => \DB::raw("CONCAT(type, ',Member')")));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		\DB::table('users')
            ->where('type', 'LIKE', '%Employee%')
            ->where('type', 'LIKE', '%,Member%')
            ->update(array('type' => \DB::raw("REPLACE(type, ',Member', '')")));
	}

}
