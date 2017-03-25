<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLocationsFranchiseMerchantIndex extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('locations', function($table)
        {
            $table->index('merchant_id', 'merchant_id_index');
            $table->index('franchise_id', 'franchise_id_index');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('locations', function($table)
        {
            $table->dropIndex('merchant_id_index');
            $table->dropIndex('franchise_id_index');
        });
	}

}
