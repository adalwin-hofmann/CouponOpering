<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNewsletterSchedulesOrdering extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('newsletter_schedules', function($table)
        {
            $table->integer('first_category');
            $table->integer('second_category');
            $table->integer('third_category');
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
            $table->dropColumn('first_category');
            $table->dropColumn('second_category');
            $table->dropColumn('third_category');
        });
    }

}
