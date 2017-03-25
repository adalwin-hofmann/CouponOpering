<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFranchisesDefaultPrints extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("ALTER TABLE franchises CHANGE `max_prints` `max_prints` INT NOT NULL DEFAULT 3");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement("ALTER TABLE franchises CHANGE `max_prints` `max_prints` INT NOT NULL");
	}

}
