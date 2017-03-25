<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMerchantsOffersAddMerchantVersionOfferTitle extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('merchants', function($table)
        {
            $table->integer('page_version')->default(1);
        });

        Schema::table('entities', function($table)
        {
            $table->string('short_name_line1');
            $table->string('short_name_line2');
        });

        Schema::table('offers', function($table)
        {
            $table->string('short_name_line1');
            $table->string('short_name_line2');
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
            $table->dropColumn('page_version');
        });

        Schema::table('entities', function($table)
        {
            $table->dropColumn('short_name_line1');
            $table->dropColumn('short_name_line2');
        });

        Schema::table('offers', function($table)
        {
            $table->dropColumn('short_name_line1');
            $table->dropColumn('short_name_line2');
        });
	}

}
