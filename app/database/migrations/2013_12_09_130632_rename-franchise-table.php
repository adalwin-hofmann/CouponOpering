<?php

use Illuminate\Database\Migrations\Migration;

class RenameFranchiseTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('RENAME TABLE `franchines` TO `franchises`');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('RENAME TABLE `franchises` TO `franchines`');
	}

}