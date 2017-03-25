<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCompanySlugs extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::table('companies')->where('name', '=', 'Great Deals Magazine')->update(array(
            'slug' => 'great-deals-magazine'
        ));

        DB::table('companies')->where('name', '=', 'Gold Clipper Magazine')->update(array(
            'slug' => 'gold-clipper-magazine'
        ));

        DB::table('companies')->where('name', '=', 'Money Saver Direct Magazine')->update(array(
            'slug' => 'money-saver-direct-magazine'
        ));

        DB::table('companies')->where('name', '=', 'ShopSmart Magazine')->update(array(
            'slug' => 'shopsmart-magazine'
        ));

        DB::table('companies')->where('name', '=', 'SaverGator')->update(array(
            'slug' => 'savergator'
        ));

        DB::table('companies')->where('name', '=', 'Deal Dogs Savings Magazine')->update(array(
            'slug' => 'deal-dogs-savings-magazine'
        ));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::table('companies')->where('name', '=', 'Great Deals Magazine')->update(array(
            'slug' => 'great-deals'
        ));

        DB::table('companies')->where('name', '=', 'Gold Clipper Magazine')->update(array(
            'slug' => 'gold-clipper'
        ));

        DB::table('companies')->where('name', '=', 'Money Saver Direct Magazine')->update(array(
            'slug' => 'money-saver'
        ));

        DB::table('companies')->where('name', '=', 'ShopSmart Magazine')->update(array(
            'slug' => 'shop-smart'
        ));

        DB::table('companies')->where('name', '=', 'SaverGator')->update(array(
            'slug' => 'saver-gator'
        ));

        DB::table('companies')->where('name', '=', 'Deal Dogs Savings Magazine')->update(array(
            'slug' => 'deal-dogs'
        ));
	}

}
