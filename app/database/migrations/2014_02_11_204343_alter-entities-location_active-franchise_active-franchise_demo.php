<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEntitiesLocationActiveFranchiseActiveFranchiseDemo extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        set_time_limit(30*60);
        Schema::table('entities', function($table)
        {
            $table->boolean('location_active')->default(true);
            $table->boolean('franchise_active')->default(true);
            $table->boolean('franchise_demo')->default(false);
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
            $table->dropColumn('location_active');
            $table->dropColumn('franchise_active');
            $table->dropColumn('franchise_demo');
        });
    }

}
