<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDealerBrandsRemoveUniqueIndex extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('dealer_brands', function($table)
        {
            $table->dropIndex('dealer_brands_old_dealer_id_old_make_id_unique');
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
            $table->unique(array('old_dealer_id', 'old_make_id'));
        });
    }

}
