<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFranchisesSyndicationNewFields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('franchises', function($table)
        {
            $table->integer('syndication_radius');
            $table->string('banner_728x90');
            $table->string('banner_300x600');
            $table->string('banner_300x250');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('franchises', function($table)
        {
            $table->dropColumn('syndication_radius');
            $table->dropColumn('banner_728x90');
            $table->dropColumn('banner_300x600');
            $table->dropColumn('banner_300x250');
        });
    }

}
