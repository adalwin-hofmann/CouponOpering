<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNewsletterSchedulesFeaturedMerchantId extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('newsletter_schedules', function($table)
        {
            $table->integer('featured_merchant_id');
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
            $table->dropColumn('featured_merchant_id');
        });
    }

}
