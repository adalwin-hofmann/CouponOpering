<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNewsletterSchedulesZipcodeRadius extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('newsletter_schedules', function($table)
        {
            $table->string('zipcode', 10)->nullable();
            $table->integer('radius');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('newsletter_schedules', function($table)
        {
            $table->dropColumn('zipcode');
            $table->dropColumn('radius');
        });
    }

}
