<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMerchantsDisclaimers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('merchants', function($table)
        {
            $table->text('new_disclaimer');
            $table->text('used_disclaimer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchants', function($table)
        {
            $table->dropColumn('new_disclaimer');
            $table->dropColumn('used_disclaimer');
        });
    }

}
