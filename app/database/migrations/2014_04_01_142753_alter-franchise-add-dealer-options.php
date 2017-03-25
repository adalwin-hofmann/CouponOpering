<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFranchiseAddDealerOptions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('franchises', function($table)
        {
            $table->boolean('is_new_car_leads')->default(false);
            $table->boolean('is_used_car_leads')->default(false);
            $table->boolean('is_print_only')->default(false);
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
            $table->dropColumn('is_new_car_leads');
            $table->dropColumn('is_used_car_leads');
            $table->dropColumn('is_print_only');
        });
	}

}
