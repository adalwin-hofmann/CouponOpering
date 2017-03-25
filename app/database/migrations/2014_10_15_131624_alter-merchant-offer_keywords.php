<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMerchantOfferKeywords extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('merchants', function($table)
        {
            $table->text('offer_keywords')->nullable();
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
            $table->dropColumn('offer_keywords');            
        });
	}

}
