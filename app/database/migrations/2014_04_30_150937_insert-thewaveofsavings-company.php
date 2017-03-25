<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertThewaveofsavingsCompany extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::table('companies')->insert(array(
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'name' => 'The Wave of Savings',
            'landing_image' => 'http://s3.amazonaws.com/saveoneverything_assets/images/whitelabel/the-wave-of-savings.png',
            'logo_image' => 'http://s3.amazonaws.com/saveoneverything_assets/images/whitelabel/the-wave-of-savings.png',
            'description' => "<p>The Best Local Businesses, The Best Local Coupons</p>
            	<p>To Advertise with us contact us at 616.735-WAVE (9283)</p>
            	<p>We are the only publication that offers a section dedicated to the business that your local customers use everyday.</p>
            	<p>This section is made up of Half and Full page ads from the  Pizza Places, Car Wash’s, Restaurants, Automotive, and Services that the customer in a 5-8 mile area visit.  We print 30,000 mailers per market and deliver them directly into the hands of prospective customers for your business.</p>",
            'slug' => 'the-wave-of-savings',
            'latitude' => '42.97',
            'longitude' => '-85.77',
            'address' => '4166 Lake Michigan Dr.',
            'city' => 'Grand Rapids',
            'state' => 'MI',
            'zip' => '49534',
            'phone' => '616-735-9283'
        ));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::table('companies')->where('slug', '=', "the-wave-of-savings")->delete();
	}

}
