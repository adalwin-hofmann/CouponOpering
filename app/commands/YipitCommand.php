<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class YipitCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'yipit';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$city = $this->option('city');
		if ($city != '')
		{
			$url = 'http://api.yipit.com/v1/deals/?key=urKgHYFyyrjXFZCW&format=json&limit=500&division='.$city;
			$this->yipit($url);
			
		} else {
			$url = 'http://api.yipit.com/v1/deals/?key=urKgHYFyyrjXFZCW&format=json&limit=500';
			$this->yipit($url);
		}
	}

    public function yipit($url)
    {
    	set_time_limit(60*50);
        ini_set('memory_limit', '2048M');
        $this->info("Starting...\n");

        $yipitCounter=0;

        $yipitOffers = SOE\DB\Offer::where('yipitdeal_id','!=',0)->get();
        $yipitActiveOffers = DB::table('offers')
        	->where(function($query)
        	{
        		$query->where('yipitdeal_id','!=',0)
        		->where('expires_at', '>', \DB::raw('NOW()'));
        	})
	        ->orWhere(function($query)
            {
            	$query->where('yipitdeal_id','=',0)
        		->where('expires_at', '>', date("Y-m-d H:i:s",strtotime("13 months ago")));
            })
	        ->get();
        foreach ($yipitOffers as $yipitOffer)
        {
        	$session = curl_init();
        	curl_setopt($session, CURLOPT_URL, 'http://api.yipit.com/v1/deals/'.$yipitOffer->yipitdeal_id.'?key=urKgHYFyyrjXFZCW&format=json');
	        curl_setopt($session, CURLOPT_HTTPGET, 1);
	        curl_setopt($session, CURLOPT_HEADER, false);
	        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($session, CURLOPT_VERBOSE, 0);
        	$retDeal = curl_exec($session);
        	$oDeals = json_decode($retDeal);

        	if($oDeals->meta->code == 404)
        	{
        		$yipitCounter++;
        		DB::table('offers')->where('id', $yipitOffer->id)->delete();
	        	DB::table('entities')->where('entitiable_id', $yipitOffer->id)->where('entitiable_type', 'Offer')->delete();
	        	foreach ($yipitActiveOffers as $yipitActiveOffer)
	        	{
	        		if($yipitOffer->merchant_id == $yipitActiveOffer->merchant_id)
	        		{
	        			continue 2;
	        		}
	        	}
	        	DB::table('merchants')->where('id', $yipitOffer->merchant_id)->delete();
	        	DB::table('locations')->where('merchant_id', $yipitOffer->merchant_id)->delete();
	        	DB::table('franchises')->where('merchant_id', $yipitOffer->merchant_id)->delete();
	        	DB::table('assets')->where('assetable_id', $yipitOffer->merchant_id)->where('assetable_type','Merchant')->delete();
	        	if($yipitCounter % 2000 == 0)
	        		$this->info("Removed $yipitCounter Yipit Deals so far...");
        	}
        	curl_close($session);
        }
        $this->info("Finished removing $yipitCounter Yipit Deals and associated data.");

        $session = curl_init();
        $counter=0;

        curl_setopt($session, CURLOPT_URL, $url);
        curl_setopt($session, CURLOPT_HTTPGET, 1);
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_VERBOSE, 0);
        $retDeal = curl_exec($session);
        $oDeals = json_decode($retDeal);

        foreach($oDeals->response->deals as $Item)
        {

        	$banned = SOE\DB\YipitBannedMerchant::where("yipitbusiness_id","=",$Item->business->id)->orWhere('merchant_name','=',$Item->business->name)->first();
        	if(!empty($banned))
        		continue;

        	if ((!empty($Item->tags)) && (!empty($Item->business->locations)))
        	{
        		$dboffer = SOE\DB\Offer::where("yipitdeal_id","=",$Item->id)->first();
	            if(empty($dboffer)) //Add Record
	            {
	            	$tags = array();
	                //Convert Tags to Array
	                foreach ($Item->tags as $tag) 
	                {
	                    $tags[] = $tag->slug;
	                }
	                $yipit_tag = SOE\DB\YipitTag::where('slug', '=', $tags[0])->first();
                    if(empty($yipit_tag))
                        continue;
	                $category = SOE\DB\Category::where('id', '=', $yipit_tag->category_id)->first();
	                $category_slug = $category->slug;
	                $subcategory = SOE\DB\Category::where('id', '=', $yipit_tag->subcategory_id)->first();
	                $subcategory_slug = $subcategory->slug;

	                $dbmerchant = SOE\DB\Merchant::on('mysql-write')->where("yipitbusiness_id","=",$Item->business->id)->first();
	            	if(empty($dbmerchant)) //Add Record
	            	{
	            		$merchant = Merchant::blank();
	            		$merchant->name = $Item->business->name;
	                    $merchant->display = $Item->business->name;
	                    $merchant->website = ($Item->business->url)?$Item->business->url:'';
	                    $merchant->slug = SoeHelper::getSlug(strtolower($this->convert_ascii($Item->business->name)));
	                    $merchant->type = 'RETAIL';
	                    $merchant->yipitbusiness_id = $Item->business->id;
	                    $merchant->category_id = $yipit_tag->category_id;
	                    $merchant->subcategory_id = $yipit_tag->subcategory_id;
	                    $merchant->save();

	                    $asset = Asset::blank();
	                    $asset->assetable_id = $merchant->id;
	                    $asset->assetable_type = 'Merchant';
	                    $asset->path = $Item->images->image_smart;
	                    $asset->name = 'logo1';
	                    $asset->type = 'image';
	                    $asset->save();

	                    $franchise = Franchise::blank();
	                    $franchise->company_id = '2';
	                    $franchise->merchant_id = $merchant->id;
	                    $franchise->save();
                        $dbmerchant = $merchant;
                        $dbfranchise = $franchise;

                        foreach ($Item->business->locations as $location) 
                        {
                            $location_latm = ((($location->lat)?$location->lat:'0')*111133);
                            $location_lngm = (111133*cos(deg2rad((double)(($location->lat)?$location->lat:'0')))*(($location->lon)?$location->lon:'0'));

                            $locations = Location::blank();
                            $locations->name = $location->locality.' Location';
                            $locations->slug = SoeHelper::getSlug(strtolower($this->convert_ascii($location->locality.' Location')));
                            $locations->address = $location->address;
                            $locations->city = $location->locality;
                            $locations->state = ($location->state)?$location->state:"";
                            $locations->zip = ($location->zip_code)?$location->zip_code:"";
                            $locations->longitude = ($location->lon)?$location->lon:"0";
                            $locations->latitude = ($location->lat)?$location->lat:"0";
                            $locations->phone = ($location->phone)?$location->phone:"0";
                            $locations->latm = $location_latm;
                            $locations->lngm = $location_lngm;
                            $locations->company_id = '2';
                            $locations->franchise_id = $franchise->id;
                            $locations->merchant_id = $merchant->id;
                            $locations->merchant_name = $merchant->name;
                            $locations->merchant_slug = $merchant->slug;
                            $locations->save();
                        }
                    }
                    else
                    {
                        $dbfranchise = SOE\DB\Franchise::on('mysql-write')->where('merchant_id', $dbmerchant->id)->first();
                    }

                    $offer = Offer::blank();
	            	$offer->name = $Item->title;
	            	$offer->slug = SoeHelper::getSlug(strtolower($this->convert_ascii($Item->title)));
	                $offer->status = $Item->active;
	                $offer->path = $Item->images->image_smart;
	                $offer->path_small = $Item->images->image_small;
	                $offer->regular_price = ($Item->value->raw)?$Item->value->raw:"0";
	                $offer->special_price = ($Item->price->raw)?$Item->price->raw:"0";
	                $offer->description = $Item->description;
	                $offer->starts_at = DB::raw('NOW()');
	                $offer->expires_at = $Item->end_date;
	                $offer->yipitdeal_id = $Item->id;
	                $offer->url = $Item->url;
	                $offer->is_yipit = '1';
	                $offer->max_prints = '3';
	                $offer->merchant_id = $dbmerchant->id;
	                $offer->franchise_id = $dbfranchise->id;
	                $offer->save();

                    foreach($Item->business->locations as $location)
                	{
                        $dblocation = SOE\DB\Location::on('mysql-write')->where('franchise_id', $dbfranchise->id)->where('name', $location->locality.' Location')->first();
                        if(!$dblocation)
                        {
                            $location_latm = ((($location->lat)?$location->lat:'0')*111133);
                            $location_lngm = (111133*cos(deg2rad((double)(($location->lat)?$location->lat:'0')))*(($location->lon)?$location->lon:'0'));
                            $dblocation = new SOE\DB\Location;
                            $dblocation->name = $location->locality.' Location';
                            $dblocation->slug = SoeHelper::getSlug(strtolower($this->convert_ascii($location->locality.' Location')));
                            $dblocation->address = $location->address;
                            $dblocation->city = $location->locality;
                            $dblocation->state = ($location->state)?$location->state:"";
                            $dblocation->zip = ($location->zip_code)?$location->zip_code:"";
                            $dblocation->longitude = ($location->lon)?$location->lon:"0";
                            $dblocation->latitude = ($location->lat)?$location->lat:"0";
                            $dblocation->phone = ($location->phone)?$location->phone:"0";
                            $dblocation->latm = $location_latm;
                            $dblocation->lngm = $location_lngm;
                            $dblocation->company_id = '2';
                            $dblocation->franchise_id = $franchise->id;
                            $dblocation->merchant_id = $merchant->id;
                            $dblocation->merchant_name = $merchant->name;
                            $dblocation->merchant_slug = $merchant->slug;
                            $dblocation->save();
                        }

						$zip_distance = DB::raw('(sqrt(pow(zipcodes.latm - '.$dblocation->latm.', 2) + pow(zipcodes.lngm - '.$dblocation->lngm.', 2))) as distance');
   						$zipcode = SOE\DB\Zipcode::where('zipcodetype', '=', 'STANDARD')
   							->where(function($query)
   							{
                            	$query->where('locationtype', '=', 'PRIMARY');
                            	$query->orWhere('locationtype', '=', 'ACCEPTABLE');
                           	})
                           	->orderBy('distance','asc')
                           	->first(array('zipcodes.*', $zip_distance));

	                    $entity = Entity::blank();
	                    $entity->entitiable_id = $offer->id;
	                    $entity->entitiable_type = 'Offer';
	                    $entity->name = $offer->name;
	                    $entity->slug = $offer->slug;
	                    $entity->location_id = $dblocation->id;
	                    $entity->category_id = $dbmerchant->category_id;
	                    $entity->subcategory_id = $dbmerchant->subcategory_id;
	                    $entity->latitude = $zipcode->latitude;
	                    $entity->longitude = $zipcode->longitude;
	                    $entity->path = $offer->path;
	                    $entity->starts_at = $offer->starts_at;
	                    $entity->expires_at = $Item->end_date;
	                    $entity->url = $offer->url;
	                    $entity->latm = $zipcode->latm;
	                    $entity->lngm = $zipcode->lngm;
	                    $entity->merchant_id = $dbmerchant->id;
	                    $entity->merchant_slug = $dbmerchant->slug;
	                    $entity->merchant_name = $dbmerchant->name;
	                    $entity->state = $zipcode->state;
	                    $entity->expires_year = date('Y', strtotime($offer->expires_at));
	                    $entity->expires_day = date('z', strtotime($offer->expires_at)) + 1;
	                    $entity->starts_year = date('Y', strtotime($offer->starts_at));
	                    $entity->starts_day = date('z', strtotime($offer->starts_at)) + 1;
	                    $entity->category_slug = $category_slug;
	                    $entity->subcategory_slug = $subcategory_slug;
	                    $entity->company_id = '2';
	                    $entity->company_name = 'Yipit';
	                    $entity->save();
                	}
                	$counter++;
	            } else {
	            	//print_r($Item);
	            	$offer = $dboffer;
	                $offer->expires_at = $Item->end_date;
	                $offer->save();

	               	DB::table('entities')->where('entitiable_id','=',$offer->id)->where('entitiable_type','=','Offer')->update(array('expires_at' => $Item->end_date));
	            }
	            //echo $offer->name;
        	}
        	
        }
        curl_close($session);
        $this->info("Loaded $counter Yipit Deals.\n");

    }

    public function convert_ascii($string) 
	{ 
	  // Replace Single Curly Quotes
	  $search[]  = chr(226).chr(128).chr(152);
	  $replace[] = "'";
	  $search[]  = chr(226).chr(128).chr(153);
	  $replace[] = "'";
	 
	  // Replace Smart Double Curly Quotes
	  $search[]  = chr(226).chr(128).chr(156);
	  $replace[] = '"';
	  $search[]  = chr(226).chr(128).chr(157);
	  $replace[] = '"';
	 
	  // Replace En Dash
	  $search[]  = chr(226).chr(128).chr(147);
	  $replace[] = '--';
	 
	  // Replace Em Dash
	  $search[]  = chr(226).chr(128).chr(148);
	  $replace[] = '---';
	 
	  // Replace Bullet
	  $search[]  = chr(226).chr(128).chr(162);
	  $replace[] = '*';
	 
	  // Replace Middle Dot
	  $search[]  = chr(194).chr(183);
	  $replace[] = '*';
	 
	  // Replace Ellipsis with three consecutive dots
	  $search[]  = chr(226).chr(128).chr(166);
	  $replace[] = '...';
	 
	  // Apply Replacements
	  $string = str_replace($search, $replace, $string);
	 
	  // Remove any non-ASCII Characters
	  $string = preg_replace("/[^\x01-\x7F]/","", $string);
	 
	  return $string; 
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			//array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('city', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
