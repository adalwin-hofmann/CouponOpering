<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOffersOneMaxPrint extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("ALTER TABLE offers CHANGE max_prints max_prints INT DEFAULT 1 NOT NULL");
        DB::table('offers')->update(array('max_prints' => 1));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement("ALTER TABLE offers CHANGE max_prints max_prints INT DEFAULT 3 NOT NULL");
	}

}
