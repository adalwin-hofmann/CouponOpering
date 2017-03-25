<?php

use Illuminate\Database\Migrations\Migration;

class AlterEntitiesMerchantData extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('entities', function($table)
        {
            $table->string('merchant_id');
            $table->string('merchant_slug');
            $table->string('merchant_name');
        });
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entities', function($table)
        {
            $table->dropColumn('merchant_id');
            $table->dropColumn('merchant_slug');
            $table->dropColumn('merchant_name');
        });
    }

}