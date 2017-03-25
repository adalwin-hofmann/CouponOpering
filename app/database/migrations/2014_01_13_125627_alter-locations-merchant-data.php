<?php

use Illuminate\Database\Migrations\Migration;

class AlterLocationsMerchantData extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('locations', function($table)
        {
            $table->string('merchant_name');
            $table->string('merchant_slug');
        });
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locations', function($table)
        {
            $table->dropColumn('merchant_name');
            $table->dropColumn('merchant_slug');
        });
    }

}