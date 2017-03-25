<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFranchisesAddNotify extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('franchises', function($table)
        {
            $table->boolean('is_offer_notifications')->default(false);
            $table->dateTime('last_offer_notification');
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
            $table->dropColumn('is_offer_notifications');
            $table->dropColumn('last_offer_notification');
        });
	}

}
