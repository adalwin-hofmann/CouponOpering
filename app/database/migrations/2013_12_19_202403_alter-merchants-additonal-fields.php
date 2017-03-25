<?php

use Illuminate\Database\Migrations\Migration;

class AlterMerchantsAdditonalFields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('merchants', function($table)
        {
            $table->text('keywords');
            $table->integer('max_prints');
            $table->boolean('mobile_redemption');
            $table->float('rating');
            $table->boolean('is_displayed')->default(true);
        });
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchants', function($table)
        {
            $table->dropColumn('keywords');
            $table->dropColumn('max_prints');
            $table->dropColumn('mobile_redemption');
            $table->dropColumn('rating');
            $table->dropColumn('is_displayed');
        });
    }

}