<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContestsAddInventory extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('contests', function($table)
        {
            $table->integer('current_inventory');
            $table->integer('total_inventory');
            $table->dateTime('date_ended');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('contests', function($table)
        {
            $table->dropColumn('current_inventory');
            $table->dropColumn('total_inventory');
            $table->dropColumn('date_ended');
        });
	}

}
