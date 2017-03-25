<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertSeoContentDefaultsCity extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        set_time_limit(60*120); // 2 hours
        ini_set('memory_limit', '4096M');
		$zipcodes = DB::table('zipcodes')
            ->where('state', 'MI')
            ->orWhere('state', 'IL')
            ->orWhere('state', 'MN')
            ->where('locationtype', 'PRIMARY')
            ->where('zipcodetype', 'STANDARD')
            ->groupBy('city')
            ->groupBy('state')
            ->get(array(
                'state',
                'city'
            ));

        foreach($zipcodes as $zipcode)
        {
            $option[0] = 'Online Free Printable Coupons for {city} Restaurant, Oil Changes, Hair & Nails Entertainment and more.';
            $option[1] = 'Online Coupons, Offers & Contests for {city} Restaurants, Automotive, Fitness & Spa, Entertainment and more.';
            $default = $option[rand()%count($option)];

            $existing = SOE\DB\SeoContent::where('page_url', 'coupons/'.strtolower($zipcode->state).'/'.SoeHelper::getSlug($zipcode->city))
                ->where('content_type', 'Sub-Header')
                ->first();
            if($existing)
            {
                if($existing->content == '')
                {
                    $existing->content = $default;
                    $existing->save();
                }
            }
            else
            {
                $content = new SOE\DB\SeoContent;
                $content->page_url = 'coupons/'.strtolower($zipcode->state).'/'.SoeHelper::getSlug($zipcode->city);
                $content->content_type = 'Sub-Header';
                $content->content = $default;
                $content->save();
            }
        }
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
