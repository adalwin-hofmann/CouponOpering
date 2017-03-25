<?php

use Illuminate\Database\Migrations\Migration;

class AlterUserPrintsRedemptionFields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('user_prints', function($table)
        {
            $table->boolean('is_redemption')->default(false);
            $table->float('latitude');
            $table->float('longitude');
            $table->double('lngm');
            $table->double('latm');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_prints', function($table)
        {
            $table->dropColumn('latm');
            $table->dropColumn('lngm');
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
            $table->dropColumn('is_redemption');
        });
    }

}