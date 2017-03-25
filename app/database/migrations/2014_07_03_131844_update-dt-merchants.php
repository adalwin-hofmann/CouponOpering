<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDtMerchants extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::table('merchants')->join('franchises', 'merchants.id', '=', 'franchises.merchant_id')
            ->where('merchants.vendor', 'detroit_trading')
            ->update(array('franchises.is_used_car_leads' => '1'));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::table('merchants')->join('franchises', 'merchants.id', '=', 'franchises.merchant_id')
            ->where('merchants.vendor', 'detroit_trading')
            ->update(array('franchises.is_used_car_leads' => '0'));
	}

}
