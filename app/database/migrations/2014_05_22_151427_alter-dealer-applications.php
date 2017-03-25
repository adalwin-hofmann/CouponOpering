<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDealerApplications extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('dealer_applications', function($table)
        {
        	$table->integer('lead_amount');
        	$table->string('market');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('dealer_applications', function($table)
        {
        	$table->dropColumn('lead_amount');
        	$table->dropColumn('market');
        });
	}

}
