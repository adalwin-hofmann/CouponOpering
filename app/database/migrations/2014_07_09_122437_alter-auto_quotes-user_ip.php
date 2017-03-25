<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAutoQuotesUserIp extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('auto_quotes', function($table)
        {
            $table->string('user_ip');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auto_quotes', function($table)
        {
            $table->dropColumn('user_ip');            
        });
    }

}
