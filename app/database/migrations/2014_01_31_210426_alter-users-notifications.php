<?php

use Illuminate\Database\Migrations\Migration;

class AlterUsersNotifications extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('users', function($table)
        {
            $table->boolean('password_reset_notification')->default(true);
            $table->boolean('contest_end_notification')->default(true);
            $table->boolean('daily_deal_end_notification')->default(true);
            $table->boolean('coupon_end_notification')->default(true);
            $table->boolean('unredeemed_notification')->default(true);
            $table->boolean('new_offers_notification')->default(true);
            $table->boolean('love_offers_notification')->default(true);
        });
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function($table)
        {
            $table->dropColumn('password_reset_notification');
            $table->dropColumn('contest_end_notification');
            $table->dropColumn('daily_deal_end_notification');
            $table->dropColumn('coupon_end_notification');
            $table->dropColumn('unredeemed_notification');
            $table->dropColumn('new_offers_notification');
            $table->dropColumn('love_offers_notification');
        });
    }

}