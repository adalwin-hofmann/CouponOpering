<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ImportCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'import';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Imports specified data.';

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
	 * @return void
	 */
	public function fire()
	{
        $type = $this->option('type');
        switch ($type)
        {
            case 'full':
                $this->zipcodes();
                $this->city_import();
                $this->categories();
                break;
            case 'zipcodes':
                $this->zipcodes();
                break;
            
            case 'cities':
                $this->city_import();
                break;

            case 'categories':
                $this->categories();
                break;

            case 'affiliate_tracking':
                $this->affiliate_tracking();
                break;

            case 'assets':
                $this->assets();
                break;

            case 'category_assets':
                $this->category_assets();
                break;

            case 'asset_categories':
                $this->asset_categories();
                break;

            case 'asset_tags':
                $this->asset_tags();
                break;

            case 'tags':
                $this->tags();
                break;

            case 'merchants':
                $this->merchants();
                break;

            case 'locations':
                $this->locations();
                break;

            case 'entities':
                $this->entities();
                break;

            case 'contest_applications':
                $this->contest_applications();
                break;

            case 'users':
                $this->users();
                break;

            case 'user_prints':
                $this->user_prints();
                break;

            case 'user_views':
                $this->user_views();
                break;

            case 'user_clipped':
                $this->user_clipped();
                break;

            case 'user_locations':
                $this->user_locations();
                break;

            case 'user_redeems':
                $this->user_redeems();
                break;

            case 'user_searches':
                $this->user_searches();
                break;

            case 'shares':
                $this->shares();
                break;

            case 'share_emails':
                $this->share_emails();
                break;

            case 'reviews':
                $this->reviews();
                break;

            case 'banners':
                $this->banners();
                break;

            case 'assignment_types':
                $this->assignment_types();
                break;

            case 'customerio_users':
                $this->customerio_users();
                break;

            case 'roles':
                $this->roles();
                break;

            case 'update':
                $this->merchants();
                $this->locations();
                $this->entities();
                break;
            case 'yipit_fix':
                $this->yipit_fix();
                break;
            case 'entity_state_fix':
                $this->entity_state_fix();
                break;
            case 'entity_dates_fix':
                $this->entity_dates_fix();
                break;
            case 'contest_location_fix':
                $this->contest_location_fix();
                break;
            case 'companies':
                $this->companies();
                break;
            case 'company_entities':
                $this->company_entities();
                break;
            case 'yipit_tags':
                $this->yipit_tags();
                break;
            case 'yipit_divisions':
                $this->yipit_divisions();
                break;
            case 'merchant_keywords':
                $this->merchant_keywords();
                break;
            default:
                $this->info('Possible --type= values: update, zipcodes, cities, categories, affiliate_tracking, assets, category_assets, asset_categories, asset_tags, tags, merchants, locations, entities, contest_applications, users, user_prints, user_views, user_clipped, user_locations, user_redeems, user_searches, shares, share_emails, reviews, banners, assignment_types, customerio_users, roles, companies');
                break;
        }
	}

	public function zipcodes()
    {
        // zipcode csv data
        // Source: http://federalgovernmentzipcodes.us/
        // Date: Database Updated: 1/22/2012
        set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("load zipcodes");
        $counter='';

        DB::table("zipcodes")->truncate();

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/free-zipcode-database.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE)
            {
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("free-zipcode-database.csv ".$num." columns\n");
            }

            $counter=0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {  
                $counter++;
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    $latm = ($data[6]*111133);
                    $lngm = (111133*cos(deg2rad((double)$data[6]))*$data[7]);
                    DB::table('zipcodes')->insert(array(
                        'recordnumber'  => $data[0],
                        'zipcode'  => $data[1],
                        'zipcodetype'  => $data[2],
                        'city'  => $data[3],
                        'state'  => $data[4],
                        'locationtype'  => $data[5],
                        'latitude'  => $data[6],
                        'longitude'  => $data[7],
                        'taxreturns2008'  => $data[8],
                        'estimatedpopulation'  => $data[17],
                        'totalwages'  => $data[18],
                        'latm' => $latm,
                        'lngm' => $lngm,
                    ));
                }
            }
            fclose($handle);
        }
        $this->info("loaded $counter zipcodes\n");
		$this->info("Load Canadian Zipcodes\n");

		$counter='';

		if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/canada_postal_codes.csv", "r")) !== FALSE)
		{
			if(($data = fgetcsv($handle, null, ",")) !== FALSE){
				//skip the label row
				//var_dump($data);
				$num = count($data);
				$this->info("canada_postal_codes.csv ".$num." columns\n");
			}
			
			$counter=0;
			while (($data = fgetcsv($handle, null, ",")) !== FALSE)
			{	
				if(count($data)!=$num)
				{
					$this->info($counter.": column # mismatch ".count($data)."\n");
				}
				else
				{
					$locations = DB::table("zipcodes")->where('zipcode','=',$data[9])->first();
					if(empty($locations)) //Add Record
                	{
						DB::table('zipcodes')->insert(array(
							'zipcode'  => $data[9],
							'zipcodetype'  => 'STANDARD',
							'city'  => strtoupper($data[3]),
							'state'  => $data[8],
							'locationtype'  => 'PRIMARY',
							'latitude'  => $data[6],
							'longitude'  => $data[5],
							'estimatedpopulation'  => '2000000',
						));
						$counter++;
					}
				}
				
			}
			fclose($handle);
		}
		$this->info("Loaded $counter Canadian Zipcodes\n");
    }

    public function city_import()
	{
        set_time_limit(30*60); // 30 Mins
		ini_set('memory_limit', '1024M');
		$this->info("Load City Information\n");
		$counter='';

        DB::table("city_images")->truncate();

		if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/city_desc.csv", "r")) !== FALSE)
		{
			if(($data = fgetcsv($handle, null, "~")) !== FALSE){
				//skip the label row
				//var_dump($data);
				$num = count($data);
				$this->info("city_desc.csv ".$num." columns\n");
			}
			
			$counter=0;
			while (($data = fgetcsv($handle, null, "~")) !== FALSE)
			{	
				
				if(count($data)!=$num)
				{
					$this->info($counter.": column # mismatch ".count($data)."\n");
				}
				else
				{
					$zipcode = SOE\DB\Zipcode::where(function($query) use ($data)
                        {
                            $query->where('city', '=', strtoupper($data[0]))->where('state','=',$data[1])->where('locationtype', '=', 'PRIMARY')->where('zipcodetype', '=', 'STANDARD');
                            $query->orWhere('zipcode', '=', $data[2]);
                        })
                        ->orderBy('estimatedpopulation', 'desc')->first();
					$cities = SOE\DB\CityImage::where('name','=',str_replace(array('.', ','),'',preg_replace("/[\s_]/", "-", strtolower(trim($data[0])))))->where('state','=',$data[1])->first();
                    if ($zipcode)
                    {
                        $latm = ($zipcode->latitude*111133);
                        $lngm = (111133*cos(deg2rad((double)$zipcode->latitude))*$zipcode->longitude);
    					if(empty($cities)) {
    						DB::table('city_images')->insert(array(
    							'name'  => str_replace(array('.', ','),'',preg_replace("/[\s_]/", "-", strtolower(trim($data[0])))),
    							'path'  => $this->fix($data[4]),
    							'zip'  => $zipcode->zipcode,
    							'img_415x258'  => $this->fix($data[6]),
    							'img_266x266'  => $this->fix($data[8]),
    							'about'  => $this->fix('<p>'.$this->convert_ascii($data[3]).'<p>'),
    							'state' => $zipcode->state,
    							'latitude' => $zipcode->latitude,
    							'longitude' => $zipcode->longitude,
                                'latm' => $latm,
                                'lngm' => $lngm,
                                'display' => ucwords(strtolower($zipcode->city)),
                                'region_type' => $data[15],
    						));
    						$counter++;
    					}
    					else 
    					{
    						$city = $cities;
    						$city->path = $data[4];
    						$city->zip = $zipcode->zipcode;
    						$city->img_415x258 = $data[6];
    						$city->img_266x266 = $data[8];
    						$city->about = '<p>'.$this->convert_ascii($data[3]).'<p>';
    						$city->state = $zipcode->state;
    						$city->latitude = $zipcode->latitude;
    						$city->longitude = $zipcode->longitude;
                            $city->latm = $latm;
                            $city->lngm = $lngm;
                            $city->display = ucwords(strtolower($zipcode->city));
                            $city->region_type = $data[15];
    						$city->save();
    						$counter++;
    					}
                    } else {
                        $latm = 0;
                        $lngm = 0;
                        if(empty($cities)) {
                            DB::table('city_images')->insert(array(
                                'name'  => str_replace(array('.', ','),'',preg_replace("/[\s_]/", "-", strtolower(trim($data[0])))),
                                'path'  => $this->fix($data[4]),
                                'img_415x258'  => $this->fix($data[6]),
                                'img_266x266'  => $this->fix($data[8]),
                                'about'  => $this->fix('<p>'.$this->convert_ascii($data[3]).'<p>'),
                                'state' => $data[1],
                                'display' => ucwords(strtolower($data[0])),
                                'region_type' => $data[15],
                            ));
                            $counter++;
                        } else {
                            $city = $cities;
                            $city->path = $data[4];
                            $city->img_415x258 = $data[6];
                            $city->img_266x266 = $data[8];
                            $city->about = '<p>'.$this->convert_ascii($data[3]).'<p>';
                            $city->state = $data[1];
                            $city->display = ucwords(strtolower($data[0]));
                            $city->region_type = $data[15];
                            $city->save();
                            $counter++;
                        }
                    }
				}
			}
			fclose($handle);
		}
		$this->info("Loaded Info for $counter Cities\n");
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

	public function categories()
	{
        set_time_limit(30*60); // 30 Mins
		ini_set('memory_limit', '1024M');
		$this->info("Load Category Information\n");
		$counter='';

		if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/categories.csv", "r")) !== FALSE)
		{
			if(($data = fgetcsv($handle, null, ",")) !== FALSE){
				//skip the label row
				//var_dump($data);
				$num = count($data);
				$this->info("categories.csv ".$num." columns\n");
			}
			
			$counter=0;
			while (($data = fgetcsv($handle, null, ",")) !== FALSE)
			{	
				
				if(count($data)!=$num)
				{
					$this->info($counter.": column # mismatch ".count($data)."\n");
				}
				else
				{
					$categories = SOE\DB\Category::where('id','=',$data[2])->first();
					if(empty($categories)) {
						
						DB::table('categories')->insert(array(
							'id'  => $data[2],
							'name'  => $data[3],
							'slug'  => $data[8],
							'tags'  => $data[4],
							'parent_id'  => $data[5],
							'above_heading'  => $this->fix(($data[6]=="NULL")?"":$data[6]),
							'footer_heading'  => $this->fix(($data[7]=="NULL")?"":$data[7]),
							'title'  => $data[9],
							'description'  => $data[10],
						));
						$counter++;
					}
					else 
					{
						$category = $categories;
						$category->id = $data[2];
						$category->name = $data[3];
						$category->slug = $data[8];
						$category->tags = $data[4];
						$category->parent_id = $data[5];
						$category->above_heading = ($data[6]=="NULL")?"":$data[6];
						$category->footer_heading = ($data[7]=="NULL")?"":$data[7];
						$category->title = $data[9];
						$category->description = $data[10];
						$category->save();
						$counter++;
					}
				}
			}
			fclose($handle);
		}
		$this->info("Loaded Info for $counter Categories\n");
	}

    public function affiliate_tracking()
    {
        set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("Load Affiliate Tracking Information\n");
        $counter='';

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/affiliate_tracking.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("affiliate_tracking.csv ".$num." columns\n");
            }
            
            $counter=0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    $tracking = DB::table('affiliate_tracking')->where('id','=',$data[2])->first();
                    if(empty($tracking)) {
                        
                        DB::table('affiliate_tracking')->insert(array(
                            'created_at' => $data[0],
                            'updated_at' => $data[1],
                            'id' => $data[2],
                            'landing_url' => $data[3],
                            'affiliate_id' => $data[4],
                            'utm_source' => $data[5],
                            'utm_medium' => $data[6],
                            'utm_campaign' => $data[7],
                            'user_id' => $data[8],
                        ));
                        $counter++;
                    }
                }
            }
            fclose($handle);
        }
        $this->info("Loaded Info for $counter Affiliate Tracking Records\n");
    }

    public function artwork()
    {
        set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("Load Artwork Information\n");
        $counter='';

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/artwork.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("artwork.csv ".$num." columns\n");
            }
            
            $counter=0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    $tracking = DB::table('artwork')->where('id','=',$data[0])->first();
                    if(empty($tracking)) {
                        
                        DB::table('artwork')->insert(array(
                            'id' => $data[0],
                            'name' => $data[1],
                            'client_contact' => $data[2],
                            'phone' => $data[3],
                            'advertiser' => $data[4],
                            'city' => $data[5],
                            'month' => $data[6],
                            'sales_rep' => $data[7],
                            'email' => $data[8],
                            'filename' => $data[9],
                            's3_link' => $this->fix($data[10]),
                            'status' => $data[11],
                            'created_at' => $data[12],
                            'updated_at' => $data[13],
                        ));
                        $counter++;
                    }
                }
            }
            fclose($handle);
        }
        $this->info("Loaded Info for $counter Artwork Records\n");
    }

    public function assets()
    {
        set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '2048M');
        $this->info("Load Asset Information\n");
        $counter='';
        DB::table('assets')->truncate();
        $sales_assets = DB::table('assets')->where('assetable_type', '=', 'sales_gallery_asset')->get(array('original_id'));
        $aSalesAsset = array();
        foreach($sales_assets as $sales_asset)
        {
            $aSalesAsset[] = $sales_asset->original_id;
        }
        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/asset.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("asset.csv ".$num." columns\n");
            }
            
            $counter=0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    /*$asset = DB::table('assets')
                                    ->where('assetable_type', '=', 'sales_gallery_asset')
                                    ->where('original_id', '=', $data[0])
                                    ->first();*/

                    if(!isset($aSalesAsset[$data[0]]))//empty($asset)) 
                    {    
                        DB::table('assets')->insert(array(
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                            'assetable_id' => 0,
                            'assetable_type' => 'sales_gallery_asset',
                            'name' => $data[1],
                            'path' => $this->fix($data[2]),
                            'long_description' => '',
                            'short_description' => '',
                            'category_id' => $data[4],
                            'subcategory_id' => $data[5],
                            'sub_subcategory_id' => $data[6],
                            'original_id' => $data[0],
                            'type' => '',
                        ));
                        $counter++;
                    }
                }
            }
            fclose($handle);
        }
        $this->info("Loaded Info for $counter Asset Records\n");

        $this->info("Load Merchant Asset Information\n");
        $counter=0;
        $merchant_assets = DB::table('assets')->where('assetable_type', '=', 'Merchant')->get(array('original_id'));
        $aMerchantAsset = array();
        foreach($merchant_assets as $merchant_asset)
        {
            $aMerchantAsset[] = $merchant_asset->original_id;
        }

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/merchant_asset.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("merchant_asset.csv ".$num." columns\n");
            }

            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    /*$asset = DB::table('assets')
                                    ->where('assetable_type', '=', 'Merchant')
                                    ->where('original_id', '=', $data[2])
                                    ->first();*/

                    if(!isset($aMerchantAsset[$data[2]]))//empty($asset))
                    {    
                        DB::table('assets')->insert(array(
                            'created_at' => $data[0],
                            'updated_at' => $data[1],
                            'assetable_id' => $data[8],
                            'assetable_type' => 'Merchant',
                            'name' => $data[5],
                            'path' => $this->fix($data[4]),
                            'long_description' => $this->fix($data[6]),
                            'short_description' => $this->fix($data[7]),
                            'category_id' => 0,
                            'subcategory_id' => 0,
                            'sub_subcategory_id' => 0,
                            'original_id' => $data[2],
                            'type' => $data[3],
                        ));
                        $counter++;
                    }
                }
            }
            fclose($handle);
        }
        $this->info("Loaded Info for $counter Merchant Asset Records\n");
    }

    public function category_assets()
    {
        set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("Load Category Asset Information\n");
        $counter='';

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/category_assets.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("category_assets.csv ".$num." columns\n");
            }
            
            $category_assets = DB::table('assets')->where('assetable_type', '=', 'category_asset')->get(array('original_id'));
            $aCategoryAsset = array();
            foreach($category_assets as $category_asset)
            {
                $aCategoryAsset[] = $category_asset->original_id;
            }
            $counter=0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    /*$asset = DB::table('assets')
                                ->where('assetable_type', '=', 'category_asset')
                                ->where('original_id','=',$data[2])
                                ->first();*/
                    if(!isset($aCategoryAsset[$data[2]]))//empty($asset))
                    {
                        
                        DB::table('assets')->insert(array(
                            'created_at' => $data[0],
                            'updated_at' => $data[1],
                            'assetable_id' => 0,
                            'assetable_type' => 'category_asset',
                            'name' => $data[3],
                            'path' => $this->fix($data[4]),
                            'long_description' => '',
                            'short_description' => '',
                            'category_id' => $data[5],
                            'subcategory_id' => $data[6],
                            'original_id' => $data[2],
                            'type' => '',
                        ));
                        $counter++;
                    }
                }
            }
            fclose($handle);
        }
        $this->info("Loaded Info for $counter Category Asset Records\n");
    }

    public function asset_categories()
    {
        set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("Load Asset Category Information\n");
        $counter='';

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/asset_category.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("asset_category.csv ".$num." columns\n");
            }
            
            $counter=0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    $asset_category = DB::table('asset_categories')->where('id','=',$data[2])->first();
                    if(empty($asset_category)) {
                        
                        DB::table('asset_categories')->insert(array(
                            'created_at' => $data[0],
                            'updated_at' => $data[1],
                            'id' => $data[2],
                            'name' => $data[3],
                            'parent_id' => $data[4]
                        ));
                        $counter++;
                    }
                }
            }
            fclose($handle);
        }
        $this->info("Loaded Info for $counter Asset Category Records\n");
    }

    public function asset_tags()
    {
        set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("Load Asset Tag Information\n");
        $counter='';

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/asset_tags.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("asset_tags.csv ".$num." columns\n");
            }
            
            $counter=0;
            DB::table('asset_tags')->truncate();
            $assets = array();
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    if(in_array($data[0], $assets))
                    {
                        $asset_id = $assets[$data[0]];
                    }
                    else
                    {
                        $asset = DB::table('assets')->where('original_id', '=', $data[0])->where('assetable_type', '=', 'sales_gallery_asset')->first();
                        $asset_id = empty($asset) ? 0 : $asset->id;
                        $assets[$data[0]] = $asset_id;
                    }
                    if($asset_id != 0)
                    {
                        DB::table('asset_tags')->insert(array(
                            'asset_id' => $asset_id,
                            'tag_id' => $data[1]
                        ));
                        $counter++;
                    }
                }
            }
            fclose($handle);
        }
        $this->info("Loaded Info for $counter Asset Tag Records\n");
    }

    public function tags()
    {
        set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("Load Tag Information\n");
        $counter='';

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/tags.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("tags.csv ".$num." columns\n");
            }
            
            $counter=0;
            DB::table('tags')->truncate();
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {

                    DB::table('tags')->insert(array(
                        'id' => $data[0],
                        'name' => $data[1],
                    ));
                    $counter++;
                }
            }
            fclose($handle);
        }
        $this->info("Loaded Info for $counter Tag Records\n");
    }

    public function merchants()
    {
        set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("Load Merchant Information\n");
        $counter='';

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/merchant.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("merchant.csv ".$num." columns\n");
            }

            DB::statement("DROP TABLE IF EXISTS merchants_temp");
            DB::statement("DROP TABLE IF EXISTS franchises_temp");
            DB::statement("CREATE TABLE merchants_temp LIKE merchants");
            DB::statement("CREATE TABLE franchises_temp LIKE franchises");
            
            $counter=0;
            /*DB::table('merchants')->truncate();
            DB::table('franchises')->truncate();*/
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    DB::table('merchants_temp')->insert(array(
                        'created_at' => $data[0],
                        'updated_at' => $data[1],
                        'id' => $data[2],
                        'name' => $data[3],
                        'display' => $this->fix($data[4]),
                        'slug' => $data[18],
                        'type' => $data[6],
                        'about' => $this->fix($data[7]),
                        'catchphrase' => $this->fix($data[8]),
                        'facebook' => $data[9],
                        'twitter' => $data[10],
                        'website' => $data[11],
                        'hours' => $data[12],
                        'phone' => $data[13],
                        'category_id' => $data[16],
                        'subcategory_id' => $data[15],
                        'default_location_id' => $data[17],
                        'yipitbusiness_id' => $data[30],
                        'primary_contact' => $data[34],
                        'coupon_tab_type' => $data[35],
                        'is_demo' => $data[19],
                        'is_active' => $data[5] == 'ACTIVE' ? 1 : 0,
                        'keywords' => $data[23],
                        'updated_by' => $data[24],
                        'created_by' => $data[25],
                        'max_prints' => $data[26],
                        'rating' => $data[28],
                        'mobile_redemption' => $data[29],
                        'is_displayed' => $data[36],
                    ));
                    
                    DB::table('franchises_temp')->insert(array(
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'name' => '',
                        'merchant_id' =>  $data[2],
                        'maghub_id' => $data[32],
                        'is_active' => $data[5] == 'ACTIVE' ? 1 : 0,
                        'max_prints' => $data[26],
                        'mobile_redemption' => $data[29],
                        'primary_contact' => $data[34]
                    ));
                    $counter++;
                }

            }
            fclose($handle);

            DB::statement("DROP TABLE merchants");
            DB::statement("RENAME TABLE merchants_temp TO merchants");
            DB::statement("DROP TABLE franchises");
            DB::statement("RENAME TABLE franchises_temp TO franchises");
        }
        $this->info("Loaded Info for $counter Merchant Records\n");
    }

    public function locations()
    {
        set_time_limit(60*60); // 60 Mins
        ini_set('memory_limit', '2048M');
        $this->info("Load Location Information\n");
        $counter='';
        $merch_count = DB::table('merchants')->count();
        $franch_count = DB::table('franchises')->count();
        if($merch_count == 0 || $franch_count == 0)
        {
            $this->info("Error: Please load merchants first.\n");
            return;
        }

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/location.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("location.csv ".$num." columns\n");
            }
            
            DB::statement("DROP TABLE IF EXISTS locations_temp");
            DB::statement("CREATE TABLE locations_temp LIKE locations");
            $counter=0;
            //DB::table('locations')->truncate();
            $franchises = DB::table('franchises')->join('merchants', 'franchises.merchant_id', '=', 'merchants.id')->get(array('franchises.id', 'franchises.merchant_id', 'merchants.display', 'merchants.slug'));
            $aFranchises = array();
            foreach($franchises as $franch)
            {
                $aFranchises[$franch->merchant_id] = array('franchise_id' => $franch->id, 'merchant_name' => $franch->display, 'merchant_slug' => $franch->slug);
            }
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    if(!isset($aFranchises[$data[13]]))
                    {
                        continue;
                    }
                    $latm = ($data[10]*111133);
                    $lngm = (111133*cos(deg2rad($data[10]))*$data[9]);
                    DB::table('locations_temp')->insert(array(
                        'created_at' => $data[0],
                        'updated_at' => $data[1],
                        'id' => $data[2],
                        'name' => $data[3],
                        'slug' => SoeHelper::getSlug($data[3]),
                        'is_demo' => 0,
                        'is_active' => $data[4] == 'ACTIVE' ? 1 : 0,
                        'address' => $data[5],
                        'address2' => $data[18],
                        'city' => $data[6],
                        'state' => $data[7],
                        'zip' => $data[8],
                        'latitude' => $data[10],
                        'longitude' => $data[9],
                        'hours' => $data[11],
                        'phone' => $data[14],
                        'website' => $data[15],
                        'rating' => $data[22],
                        'rating_count' => $data[23],
                        'merchant_id' => $data[13],
                        'division_id' => $data[19],
                        'franchise_id' => $aFranchises[$data[13]]['franchise_id'],
                        'is_national' => 0,
                        'created_by' => $data[17],
                        'updated_by' => $data[16],
                        'company_id' => $data[21],
                        'latm' => $latm,
                        'lngm' => $lngm,
                        'merchant_name' => $aFranchises[$data[13]]['merchant_name'],
                        'merchant_slug' => $aFranchises[$data[13]]['merchant_slug'],
                    ));
                    $counter++;
                }

            }

            $locs = DB::table('locations_temp')->groupBy('franchise_id')->get();
            foreach($locs as $loc)
            {
                DB::table('franchises')->where('id', '=', $loc->franchise_id)->update(array('company_id' => $loc->company_id));
            }

            fclose($handle);

            DB::statement("DROP TABLE locations");
            DB::statement("RENAME TABLE locations_temp TO locations");
        }
        $this->info("Loaded Info for $counter Location Records\n");
    }

    public function entities()
    {
        set_time_limit(60*180); // 3 Hours
        ini_set('memory_limit', '4096M');
        $this->info("Load Entity Information - ".date('Y-m-d H:i:s'));
        $counter='';
        $loc_count = DB::table('locations')->count();
        if($loc_count == 0)
        {
            $this->info("Error: Please load locations first.\n");
            return;
        }
        $locations = DB::table('locations')->where('is_national', '=', '0')->get(array('id', 'merchant_id', 'latitude', 'longitude', 'state'));
        $aLocations = array();
        foreach($locations as $location)
        {
            if(isset($aLocations[$location->merchant_id]))
            {
                $aLocations[$location->merchant_id][] = $location;
            }
            else
            {
                $aLocations[$location->merchant_id] = array($location);
            }
        }

        $counter=0;
        $merchants = DB::table('merchants')->get(array('merchants.id', 'merchants.category_id', 'merchants.subcategory_id', 'merchants.slug', 'merchants.display', 'merchants.is_demo'));
        $aMerchants = array();
        $aMerchIDs = array(0);
        foreach($merchants as $merchant)
        {
            $aMerchIDs[] = $merchant->id;
            $aMerchants[$merchant->id] = array('category_id' => $merchant->category_id, 'subcategory_id' => $merchant->subcategory_id, 'path' => '', 'slug' => $merchant->slug, 'display_name' => $merchant->display, 'is_demo' => $merchant->is_demo);
        }
        $logos = DB::table('assets')->whereIn('assetable_id', $aMerchIDs)->where('assetable_type', '=', 'Merchant')->where('name', '=', 'logo1')->get(array('assetable_id', 'path'));
        foreach($logos as $logo)
        {
            $aMerchants[$logo->assetable_id]['path'] = $logo->path;
        }
        
        $offers_sync = DB::table('sync')->where('import_type', '=', 'entities')
                                        ->where('is_imported', '=', '1')
                                        ->orderBy('updated_at', 'desc')
                                        ->first();
        if(!empty($offers_sync))
        {
            $starting = $this->option('starting');
            if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/offer.csv", "r")) !== FALSE)
            {
                if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                    //skip the label row
                    //var_dump($data);
                    $num = count($data);
                    $this->info("offer.csv ".$num." columns\n");
                }
                $offers_counter = 0;
                if(empty($starting))
                {
                    DB::table('offers_import')->truncate();
                    $this->info("Loading data into offers_import table...");
                    while (($data = fgetcsv($handle, null, ",")) !== FALSE)
                    {   
                        if(count($data)!=$num)
                        {
                            $this->info("Row #".$offers_counter." column # mismatch ".count($data)."\n");
                        }
                        else
                        {
                            if($data[0] == '0000-00-00 00:00:00')
                            {
                                // Bad record
                                $offers_counter++;
                                continue;
                            }
                            DB::table('offers_import')->insert(array(
                                    'created_at' => $data[0],
                                    'updated_at' => $data[1],
                                    'id' => $data[2],
                                    'name' => $this->fix($data[3]),
                                    'slug' => SoeHelper::getSlug($data[3]),
                                    'merchant_id' => $data[12],
                                    'yipitdeal_id' => $data[27],
                                    'path' => $this->fix($data[14]),
                                    'path_small' => $this->fix($data[26]),
                                    'is_dailydeal' => $data[13],
                                    'special_price' => $data[22],
                                    'regular_price' => $data[21],
                                    'code' => $data[5],
                                    'savings' => $data[7],
                                    'description' => $this->fix($data[8]),
                                    'starts_at' => $data[9],
                                    'expires_at' => $data[10],
                                    'rating' => 0,
                                    'rating_count' => 0,
                                    'max_redeems' => $data[24],
                                    'max_prints' => $data[25],
                                    'url' => $data[28],
                                    'print_override' => $data[30],
                                    'is_demo' => isset($aMerchants[$data[12]]) ? $aMerchants[$data[12]]['is_demo'] : 0,
                                    'is_active' => $data[4],
                                    'created_by' => $data[16],
                                    'updated_by' => $data[15],
                                    'merchant_wide' => $data[11] == 0 ? 1 : 0,
                                    'is_featured' => isset($data[32]) ? $data[32] : 0,
                                ));
                            $offers_counter++;
                        }
                    }
                    fclose($handle);
                }
            }
            else
            {
                $this->info("Could not read offer.csv");
                return;
            }

            $query = DB::table('offers_import')->join('merchants', 'offers_import.merchant_id', '=', 'merchants.id');
            if(empty($starting))
            {
                $query = $query->where('offers_import.updated_at', '>', $offers_sync->updated_at);
                if($offers_sync->updated_at == '2014-01-15 00:00:00' || $first = $this->option('first'))
                {
                    $query = $query->orWhere('offers_import.id', '>', '154637');
                }
            }
            else
            {
                $query = $query->where('offers_import.id', '>=', $starting);
            }
            
            $updated_offers = $query->get(array('offers_import.*', DB::raw('merchants.type as merchant_type')));
            $this->info(count($updated_offers).' Offers to be updated.');
            $aUpdatedIDs = array(0);
            $aUpdated = array();
            foreach($updated_offers as $updated)
            {
                $aUpdatedIDs[] = $updated->id;
                $aUpdated[$updated->id] = $updated;
            }

            $existing_offers = DB::table('offers')->whereIn('id', $aUpdatedIDs)->get();
            foreach($existing_offers as $existing)
            {
                $updated = $aUpdated[$existing->id];
                DB::table('offers')->where('id', '=', $existing->id)->update(array(
                    'created_at' => $updated->created_at,
                    'updated_at' => $updated->updated_at,
                    'name' => $updated->name,
                    'slug' => $updated->slug,
                    'merchant_id' => $updated->merchant_id,
                    'yipitdeal_id' => $updated->yipitdeal_id,
                    'path' => $updated->path,
                    'path_small' => $updated->path_small,
                    'is_dailydeal' => $updated->is_dailydeal,
                    'special_price' => $updated->special_price,
                    'regular_price' => $updated->regular_price,
                    'code' => $updated->code,
                    'savings' => $updated->savings,
                    'description' => $updated->description,
                    'starts_at' => $updated->starts_at,
                    'expires_at' => $updated->expires_at,
                    'max_redeems' => $updated->max_redeems,
                    'max_prints' => $updated->max_prints,
                    'url' => $updated->url,
                    'print_override' => $updated->print_override,
                    'is_demo' => isset($aMerchants[$updated->merchant_id]) ? $aMerchants[$updated->merchant_id]['is_demo'] : 0,
                    'is_active' => $updated->is_active,
                    'created_by' => $updated->created_by,
                    'updated_by' => $updated->updated_by,
                    'is_featured' => $updated->is_featured,
                ));
                unset($aUpdated[$existing->id]);
            }
            $this->info('Existing offers updated');
            foreach($aUpdated as $updated)
            {
                DB::table('offers')->insert(array(
                    'created_at' => $updated->created_at,
                    'updated_at' => $updated->updated_at,
                    'id' => $updated->id,
                    'name' => $updated->name,
                    'slug' => $updated->slug,
                    'merchant_id' => $updated->merchant_id,
                    'yipitdeal_id' => $updated->yipitdeal_id,
                    'path' => $updated->path,
                    'path_small' => $updated->path_small,
                    'is_dailydeal' => $updated->is_dailydeal,
                    'special_price' => $updated->special_price,
                    'regular_price' => $updated->regular_price,
                    'code' => $updated->code,
                    'savings' => $updated->savings,
                    'description' => $updated->description,
                    'starts_at' => $updated->starts_at,
                    'expires_at' => $updated->expires_at,
                    'rating' => 0,
                    'rating_count' => 0,
                    'max_redeems' => $updated->max_redeems,
                    'max_prints' => $updated->max_prints,
                    'url' => $updated->url,
                    'print_override' => $updated->print_override,
                    'is_demo' => isset($aMerchants[$updated->merchant_id]) ? $aMerchants[$updated->merchant_id]['is_demo'] : 0,
                    'is_active' => $updated->is_active,
                    'created_by' => $updated->created_by,
                    'updated_by' => $updated->updated_by,
                    'is_featured' => $updated->is_featured,
                ));
                $this->info("Inserting into offers table - ".$updated->id);
            }

            $this->info("Creating index on entities...");
            Schema::table('entities', function($table)
            {
                $table->index(array('entitiable_id', 'entitiable_type', 'location_id'), 'id_type_location_index');
            });

            //print_r($aUpdatedIDs);exit;
            $this->info('Updating offer entites...');
            $aExisting = array();
            $aExistingOffers = array();
            $existing_entities = DB::table('entities')->whereIn('entitiable_id', $aUpdatedIDs)
                                                    ->where('entitiable_type', '=', 'Offer')
                                                    ->get(array('entitiable_id', 'location_id'));
            foreach($existing_entities as $existing)
            {
                if(isset($aExisting[$existing->location_id]))
                    $aExisting[$existing->location_id][$existing->entitiable_id] = 1;
                else
                    $aExisting[$existing->location_id] = array($existing->entitiable_id => 1);
                $aExistingOffers[] = $existing->entitiable_id;
            }

            foreach($updated_offers as $updated)
            {
                if(isset($aLocations[$updated->merchant_id]) && $updated->merchant_wide == 1)
                {
                    if($updated->merchant_type == 'PROSPECT')
                    {
                        if(empty($starting))
                        {
                            if(in_array($updated->id, $aExistingOffers))
                            {
                                $this->info("Updating National Entity - Offer Id: ".$updated->id);
                                DB::table('entities')->where('entitiable_id', '=', $updated->id)->where('entitiable_type', '=', 'Offer')->update(array(
                                    'updated_at' => date('Y-m-d H:i:s'),
                                    'name' => $updated->name,
                                    'slug' => $updated->slug,
                                    'merchant_id' => $updated->merchant_id,
                                    'merchant_slug' => $aMerchants[$updated->merchant_id]['slug'],
                                    'merchant_name' => $aMerchants[$updated->merchant_id]['display_name'],
                                    'category_id' => $aMerchants[$updated->merchant_id]['category_id'],
                                    'subcategory_id' => $aMerchants[$updated->merchant_id]['subcategory_id'],
                                    'path' => $updated->path == '' ? $aMerchants[$updated->merchant_id]['path'] : $updated->path,
                                    'is_dailydeal' => $updated->is_dailydeal,
                                    'savings' => $updated->savings,
                                    'special_price' => $updated->special_price,
                                    'regular_price' => $updated->regular_price,
                                    'is_demo' => $aMerchants[$updated->merchant_id]['is_demo'],
                                    'is_active' => $updated->is_active,
                                    'starts_at' => $updated->starts_at,
                                    'expires_at' => $updated->expires_at,
                                    'url' => $updated->url,
                                    'print_override' => $updated->print_override,
                                    'starts_day' => date('z', strtotime($updated->starts_at)) + 1,
                                    'starts_year' => date('Y', strtotime($updated->starts_at)),
                                    'expires_day' => date('z', strtotime($updated->expires_at)) + 1,
                                    'expires_year' => date('Y', strtotime($updated->expires_at)),
                                    'is_featured' => $updated->is_featured,
                                ));
                            }
                            else
                            {
                                $locations_count = count($aLocations[$updated->merchant_id]);
                                $start = 0;
                                for($k = 0; $k < ceil($locations_count / 1000); $k++)
                                {
                                    $inserts = array();
                                    for($i = $start; $i < (1000+$start) && $i < $locations_count; $i++)
                                    {
                                        $inserts[] = array(
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'updated_at' => date('Y-m-d H:i:s'),
                                            'entitiable_id' => $updated->id,
                                            'entitiable_type' => 'Offer',
                                            'name' => $updated->name,
                                            'slug' => $updated->slug,
                                            'merchant_id' => $updated->merchant_id,
                                            'merchant_slug' => $aMerchants[$updated->merchant_id]['slug'],
                                            'merchant_name' => $aMerchants[$updated->merchant_id]['display_name'],
                                            'location_id' => $aLocations[$updated->merchant_id][$i]->id,
                                            'category_id' => $aMerchants[$updated->merchant_id]['category_id'],
                                            'subcategory_id' => $aMerchants[$updated->merchant_id]['subcategory_id'],
                                            'path' => $updated->path == '' ? $aMerchants[$updated->merchant_id]['path'] : $updated->path,
                                            'is_dailydeal' => $updated->is_dailydeal,
                                            'rating' => 0,
                                            'rating_count' => 0,
                                            'savings' => $updated->savings,
                                            'special_price' => $updated->special_price,
                                            'regular_price' => $updated->regular_price,
                                            'is_demo' => $aMerchants[$updated->merchant_id]['is_demo'],
                                            'is_active' => $updated->is_active,
                                            'starts_at' => $updated->starts_at,
                                            'expires_at' => $updated->expires_at,
                                            'url' => $updated->url,
                                            'print_override' => $updated->print_override,
                                            'latitude' => $aLocations[$updated->merchant_id][$i]->latitude,
                                            'longitude' => $aLocations[$updated->merchant_id][$i]->longitude,
                                            'latm' => ($aLocations[$updated->merchant_id][$i]->latitude*111133),
                                            'lngm' => (111133*cos(deg2rad($aLocations[$updated->merchant_id][$i]->latitude))*$aLocations[$updated->merchant_id][$i]->longitude),
                                            'starts_day' => date('z', strtotime($updated->starts_at)) + 1,
                                            'starts_year' => date('Y', strtotime($updated->starts_at)),
                                            'expires_day' => date('z', strtotime($updated->expires_at)) + 1,
                                            'expires_year' => date('Y', strtotime($updated->expires_at)),
                                            'state' => $aLocations[$updated->merchant_id][$i]->state,
                                            'is_featured' => $updated->is_featured,
                                        );
                                        $counter++;
                                    }
                                    if(count($inserts))
                                    {
                                        $this->info("Inserting National Entity - Locations: ".$start." - ".($start+count($inserts))." Offer Id: ".$updated->id);
                                        DB::table('entities')->insert($inserts);
                                    }
                                    $start += count($inserts);
                                }
                            }
                        }
                        else
                        {
                            if(in_array($updated->id, $aExistingOffers))
                            {
                                $last_loc = DB::table('entities')->where('entitiable_id', '=', $updated->id)->where('entitiable_type', '=', 'Offer')->orderBy('location_id', 'desc')->first(array('location_id'));
                                if(!empty($last_loc))
                                {
                                    $remaining_locs = DB::table('locations')->where('merchant_id', '=', $updated->merchant_id)->where('id', '>', $last_loc->location_id)->get();
                                    $aRemaining = array();
                                    foreach($remaining_locs as $remaining)
                                    {
                                        $aRemaining[] = $remaining;
                                    }
                                    $locations_count = count($remaining_locs);
                                    $start = 0;
                                    for($k = 0; $k < ceil($locations_count / 1000); $k++)
                                    {
                                        $inserts = array();
                                        for($i = $start; $i < (1000+$start) && $i < $locations_count; $i++)
                                        {
                                            $inserts[] = array(
                                                'created_at' => date('Y-m-d H:i:s'),
                                                'updated_at' => date('Y-m-d H:i:s'),
                                                'entitiable_id' => $updated->id,
                                                'entitiable_type' => 'Offer',
                                                'name' => $updated->name,
                                                'slug' => $updated->slug,
                                                'merchant_id' => $updated->merchant_id,
                                                'merchant_slug' => $aMerchants[$updated->merchant_id]['slug'],
                                                'merchant_name' => $aMerchants[$updated->merchant_id]['display_name'],
                                                'location_id' => $aRemaining[$i]->id,
                                                'category_id' => $aMerchants[$updated->merchant_id]['category_id'],
                                                'subcategory_id' => $aMerchants[$updated->merchant_id]['subcategory_id'],
                                                'path' => $updated->path == '' ? $aMerchants[$updated->merchant_id]['path'] : $updated->path,
                                                'is_dailydeal' => $updated->is_dailydeal,
                                                'rating' => 0,
                                                'rating_count' => 0,
                                                'savings' => $updated->savings,
                                                'special_price' => $updated->special_price,
                                                'regular_price' => $updated->regular_price,
                                                'is_demo' => $aMerchants[$updated->merchant_id]['is_demo'],
                                                'is_active' => $updated->is_active,
                                                'starts_at' => $updated->starts_at,
                                                'expires_at' => $updated->expires_at,
                                                'url' => $updated->url,
                                                'print_override' => $updated->print_override,
                                                'latitude' => $aRemaining[$i]->latitude,
                                                'longitude' => $aRemaining[$i]->longitude,
                                                'latm' => ($aRemaining[$i]->latitude*111133),
                                                'lngm' => (111133*cos(deg2rad($aRemaining[$i]->latitude))*$aRemaining[$i]->longitude),
                                                'starts_day' => date('z', strtotime($updated->starts_at)) + 1,
                                                'starts_year' => date('Y', strtotime($updated->starts_at)),
                                                'expires_day' => date('z', strtotime($updated->expires_at)) + 1,
                                                'expires_year' => date('Y', strtotime($updated->expires_at)),
                                                'state' => $aRemaining[$i]->state,
                                                'is_featured' => $updated->is_featured,
                                            );
                                            $counter++;
                                        }
                                        if(count($inserts))
                                        {
                                            $this->info("Inserting National Entity - Locations: ".$start." - ".($start+count($inserts))." Offer Id: ".$updated->id);
                                            DB::table('entities')->insert($inserts);
                                        }
                                        $start += count($inserts);
                                    }
                                }
                            }
                            else
                            {
                                $locations_count = count($aLocations[$updated->merchant_id]);
                                $start = 0;
                                for($k = 0; $k < ceil($locations_count / 1000); $k++)
                                {
                                    $inserts = array();
                                    for($i = $start; $i < (1000+$start) && $i < $locations_count; $i++)
                                    {
                                        $inserts[] = array(
                                            'created_at' => date('Y-m-d H:i:s'),
                                            'updated_at' => date('Y-m-d H:i:s'),
                                            'entitiable_id' => $updated->id,
                                            'entitiable_type' => 'Offer',
                                            'name' => $updated->name,
                                            'slug' => $updated->slug,
                                            'merchant_id' => $updated->merchant_id,
                                            'merchant_slug' => $aMerchants[$updated->merchant_id]['slug'],
                                            'merchant_name' => $aMerchants[$updated->merchant_id]['display_name'],
                                            'location_id' => $aLocations[$updated->merchant_id][$i]->id,
                                            'category_id' => $aMerchants[$updated->merchant_id]['category_id'],
                                            'subcategory_id' => $aMerchants[$updated->merchant_id]['subcategory_id'],
                                            'path' => $updated->path == '' ? $aMerchants[$updated->merchant_id]['path'] : $updated->path,
                                            'is_dailydeal' => $updated->is_dailydeal,
                                            'rating' => 0,
                                            'rating_count' => 0,
                                            'savings' => $updated->savings,
                                            'special_price' => $updated->special_price,
                                            'regular_price' => $updated->regular_price,
                                            'is_demo' => $aMerchants[$updated->merchant_id]['is_demo'],
                                            'is_active' => $updated->is_active,
                                            'starts_at' => $updated->starts_at,
                                            'expires_at' => $updated->expires_at,
                                            'url' => $updated->url,
                                            'print_override' => $updated->print_override,
                                            'latitude' => $aLocations[$updated->merchant_id][$i]->latitude,
                                            'longitude' => $aLocations[$updated->merchant_id][$i]->longitude,
                                            'latm' => ($aLocations[$updated->merchant_id][$i]->latitude*111133),
                                            'lngm' => (111133*cos(deg2rad($aLocations[$updated->merchant_id][$i]->latitude))*$aLocations[$updated->merchant_id][$i]->longitude),
                                            'starts_day' => date('z', strtotime($updated->starts_at)) + 1,
                                            'starts_year' => date('Y', strtotime($updated->starts_at)),
                                            'expires_day' => date('z', strtotime($updated->expires_at)) + 1,
                                            'expires_year' => date('Y', strtotime($updated->expires_at)),
                                            'state' => $aLocations[$updated->merchant_id][$i]->state,
                                            'is_featured' => $updated->is_featured,
                                        );
                                        $counter++;
                                    }
                                    if(count($inserts))
                                    {
                                        $this->info("Inserting National Entity - Locations: ".$start." - ".($start+count($inserts))." Offer Id: ".$updated->id);
                                        DB::table('entities')->insert($inserts);
                                    }
                                    $start += count($inserts);
                                }
                            }
                        }
                    }
                    else
                    {
                        foreach($aLocations[$updated->merchant_id] as $location)
                        {
                            $existing = isset($aExisting[$location->id]) && isset($aExisting[$location->id][$updated->id]);
                            if(!$existing)
                            {
                                $this->info("Inserting Entity - Loc Id: ".$location->id." Offer Id: ".$updated->id);
                                DB::table('entities')->insert(array(
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s'),
                                    'entitiable_id' => $updated->id,
                                    'entitiable_type' => 'Offer',
                                    'name' => $updated->name,
                                    'slug' => $updated->slug,
                                    'merchant_id' => $updated->merchant_id,
                                    'merchant_slug' => $aMerchants[$updated->merchant_id]['slug'],
                                    'merchant_name' => $aMerchants[$updated->merchant_id]['display_name'],
                                    'location_id' => $location->id,
                                    'category_id' => $aMerchants[$updated->merchant_id]['category_id'],
                                    'subcategory_id' => $aMerchants[$updated->merchant_id]['subcategory_id'],
                                    'path' => $updated->path == '' ? $aMerchants[$updated->merchant_id]['path'] : $updated->path,
                                    'is_dailydeal' => $updated->is_dailydeal,
                                    'rating' => 0,
                                    'rating_count' => 0,
                                    'savings' => $updated->savings,
                                    'special_price' => $updated->special_price,
                                    'regular_price' => $updated->regular_price,
                                    'is_demo' => $aMerchants[$updated->merchant_id]['is_demo'],
                                    'is_active' => $updated->is_active,
                                    'starts_at' => $updated->starts_at,
                                    'expires_at' => $updated->expires_at,
                                    'url' => $updated->url,
                                    'print_override' => $updated->print_override,
                                    'latitude' => $location->latitude,
                                    'longitude' => $location->longitude,
                                    'latm' => ($location->latitude*111133),
                                    'lngm' => (111133*cos(deg2rad($location->latitude))*$location->longitude),
                                    'starts_day' => date('z', strtotime($updated->starts_at)) + 1,
                                    'starts_year' => date('Y', strtotime($updated->starts_at)),
                                    'expires_day' => date('z', strtotime($updated->expires_at)) + 1,
                                    'expires_year' => date('Y', strtotime($updated->expires_at)),
                                    'state' => $location->state,
                                    'is_featured' => $updated->is_featured,
                                ));
                                $counter++;
                            }
                            else
                            {
                                $this->info("Updating Entity - Loc Id: ".$location->id." Offer Id: ".$updated->id);
                                DB::table('entities')->where('entitiable_id', '=', $updated->id)->where('entitiable_type', '=', 'Offer')->where('location_id', '=', $location->id)->update(array(
                                    'updated_at' => date('Y-m-d H:i:s'),
                                    'name' => $updated->name,
                                    'slug' => $updated->slug,
                                    'merchant_id' => $updated->merchant_id,
                                    'merchant_slug' => $aMerchants[$updated->merchant_id]['slug'],
                                    'merchant_name' => $aMerchants[$updated->merchant_id]['display_name'],
                                    'category_id' => $aMerchants[$updated->merchant_id]['category_id'],
                                    'subcategory_id' => $aMerchants[$updated->merchant_id]['subcategory_id'],
                                    'path' => $updated->path == '' ? $aMerchants[$updated->merchant_id]['path'] : $updated->path,
                                    'is_dailydeal' => $updated->is_dailydeal,
                                    'savings' => $updated->savings,
                                    'special_price' => $updated->special_price,
                                    'regular_price' => $updated->regular_price,
                                    'is_demo' => $aMerchants[$updated->merchant_id]['is_demo'],
                                    'is_active' => $updated->is_active,
                                    'starts_at' => $updated->starts_at,
                                    'expires_at' => $updated->expires_at,
                                    'url' => $updated->url,
                                    'print_override' => $updated->print_override,
                                    'starts_day' => date('z', strtotime($updated->starts_at)) + 1,
                                    'starts_year' => date('Y', strtotime($updated->starts_at)),
                                    'expires_day' => date('z', strtotime($updated->expires_at)) + 1,
                                    'expires_year' => date('Y', strtotime($updated->expires_at)),
                                    'state' => $location->state,
                                    'is_featured' => $updated->is_featured,
                                ));
                            }
                        }
                    }
                }
            }

            /*$this->info("Dropping index on entities...");
            Schema::table('entities', function($table)
            {
                $table->dropIndex('id_type_location_index');
            });*/

            // Get location specific offers
            if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/offer_location.csv", "r")) !== FALSE)
            {
                $this->info("Updating location specific entities...");
                if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                    //skip the label row
                    //var_dump($data);
                    $num = count($data);
                    $this->info("offer_location.csv ".$num." columns\n");
                }
                
                $aOffers = array();
                $aSpecIDs = array();
                $aOffIDs = array();
                while (($data = fgetcsv($handle, null, ",")) !== FALSE)
                { 
                    if(count($data)!=$num)
                    {
                        $this->info($counter.": column # mismatch ".count($data)."\n");
                    }
                    else
                    {
                        if(isset($aOffers[$data[4]]))
                            $aOffers[$data[4]][] = $data[3];
                        else
                            $aOffers[$data[4]] = array($data[3]);
                        $aSpecIDs[] = $data[3];
                        $aOffIDs[] = $data[4];
                    }
                }
                DB::table('offers')->whereIn('id', $aOffIDs)->update(array('is_location_specific' => '1'));

                $existing_specifics = DB::table('entities')->whereIn('entitiable_id', $aOffIDs)
                                                        ->where('entitiable_type', '=', 'Offer')
                                                        ->get(array('entitiable_id', 'location_id'));
                $aSpecifics = array();
                foreach($existing_specifics as $specific)
                {
                    if(isset($aSpecifics[$specific->entitiable_id]))
                        $aSpecifics[$specific->entitiable_id][] = $specific->location_id;
                    else
                        $aSpecifics[$specific->entitiable_id] = array($specific->location_id);
                }

                $aLocs = DB::table('locations')->whereIn('id', $aSpecIDs)->get();
                $aSpecificLocations = array();
                foreach($aLocs as $loc)
                {
                    $aSpecificLocations[$loc->id] = $loc;
                }

                foreach($aOffers as $o => $locations)
                {
                    if(isset($aSpecifics[$o]))
                    {
                        $offer = DB::table('offers')->find($o);
                        foreach($locations as $location)
                        {
                            if(isset($aSpecificLocations[$location]))
                            {
                                $loc = $aSpecificLocations[$location];
                            }
                            else
                            {
                                $loc = DB::table('locations')->find($location);
                            }
                            if(empty($loc))
                            {
                                $this->info("Cannot find Location - ".$location);
                                continue;
                            }
                            if(in_array($location, $aSpecifics[$o]))
                            {
                                $latm = ($loc->latitude*111133);
                                $lngm = (111133*cos(deg2rad($loc->latitude))*$loc->longitude);
                                DB::table('entities')->where('entitiable_id', '=', $o)->where('entitiable_type', '=', 'Offer')->where('location_id', '=', $location)->update(array(
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s'),
                                    'name' => $this->fix($offer->name),
                                    'slug' => $offer->slug,
                                    'location_id' => $loc->id,
                                    'merchant_id' => $offer->merchant_id,
                                    'merchant_slug' => $aMerchants[$offer->merchant_id]['slug'],
                                    'merchant_name' => $aMerchants[$offer->merchant_id]['display_name'],
                                    'category_id' => $aMerchants[$offer->merchant_id]['category_id'],
                                    'subcategory_id' => $aMerchants[$offer->merchant_id]['subcategory_id'],
                                    'latitude' => $loc->latitude,
                                    'longitude' => $loc->longitude,
                                    'path' => $offer->path == '' ? $aMerchants[$offer->merchant_id]['path'] : $offer->path,
                                    'is_dailydeal' => $offer->is_dailydeal,
                                    'rating' => $offer->rating,
                                    'special_price' => $offer->special_price,
                                    'regular_price' => $offer->regular_price,
                                    'is_demo' => $offer->is_demo,
                                    'is_active' => $offer->is_active,
                                    'starts_at' => $offer->starts_at,
                                    'expires_at' => $offer->expires_at,
                                    'rating_count' => $offer->rating_count,
                                    'savings' => $offer->savings,
                                    'url' => $offer->url,
                                    'print_override' => $offer->print_override,
                                    'latm' => $latm,
                                    'lngm' => $lngm,
                                    'starts_day' => date('z', strtotime($offer->starts_at)) + 1,
                                    'starts_year' => date('Y', strtotime($offer->starts_at)),
                                    'expires_day' => date('z', strtotime($offer->expires_at)) + 1,
                                    'expires_year' => date('Y', strtotime($offer->expires_at)),
                                    'state' => $loc->state,
                                    'is_featured' => $offer->is_featured,
                                ));
                                $this->info("Updating Specific - Offer Id: ".$o." Loc Id: ".$location);
                            }
                            else
                            {
                                $latm = ($loc->latitude*111133);
                                $lngm = (111133*cos(deg2rad($loc->latitude))*$loc->longitude);
                                DB::table('entities')->insert(array(
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s'),
                                    'entitiable_id' => $offer->id,
                                    'entitiable_type' => 'Offer',
                                    'name' => $this->fix($offer->name),
                                    'slug' => $offer->slug,
                                    'location_id' => $loc->id,
                                    'merchant_id' => $offer->merchant_id,
                                    'merchant_slug' => $aMerchants[$offer->merchant_id]['slug'],
                                    'merchant_name' => $aMerchants[$offer->merchant_id]['display_name'],
                                    'category_id' => $aMerchants[$offer->merchant_id]['category_id'],
                                    'subcategory_id' => $aMerchants[$offer->merchant_id]['subcategory_id'],
                                    'latitude' => $loc->latitude,
                                    'longitude' => $loc->longitude,
                                    'path' => $offer->path == '' ? $aMerchants[$offer->merchant_id]['path'] : $offer->path,
                                    'is_dailydeal' => $offer->is_dailydeal,
                                    'rating' => $offer->rating,
                                    'special_price' => $offer->special_price,
                                    'regular_price' => $offer->regular_price,
                                    'is_demo' => $offer->is_demo,
                                    'is_active' => $offer->is_active,
                                    'starts_at' => $offer->starts_at,
                                    'expires_at' => $offer->expires_at,
                                    'rating_count' => $offer->rating_count,
                                    'savings' => $offer->savings,
                                    'url' => $offer->url,
                                    'print_override' => $offer->print_override,
                                    'latm' => $latm,
                                    'lngm' => $lngm,
                                    'starts_day' => date('z', strtotime($offer->starts_at)) + 1,
                                    'starts_year' => date('Y', strtotime($offer->starts_at)),
                                    'expires_day' => date('z', strtotime($offer->expires_at)) + 1,
                                    'expires_year' => date('Y', strtotime($offer->expires_at)),
                                    'state' => $loc->state,
                                    'is_featured' => $offer->is_featured,
                                ));
                                $this->info("Inserting Specific - Offer Id: ".$o." Loc Id: ".$location);
                            }
                        }
                    }
                    else
                    {
                        $offer = DB::table('offers')->find($o);
                        if(empty($offer))
                        {
                            $this->info("Cannot Find Offer - ".$o);
                            continue;
                        }
                        foreach($locations as $location)
                        {
                            if(isset($aSpecificLocations[$location]))
                            {
                                $loc = $aSpecificLocations[$location];
                            }
                            else
                            {
                                $loc = DB::table('locations')->find($location);
                            }
                            $latm = ($loc->latitude*111133);
                            $lngm = (111133*cos(deg2rad($loc->latitude))*$loc->longitude);
                            DB::table('entities')->insert(array(
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                                'entitiable_id' => $offer->id,
                                'entitiable_type' => 'Offer',
                                'name' => $this->fix($offer->name),
                                'slug' => $offer->slug,
                                'location_id' => $loc->id,
                                'merchant_id' => $offer->merchant_id,
                                'merchant_slug' => $aMerchants[$offer->merchant_id]['slug'],
                                'merchant_name' => $aMerchants[$offer->merchant_id]['display_name'],
                                'category_id' => $aMerchants[$offer->merchant_id]['category_id'],
                                'subcategory_id' => $aMerchants[$offer->merchant_id]['subcategory_id'],
                                'latitude' => $loc->latitude,
                                'longitude' => $loc->longitude,
                                'path' => $offer->path == '' ? $aMerchants[$offer->merchant_id]['path'] : $offer->path,
                                'is_dailydeal' => $offer->is_dailydeal,
                                'rating' => $offer->rating,
                                'special_price' => $offer->special_price,
                                'regular_price' => $offer->regular_price,
                                'is_demo' => $offer->is_demo,
                                'is_active' => $offer->is_active,
                                'starts_at' => $offer->starts_at,
                                'expires_at' => $offer->expires_at,
                                'rating_count' => $offer->rating_count,
                                'savings' => $offer->savings,
                                'url' => $offer->url,
                                'print_override' => $offer->print_override,
                                'latm' => $latm,
                                'lngm' => $lngm,
                                'starts_day' => date('z', strtotime($offer->starts_at)) + 1,
                                'starts_year' => date('Y', strtotime($offer->starts_at)),
                                'expires_day' => date('z', strtotime($offer->expires_at)) + 1,
                                'expires_year' => date('Y', strtotime($offer->expires_at)),
                                'state' => $loc->state,
                                'is_featured' => $offer->is_featured,
                            ));
                            $this->info("Inserting Specifc - Offer Id: ".$o." Loc Id: ".$location);
                        }
                    }
                }
                fclose($handle);

                $this->info("Dropping index on entities...");
                Schema::table('entities', function($table)
                {
                    $table->dropIndex('id_type_location_index');
                });

                $new_locations = DB::table('locations')->where('created_at', '>', $offers_sync->updated_at)->get();
                $this->info("Creating entities for new locations...");
                $this->info("Creating unique index on entities...");
                DB::table('entities')->where('location_id', '=', '0')->update(array('location_id' => DB::raw('`entities`.`id`')));
                Schema::table('entities', function($table)
                {
                    $table->unique(array('entitiable_id', 'entitiable_type', 'location_id'), 'id_type_location');
                });
                foreach($new_locations as $location)
                {
                    $offers = DB::table('offers')->whereNotIn('id', $aOffIDs)->where('merchant_id', '=', $location->merchant_id)->get();
                    foreach($offers as $offer)
                    {
                        /*$existing_offer = DB::table('entities')->where('entitiable_id', '=', $offer->id)->where('entitiable_type', '=', DB::raw("'Offer'"))->where('location_id', '=', $location->id)->first();
                        if(empty($existing_offer))
                        {
                            $latm = ($location->latitude*111133);
                            $lngm = (111133*cos(deg2rad($location->latitude))*$location->longitude);
                            DB::table('entities')->insert(array(
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                                'entitiable_id' => $offer->id,
                                'entitiable_type' => 'Offer',
                                'name' => $this->fix($offer->name),
                                'slug' => $offer->slug,
                                'location_id' => $location->id,
                                'merchant_id' => $offer->merchant_id,
                                'merchant_slug' => $aMerchants[$offer->merchant_id]['slug'],
                                'merchant_name' => $aMerchants[$offer->merchant_id]['display_name'],
                                'category_id' => $aMerchants[$offer->merchant_id]['category_id'],
                                'subcategory_id' => $aMerchants[$offer->merchant_id]['subcategory_id'],
                                'latitude' => $location->latitude,
                                'longitude' => $location->longitude,
                                'path' => $offer->path == '' ? $aMerchants[$offer->merchant_id]['path'] : $offer->path,
                                'is_dailydeal' => $offer->is_dailydeal,
                                'rating' => $offer->rating,
                                'special_price' => $offer->special_price,
                                'regular_price' => $offer->regular_price,
                                'is_demo' => $offer->is_demo,
                                'is_active' => $offer->is_active,
                                'starts_at' => $offer->starts_at,
                                'expires_at' => $offer->expires_at,
                                'rating_count' => $offer->rating_count,
                                'savings' => $offer->savings,
                                'url' => $offer->url,
                                'print_override' => $offer->print_override,
                                'latm' => $latm,
                                'lngm' => $lngm,
                                'starts_day' => date('z', strtotime($offer->starts_at)) + 1,
                                'starts_year' => date('Y', strtotime($offer->starts_at)),
                                'expires_day' => date('z', strtotime($offer->expires_at)) + 1,
                                'expires_year' => date('Y', strtotime($offer->expires_at)),
                                'state' => $location->state
                            ));
                            $this->info("Inserting New Location Entity - Offer Id: ".$offer->id." Loc Id: ".$location->id);
                        }*/
                        try
                        {
                            $latm = ($location->latitude*111133);
                            $lngm = (111133*cos(deg2rad($location->latitude))*$location->longitude);
                            DB::table('entities')->insert(array(
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                                'entitiable_id' => $offer->id,
                                'entitiable_type' => 'Offer',
                                'name' => $this->fix($offer->name),
                                'slug' => $offer->slug,
                                'location_id' => $location->id,
                                'merchant_id' => $offer->merchant_id,
                                'merchant_slug' => $aMerchants[$offer->merchant_id]['slug'],
                                'merchant_name' => $aMerchants[$offer->merchant_id]['display_name'],
                                'category_id' => $aMerchants[$offer->merchant_id]['category_id'],
                                'subcategory_id' => $aMerchants[$offer->merchant_id]['subcategory_id'],
                                'latitude' => $location->latitude,
                                'longitude' => $location->longitude,
                                'path' => $offer->path == '' ? $aMerchants[$offer->merchant_id]['path'] : $offer->path,
                                'is_dailydeal' => $offer->is_dailydeal,
                                'rating' => $offer->rating,
                                'special_price' => $offer->special_price,
                                'regular_price' => $offer->regular_price,
                                'is_demo' => $offer->is_demo,
                                'is_active' => $offer->is_active,
                                'starts_at' => $offer->starts_at,
                                'expires_at' => $offer->expires_at,
                                'rating_count' => $offer->rating_count,
                                'savings' => $offer->savings,
                                'url' => $offer->url,
                                'print_override' => $offer->print_override,
                                'latm' => $latm,
                                'lngm' => $lngm,
                                'starts_day' => date('z', strtotime($offer->starts_at)) + 1,
                                'starts_year' => date('Y', strtotime($offer->starts_at)),
                                'expires_day' => date('z', strtotime($offer->expires_at)) + 1,
                                'expires_year' => date('Y', strtotime($offer->expires_at)),
                                'state' => $location->state,
                                'is_featured' => $offer->is_featured,
                            ));
                            $this->info("Inserting New Location Entity - Offer Id: ".$offer->id." Loc Id: ".$location->id);
                        }
                        catch(Exception $e)
                        {
                            continue;
                        }
                    }
                    $this->info("Done Inserting Entities for Location: ".$location->id);
                }
                $this->info("Dropping unique index on entities...");
                Schema::table('entities', function($table)
                {
                    $table->dropIndex('id_type_location');
                });
            }

            $this->info("Done Loading Offer Entities.");

            // Load contests
            if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/contests.csv", "r")) !== FALSE)
            {
                $this->info("Loading contests_import table...");
                if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                    //skip the label row
                    //var_dump($data);
                    $num = count($data);
                    $this->info("contests.csv ".$num." columns\n");
                }

                $aMarkets = array('1' => array('lat' => '41.83', 'lng' => '-87.68'),
                                '2' => array('lat' => '42.38', 'lng' => '-83.1'), 
                                '3' => array('lat' => '44.96', 'lng' => '-93.16'));
                DB::table('contests_import')->truncate();
                while (($data = fgetcsv($handle, null, ",")) !== FALSE)
                {   
                    
                    if(count($data)!=$num)
                    {
                        $this->info($counter.": column # mismatch ".count($data)."\n");
                    }
                    else
                    {
                        if($data[26] === '0' && $data[25] == 0)
                        {
                            // Must have either a merchant_id or market
                            $this->info("Contest ".$data[2]." must have either a merchant id or market.\n");
                            continue;
                        }
                        DB::table('contests_import')->insert(array(
                            'created_at' => $data[0],
                            'updated_at' => $data[1],
                            'id' => $data[2],
                            'name' => $this->fix($data[3]),
                            'type' => $data[4],
                            'path' => $data[5],
                            'starts_at' => $data[6],
                            'expires_at' => $data[7],
                            'require_user_id' => $data[8],
                            'banner' => $data[9],
                            'logo' => $data[10],
                            'landing' => $data[11],
                            'contest_logo' => $data[12],
                            'about_us' => $this->fix($data[13]),
                            'contest_description' => $data[14],
                            'slug' => $data[15],
                            'customerio_non_member_attr' => $data[16],
                            'customerio_member_attr' => $data[17],
                            'logo_link' => $data[20],
                            'display_name' => $this->fix($data[21]),
                            'wufoo_link' => $data[22],
                            'contest_rules' => $data[23],
                            'merchant_id' => $data[25],
                            'markets' => $data[26],
                            'fields' => $data[27],
                            'tracking_code' => $data[28],
                            'is_demo' => $data[18],
                            'is_active' => $data[24],
                            'is_featured' => isset($data[32]) ? $data[32] : 0,
                        ));
                    }
                }
                fclose($handle);
                $updated_contests = DB::table('contests_import')->get();
                $this->info(count($updated_contests).' Contests to be updated.');
                $aUpdatedIDs = array(0);
                $aUpdated = array();
                foreach($updated_contests as $updated)
                {
                    $aUpdatedIDs[] = $updated->id;
                    $aUpdated[$updated->id] = $updated;
                }
                $existing_contests = DB::table('contests')->whereIn('id', $aUpdatedIDs)->get(); 
                foreach($existing_contests as $existing)
                {
                    DB::table('contests')->where('id', '=', $existing->id)->update(array(
                        'created_at' => $aUpdated[$existing->id]->created_at,
                        'updated_at' => $aUpdated[$existing->id]->updated_at,
                        'name' => $aUpdated[$existing->id]->name,
                        'type' => $aUpdated[$existing->id]->type,
                        'path' => $aUpdated[$existing->id]->path,
                        'starts_at' => $aUpdated[$existing->id]->starts_at,
                        'expires_at' => $aUpdated[$existing->id]->expires_at,
                        'require_user_id' => $aUpdated[$existing->id]->require_user_id,
                        'banner' => $aUpdated[$existing->id]->banner,
                        'logo' => $aUpdated[$existing->id]->logo,
                        'landing' => $aUpdated[$existing->id]->landing,
                        'contest_logo' => $aUpdated[$existing->id]->contest_logo,
                        'about_us' => $aUpdated[$existing->id]->about_us,
                        'contest_description' => $aUpdated[$existing->id]->contest_description,
                        'slug' => $aUpdated[$existing->id]->slug,
                        'customerio_non_member_attr' => $aUpdated[$existing->id]->customerio_non_member_attr,
                        'customerio_member_attr' => $aUpdated[$existing->id]->customerio_member_attr,
                        'logo_link' => $aUpdated[$existing->id]->logo_link,
                        'display_name' => $aUpdated[$existing->id]->display_name,
                        'wufoo_link' => $aUpdated[$existing->id]->wufoo_link,
                        'contest_rules' => $aUpdated[$existing->id]->contest_rules,
                        'merchant_id' => $aUpdated[$existing->id]->merchant_id,
                        'markets' => $aUpdated[$existing->id]->markets,
                        'fields' => $aUpdated[$existing->id]->fields,
                        'tracking_code' => $aUpdated[$existing->id]->tracking_code,
                        'is_demo' => $aUpdated[$existing->id]->is_demo,
                        'is_active' => $aUpdated[$existing->id]->is_active,
                        'is_featured' => $aUpdated[$existing->id]->is_featured,
                    ));
                    $this->info('Updating Contest - '.$existing->id);
                    unset($aUpdated[$existing->id]);
                }   
                unset($aUpdatedIDs);
                foreach($aUpdated as $updated)
                {
                    DB::table('contests')->insert(array(
                        'created_at' => $updated->created_at,
                        'updated_at' => $updated->updated_at,
                        'id' => $updated->id,
                        'name' => $updated->name,
                        'type' => $updated->type,
                        'path' => $updated->path,
                        'starts_at' => $updated->starts_at,
                        'expires_at' => $updated->expires_at,
                        'require_user_id' => $updated->require_user_id,
                        'banner' => $updated->banner,
                        'logo' => $updated->logo,
                        'landing' => $updated->landing,
                        'contest_logo' => $updated->contest_logo,
                        'about_us' => $updated->about_us,
                        'contest_description' => $updated->contest_description,
                        'slug' => $updated->slug,
                        'customerio_non_member_attr' => $updated->customerio_non_member_attr,
                        'customerio_member_attr' => $updated->customerio_member_attr,
                        'logo_link' => $updated->logo_link,
                        'display_name' => $updated->display_name,
                        'wufoo_link' => $updated->wufoo_link,
                        'contest_rules' => $updated->contest_rules,
                        'merchant_id' => $updated->merchant_id,
                        'markets' => $updated->markets,
                        'fields' => $updated->fields,
                        'tracking_code' => $updated->tracking_code,
                        'is_demo' => $updated->is_demo,
                        'is_active' => $updated->is_active,
                        'is_featured' => $updated->is_featured,
                    ));
                    $this->info('Inserting Contest - '.$updated->id);
                }

                $this->info('Updating contest entites...');
                foreach($updated_contests as $contest)
                {
                    // Create contest entities
                    $markets = explode(',', $contest->markets);
                    $merchant_id = $contest->merchant_id;
                    if($merchant_id != 0)
                    {
                        foreach($aLocations[$contest->merchant_id] as $location)
                        {
                            $existing_entity = DB::table('entities')->where('entitiable_id', '=', $contest->id)
                                                                    ->where('entitiable_type', '=', 'Contest')
                                                                    ->where('location_id', '=', $location->id)
                                                                    ->first();
                            if(empty($existing_entity))
                            {
                                DB::table('entities')->insert(array(
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s'),
                                    'entitiable_id' => $contest->id,
                                    'entitiable_type' => 'Contest',
                                    'name' => $contest->name,
                                    'slug' => $contest->slug,
                                    'secondary_type' => $contest->type,
                                    'location_id' => $location->id,
                                    'merchant_id' => $contest->merchant_id,
                                    'merchant_slug' => $aMerchants[$contest->merchant_id]['slug'],
                                    'merchant_name' => $aMerchants[$contest->merchant_id]['display_name'],
                                    'category_id' => $aMerchants[$contest->merchant_id]['category_id'],
                                    'subcategory_id' => $aMerchants[$contest->merchant_id]['subcategory_id'],
                                    'path' => $contest->path,
                                    'is_demo' => $contest->is_demo,
                                    'is_active' => $contest->is_active,
                                    'starts_at' => $contest->starts_at,
                                    'expires_at' => $contest->expires_at,
                                    'url' => $contest->logo_link,
                                    'latitude' => $location->latitude,
                                    'longitude' => $location->longitude,
                                    'latm' => ($location->latitude*111133),
                                    'lngm' => (111133*cos(deg2rad($location->latitude))*$location->longitude),
                                    'starts_day' => date('z', strtotime($contest->starts_at)) + 1,
                                    'starts_year' => date('Y', strtotime($contest->starts_at)),
                                    'expires_day' => date('z', strtotime($contest->expires_at)) + 1,
                                    'expires_year' => date('Y', strtotime($contest->expires_at)),
                                    'state' => $location->state,
                                    'is_featured' => $contest->is_featured,
                                ));
                                $this->info("Inserting Contest Entity - Contest Id: ".$contest->id);
                                $counter++;
                            }
                            else
                            {
                                DB::table('entities')->where('id', '=', $existing_entity->id)->update(array(
                                    'updated_at' => date('Y-m-d H:i:s'),
                                    'name' => $contest->name,
                                    'slug' => $contest->slug,
                                    'secondary_type' => $contest->type,
                                    'merchant_id' => $contest->merchant_id,
                                    'merchant_slug' => $aMerchants[$contest->merchant_id]['slug'],
                                    'merchant_name' => $aMerchants[$contest->merchant_id]['display_name'],
                                    'category_id' => $aMerchants[$contest->merchant_id]['category_id'],
                                    'subcategory_id' => $aMerchants[$contest->merchant_id]['subcategory_id'],
                                    'path' => $contest->path,
                                    'is_demo' => $contest->is_demo,
                                    'is_active' => $contest->is_active,
                                    'starts_at' => $contest->starts_at,
                                    'expires_at' => $contest->expires_at,
                                    'url' => $contest->logo_link,
                                    'starts_day' => date('z', strtotime($contest->starts_at)) + 1,
                                    'starts_year' => date('Y', strtotime($contest->starts_at)),
                                    'expires_day' => date('z', strtotime($contest->expires_at)) + 1,
                                    'expires_year' => date('Y', strtotime($contest->expires_at)),
                                    'state' => $market == 'CHI' ? 'IL' : $market == 'DET' ? 'MI' : 'MN',
                                    'is_featured' => $contest->is_featured,
                                ));
                                $this->info("Updating Contest Entities - Entity Id: ".$existing_entity->id);
                            }
                        }
                    }
                    else
                    {
                        $this->info("Creating market-specific contest entities...");
                        foreach($markets as $market)
                        {
                            if($market == '0' || $market == '4' || !isset($aMarkets[$market]))
                            {
                                continue;
                            }
                            $latm = ($aMarkets[$market]['lat']*111133);
                            $lngm = (111133*cos(deg2rad($aMarkets[$market]['lat']))*$aMarkets[$market]['lng']);
                            $existing = DB::table('entities')->where('entitiable_id', '=', $contest->id)
                                                            ->where('entitiable_type', '=', 'Contest')
                                                            ->where('latitude', '=', $aMarkets[$market]['lat'])
                                                            ->where('longitude', '=', $aMarkets[$market]['lng'])
                                                            ->first();
                            if(empty($existing))
                            {
                                DB::table('entities')->insert(array(
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s'),
                                    'entitiable_id' => $contest->id,
                                    'entitiable_type' => 'Contest',
                                    'name' => $contest->name,
                                    'slug' => $contest->slug,
                                    'secondary_type' => $contest->type,
                                    'location_id' => $contest->id,
                                    'latitude' => $aMarkets[$market]['lat'],
                                    'longitude' => $aMarkets[$market]['lng'],
                                    'path' => $contest->path,
                                    'is_demo' => $contest->is_demo,
                                    'is_active' => $contest->is_active,
                                    'starts_at' => $contest->starts_at,
                                    'expires_at' => $contest->expires_at,
                                    'url' => $contest->logo_link,
                                    'latm' => ($aMarkets[$market]['lat']*111133),
                                    'lngm' => (111133*cos(deg2rad($aMarkets[$market]['lat']))*$aMarkets[$market]['lng']),
                                    'starts_day' => date('z', strtotime($contest->starts_at)) + 1,
                                    'starts_year' => date('Y', strtotime($contest->starts_at)),
                                    'expires_day' => date('z', strtotime($contest->expires_at)) + 1,
                                    'expires_year' => date('Y', strtotime($contest->expires_at)),
                                    'state' => $market == 'CHI' ? 'IL' : $market == 'DET' ? 'MI' : 'MN',
                                    'is_featured' => $contest->is_featured,
                                ));
                                $counter++;
                            }
                            else
                            {
                                DB::table('entities')->where('id', '=', $existing->id)->update(array(
                                    'updated_at' => date('Y-m-d H:i:s'),
                                    'name' => $contest->name,
                                    'slug' => $contest->slug,
                                    'secondary_type' => $contest->type,
                                    'location_id' => $contest->id,
                                    'latitude' => $aMarkets[$market]['lat'],
                                    'longitude' => $aMarkets[$market]['lng'],
                                    'path' => $contest->path,
                                    'is_demo' => $contest->is_demo,
                                    'is_active' => $contest->is_active,
                                    'starts_at' => $contest->starts_at,
                                    'expires_at' => $contest->expires_at,
                                    'url' => $contest->logo_link,
                                    'latm' => ($aMarkets[$market]['lat']*111133),
                                    'lngm' => (111133*cos(deg2rad($aMarkets[$market]['lat']))*$aMarkets[$market]['lng']),
                                    'starts_day' => date('z', strtotime($contest->starts_at)) + 1,
                                    'starts_year' => date('Y', strtotime($contest->starts_at)),
                                    'expires_day' => date('z', strtotime($contest->expires_at)) + 1,
                                    'expires_year' => date('Y', strtotime($contest->expires_at)),
                                    'state' => $market == 'CHI' ? 'IL' : $market == 'DET' ? 'MI' : 'MN',
                                    'is_featured' => $contest->is_featured,
                                ));
                            }
                        }
                    }
                }
                $this->info("Done loading contest entites.");
            }

            $this->info("Done importing - ".date('Y-m-d H:i:s'));
        }
        else
        {
            $counter=0;
            DB::table('offers')->truncate();
            DB::table('entities')->truncate();
            if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/baseline_data/offers.csv", "r")) !== FALSE)
            {
                if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                    //skip the label row
                    //var_dump($data);
                    $num = count($data);
                    $this->info("offers.csv ".$num." columns\n");
                }
                while (($data = fgetcsv($handle, null, ",")) !== FALSE)
                {   
                    if(count($data)!=$num)
                    {
                        $this->info($counter++.": column # mismatch ".count($data)."\n");
                    }
                    else
                    {
                        if($data[0] == '0000-00-00 00:00:00')
                        {
                            // Bad record
                            continue;
                        }
                        
                        DB::table('offers')->insert(array(
                            'created_at' => $data[0],
                            'updated_at' => $data[1],
                            'id' => $data[2],
                            'name' => $data[3],
                            'slug' => $data[4],
                            'location_id' => $data[5],
                            'merchant_id' => $data[6],
                            'yipitdeal_id' => $data[7],
                            'path' => $data[8],
                            'path_small' => $data[9],
                            'is_dailydeal' => $data[10],
                            'special_price' => $data[11],
                            'regular_price' => $data[12],
                            'code' => $data[13],
                            'description' => $data[14],
                            'starts_at' => $data[15],
                            'expires_at' => $data[16],
                            'rating' => $data[17],
                            'rating_count' => $data[18],
                            'max_redeems' => $data[19],
                            'max_prints' => $data[20],
                            'url' => $data[21],
                            'print_override' => $data[22],
                            'is_demo' => $data[23],
                            'is_active' => $data[24],
                            'created_by' => $data[25],
                            'updated_by' => $data[26],
                            'savings' => $data[27],
                        ));

                        $counter++;
                    }

                }
                $this->info("Loaded {$counter} Offers.");
                fclose($handle);
            }

            // Load entities
            if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/baseline_data/entities.csv", "r")) !== FALSE)
            {
                $counter = 0;
                if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                    //skip the label row
                    //var_dump($data);
                    $num = count($data);
                    $this->info("entities.csv ".$num." columns\n");
                }
                
                while (($data = fgetcsv($handle, null, ",")) !== FALSE)
                { 
                    if(count($data)!=$num)
                    {
                        $this->info($counter.": column # mismatch ".count($data)."\n");
                    }
                    else
                    {
                        DB::table('entities')->insert(array(
                            'created_at' => $data[0],
                            'updated_at' => $data[1],
                            'id' => $data[2],
                            'entitiable_id' => $data[3],
                            'entitiable_type' => $data[4],
                            'name' => $this->fix($data[5]),
                            'slug' => $data[6],
                            'location_id' => $data[7],
                            'category_id' => $data[8],
                            'subcategory_id' => $data[9],
                            'latitude' => $data[10],
                            'longitude' => $data[11],
                            'path' => $data[12],
                            'is_dailydeal' => $data[13],
                            'rating' => $data[14],
                            'special_price' => $data[15],
                            'regular_price' => $data[16],
                            'is_demo' => $data[17],
                            'is_active' => $data[18],
                            'starts_at' => $data[19],
                            'expires_at' => $data[20],
                            'rating_count' => $data[21],
                            'savings' => $data[22],
                            'url' => $data[23],
                            'print_override' => $data[24],
                            'secondary_type' => $data[25],
                            'latm' => $data[26],
                            'lngm' => $data[27],
                            'merchant_id' => $data[28],
                            'merchant_slug' => $data[29],
                            'merchant_name' => $data[30],
                        ));
                    }
                }
                $this->info("Loaded {$counter} Entities.");
                fclose($handle);
            }
            // Load contests
            if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/baseline_data/contests.csv", "r")) !== FALSE)
            {
                $counter = 0;
                if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                    //skip the label row
                    //var_dump($data);
                    $num = count($data);
                    $this->info("contests.csv ".$num." columns\n");
                }
                DB::table('contests')->truncate();
                while (($data = fgetcsv($handle, null, ",")) !== FALSE)
                {   
                    
                    if(count($data)!=$num)
                    {
                        $this->info($counter.": column # mismatch ".count($data)."\n");
                    }
                    else
                    {
                        DB::table('contests')->insert(array(
                            'created_at' => $data[0],
                            'updated_at' => $data[1],
                            'id' => $data[2],
                            'name' => $data[3],
                            'type' => $data[4],
                            'path' => $data[5],
                            'starts_at' => $data[6],
                            'expires_at' => $data[7],
                            'require_user_id' => $data[8],
                            'banner' => $data[9],
                            'logo' => $data[10],
                            'landing' => $data[11],
                            'contest_logo' => $data[12],
                            'about_us' => $data[13],
                            'contest_description' => $data[14],
                            'slug' => $data[15],
                            'customerio_non_member_attr' => $data[16],
                            'customerio_member_attr' => $data[17],
                            'logo_link' => $data[18],
                            'display_name' => $this->fix($data[19]),
                            'wufoo_link' => $data[20],
                            'contest_rules' => $data[21],
                            'merchant_id' => $data[22],
                            'markets' => $data[23],
                            'fields' => $data[24],
                            'tracking_code' => $data[25],
                            'is_demo' => $data[26],
                            'is_active' => $data[27],
                        ));
                        $counter++;
                    }
                }
                $this->info("Loaded {$counter} Contests.");
                fclose($handle);
            }
            DB::table('sync')->truncate();
            DB::table('sync')->insert(array(
                'created_at' => '2014-01-15 00:00:00',
                'updated_at' => '2014-01-15 00:00:00',
                'import_type' => 'entities',
                'is_imported' => '1',
                'is_running' => '0'
            ));
        }
    }

    public function yipit_fix()
    {
        set_time_limit(60*60); // 30 Mins
        ini_set('memory_limit', '2048M');
        $this->info('Compiling list of yipits...');
        $yipit_locations = DB::table('merchants')->join('locations', 'merchants.id', '=', 'locations.merchant_id')
                                                ->where('yipitbusiness_id', '!=', 0)
                                                ->get(array(DB::raw('merchants.id as merchant_id'),
                                                    DB::raw('locations.id as location_id'), 
                                                    'merchants.category_id', 
                                                    'merchants.subcategory_id', 
                                                    'merchants.display',
                                                    'merchants.slug',
                                                    'locations.latitude', 
                                                    'locations.longitude',
                                                    'locations.state'
                                                    ));
        $aLocations = array();
        foreach($yipit_locations as $yl)
        {
            if(isset($aLocations[$yl->merchant_id]))
            {
                $aLocations[$yl->merchant_id][] = $yl;
            }
            else
            {
                $aLocations[$yl->merchant_id] = array($yl);
            }
        }

        $assets = DB::table('merchants')->join('assets', function($join)
                                        {
                                            $join->on('merchants.id', '=', 'assets.assetable_id');
                                            $join->on('assets.assetable_type', '=', DB::raw("'Merchant'"));
                                            $join->on('assets.name', '=', DB::raw("'logo1'"));
                                        })
                                        ->where('merchants.yipitbusiness_id', '!=', 0)
                                        ->get(array('merchants.id', 'assets.path'));

        $aAssets = array();
        foreach($assets as $asset)
        {
            $aAssets[$asset->id] = $asset->path;
        }

        $existing_yipit_entities = DB::table('entities')->join('offers', function($join)
                                                        {
                                                            $join->on('entities.entitiable_id', '=', 'offers.id');
                                                            $join->on('entities.entitiable_type', '=', DB::raw("'Offer'"));
                                                        })
                                                        ->where('offers.yipitdeal_id', '!=', 0)
                                                        ->get(array('offers.id'));
        $aExistingIDs = array(0);
        foreach($existing_yipit_entities as $existing)
        {
            $aExistingIDs[] = $existing->id;
        }
        $this->info('Grabbing non-existing yipits...');
        $yipits = DB::table('offers')->where('yipitdeal_id', '!=', 0)->whereNotIn('id', $aExistingIDs)->get();
        foreach($yipits as $offer)
        {
            if(!isset($aLocations[$offer->merchant_id]))
            {
                $this->info('Could not find locations for OfferId: '.$offer->id);
                continue;
            }
            foreach($aLocations[$offer->merchant_id] as $location)
            {
                $path = isset($aAssets[$offer->merchant_id]) ? $aAssets[$offer->merchant_id] : '';
                $latm = ($location->latitude*111133);
                $lngm = (111133*cos(deg2rad($location->latitude))*$location->longitude);
                DB::table('entities')->insert(array(
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'entitiable_id' => $offer->id,
                    'entitiable_type' => 'Offer',
                    'name' => $this->fix($offer->name),
                    'slug' => $offer->slug,
                    'location_id' => $location->location_id,
                    'merchant_id' => $offer->merchant_id,
                    'merchant_slug' => $location->slug,
                    'merchant_name' => $location->display,
                    'category_id' => $location->category_id,
                    'subcategory_id' => $location->subcategory_id,
                    'latitude' => $location->latitude,
                    'longitude' => $location->longitude,
                    'path' => $offer->path == '' ? $path : $offer->path,
                    'is_dailydeal' => $offer->is_dailydeal,
                    'rating' => $offer->rating,
                    'special_price' => $offer->special_price,
                    'regular_price' => $offer->regular_price,
                    'is_demo' => $offer->is_demo,
                    'is_active' => $offer->is_active,
                    'starts_at' => $offer->starts_at,
                    'expires_at' => $offer->expires_at,
                    'rating_count' => $offer->rating_count,
                    'savings' => $offer->savings,
                    'url' => $offer->url,
                    'print_override' => $offer->print_override,
                    'latm' => $latm,
                    'lngm' => $lngm,
                    'starts_day' => date('z', strtotime($offer->starts_at)) + 1,
                    'starts_year' => date('Y', strtotime($offer->starts_at)),
                    'expires_day' => date('z', strtotime($offer->expires_at)) + 1,
                    'expires_year' => date('Y', strtotime($offer->expires_at)),
                    'state' => $location->state
                ));
                $this->info('Inserting Yipit Entity - OfferId: '.$offer->id.' LocId: '.$location->location_id);
            }
        }
        $this->info('Done inserting entities.');
    }

    public function entity_state_fix()
    {
        $this->info('Updating states...');
        DB::table('entities')->join('locations', 'entities.location_id', '=', 'locations.id')->where('entities.state', '=', '')->where('entities.merchant_id', '!=', '0')->update(array('entities.state' => DB::raw('`locations`.`state`')));
        $this->info('Done.');
    }

    public function entity_dates_fix()
    {
        $this->info('Updating dates...');
        DB::table('entities')->where('expires_year', '=', 0)
                            ->update(array('entities.starts_year' => DB::raw("YEAR(starts_at)"),
                                    'entities.starts_day' => DB::raw("DAYOFYEAR(starts_at)"),
                                    'entities.expires_year' => DB::raw("YEAR(expires_at)"),
                                    'entities.expires_day' => DB::raw("DAYOFYEAR(expires_at)"),
                                ));   
        $this->info('Done.');
    }

    public function contest_location_fix()
    {
        $this->info('Updating contests...');
        DB::table('entities')->where('location_id', '=', '0')->update(array('location_id' => DB::raw('`entities`.`entitiable_id`')));
        $this->info('Done.');
    }

    public function contest_applications()
    {
        set_time_limit(60*30); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("Load Contest Application Information\n");
        $counter='';

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/contest_application.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("contest_application.csv ".$num." columns\n");
            }
            DB::table('contest_applications')->truncate();
            $counter=0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    DB::table('contest_applications')->insert(array(
                        'created_at' => $data[0],
                        'updated_at' => $data[1],
                        'id' => $data[2],
                        'contest_id' => $data[3],
                        'user_id' => $data[11],
                        'nonmember_id' => 0,
                        'name' => $data[4],
                        'city' => $data[6],
                        'state' => $data[7],
                        'zip' => $data[8],
                        'phone' => $data[10],
                        'address' => $data[5],
                        'email' => $data[9],
                    ));
                    $counter++;
                }
            }
            fclose($handle);
        }
        $this->info("Loaded Info for $counter Contest Application Records\n");
    }

    public function users()
    {
        set_time_limit(60*30); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("Load User Information\n");
        $counter='';

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/user.csv", "r")) !== false)//fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/user.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("user.csv ".$num." columns\n");
            }
            DB::table('users')->truncate();
            $counter=0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    $latm = ($data[12]*111133);
                    $lngm = (111133*cos(deg2rad($data[12]))*$data[13]);
                    DB::table('users')->insert(array(
                                    'id' => $data[0],
                                    'email' => $data[1],
                                    'password' => $data[2],
                                    'username' => $data[3],
                                    'type' => $data[4],
                                    'name' => $data[5],
                                    'zipcode' => $data[6],
                                    'city' => $data[7],
                                    'state' => $data[8],
                                    'ip' => $data[9],
                                    'homezip' => $data[10],
                                    'workzip' => $data[11],
                                    'latitude' => $data[12],
                                    'longitude' => $data[13],
                                    'age' => $data[14],
                                    'birthday' => $data[15],
                                    'sex' => $data[16],
                                    'facebookid' => $data[17],
                                    'accesskey' => $data[18],
                                    'secretkey' => $data[19],
                                    'created_at' => $data[20],
                                    'updated_at' => $data[21],
                                    'address' => $data[22],
                                    'signup_source' => $data[23],
                                    'reputation' => $data[24],
                                    'badrep_date' => $data[25],
                                    'is_suspended' => $data[26],
                                    'win5kid' => $data[27],
                                    'latm' => $latm,
                                    'lngm' => $lngm,
                                ));
                    $counter++;
                }
            }
            fclose($handle);
        }
        $this->info("Loaded Info for $counter User Records\n");
    }

    public function user_prints()
    {
        set_time_limit(60*30); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("Load User Print Information\n");
        $counter='';

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/user_print.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("user_print.csv ".$num." columns\n");
            }
            DB::table('user_prints')->truncate();
            $counter=0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    DB::table('user_prints')->insert(array(
                        'created_at' => $data[4],
                        'updated_at' => $data[5],
                        'id' => $data[0],
                        'offer_id' => $data[1],
                        'user_id' => $data[2],
                        'nonmember_id' => $data[6],
                        'code' => $data[3],
                        'type' => $data[7],
                    ));
                    $counter++;
                }
            }
            fclose($handle);
        }
        $this->info("Loaded Info for $counter Print Records\n");
    }

    /**
    * Do we even need this import?
    */
    public function user_views()
    {
        set_time_limit(60*30); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("Load User View Information\n");
        $counter='';

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/user_view.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("user_view.csv ".$num." columns\n");
            }
            DB::table('user_views')->truncate();
            $counter=0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    DB::table('user_views')->insert(array(
                        'created_at' => $data[3],
                        'updated_at' => $data[4],
                        'id' => $data[0],
                        'merchant_id' => $data[1],
                        'user_id' => $data[2],
                        'user_agent' => $data[5],
                        'nonmember_id' => $data[6],
                        'location_id' => 0,
                        'franchise_id' => 0,
                    ));
                    $counter++;
                }
            }
            fclose($handle);
        }
        $this->info("Loaded Info for $counter View Records\n");
    }

    public function user_clipped()
    {
        set_time_limit(60*30); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("Load User Clipped Information\n");
        $counter='';

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/user_clipped.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("user_clipped.csv ".$num." columns\n");
            }
            DB::table('user_clipped')->truncate();
            $counter=0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    DB::table('user_clipped')->insert(array(
                        'created_at' => $data[3],
                        'updated_at' => $data[4],
                        'id' => $data[0],
                        'offer_id' => $data[1],
                        'user_id' => $data[2],
                        'nonmember_id' => 0,
                        'code' => '',
                        'is_deleted' => $data[5],
                    ));
                    $counter++;
                }
            }
            fclose($handle);
        }
        $this->info("Loaded Info for $counter Clipped Records\n");
    }

    public function user_locations()
    {
        set_time_limit(60*30); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("Load User Location Information\n");
        $counter='';

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/user_location.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("user_location.csv ".$num." columns\n");
            }
            DB::table('user_locations')->truncate();
            $counter=0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    $latm = ($data[7]*111133);
                    $lngm = (111133*cos(deg2rad($data[7]))*$data[8]);
                    DB::table('user_locations')->insert(array(
                        'created_at' => $data[0],
                        'updated_at' => $data[1],
                        'id' => $data[2],
                        'user_id' => $data[3],
                        'city' => $data[4],
                        'state' => $data[5],
                        'zip' => '',
                        'latitude' => $data[7],
                        'longitude' => $data[8],
                        'ip' => $data[6],
                        'my_point' => GeometryHelper::point($data[7], $data[8]),
                        'nonmember_id' => 0,
                        'latm' => $latm,
                        'lngm' => $lngm,
                    ));
                    $counter++;
                }
            }
            fclose($handle);
        }
        $this->info("Loaded Info for $counter User Location Records\n");
    }

    public function user_redeems()
    {
        set_time_limit(60*30); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("Load User Redeem Information\n");
        $counter='';

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/user_redeem.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("user_redeem.csv ".$num." columns\n");
            }
            DB::table('user_redeems')->truncate();
            $counter=0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    DB::table('user_redeems')->insert(array(
                        'created_at' => $data[8],
                        'updated_at' => $data[9],
                        'id' => $data[0],
                        'offer_id' => $data[1],
                        'user_id' => $data[2],
                        'nonmember_id' => $data[11],
                        'code' => $data[10],
                        'city' => $data[4],
                        'state' => $data[5],
                        'zip' => $data[3],
                        'latitude' => $data[6],
                        'longitude' => $data[7],
                    ));
                    $counter++;
                }
            }
            fclose($handle);
        }
        $this->info("Loaded Info for $counter User Redeem Records\n");
    }

    public function user_searches()
    {
        set_time_limit(60*30); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("Load User Search Information\n");
        $counter='';

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/user_search.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("user_search.csv ".$num." columns\n");
            }
            DB::table('user_searches')->truncate();
            $counter=0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    DB::table('user_searches')->insert(array(
                        'created_at' => $data[0],
                        'updated_at' => $data[1],
                        'id' => $data[2],
                        'search_text' => $data[3],
                        'city_zip' => $data[4],
                        'user_id' => $data[5],
                        'nonmember_id' => $data[7],
                        'is_anonymous' => $data[6],
                    ));
                    $counter++;
                }
            }
            fclose($handle);
        }
        $this->info("Loaded Info for $counter User Search Records\n");
    }

    public function shares()
    {
        set_time_limit(60*30); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("Load User Share Information\n");
        $counter='';

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/user_share.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("user_share.csv ".$num." columns\n");
            }
            DB::table('shares')->truncate();
            $counter=0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    DB::table('shares')->insert(array(
                        'created_at' => $data[0],
                        'updated_at' => $data[1],
                        'id' => $data[2],
                        'shareable_id' => $data[5],
                        'shareable_type' => 'Offer',
                        'user_id' => $data[3],
                        'type' => $data[6],
                    ));
                    $counter++;
                }
            }
            fclose($handle);
        }
        $this->info("Loaded Info for $counter User Share Records\n");
    }

    public function share_emails()
    {
        set_time_limit(60*30); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("Load User Share Email Information\n");
        $counter='';

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/user_share_email.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("user_share_email.csv ".$num." columns\n");
            }
            DB::table('share_emails')->truncate();
            $counter=0;
            $shares = DB::table('shares')->get(array('id', 'shareable_id', 'shareable_type'));
            $aShares = array();
            foreach($shares as $share)
            {
                $aShares[$share->id] = array('shareable_id' => $share->shareable_id, 'shareable_type' => $share->shareable_type);
            }
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    if(isset($aShares[$data[4]]))
                    {
                        DB::table('share_emails')->insert(array(
                            'created_at' => $data[0],
                            'updated_at' => $data[1],
                            'id' => $data[2],
                            'shareable_id' => $aShares[$data[4]]['shareable_id'],
                            'shareable_type' => $aShares[$data[4]]['shareable_type'],
                            'share_id' => $data[4],
                            'share_email' => $data[5],
                        ));
                        $counter++;
                    }
                }
            }
            fclose($handle);
        }
        $this->info("Loaded Info for $counter User Share Email Records\n");
    }

    public function reviews()
    {
        set_time_limit(60*30); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("Load Review Information\n");
        $counter='';

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/location_review.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("location_review.csv ".$num." columns\n");
            }
            DB::table('reviews')->truncate();
            $counter=0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    DB::table('reviews')->insert(array(
                        'created_at' => $data[0],
                        'updated_at' => $data[1],
                        'id' => $data[2],
                        'reviewable_id' => $data[3],
                        'reviewable_type' => 'Location',
                        'user_id' => $data[4],
                        'content' => $data[5],
                        'is_deleted' => $data[7],
                        'rating' => $data[8],
                        'upvotes' => $data[6],
                    ));
                    $counter++;
                }
            }
            fclose($handle);
        }
        $this->info("Loaded Info for $counter Location Review Records\n");

        $this->info("Load Coupon Review Information\n");
        $counter=0;
        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/user_rating.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("user_rating.csv ".$num." columns\n");
            }
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    DB::table('reviews')->insert(array(
                        'created_at' => $data[5],
                        'updated_at' => $data[6],
                        'reviewable_id' => $data[2],
                        'reviewable_type' => 'Offer',
                        'user_id' => $data[1],
                        'content' => '',
                        'is_deleted' => 0,
                        'rating' => $data[4],
                        'upvotes' => 0,
                    ));
                    $counter++;
                }
            }
            fclose($handle);
        }
        $this->info("Loaded Info for $counter Coupon Review Records\n");
    }

    public function banners()
    {
        set_time_limit(60*30); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("Load Banner Information\n");
        $counter='';
        $merch_count = DB::table('merchants')->count();
        if($merch_count == 0)
        {
            $this->info("Please load merchants first.");
            exit;
        }

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/banner.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("banner.csv ".$num." columns\n");
            }
            DB::table('banners')->truncate();
            $counter=0;
            $merchants = DB::table('merchants')->join('locations', 'merchants.id', '=', 'locations.merchant_id')
                                                ->where('locations.is_national', '=', '1')
                                                ->get(array(DB::raw('merchants.id as merchant_id'), DB::raw('locations.id as location_id'), 'locations.company_id'));
            $aMerchants = array();
            foreach($merchants as $merchant)
            {
                $aMerchants[$merchant->merchant_id] = array('location_id' => $merchant->location_id, 'company_id' => $merchant->company_id);
            }
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    if(isset($aMerchants[$data[9]]))
                    {
                        DB::table('banners')->insert(array(
                            'created_at' => $data[0],
                            'updated_at' => $data[1],
                            'id' => $data[2],
                            'company_id' => $aMerchants[$data[9]]['company_id'],
                            'location_id' => $aMerchants[$data[9]]['location_id'],
                            'post_date' => $data[3],
                            'expire_date' => $data[4],
                            'path' => $this->fix($data[5]),
                            'name' => $data[6],
                            'impressions' => $data[7],
                            'type' => $data[10],
                            'banner_link' => $data[11],
                            'is_deleted' => $data[12],
                            'is_paying_category' => $data[13],
                            'is_paying_subcategory' => $data[14],
                            'asset_type' => $data[15],
                        ));
                        $counter++;
                    }
                    else
                    {
                        $this->info("Error finding merchant for Banner: ".$data[2]."\n");
                    }
                }
            }
            fclose($handle);
        }
        $this->info("Loaded Info for $counter Banner Records\n");

        $this->info("Load Banner Information\n");
        $counter = 0;
        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/banner_click.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("banner_click.csv ".$num." columns\n");
            }
            DB::table('banner_clicks')->truncate();
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    DB::table('banner_clicks')->insert(array(
                        'created_at' => $data[0],
                        'updated_at' => $data[1],
                        'id' => $data[2],
                        'banner_id' => $data[3],
                        'user_id' => $data[4],
                        'category_id' => $data[5],
                        'subcategory_id' => $data[6],
                        'state' => $data[7],
                        'latitude' => $data[8],
                        'longitude' => $data[9],
                        'type' => $data[10],
                    ));
                    $counter++;
                }
            }
            fclose($handle);
        }
        $this->info("Loaded Info for $counter Banner Click Records\n");
    }

    public function assignment_types()
    {
        set_time_limit(60*30); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("Load Assignment Type Information\n");
        $counter='';
        $franch_count = DB::table('franchises')->count();
        if($franch_count == 0)
        {
            $this->info("Please load merchants first.");
            exit;
        }

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/assignmenttype.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("assignmenttype.csv ".$num." columns\n");
            }
            DB::table('assignment_types')->truncate();
            $counter=0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    DB::table('assignment_types')->insert(array(
                        'created_at' => $data[2],
                        'updated_at' => $data[3],
                        'id' => $data[0],
                        'name' => $data[1],
                    ));
                    $counter++;
                }
            }
            fclose($handle);
        }
        $this->info("Loaded Info for $counter Assignment Type Records\n");

        $this->info("Load User Assignment Type Information\n");
        $counter = 0;

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/user_assignmenttype.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("user_assignmenttype.csv ".$num." columns\n");
            }
            DB::table('user_assignment_types')->truncate();
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    DB::table('user_assignment_types')->insert(array(
                        'created_at' => $data[3],
                        'updated_at' => $data[4],
                        'id' => $data[0],
                        'user_id' => $data[1],
                        'assignment_type_id' => $data[2],
                    ));
                    $counter++;
                }
            }
            fclose($handle);
        }
        $this->info("Loaded Info for $counter User Assignment Type Records\n");

        $this->info("Load Merchant User Assignment Information\n");
        $counter = 0;

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/merchant_user_assignmenttype.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("merchant_user_assignmenttype.csv ".$num." columns\n");
            }
            DB::table('franchise_assignments')->truncate();
            $franchises = DB::table('franchises')->groupBy('merchant_id')->get(array('id', 'merchant_id'));
            $aFranchises = array();
            foreach($franchises as $franchise)
            {
                $aFranchises[$franchise->merchant_id] = $franchise->id;
            }
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    if(isset($aFranchises[$data[1]]))
                    {
                        DB::table('franchise_assignments')->insert(array(
                            'created_at' => $data[4],
                            'updated_at' => $data[5],
                            'id' => $data[0],
                            'franchise_id' => $aFranchises[$data[1]],
                            'user_id' => $data[2],
                            'assignment_type_id' => $data[3],
                        ));
                        $counter++;
                    }
                    else
                    {
                        $this->info("Error finding franchise for Assignment: ".$data[0]."\n");
                    }
                }
            }
            fclose($handle);
        }
        $this->info("Loaded Info for $counter Merchant User Assignment Records\n");
    }

    public function customerio_users()
    {
        set_time_limit(60*30); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("Load Customerio User Information\n");
        $counter='';

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/customerio_user.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("customerio_user.csv ".$num." columns\n");
            }
            DB::table('customerio_users')->truncate();
            $counter=0;
            $franchises = DB::table('franchises')->get(array('id', 'merchant_id'));
            $aFranchises = array();
            foreach($franchises as $franchise)
            {
                $aFranchises[$franchise->merchant_id] = $franchise->id;
            }
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    DB::table('customerio_users')->insert(array(
                        'created_at' => $data[0],
                        'updated_at' => $data[1],
                        'id' => $data[2],
                        'custio_created_at' => $data[3],
                        'unsubscribed' => $data[4],
                        'unsubscribed_at' => $data[5],
                        'email' => $data[6],
                        'user_id' => $data[7],
                        'zip' => $data[8],
                        'latitude' => $data[9],
                        'longitude' => $data[10],
                        'version' => $data[11],
                        'franchise_id' => isset($aFranchises[$data[12]]) ? $aFranchises[$data[12]] : 0,
                    ));
                    $counter++;
                }
            }
            fclose($handle);
        }
        $this->info("Loaded Info for $counter Customerio User Records\n");

        $this->info("Load Customerio Preference Information\n");
        $counter = 0;

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/customerio_preferences.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("customerio_preferences.csv ".$num." columns\n");
            }
            DB::table('customerio_preferences')->truncate();
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    DB::table('customerio_preferences')->insert(array(
                        'created_at' => $data[0],
                        'updated_at' => $data[1],
                        'id' => $data[2],
                        'name' => $data[3],
                    ));
                    $counter++;
                }
            }
            fclose($handle);
        }
        $this->info("Loaded Info for $counter Customerio Preference Records\n");

        $this->info("Load Customerio User Preference Information\n");
        $counter = 0;

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/customerio_user_preference.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("customerio_user_preference.csv ".$num." columns\n");
            }
            DB::table('customerio_user_preferences')->truncate();
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    DB::table('customerio_user_preferences')->insert(array(
                        'created_at' => $data[0],
                        'updated_at' => $data[1],
                        'id' => $data[2],
                        'customerio_user_id' => $data[3],
                        'customerio_preference_id' => $data[4],
                    ));
                    $counter++;
                }
            }
            fclose($handle);
        }
        $this->info("Loaded Info for $counter Customerio User Preference Records\n");
    }

    public function roles()
    {
        set_time_limit(60*30); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("Load Role Information\n");
        $counter='';

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/roles.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("roles.csv ".$num." columns\n");
            }
            DB::table('roles')->truncate();
            $counter=0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    DB::table('roles')->insert(array(
                        'created_at' => $data[2],
                        'updated_at' => $data[3],
                        'id' => $data[0],
                        'name' => $data[1],
                    ));
                    $counter++;
                }
            }
            fclose($handle);
        }
        $this->info("Loaded Info for $counter Role Records\n");

        $this->info("Load Rule Information\n");
        $counter = 0;

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/rules.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("rules.csv ".$num." columns\n");
            }
            DB::table('rules')->truncate();
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    DB::table('rules')->insert(array(
                        'created_at' => $data[4],
                        'updated_at' => $data[5],
                        'id' => $data[0],
                        'group' => $data[1],
                        'action' => $data[2],
                        'description' => $data[3],
                    ));
                    $counter++;
                }
            }
            fclose($handle);
        }
        $this->info("Loaded Info for $counter Rule Records\n");

        $this->info("Load Role Rule Information\n");
        $counter = 0;

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/role_rule.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("role_rule.csv ".$num." columns\n");
            }
            DB::table('role_rules')->truncate();
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    DB::table('role_rules')->insert(array(
                        'created_at' => $data[3],
                        'updated_at' => $data[4],
                        'id' => $data[0],
                        'role_id' => $data[1],
                        'rule_id' => $data[2],
                    ));
                    $counter++;
                }
            }
            fclose($handle);
        }
        $this->info("Loaded Info for $counter Role Rule Records\n");

        $this->info("Load Role User Information\n");
        $counter = 0;

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/role_user.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("role_user.csv ".$num." columns\n");
            }
            DB::table('role_users')->truncate();
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    DB::table('role_users')->insert(array(
                        'created_at' => $data[3],
                        'updated_at' => $data[4],
                        'id' => $data[0],
                        'user_id' => $data[1],
                        'role_id' => $data[2],
                    ));
                    $counter++;
                }
            }
            fclose($handle);
        }
        $this->info("Loaded Info for $counter Role User Records\n");
    }

    public function companies()
    {
        set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("Load Company Information\n");
        $counter='';

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/company.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("companies.csv ".$num." columns\n");
            }
            
            $counter=0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    $companies = SOE\DB\Company::where('id','=',$data[2])->first();
                    if(empty($companies)) {
                        
                        DB::table('companies')->insert(array(
                            'id'  => $data[2],
                            'name'  => $data[3],
                            'is_active'  => $data[4],
                            'landing_image'  => $data[5],
                            'logo_image'  => $data[6],
                            'description'  => $data[7],
                            'slogan'  => $data[8],
                            'zip'  => $data[9],
                            'slug'  => $data[10],
                            'is_demo'  => $data[11],
                            'own_market'  => $data[12],
                            'radius'  => $data[13],
                            'latitude'  => $data[14],
                            'longitude'  => $data[15],
                            'has_corporate'  => $data[16],
                            'has_custom_colors'  => $data[17],
                            'address'  => $data[18],
                            'address2'  => $data[19],
                            'city'  => $data[20],
                            'state'  => $data[21],
                            'phone'  => $data[22],
                        ));
                        $counter++;
                    }
                    else 
                    {
                        $company = $companies;
                        $company->id = $data[2];
                        $company->name = $data[3];
                        $company->is_active = $data[4];
                        $company->landing_image = $data[5];
                        $company->logo_image = $data[6];
                        $company->description = $data[7];
                        $company->slogan = $data[8];
                        $company->zip = $data[9];
                        $company->slug = $data[10];
                        $company->is_demo = $data[11];
                        $company->own_market = $data[12];
                        $company->radius = $data[13];
                        $company->latitude = $data[14];
                        $company->longitude = $data[15];
                        $company->has_corporate = $data[16];
                        $company->has_custom_colors = $data[17];
                        $company->address = $data[18];
                        $company->address2 = $data[19];
                        $company->city = $data[20];
                        $company->state = $data[21];
                        $company->phone = $data[22];
                        $company->save();
                        $counter++;
                    }
                }
            }
            fclose($handle);
        }
        $this->info("Loaded Info for $counter Companies\n");
    }

    public function company_entities()
    {
        set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("Update Company Entities\n");
        $counter='';

        $companies = SOE\DB\Company::where('id','!=','1')->where('id','!=','2')->get();
        foreach ($companies as $company) {
            $franchises = SOE\DB\Franchise::where('company_id','=',$company->id)->get();
            foreach ($franchises as $franchise) {
                $locations = SOE\DB\Location::where('franchise_id','=',$franchise->id)->get();
                foreach ($locations as $location) {
                    DB::table('entities')->where('location_id','=',$location->id)->update(array(
                        'company_id'  => $company->id,
                        'company_name'  => $company->name,
                    ));
                    $counter++;
                }
            }
        }
        $this->info("Loaded Info for $counter Companies\n");
    }

    public function yipit_tags()
    {
        set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("Load Yipit Tag Information\n");
        $counter='';

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/yipit_tags.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("yipit_tags.csv ".$num." columns\n");
            }
            
            $counter=0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    switch ($data[3]) {
                        case 1 :
                            $subcategory_id = 5;
                            $category_id = 5;
                            break;
                        case 2 :
                            $subcategory_id = 4;
                            $category_id = 4;
                            break;
                        case 3 :
                            $subcategory_id = 108;
                            $category_id = 11;
                            break;
                        case 4 :
                            $subcategory_id = 11;
                            $category_id = 11;
                            break;
                        case 5 :
                            $subcategory_id = 8;
                            $category_id = 8;
                            break;
                        case 6 :
                            $subcategory_id = 9;
                            $category_id = 9;
                            break;
                        default:
                            $category_id = 9;
                            $subcategory_id = 9;
                    }

                    $yipit_tags = SOE\DB\YipitTag::where('id','=',$data[2])->first();
                    if(empty($yipit_tags)) {
                        
                        DB::table('yipit_tags')->insert(array(
                            'id'  => $data[2],
                            'category_id'  => $category_id,
                            'subcategory_id'  => $subcategory_id,
                            'name'  => $data[4],
                            'slug'  => $data[5],
                        ));
                        $counter++;
                    } else {
                        $yipit_tag = $yipit_tags;
                        $yipit_tag->category_id = $category_id;
                        $yipit_tag->subcategory_id = $subcategory_id;
                        $yipit_tag->name = $data[4];
                        $yipit_tag->slug = $data[5];
                        $yipit_tag->save();
                        $counter++;
                    }

                }
            }
            fclose($handle);
        }
        $this->info("Loaded Info for $counter Yipit Tags\n");
    }

    public function yipit_divisions()
    {
        set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("Load Yipit Division Information\n");
        $counter='';

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/olddata/yipit_division.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("yipit_division.csv ".$num." columns\n");
            }
            DB::table('yipit_divisions')->truncate();
            $counter=0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    DB::table('yipit_divisions')->insert(array(
                        'id' => $data[2],
                        'name' => $data[3],
                        'url' => $data[4],
                        'country' => $data[5],
                        'lon' => $data[6],
                        'lat' => $data[7],
                        'about' => $data[8],
                        'is_active' => $data[9],
                        'time_zone_diff' => $data[10],
                        'slug' => $data[11],
                    ));
                    $counter++;
                }
            }
            fclose($handle);
        }
        $this->info("Loaded Info for $counter Yipit Divisions\n");
    }

    public function merchant_keywords()
    {
        set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("Load New Merchant Keywords\n");
        $counter='';
        $rowCounter='';

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/merchant_keywords.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("merchant_keywords.csv ".$num." columns\n");
            }
            $counter=0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    $merchant = SOE\DB\Merchant::where('name', '=', $data[1])->first();
                    if (!empty($merchant))
                    {
                        $merchant->keywords = $data[2];
                        $merchant->save();
                        $counter++;
                    } else {
                        //$this->info("Name mismatch: ".$data[1]);
                    }
                    $rowCounter++;
                }
            }
            fclose($handle);
        }
        $this->info("Updated $counter merchant keywords for $rowCounter rows of data\n");
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
			array('type', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
            array('first', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
            array('starting', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

    /**
     * fix data from csv files if needed.
     * @param type $data
     */
    public function fix($data)
    {
        $data = str_replace("\\","",$data);
        return($data);
    }

}