<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCouponsEntitiesCategoryVisible extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('offers', function($table)
        {
            $table->boolean('category_visible')->default(true);
        });

        Schema::table('entities', function($table)
        {
            $table->boolean('category_visible')->default(true);
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
            $table->dropColumn('category_visible');
        });

        Schema::table('entities', function($table)
        {
            $table->dropColumn('category_visible');
        });
    }

}
