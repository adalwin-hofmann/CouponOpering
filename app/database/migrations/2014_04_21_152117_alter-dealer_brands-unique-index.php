<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDealerBrandsUniqueIndex extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('dealer_brands', function($table)
        {
            $table->unique(array('old_dealer_id', 'old_make_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dealer_brands', function($table)
        {
            $table->dropUnique('dealer_brands_old_dealer_id_old_make_id_unique');
        });
    }

}
