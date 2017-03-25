<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEntitiesOffersAddMerchantLogo extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('entities', function($table)
        {
            $table->text('merchant_logo');
        });

        Schema::table('offers', function($table)
        {
            $table->text('merchant_logo');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('entities', function($table)
        {
            $table->dropColumn('merchant_logo');
        });

        Schema::table('offers', function($table)
        {
            $table->dropColumn('merchant_logo');
        });
	}

}
