<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMiApplebees extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::table('city_images')->where('name', '=', 'michigan')->update(array(
            'about' => '<p>Michigan has great offers and specials in your area! Come see what coupons and discounts are available in metro Detroit and surrounding areas. We have deals available to help you save on gym memberships, home improvement, spa treatments, <a href="http://www.saveon.com/coupons/mi/southfield/food-dining/casual-dining/applebee-s-in-michigan/292434">Applebee\'s in Michigan</a> and much more!<p>'
        ));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::table('city_images')->where('name', '=', 'michigan')->update(array(
            'about' => '<p>Michigan has great offers and specials in your area! Come see what coupons and discounts are available in metro Detroit and surrounding areas. We have deals available to help you save on gym memberships, home improvement, spa treatments and much more!<p>'
        ));
	}

}
