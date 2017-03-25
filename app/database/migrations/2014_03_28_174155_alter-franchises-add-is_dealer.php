<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFranchisesAddIsDealer extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('franchises', function($table)
        {
            $table->boolean('is_dealer')->default(false);
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('franchises', function($table)
        {
            $table->dropColumn('is_dealer');
        });
	}

}
