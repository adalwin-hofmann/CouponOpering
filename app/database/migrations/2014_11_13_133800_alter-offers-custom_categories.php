<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOffersCustomCategories extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('offers', function($table)
        {
            $table->integer('custom_category_id');
            $table->integer('custom_subcategory_id');
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
            $table->dropColumn('custom_category_id');
            $table->dropColumn('custom_subcategory_id');
        });
    }

}
