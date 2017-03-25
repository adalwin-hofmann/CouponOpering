<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFranchisesAddContract extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('franchises', function($table)
        {
            $table->dateTime('contract_start');
            $table->dateTime('contract_end');
            $table->boolean('is_permanent');
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
            $table->dropColumn('contract_start');
            $table->dropColumn('contract_end');
            $table->dropColumn('is_permanent');
        });
	}

}
