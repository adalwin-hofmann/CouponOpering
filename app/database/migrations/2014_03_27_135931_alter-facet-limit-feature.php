<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFacetLimitFeature extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//DB::statement("ALTER TABLE features CHANGE max_prints max_prints INT DEFAULT 1 NOT NULL");
		DB::table('features')->where('name', '=', 'top_ten_filter')->update(array('name' => 'facet_limit','value' => 10));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::table('features')->where('name', '=', 'facet_limit')->update(array('name' => 'top_ten_filter','value' => 0));
	}

}
