<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOffersEntitiesIsReoccuring extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('offers', function($table)
        {
            $table->boolean('is_reoccurring');
        });
        Schema::table('entities', function($table)
        {
            $table->boolean('is_reoccurring');
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offers', function($table)
        {
            $table->dropColumn('is_reoccurring');
        });
        Schema::table('entities', function($table)
        {
            $table->dropColumn('is_reoccurring');
        });
    }

}
