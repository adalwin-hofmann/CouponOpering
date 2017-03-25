<?php

use Illuminate\Database\Migrations\Migration;

class AlterImpressionsPrintsEntityData extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('user_impressions', function($table)
        {
            $table->integer('entity_id');
            $table->integer('location_id');
            $table->integer('merchant_id');
        });

        Schema::table('user_prints', function($table)
        {
            $table->integer('entity_id');
            $table->integer('location_id');
            $table->integer('merchant_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_impressions', function($table)
        {
            $table->dropColumn('entity_id');
            $table->dropColumn('location_id');
            $table->dropColumn('merchant_id');
        });

        Schema::table('user_prints', function($table)
        {
            $table->dropColumn('entity_id');
            $table->dropColumn('location_id');
            $table->dropColumn('merchant_id');
        });
    }

}