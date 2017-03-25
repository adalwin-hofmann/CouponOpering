<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLocationsCertifiedFields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('locations', function($table)
        {
            $table->boolean('is_certified');
            $table->dateTime('certified_at')->nullable();
            $table->dateTime('uncertified_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locations', function($table)
        {
            $table->dropColumn('is_certified');
            $table->dropColumn('certified_at');
            $table->dropColumn('uncertified_at');
        });
    }

}
