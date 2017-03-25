<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMerchantsPageTitleMetaDescription extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('merchants', function($table)
        {
            $table->string('page_title');
            $table->string('meta_description');
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
            $table->dropColumn('page_title');
            $table->dropColumn('meta_description');
        });
    }

}
