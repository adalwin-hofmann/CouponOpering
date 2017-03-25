<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFranchisesMerchantsLocationsOffersContestsDeletedAt extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('contests', function($table)
        {
            $table->softDeletes();
        });
        Schema::table('franchises', function($table)
        {
            $table->softDeletes();
        });
        Schema::table('locations', function($table)
        {
            $table->softDeletes();
        });
        Schema::table('merchants', function($table)
        {
            $table->softDeletes();
        });
        Schema::table('offers', function($table)
        {
            $table->softDeletes();
        });
        Schema::table('entities', function($table)
        {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contests', function($table)
        {
            $table->dropColumn('deleted_at');
        });
        Schema::table('franchises', function($table)
        {
            $table->dropColumn('deleted_at');
        });
        Schema::table('locations', function($table)
        {
            $table->dropColumn('deleted_at');
        });
        Schema::table('merchants', function($table)
        {
            $table->dropColumn('deleted_at');
        });
        Schema::table('offers', function($table)
        {
            $table->dropColumn('deleted_at');
        });
        Schema::table('entities', function($table)
        {
            $table->dropColumn('deleted_at');
        });
    }

}
