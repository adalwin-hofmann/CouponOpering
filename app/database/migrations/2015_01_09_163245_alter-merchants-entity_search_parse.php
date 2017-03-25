<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMerchantsEntitySearchParse extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('merchants', function($table)
        {
            $table->boolean('entity_search_parse')->default(true);
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
            $table->dropColumn('entity_search_parse');
        });
    }

}
