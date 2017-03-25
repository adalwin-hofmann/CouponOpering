<?php

use Illuminate\Database\Migrations\Migration;

class InsertFeatures extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'save',
            'name' => 'search_sorting',
            'value' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ));
        DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'save',
            'name' => 'info_correct',
            'value' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ));
        DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'save',
            'name' => 'sharing',
            'value' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ));
        DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'save',
            'name' => 'entity_search',
            'value' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ));
        DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'save',
            'name' => 'notification_password_reset',
            'value' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ));
        DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'save',
            'name' => 'notification_contest_expiring',
            'value' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ));
        DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'save',
            'name' => 'notification_save_today_expiring',
            'value' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ));
        DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'save',
            'name' => 'notification_coupon_expiring',
            'value' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ));
        DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'save',
            'name' => 'notification_unredeemed_coupons',
            'value' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ));
        DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'save',
            'name' => 'notification_merchant_new_offers',
            'value' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ));
        DB::table('features')->insert(array(
            'type' => 'config',
            'entity' => 'save',
            'name' => 'notification_reccomended_offers',
            'value' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'search_sorting')->delete();
		DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'info_correct')->delete();
		DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'sharing')->delete();
		DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'entity_search')->delete();
		DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'notification_password_reset')->delete();
		DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'notification_contest_expiring')->delete();
		DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'notification_save_today_expiring')->delete();
		DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'notification_coupon_expiring')->delete();
		DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'notification_unredeemed_coupons')->delete();
		DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'notification_merchant_new_offers')->delete();
		DB::table('features')->where('type', '=', 'config')->where('entity', '=', 'save')->where('name', '=', 'notification_reccomended_offers')->delete();
	}

}