<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRecoveryType extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('recovery', function($table)
        {
            $table->string('type');
            $table->dateTime('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recovery', function($table)
        {
            $table->dropColumn('type');
            $table->dropColumn('expires_at');
        });
    }

}
