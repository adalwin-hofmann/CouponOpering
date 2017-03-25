<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOffersEntitiesHideExpiration extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('offers', function($table)
        {
            $table->boolean('hide_expiration')->default(false);
        });

        Schema::table('entities', function($table)
        {
            $table->boolean('hide_expiration')->default(false);
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
            $table->dropColumn('hide_expiration')->default(false);
        });

        Schema::table('entities', function($table)
        {
            $table->dropColumn('hide_expiration')->default(false);
        });
    }

}
