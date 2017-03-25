<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SoctCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'soct';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import SoCT data.';

    protected $assetRepository;
    protected $categoryRepository;
    protected $franchiseRepository;
    protected $leadEmailRepository;
    protected $usedVehicleRepository;
    protected $zipcodeRepository;

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
        $this->assetRepository = App::make('AssetRepositoryInterface');
        $this->categoryRepository = App::make('CategoryRepositoryInterface');
        $this->franchiseRepository = App::make('FranchiseRepositoryInterface');
        $this->leadEmailRepository = App::make('LeadEmailRepositoryInterface');
        $this->usedVehicleRepository = App::make('UsedVehicleRepositoryInterface');
        $this->zipcodeRepository = App::make('ZipcodeRepositoryInterface');
        $this->vehicleEntities = App::make('VehicleEntityRepositoryInterface');        
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        set_time_limit(60*60); // 60 Mins
        ini_set('memory_limit', '2048M');
		$type = $this->option('type');
        switch ($type)
        {
            case 'initialLoad':
                $this->initialLoad();
                break;
            case 'dealers':
                $this->importDealers();
                break;
            case 'dealers_txt':
                $this->loaddealertxt();
                break;
            case 'used_cars_txt':
                $this->loadusedcarstxt();
                break;
            case 'deals':
                $this->importDeals();
                break;
            case 'inventory':
                $this->importInventory();
                break;
            case 'vehicle_desc':
                $this->vehicle_desc();
                break;
            case 'dealer_brand_keywords':
                $this->dealer_brand_keywords();
                break;
            case 'import_live_dealers':
                $this->importLiveDealers();
                break;
            case 'import_live_inventory':
                $this->importLiveInventory();
                break;
            case 'inventory_only':
                $this->importInventoryOnly();
                break;
            default:
                $this->info('Possible --type= values: initialLoad, dealers, dealers_txt, used_cars_txt, deals, inventory');
            	//$this->initialLoad();
            	//$this->dealerships();
                //$this->loadusedcarstxt();
                break;
        }
	}

	public function initialLoad()
	{
		set_time_limit(30*60); // 30 Mins
		ini_set('memory_limit', '1024M');
		$this->info("Begin Loading Vehicle Make Data\n\n");
		$brandCounter=0;
    	$session = curl_init();

    	$gmc = SOE\DB\VehicleMake::where('slug','=','gmc')->first();
    	$gmc->old_id = "1";
    	$gmc->save();
    	$brandCounter++;
    	$dodge = SOE\DB\VehicleMake::where('slug','=','dodge')->first();
    	$dodge->old_id = "2";
    	$dodge->save();
    	$brandCounter++;
    	$chevrolet = SOE\DB\VehicleMake::where('slug','=','chevrolet')->first();
    	$chevrolet->old_id = "3";
    	$chevrolet->save();
    	$brandCounter++;
    	$buick = SOE\DB\VehicleMake::where('slug','=','buick')->first();
    	$buick->old_id = "4";
    	$buick->save();
    	$brandCounter++;
    	$saab = SOE\DB\VehicleMake::where('slug','=','saab')->first();
    	$saab->old_id = "5";
    	$saab->save();
    	$brandCounter++;
    	$jaguar = SOE\DB\VehicleMake::where('slug','=','jaguar')->first();
    	$jaguar->old_id = "6";
    	$jaguar->save();
    	$brandCounter++;
    	$chrysler = SOE\DB\VehicleMake::where('slug','=','chrysler')->first();
    	$chrysler->old_id = "7";
    	$chrysler->save();
    	$brandCounter++;
    	$jeep = SOE\DB\VehicleMake::where('slug','=','jeep')->first();
    	$jeep->old_id = "8";
    	$jeep->save();
    	$brandCounter++;
    	$ford = SOE\DB\VehicleMake::where('slug','=','ford')->first();
    	$ford->old_id = "9";
    	$ford->save();
    	$brandCounter++;
    	$cadillac = SOE\DB\VehicleMake::where('slug','=','cadillac')->first();
    	$cadillac->old_id = "10";
    	$cadillac->save();
    	$brandCounter++;
    	$landrover = SOE\DB\VehicleMake::where('slug','=','land-rover')->first();
    	$landrover->old_id = "11";
    	$landrover->save();
    	$brandCounter++;
    	$bmw = SOE\DB\VehicleMake::where('slug','=','bmw')->first();
    	$bmw->old_id = "12";
    	$bmw->save();
    	$brandCounter++;
    	$mercedesbenz = SOE\DB\VehicleMake::where('slug','=','mercedes-benz')->first();
    	$mercedesbenz->old_id = "13";
    	$mercedesbenz->save();
    	$brandCounter++;
    	$hyundai = SOE\DB\VehicleMake::where('slug','=','hyundai')->first();
    	$hyundai->old_id = "14";
    	$hyundai->save();
    	$brandCounter++;
    	$audi = SOE\DB\VehicleMake::where('slug','=','audi')->first();
    	$audi->old_id = "15";
    	$audi->save();
    	$brandCounter++;
    	$volkswagen = SOE\DB\VehicleMake::where('slug','=','volkswagen')->first();
    	$volkswagen->old_id = "16";
    	$volkswagen->save();
    	$brandCounter++;
    	$kia = SOE\DB\VehicleMake::where('slug','=','kia')->first();
    	$kia->old_id = "17";
    	$kia->save();
    	$brandCounter++;
    	$mazda = SOE\DB\VehicleMake::where('slug','=','mazda')->first();
    	$mazda->old_id = "18";
    	$mazda->save();
    	$brandCounter++;
    	$acura = SOE\DB\VehicleMake::where('slug','=','acura')->first();
    	$acura->old_id = "19";
    	$acura->save();
    	$brandCounter++;
    	$honda = SOE\DB\VehicleMake::where('slug','=','honda')->first();
    	$honda->old_id = "20";
    	$honda->save();
    	$brandCounter++;
    	$nissan = SOE\DB\VehicleMake::where('slug','=','nissan')->first();
    	$nissan->old_id = "21";
    	$nissan->save();
    	$brandCounter++;
    	$lincoln = SOE\DB\VehicleMake::where('slug','=','lincoln')->first();
    	$lincoln->old_id = "22";
    	$lincoln->save();
    	$brandCounter++;
    	$volvo = SOE\DB\VehicleMake::where('slug','=','volvo')->first();
    	$volvo->old_id = "23";
    	$volvo->save();
    	$brandCounter++;
    	$ram = SOE\DB\VehicleMake::where('slug','=','ram')->first();
    	$ram->old_id = "31";
    	$ram->save();
    	$brandCounter++;
    	$toyota = SOE\DB\VehicleMake::where('slug','=','toyota')->first();
    	$toyota->old_id = "32";
    	$toyota->save();
    	$brandCounter++;
    	$scion = SOE\DB\VehicleMake::where('slug','=','scion')->first();
    	$scion->old_id = "33";
    	$scion->save();
    	$brandCounter++;
    	$subaru = SOE\DB\VehicleMake::where('slug','=','subaru')->first();
    	$subaru->old_id = "34";
    	$subaru->save();
    	$brandCounter++;
    	$mitsubishi = SOE\DB\VehicleMake::where('slug','=','mitsubishi')->first();
    	$mitsubishi->old_id = "35";
    	$mitsubishi->save();
    	$brandCounter++;

    	$harleydavison = SOE\DB\VehicleMake::where('slug','=','harley-davidson')->first();
    	if (empty($harleydavison))
    	{
    		$harleydavison = VehicleMake::blank();
    		$harleydavison->name = "Harley-Davidson";
    		$harleydavison->slug = "harley-davidson";
    		$harleydavison->is_active = 1;
    		$harleydavison->old_id = 24;
    		$harleydavison->save();
    		$brandCounter++;
    	}
    	$other = SOE\DB\VehicleMake::where('slug','=','other')->first();
    	if (empty($other))
    	{
    		$other = VehicleMake::blank();
    		$other->name = "Other";
    		$other->slug = "other";
    		$other->is_active = 1;
    		$other->old_id = 25;
    		$other->save();
    		$brandCounter++;
    	}

    	/*if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/vehicle-data/brands.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE)
            {
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("brands.csv ".$num." columns\n");
            }

            $brandCounter=0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {  
                $brandCounter++;
                if(count($data)!=$num)
                {
                    $this->info($brandCounter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                	$csvMake = trim($data[1]);
                	if (!in_array(strtoupper($csvMake), $makeName))
                	{
                		$this->info("$csvMake\n");
                	}
                }
            }
        }*/
        $this->info("Loaded Info for $brandCounter Vehicle Makes\n");
	}

    protected function importLiveDealers()
    {
        set_time_limit(60*60); // 60 Mins
        ini_set('memory_limit', '2048M');
        $dealers = DB::connection('soct_old')->table('dealerships')->get();
        foreach($dealers as $dealer)
        {
            $existing = \SOE\DB\Merchant::on('mysql-write')
                                        ->join('franchises', 'merchants.id', '=', 'franchises.merchant_id')
                                        ->where('merchants.name', '=', $this->removePunctuation($this->removeHtml($dealer->name)))
                                        ->where('merchants.vendor', '=', 'soct')
                                        ->first(array('merchants.*', DB::raw('franchises.id as franchise_id')));
            if($existing)
                $this->importLiveExistingDealer($existing, $dealer);
            else
                $this->importLiveNewDealer($dealer);
        }
    }

	protected function importDealers()
    {
        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/vehicle-data/dealerships.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                $num = count($data);
                $this->info("dealerships.csv ".$num." columns\n");
            }
            $counter=0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {
                if($data[2] != 1)
                    continue;

                $counter++;
                $existing = \SOE\DB\Merchant::join('franchises', 'merchants.id', '=', 'franchises.merchant_id')
                                            ->where('merchants.name', '=', $this->removePunctuation($this->removeHtml($data[3])))
                                            ->first(array('merchants.*', DB::raw('franchises.id as franchise_id')));
                if($existing)
                    $this->importExistingDealer($existing, $data);
                else
                    $this->importNewDealer($data);
            }

            fclose($handle);
            $this->info($counter.' Dealers Imported');

            if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/vehicle-data/dealership_brands.csv", "r")) !== FALSE)
            {
                if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                    //skip the label row
                    $num = count($data);
                    $this->info("dealership_brands.csv ".$num." columns\n");
                }
                $aDealerIds = array(-1);
                $counter=0;
                while (($data = fgetcsv($handle, null, ",")) !== FALSE)
                {
                    try
                    {
                        $aDealerIds[] = $data[1];
                        DB::table('dealer_brands')->insert(array(
                            'created_at' => DB::raw('NOW()'),
                            'updated_at' => DB::raw('NOW()'),
                            'old_dealer_id' => $data[1],
                            'old_make_id' => $data[2]
                        ));
                    }
                    catch(\Exception $e)
                    {
                        // Dealer brand association already exists
                        continue;
                    }
                    $counter++;                    
                }
                fclose($handle);

                DB::table('dealer_brands')->join('vehicle_makes', 'dealer_brands.old_make_id', '=', 'vehicle_makes.old_id')
                                    ->where('dealer_brands.make_id', '=', '0')
                                    ->update(array('dealer_brands.make_id' => DB::raw('`vehicle_makes`.`id`')));

                DB::table('dealer_brands')->join('merchants', 'dealer_brands.old_dealer_id', '=', 'merchants.old_id')
                                    ->where('dealer_brands.merchant_id', '=', '0')
                                    ->update(array('dealer_brands.merchant_id' => DB::raw('`merchants`.`id`')));

                $franchises = \SOE\DB\Franchise::join('merchants', 'franchises.merchant_id', '=', 'merchants.id')
                                                ->whereIn('merchants.old_id', $aDealerIds)
                                                ->get(array('franchises.*'));
                foreach($franchises as $franchise)
                {
                    $franchise->touch();
                }

                $this->info($counter.' Dealer Brands Imported');
            }
        }
    }

    protected function importLiveNewDealer($dealer)
    {
        $merchant = new \SOE\DB\Merchant;
        $merchant->name = $this->removePunctuation($this->removeHtml($dealer->name));
        $merchant->display = $this->removeHtml($dealer->name);
        $merchant->slug = SoeHelper::getSlug($this->removeHtml($dealer->name));
        $merchant->type = 'PPL';
        $merchant->about = $this->removeHtml($dealer->description);
        $merchant->catchphrase = $this->removeHtml($dealer->catchPhrase);
        $merchant->website = $dealer->website;
        $merchant->hours = $dealer->hours;
        $merchant->phone = $dealer->phone;
        $merchant->category_id = 10;
        $merchant->subcategory_id = 120;
        $merchant->is_demo = 0;
        $merchant->is_active = $dealer->isActive;
        $merchant->max_prints = 1;
        $merchant->mobile_redemption = 1;
        $merchant->rating = 3.5;
        $merchant->is_displayed = 1;
        $merchant->tnl_id = $dealer->tnlID;
        $merchant->old_id = $dealer->id;
        $merchant->vendor = 'soct';
        $merchant->save();

        if($dealer->imagePath != '')
        {
            $logo = new \SOE\DB\Asset;
            $logo->assetable_id = $merchant->id;
            $logo->assetable_type = 'Merchant';
            $logo->path = 'http://saveoncarsandtrucks.com'.$dealer->imagePath;
            $logo->name = 'logo1';
            $logo->type = 'image';
            $logo->save();
        }

        $franchise = new \SOE\DB\Franchise;
        $franchise->name = $merchant->name;
        $franchise->company_id = 1;
        $franchise->merchant_id = $merchant->id;
        $franchise->is_active = $dealer->isActive;
        $franchise->max_prints = 1;
        $franchise->mobile_redemption = 1;
        $franchise->is_demo = 0;
        $franchise->service_plan = 'basic';
        $franchise->is_used_car_leads = 1;
        $franchise->is_new_car_leads = 1;
        
        $franchise->save();

        if($dealer->dealerEmail != '')
        {
            $this->leadEmailRepository->addEmails($franchise, array(array('email' => $dealer->dealerEmail, 'format' => 'pretty')));
            $franchise->is_dealer = 1;
        }
        else if(!empty($franchise->primary_contact))
        {
            $this->leadEmailRepository->addEmails($franchise, array(array('email' => $franchise->primary_contact, 'format' => 'pretty')));
            $franchise->is_dealer = 1;
        }
        $franchise->zipcode = $dealer->zip;
        $franchise->radius = 50*1600;
        $franchise->monthly_budget = 100;
        $franchise->contact_phone = $dealer->phone;
        $franchise->save();

        $location = new \SOE\DB\Location;
        $location->name = $this->removeHtml($dealer->name);
        $location->slug = SoeHelper::getSlug($this->removeHtml($dealer->name));
        $location->is_demo = 0;
        $location->is_active = $dealer->isActive;
        $location->address = $dealer->address1;
        $location->address2 = $dealer->address2;
        $location->city = $dealer->city;
        $location->state = $dealer->state;
        $location->zip = $dealer->zip;
        $location->latitude = $dealer->lat;
        $location->longitude = $dealer->lng;
        $location->hours = $dealer->hours;
        $location->phone = $dealer->phone;
        $location->website = $dealer->website;
        $location->rating = 3.5;
        $location->merchant_id = $merchant->id;
        $location->franchise_id = $franchise->id;
        $location->company_id = 1;
        $cartesian = SoeHelper::getCartesian((double) $dealer->lat, (double) $dealer->lng);
        $location->latm = $cartesian['latm'];
        $location->lngm = $cartesian['lngm'];
        $location->merchant_name = $merchant->display;
        $location->merchant_slug = $merchant->slug;
        $location->save();
    }

    protected function importNewDealer($data)
    {
        $merchant = new \SOE\DB\Merchant;
        $merchant->name = $this->removePunctuation($this->removeHtml($data[3]));
        $merchant->display = $this->removeHtml($data[3]);
        $merchant->slug = SoeHelper::getSlug($this->removeHtml($data[3]));
        $merchant->type = 'PPL';
        $merchant->about = $this->removeHtml($data[17]);
        $merchant->catchphrase = $this->removeHtml($data[5]);
        $merchant->website = $data[14];
        $merchant->hours = $data[13];
        $merchant->phone = $data[11];
        $merchant->category_id = 10;
        $merchant->subcategory_id = 120;
        $merchant->is_demo = 0;
        $merchant->is_active = $data[2];
        $merchant->max_prints = 1;
        $merchant->mobile_redemption = 1;
        $merchant->rating = 3.5;
        $merchant->is_displayed = 1;
        $merchant->tnl_id = $data[1];
        $merchant->old_id = $data[0];
        $merchant->save();

        if($data[18] != '')
        {
            $logo = new \SOE\DB\Asset;
            $logo->assetable_id = $merchant->id;
            $logo->assetable_type = 'Merchant';
            $logo->path = 'http://saveoncarsandtrucks.com'.$data[18];
            $logo->name = 'logo1';
            $logo->type = 'image';
            $logo->save();
        }

        $franchise = new \SOE\DB\Franchise;
        $franchise->name = $merchant->name;
        $franchise->company_id = 1;
        $franchise->merchant_id = $merchant->id;
        $franchise->is_active = $data[2];
        $franchise->max_prints = 1;
        $franchise->mobile_redemption = 1;
        $franchise->is_demo = 0;
        $franchise->service_plan = 'basic';
        $franchise->save();

        if($data[37] != '')
        {
            $this->leadEmailRepository->addEmails($franchise, array(array('email' => $data[37], 'format' => 'pretty')));
            $franchise->is_dealer = 1;
        }
        else if(!empty($franchise->primary_contact))
        {
            $this->leadEmailRepository->addEmails($franchise, array(array('email' => $franchise->primary_contact, 'format' => 'pretty')));
            $franchise->is_dealer = 1;
        }
        $franchise->zipcode = $data[10];
        $franchise->radius = 50*1600;
        $franchise->monthly_budget = 0;
        $franchise->contact_phone = $data[11];
        $franchise->save();

        $location = new \SOE\DB\Location;
        $location->name = $this->removeHtml($data[3]);
        $location->slug = SoeHelper::getSlug($this->removeHtml($data[3]));
        $location->is_demo = 0;
        $location->is_active = $data[2];
        $location->address = $data[6];
        $location->address2 = $data[7];
        $location->city = $data[8];
        $location->state = $data[9];
        $location->zip = $data[10];
        $location->latitude = $data[23];
        $location->longitude = $data[24];
        $location->hours = $data[13];
        $location->phone = $data[11];
        $location->website = $data[14];
        $location->rating = 3.5;
        $location->merchant_id = $merchant->id;
        $location->franchise_id = $franchise->id;
        $location->company_id = 1;
        $cartesian = SoeHelper::getCartesian((double) $data[23], (double) $data[24]);
        $location->latm = $cartesian['latm'];
        $location->lngm = $cartesian['lngm'];
        $location->merchant_name = $merchant->display;
        $location->merchant_slug = $merchant->slug;
        $location->save();
    }

    protected function importLiveExistingDealer($merchant, $dealer)
    {
        $merchant->type = 'PPL';
        $merchant->tnl_id = $dealer->tnlID;
        $merchant->old_id = $dealer->id;
        $merchant->vendor = 'soct';
        $merchant->save();

        if($dealer->imagePath != '')
        {
            $logo = \SOE\DB\Asset::where('assetable_id', '=', $merchant->id)
                                ->where('assetable_type', '=', 'Merchant')
                                ->where('name', '=', 'logo1')->first();
            if(!$logo)
            {
                $logo = new \SOE\DB\Asset;
                $logo->assetable_id = $merchant->id;
                $logo->assetable_type = 'Merchant';
                $logo->name = 'logo1';
                $logo->type = 'image';
                $logo->path = 'http://saveoncarsandtrucks.com'.$dealer->imagePath;
                $logo->save();
            }
        }

        $franchise = \SOE\DB\Franchise::where('merchant_id', '=', $merchant->id)->first();
        $franchise->name = $merchant->name;
        //$franchise->is_active = $data[2];
        $franchise->zipcode = $dealer->zip;
        $franchise->contact_phone = $dealer->phone;
        $franchise->service_plan = 'basic';
        $franchise->radius = 50*1600;
        $franchise->save();

        if($dealer->dealerEmail != '')
        {
            $this->leadEmailRepository->addEmails($franchise, array(array('email' => $dealer->dealerEmail, 'format' => 'pretty')));
            $franchise->is_dealer = 1;
        }
        else if(!empty($franchise->primary_contact))
        {
            $this->leadEmailRepository->addEmails($franchise, array(array('email' => $franchise->primary_contact, 'format' => 'pretty')));
            $franchise->is_dealer = 1;
        }
        $franchise->save();
        $franchise->touch();
    }

    protected function importExistingDealer($merchant, $data)
    {
        //$merchant->name = $this->removePunctuation($this->removeHtml($data[3]));
        //$merchant->display = $this->removeHtml($data[3]);
        //$merchant->slug = SoeHelper::getSlug($this->removeHtml($data[3]));
        $merchant->type = 'PPL';
        //$merchant->about = $this->removeHtml($data[17]);
        //$merchant->catchphrase = $this->removeHtml($data[5]);
        //$merchant->website = $data[14];
        //$merchant->hours = $data[13];
        //$merchant->phone = $data[11];
        //$merchant->is_active = $data[2];
        $merchant->tnl_id = $data[1];
        $merchant->old_id = $data[0];
        $merchant->save();

        if($data[18] != '')
        {
            $logo = \SOE\DB\Asset::where('assetable_id', '=', $merchant->id)
                                ->where('assetable_type', '=', 'Merchant')
                                ->where('name', '=', 'logo1')->first();
            if(!$logo)
            {
                $logo = new \SOE\DB\Asset;
                $logo->assetable_id = $merchant->id;
                $logo->assetable_type = 'Merchant';
                $logo->name = 'logo1';
                $logo->type = 'image';
                $logo->path = 'http://saveoncarsandtrucks.com'.$data[18];
                $logo->save();
            }
        }

        $franchise = \SOE\DB\Franchise::where('merchant_id', '=', $merchant->id)->first();
        $franchise->name = $merchant->name;
        //$franchise->is_active = $data[2];
        $franchise->zipcode = $data[10];
        $franchise->contact_phone = $data[11];
        $franchise->service_plan = 'basic';
        $franchise->radius = 50*1600;
        $franchise->save();

        if($data[37] != '')
        {
            $this->leadEmailRepository->addEmails($franchise, array(array('email' => $data[37], 'format' => 'pretty')));
            $franchise->is_dealer = 1;
        }
        else if(!empty($franchise->primary_contact))
        {
            $this->leadEmailRepository->addEmails($franchise, array(array('email' => $franchise->primary_contact, 'format' => 'pretty')));
            $franchise->is_dealer = 1;
        }
        $franchise->save();
        
        $franchise->touch();

        /*$location = \SOE\DB\Location::where('franchise_id', '=', $franchise->id)->first();
        $location->name = $this->removeHtml($data[3]);
        $location->slug = SoeHelper::getSlug($this->removeHtml($data[3]));
        $location->is_active = $data[2];
        $location->address = $data[6];
        $location->address2 = $data[7];
        $location->city = $data[8];
        $location->state = $data[9];
        $location->zip = $data[10];
        $location->latitude = $data[23];
        $location->longitude = $data[24];
        $location->hours = $data[13];
        $location->phone = $data[11];
        $location->website = $data[14];
        $cartesian = SoeHelper::getCartesian((double) $data[23], (double) $data[24]);
        $location->latm = $cartesian['latm'];
        $location->lngm = $cartesian['lngm'];
        $location->merchant_name = $merchant->display;
        $location->merchant_slug = $merchant->slug;
        $location->save();*/
    }

    protected function importLiveInventory()
    {
        set_time_limit(60*60); // 60 Mins
        ini_set('memory_limit', '2048M');

        $this->info("Updating Dealerships...\n");
        $this->importLiveDealers();

        $this->importInventoryOnly();
    }

    protected function importInventoryOnly()
    {
        $this->info("Updating Inventory...\n");
        $cars = DB::connection('soct_old')->table('used_cars_live')->get();
        $updated_at = date('Y-m-d H:i:s');
        Eloquent::unguard();
        foreach($cars as $car)
        {
            $existing = \SOE\DB\VehicleEntity::on('mysql-used')
                                            ->where('vendor', 'soct')
                                            ->where('vendor_dealer_key', '=', $car->tnlID)
                                            ->where('vin', '=', $car->vin)
                                            ->first();
            if($existing)
            {
                $existing->vendor_dealer_key = $car->tnlID;
                $existing->vendor_inventory_id = $car->id;
                $existing->save();
                $existing->touch();
                $this->info("Existing: ".$existing->id);
            }
            else
            {
                $merchant = \SOE\DB\Merchant::on('mysql-write')->where('tnl_id', '=', $car->tnlID)->first();
                if(empty($merchant))
                    continue;
                $location = \SOE\DB\Location::on('mysql-write')->where('merchant_id', '=', $merchant->id)->first();
                if(empty($location))
                    continue;
                $make = $this->getMake($car->make);
                if(!$make)
                    continue;
                $model = \SOE\DB\VehicleModel::on('mysql')->where('make_id', '=', $make->id)->where('name', '=', $car->model)->first();
                $model_id = empty($model) ? 0 : $model->id;
                $model_year = \SOE\DB\VehicleYear::on('mysql')->where('model_id', '=', $model_id)->where('year', '=', $car->year)->first();
                $model_year_id = empty($model_year) ? 0 : $model_year->id;
                $aImages = explode('|', $car->imageURIs);

                $id = DB::connection('mysql-used')->table('vehicle_entities')->insertGetId(array(
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'vendor_dealer_key' => $car->tnlID,
                    'dealer_name' => $car->franchise,
                    'dealer_slug' => SoeHelper::getSlug($car->franchise),
                    'condition' => 'pre-owned',
                    'address' => $car->address,
                    'city' => $car->city,
                    'state' => $car->state,
                    'zipcode' => $car->zip,
                    'phone' => $car->phone,
                    'latitude' => $car->latitude,
                    'longitude' => $car->longitude,
                    'stock_number' => $car->stockNumber,
                    'vin' => $car->vin,
                    'year' => $car->year,
                    'model_year_id' => $model_year_id,
                    'make' => $make->name,
                    'make_id' => $make->id,
                    'model' => $car->model,
                    'model_id' => $model_id,
                    'mileage' => $car->mileage,
                    'is_certified' => $car->certified,
                    'class' => $car->class,
                    'body_type' => $car->bodyStyle,
                    'fuel' => $car->fuel,
                    'engine' => $car->engine,
                    'cylinders' => $car->cylinders,
                    'transmission' => $car->transmission,
                    'drive_type' => $car->driveType,
                    'trim_level' => $car->trimLevel,
                    'exterior_color' => $car->exteriorColor,
                    'interior_color' => $car->interiorColor,
                    'options' => $car->dealerSpecifiedFeatures,
                    'dealer_comments' => $car->dealerComments,
                    'msrp' => $car->msrp,
                    'internet_price' => $car->internetPrice,
                    'image_count' => count($aImages),
                    'image_urls' => $car->imageURIs,
                    'display_image' => count($aImages) ? $aImages[0] : '',
                    'merchant_id' => $merchant->id,
                    'location_id' => $location->id,
                    'latm' => $location->latm,
                    'lngm' => $location->lngm,
                    'vendor_dealer_id' => $merchant->old_id,
                    'vendor' => 'soct',
                    'vendor_inventory_id' => $car->id
                ));
            }
        }

        \SOE\DB\VehicleEntity::on('mysql-used')->where('vendor', 'soct')->where('updated_at', '<', $updated_at)->delete();
    }

    protected function importInventory()
    {
        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/vehicle-data/used_cars.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                $num = count($data);
                $this->info("used_cars.csv ".$num." columns\n");
            }
            $counter=0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {
                try
                {
                    $car = new \SOE\DB\UsedVehicle;
                    $car->tnl_id = $data[1];
                    $car->dealer_name = $this->removeHtml($data[2]);
                    $car->address = $data[3];
                    $car->city = $data[4];
                    $car->state = $data[5];
                    $car->zip = $data[6];
                    $car->phone = $data[7];
                    $car->latitude = $data[8];
                    $car->longitude = $data[9];
                    $car->stock_number = $data[10];
                    $car->vin = $data[11];
                    $car->year = $data[12];
                    $make = $this->getMake($data[13]);
                    $car->make = $make->name;
                    $car->make_id = $make->id;
                    $model = \SOE\DB\VehicleModel::where('make_id', '=', $make->id)->where('name', '=', $data[14])->first();
                    $car->model = $data[14];
                    $car->model_id = empty($model) ? 0 : $model->id;
                    $model_year = \SOE\DB\VehicleYear::where('model_id', '=', $car->model_id)->where('year', '=', $data[12])->first();
                    $car->model_year_id = empty($model_year) ? 0 : $model_year->id;
                    $car->mileage = $data[15];
                    $car->is_certified = $data[16] == 'Y' || $data[16] == 'True' ? 1 : 0;
                    $car->class = $data[17];
                    $car->body_style = $data[18];
                    $car->fuel = $data[19];
                    $car->engine = $data[20];
                    $car->cylinders = $data[21];
                    $car->transmission = $data[22];
                    $car->drive_type = $data[23];
                    $car->trim_level = $data[24];
                    $car->exterior_color = $data[25];
                    $car->interior_color = $data[26];
                    $car->dealer_specified_features = $data[27];
                    $car->standard_interior_features = $data[28];
                    $car->standard_exterior_features = $data[29];
                    $car->standard_safety_features = $data[30];
                    $car->standard_mechanical_features = $data[31];
                    $car->dealer_comments = $data[32];
                    $car->msrp = $data[33];
                    $car->internet_price = $data[34];
                    $car->image_count = $data[35];
                    $car->image_urls = $data[36];
                    $merchant = \SOE\DB\Merchant::where('tnl_id', '=', $data[1])->first();
                    if(empty($merchant))
                        continue;
                    $car->merchant_id = $merchant->id;
                    $location = \SOE\DB\Location::where('merchant_id', '=', $merchant->id)->first();
                    $car->location_id = $location->id;
                    $cartesian = SoeHelper::getCartesian((double) $data[8], (double) $data[9]);
                    $car->latm = $cartesian['latm'];
                    $car->lngm = $cartesian['lngm'];
                    $car->old_dealer_id = $merchant->old_id;
                    $car->vendor = '';
                    $car->save();
                }
                catch(\Exception $e)
                {
                    continue;
                }
                $counter++;
            }
            fclose($handle);
            $this->info($counter.' Used Cars Imported');
        }
    }

    protected function importDeals()
    {
        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/vehicle-data/coupons.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                $num = count($data);
                $this->info("coupons.csv ".$num." columns\n");
            }
            $counter=0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {
                if($data[1] != 'special')
                    continue;

                $existing = \SOE\DB\Offer::where('name', '=', $data[2])->where('old_dealer_id', '=', $data[9])->first();
                if($existing)
                    continue;

                $offer = new \SOE\DB\Offer;
                $offer->name = $data[2];
                $offer->slug = SoeHelper::getSlug($data[2]);
                $merchant = \SOE\DB\Merchant::where('old_id', '=', $data[9])->first();
                if(empty($merchant))
                    continue;
                $offer->merchant_id = $merchant->id;
                $offer->path = $data[8] != '' ? 'http://saveoncarsandtrucks.com'.$data[8] : '';
                $description = $data[3].'<br/>';
                if($data[4] != '')
                    $description .= $data[4].'<br/>';
                $description .= $data[5].'<br/>';
                if($data[6] != '')
                    $description .= $data[6].' '.$data[7];
                $offer->description = $this->removeHtml($description);
                $offer->starts_at = '2014-04-22 00:00:00';
                $offer->expires_at = '2014-05-31 23:59:59';
                $offer->max_redeems = 1;
                $offer->max_prints = 1;
                $offer->is_active = $data[12];
                $franchise = \SOE\DB\Franchise::where('merchant_id', '=', $merchant->id)->first();
                $offer->franchise_id = $franchise->id;
                $offer->old_dealer_id = $data[9];
                $offer->save();

                $category = $this->categoryRepository->find($merchant->category_id);
                $subcategory = $this->categoryRepository->find($merchant->subcategory_id);
                $logo = $this->assetRepository->getLogo($merchant);
                $loc = \SOE\DB\Location::where('franchise_id', '=', $offer->franchise_id)->first();

                Eloquent::unguard();
                $entity = \SOE\DB\Entity::create(array(
                    'entitiable_id' => $offer->id,
                    'entitiable_type' => 'Offer',
                    'name' => $offer->name,
                    'slug' => $offer->slug,
                    'location_id' => $loc->id,
                    'category_id' => $merchant->category_id,
                    'subcategory_id' => $merchant->subcategory_id,
                    'latitude' => $loc->latitude,
                    'longitude' => $loc->longitude,
                    'path' => $offer->path == '' ? (!empty($logo) ? $logo->path : '') : $offer->path,
                    'is_dailydeal' => 0,
                    'rating' => 0,
                    'special_price' => 0,
                    'regular_price' => 0,
                    'is_demo' => 0,
                    'is_active' => $offer->is_active,
                    'starts_at' => $offer->starts_at,
                    'expires_at' => $offer->expires_at,
                    'rating_count' => 0,
                    'savings' => '',
                    'url' => '',
                    'print_override' => '',
                    'latm' => $loc->latm,
                    'lngm' => $loc->lngm,
                    'merchant_id' => $offer->merchant_id,
                    'merchant_slug' => $merchant->slug,
                    'merchant_name' => $merchant->display,
                    'is_featured' => 0,
                    'state' => $loc->state,
                    'expires_year' => date('Y', strtotime($offer->expires_at)),
                    'expires_day' => date('z', strtotime($offer->expires_at)) + 1,
                    'starts_year' => date('Y', strtotime($offer->starts_at)),
                    'starts_day' => date('z', strtotime($offer->starts_at)) + 1,
                    'location_active' => $loc->is_active,
                    'franchise_active' => $franchise->is_active,
                    'franchise_demo' => $franchise->is_demo,
                    'category_slug' => $category->slug,
                    'subcategory_slug' => $subcategory->slug,
                    'company_id' => $franchise->company_id,
                    'company_name' => 'Save On Everything',
                ));
                $counter++;
            }
            fclose($handle);
            $this->info($counter.' Coupons Imported');
        }
    }

    protected function getMake($make)
    {
        $aID = array(
            "1" => 'gmc',
            "2" => 'dodge',
            "3" => 'chevrolet',
            "4" => 'buick',
            "5" => 'saab',
            "6" => 'jaguar',
            "7" => 'chrysler',
            "8" => 'jeep',
            "9" => 'ford',
            "10" => 'cadillac',
            "11" => 'land-rover',
            "12" => 'bmw',
            "13" => 'mercedes-benz',
            "14" => 'hyundai',
            '15' => 'audi',
            "16" => 'volkswagen',
            "17" => 'kia',
            "18" => 'mazda',
            "19" => 'acura',
            "20" => 'honda',
            "21" => 'nissan',
            "22" => 'lincoln',
            "23" => 'volvo',
            "31" => 'ram',
            "32" => 'toyota',
            "33" => 'scion',
            "34" => 'subaru',
            "35" => 'mitsubishi',
            "24" => "harley-davison",
            "25" => "other",
        );

        $aName = array(
            "GMC" => 'gmc',
            "Dodge" => 'dodge',
            "Chevrolet" => 'chevrolet',
            "Buick" => 'buick',
            "SAAB" => 'saab',
            'Saab' => 'saab',
            "Jaguar" => 'jaguar',
            "Chrysler" => 'chrysler',
            "Jeep" => 'jeep',
            "Ford" => 'ford',
            "Cadillac" => 'cadillac',
            "Landrover" => 'land-rover',
            "Land Rover" => 'land-rover',
            "BMW" => 'bmw',
            "Mercedes" => 'mercedes-benz',
            "Mercedes-Benz" => 'mercedes-benz',
            "Hyundai" => 'hyundai',
            'Audi' => 'audi',
            "VW" => 'volkswagen',
            'Volkswagen' => 'volkswagen',
            "Kia" => 'kia',
            "Mazda" => 'mazda',
            "Acura" => 'acura',
            "Honda" => 'honda',
            "Nissan" => 'nissan',
            "Lincoln" => 'lincoln',
            "Volvo" => 'volvo',
            "Ram" => 'ram',
            "Toyota" => 'toyota',
            "Scion" => 'scion',
            "Subaru" => 'subaru',
            "Mitsubishi" => 'mitsubishi',
            "Harley Davidson" => "harley-davidson",
            "Harley-Davidson" => "harley-davidson",
            "Other" => "other",
            "FIAT" => 'fiat',
            'HUMMER' => 'hummer',
            'Hummer' => 'hummer',
            'Infiniti' => 'infiniti',
            'Lexus' => 'lexus',
            'Mercury' => 'mercury',
            'MINI' => 'mini',
            'Mini' => 'mini',
            'Oldsmobile' => 'oldsmobile',
            'Plymouth' => 'plymouth',
            'Pontiac' => 'pontiac',
            'Porsche' => 'porsche',
            'RAM' => 'ram',
            'Saturn' => 'saturn',
            'Suzuki' => 'suzuki',
        );

        $slug = '';
        if(is_numeric($make))
        {
            $slug = $aID[$make];
        }
        else
        {
            $slug = isset($aName[$make]) ? $aName[$make] : $make;
        }

        return \SOE\DB\VehicleMake::where('slug', '=', $slug)->first();
    }

    protected function removePunctuation($string)
    {
        return preg_replace("/[^A-Za-z0-9 ]/", '', $string);
    }

    protected function removeHtml($string)
    {
        return str_replace("&#39;", "'", $string);
    }

    public function loaddealertxt()
    {
        set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("Load Dealer Information via txt File\n");
        $lineCounter=0;
        $dealerCounter=0;

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/vehicle-data/used_dealers.txt", "r")) !== FALSE)
        {
            while (($data = fgets($handle)) !== FALSE)
            {
                $lineCounter++;
                $row_data = explode('|~', $data);
                
                // Skip First Line
                if ($lineCounter == 1)
                {
                    continue;
                }

                $zip = $this->zipcodeRepository->findByZipcode($row_data[5]);
                if (empty($zip))
                {
                    continue;
                }

                $merchantDB = SOE\DB\Merchant::where('name','=',$row_data[1])
                    ->orWhere(function($query) use ($row_data)
                        {
                            $query->where('old_id', '=', $row_data[0])
                                  ->where('vendor', '=', 'LotPro');
                        })
                    ->first();
                if(empty($merchantDB))
                {
                    $merchant = Merchant::blank();
                } else {
                    $merchant = $merchantDB;
                }
                $merchant->name = $row_data[1];
                $merchant->display = $row_data[1];
                $merchant->slug = SoeHelper::getSlug($row_data[1]);
                $merchant->old_id = $row_data[0];
                $merchant->vendor = 'LotPro';
                $merchant->type = 'RETAIL';
                $merchant->category_id = '10';
                $merchant->subcategory_id = '120';
                $merchant->save();
                //$this->info("$row_data[1]\n");
                
                $franchiseDB = SOE\DB\Franchise::where('merchant_id','=',$merchant->id)->first();
                if(empty($franchiseDB))
                {
                    $franchise = Franchise::blank();
                    $franchise->merchant_id = $merchant->id;
                } else {
                    $franchise = $franchiseDB;
                }
                $franchise->zipcode = $row_data[5];
                $franchise->contact_phone = $row_data[6];
                $franchise->is_dealer = 1;
                //$franchise->is_new_car_leads = '1';
                //$franchise->is_used_car_leads = '1';
                $franchise->save();

                $locationDB = SOE\DB\Location::where('merchant_id','=',$merchant->id)->where('franchise_id','=',$franchise->id)->first();
                if(empty($locationDB))
                {
                    $location = Location::blank();
                    $location->merchant_id = $merchant->id;
                    $location->franchise_id = $franchise->id;
                } else {
                    $location = $locationDB;
                }
                $location->name = $row_data[1].' '.$row_data[3];
                $location->slug = SoeHelper::getSlug($row_data[1].' '.$row_data[3]);
                $location->address = $row_data[2];
                $location->city = $row_data[3];
                $location->state = $row_data[4];
                $location->zip = $row_data[5];
                $location->phone = $row_data[6];
                $location->latitude = $zip->latitude;
                $location->longitude = $zip->longitude;
                $location->latm = $zip->latm;
                $location->lngm = $zip->lngm;
                $location->merchant_name = $merchant->name;
                $location->merchant_slug = $merchant->slug;
                $location->save();

                $dealerCounter++;
            }
        }
        $this->info("$lineCounter lines of data\n");
        $this->info("$dealerCounter dealers in data\n");
    }

    public function loadusedcarstxt()
    {
        set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("Load Used Car Information via txt File\n");
        $lineCounter=0;
        $carCounter=0;

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/vehicle-data/lotpro_used_inventory.txt", "r")) !== FALSE)
        {
            while (($data = fgets($handle)) !== FALSE)
            {
                $lineCounter++;
                $row_data = explode('|~', $data);
                
                // Skip First Line
                if ($lineCounter == 1)
                {
                    //print_r($row_data);
                    continue;
                }

                $merchant = SOE\DB\Merchant::where('old_id', '=', $row_data[0])->where('vendor', '=', 'LotPro')->first();
                if (empty($merchant))
                {
                    continue;
                }
                $location = SOE\DB\Location::where('merchant_id','=',$merchant->id)->first();

                $usedVehicleDB = SOE\DB\UsedVehicle::where('vin','=',$row_data[1])->first();
                if(empty($usedVehicleDB))
                {
                    $usedVehicle = $this->usedVehicleRepository->blank();
                    $usedVehicle->vin = $row_data[1];
                } else {
                    $usedVehicle = $usedVehicleDB;
                }
                $usedVehicle->name = $row_data[5];
                $usedVehicle->dealer_name = $merchant->name;
                $usedVehicle->address = $location->address;
                $usedVehicle->city = $location->city;
                $usedVehicle->state = $location->state;
                $usedVehicle->zip = $location->zip;
                $usedVehicle->phone = $location->phone;
                $usedVehicle->latitude = $location->latitude;
                $usedVehicle->longitude = $location->longitude;
                $usedVehicle->year = $row_data[3];
                $usedVehicle->make = $row_data[4];
                $usedVehicle->model = $row_data[5];
                $usedVehicle->mileage = $row_data[13];
                $usedVehicle->is_certified = $row_data[33];
                $usedVehicle->body_style = $row_data[8];
                $usedVehicle->fuel = $row_data[11];
                $usedVehicle->engine = $row_data[10];
                $usedVehicle->cylinders = $row_data[14];
                $usedVehicle->transmission = $row_data[12];
                $usedVehicle->drive_type = $row_data[9];
                $usedVehicle->trim_level = $row_data[6];
                $usedVehicle->exterior_color = $row_data[16];
                $usedVehicle->interior_color = $row_data[17];
                $usedVehicle->dealer_specified_features = $row_data[20];
                $usedVehicle->internet_price = $row_data[2];
                $usedVehicle->image_urls = $row_data[36];
                $usedVehicle->merchant_id = $merchant->id;
                $usedVehicle->location_id = $location->id;
                $usedVehicle->latm = $location->latm;
                $usedVehicle->lngm = $location->lngm;
                $usedVehicle->old_dealer_id = $row_data[0];
                $usedVehicle->vendor = 'LotPro';
                $usedVehicle->save();

                $vehicleYear = SOE\DB\VehicleYear::where('year','=',$usedVehicle->year)->where('model_name','=',$usedVehicle->model)->where('make_name','=',$usedVehicle->make)->first();
                if(!empty($vehicleYear))
                {
                    $usedVehicle->model_year_id = $vehicleYear->id;
                    $usedVehicle->make_id = $vehicleYear->make_id;
                    $usedVehicle->model_id = $vehicleYear->model_id;
                    $usedVehicle->save();
                } else {
                    $vehicleModel = SOE\DB\VehicleModel::where('name','=',$usedVehicle->model)->where('make_name','=',$usedVehicle->make)->first();
                    if(!empty($vehicleModel))
                    {
                        $usedVehicle->make_id = $vehicleModel->make_id;
                        $usedVehicle->model_id = $vehicleModel->id;
                        $usedVehicle->save();
                    } else {
                        $vehicleMake = SOE\DB\VehicleMake::where('name','=',$usedVehicle->make)->first();
                        if(!empty($vehicleMake))
                        {
                            $usedVehicle->make_id = $vehicleMake->id;
                            $usedVehicle->save();
                        }
                    }
                }

                $carCounter++;

                /*if ($lineCounter == 10)
                {
                    exit;
                }*/
            }
        }
        $this->info("$lineCounter lines of data\n");
        $this->info("$carCounter cars in data\n");
    }

    public function vehicle_desc()
    {
        set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("Load Model Descriptions\n");
        $modelCounter='';

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/vehicle-data/old_vehicle_models.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("vehicle_models.csv ".$num." columns\n");
            }
            
            $modelCounter=0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                
                if(count($data)!=$num)
                {
                    $this->info($modelCounter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    if ($data[10] != '')
                    {
                        $modelDB = SOE\DB\VehicleModel::where("slug","=",$data[8])->where('make_slug','=',$data[9])->first();
                        if(!empty($modelDB))
                        {
                            $model = $modelDB;
                            $model->about = $data[10];
                            $model->save();
                            $modelCounter++;
                        }
                    }
                }
            }
        }
        $this->info("Loaded Descriptions for $modelCounter Models.\n");
    }

    public function dealer_brand_keywords()
    {
        set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("Update Merchant Keywords with Vehicle Info\n");
        $merchantCounter=0;

        $merchants = \SOE\DB\Merchant::join('franchises', 'merchants.id', '=', 'franchises.merchant_id')
            ->where('is_dealer','=','1')->get(array('merchants.*', DB::raw('franchises.id as franchise_id')));

        foreach ($merchants as $merchant) {
            
            if(substr($merchant->keywords, -1) === '.'){
                //$this->info("$merchant->id");
                $newKeyword = rtrim($merchant->keywords, '.');
                //$this->info("$newKeyword");
            } else {
                $newKeyword = $merchant->keywords;
            }
            $brands = DB::table('dealer_brands')->where('merchant_id','=',$merchant->id)->get();

            if (empty($brands))
            {
                continue;
            }

            foreach ($brands as $brand) {
                $make = \SOE\DB\VehicleMake::where('id','=',$brand->make_id)->first();
                if (strpos($newKeyword,strtolower($make->name)) === false) {
                    if ($newKeyword != '')
                    {
                        $newKeyword = $newKeyword.', ';
                    }
                    $newKeyword = $newKeyword.strtolower($make->name);
                }
                $models = \SOE\DB\VehicleYear::where('make_id','=',$make->id)->where('year','>=','2004')->groupBy('model_name')->get();
                foreach ($models as $model) {
                    if (strpos($newKeyword,strtolower($model->model_name)) === false) {
                        if ($newKeyword != '')
                        {
                            $newKeyword = $newKeyword.', ';
                        }
                        $newKeyword = $newKeyword.strtolower($model->model_name);
                    }
                }
            }
            
            $merchant->keywords = $newKeyword;
            $merchant->save();

            $merchantCounter++;

        }

        $this->info("Updated Keywords for $merchantCounter Dealers.\n");
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
		);
	}

}
