<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class EdmundsCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'edmunds';

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
		$type = $this->option('type');
        switch ($type)
        {
            case 'initialLoad':
                $this->initialLoad();
                break;
            case 'yearUpdate':
            	$this->yearUpdate();
            	break;
            case 'vehicles':
            	$this->vehicles();
            	break;
            case 'incentives':
            	$this->incentives();
            	break;
            case 'assets':
            	$this->assets();
            	break;
            case 'assetCleanup':
            	$this->assetCleanup();
            	break;
            case 'styleAssetRefresh':
            	$this->styleAssetRefresh();
            	break;
            case 'assetAddSize':
            	$this->assetAddSize();
            	break;
            case 'styles':
            	$this->styles();
            	break;
            case 'loadCSVs':
            	$this->loadCSVs();
            	break;
            case 'makeCSV':
            	$this->makeCSV();
            	break;
            case 'modelCSV':
            	$this->modelCSV();
            	break;
            case 'yearCSV':
            	$this->yearCSV();
            	break;
            case 'styleCSV':
            	$this->styleCSV();
            	break;
            case 'assetCSV':
            	$this->assetCSV();
            	break;
            case 'incentiveCSV':
            	$this->incentiveCSV();
            	break;
            case 'slugFix':
            	$this->slugFix();
            	break;
            default:
                $this->info('Possible --type= values: initialLoad, vehicles, incentives, assets, styles, loadCSVs');
            	//$this->makeUpdate();
                break;
        }
	}

	public function initialLoad()
	{
        set_time_limit(30*60); // 30 Mins
		ini_set('memory_limit', '1024M');
		$this->info("Begin Loading Edmunds Data\n\n");
		$makeCounter=0;
		$modelCounter=0;
    	$session = curl_init();

		$url = 'https://api.edmunds.com/api/vehicle/v2/makes?view=full&fmt=json&api_key=93t4dwr4wxyppkyxwekk25yc';
        curl_setopt($session, CURLOPT_URL, $url);
        curl_setopt($session, CURLOPT_HTTPGET, 1);
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_VERBOSE, 0);
        $retMakes = curl_exec($session);
        $oMakes = json_decode($retMakes);

        //Insert Make Data
        foreach($oMakes->makes as $Item)
		{
			$dbRec = SOE\DB\VehicleMake::where("edmunds_id","=",$Item->id)->first();
			if(empty($dbRec)) //Add Record
			{
				$makes = new SOE\DB\VehicleMake;
				$makes->name = $Item->name;
				$makes->slug = $Item->niceName;
				$makes->edmunds_id = $Item->id;
				$makes->save();
				$makeCounter++;
			} else {
				$makes = $dbRec;
			}
			// Insert Make
			foreach($Item->models as $Model)
			{
				//Insert Model Data
				$dbRecModel = SOE\DB\VehicleModel::where("slug","=",$Model->niceName)->where('make_id','=',$makes->id)->first();
				if(empty($dbRecModel)) //Add Record
				{
					$models = new SOE\DB\VehicleModel;
					$models->name = $Model->name;
					$models->slug = $Model->niceName;
					$models->make_name = $Item->name;
					$models->make_id = $makes->id;
					$models->make_slug = $makes->slug;
					$models->save();
					$modelCounter++;
				} else {
					$models = $dbRecModel;
				}

				foreach($Model->years as $Year)
				{
					if (in_array("NEW",$Year->states))
					{
						$yearState = 'NEW';
					}
					elseif (in_array("USED",$Year->states))
					{
						$yearState = 'USED';
					} 
					else
					{
						$yearState = 'FUTURE';
					}
					
					$dbRecYear = SOE\DB\VehicleYear::where("edmunds_id","=",$Year->id)->first();
					if(empty($dbRecYear)) //Add Record
					{
						$years = new SOE\DB\VehicleYear;
						$years->year = $Year->year;
						$years->model_name = $models->name;
						$years->model_id = $models->id;
						$years->make_name = $Item->name;
						$years->make_id = $makes->id;
						$years->edmunds_id = $Year->id;
						$years->model_slug = $models->slug;
						$years->make_slug = $makes->slug;
						$years->state = $yearState;
						$years->save();
					} else {
						$years = $dbRecYear;
						$years->state = $yearState;
						$years->save();
					}
				}
			}
		}
		
		$this->makeUpdate();
		
		$this->info("Loaded Info for $makeCounter Makes and $modelCounter Models\n");
	}

	public function yearUpdate()
	{
		set_time_limit(30*60); // 30 Mins
		ini_set('memory_limit', '1024M');
		$this->info("Begin Yearly Vehicle Update\n");
		$makeCounter=0;
		$modelCounter=0;
		$yearCounter=0;
    	$session = curl_init();

    	$url = 'https://api.edmunds.com/api/vehicle/v2/makes?state=new&view=full&fmt=json&api_key=93t4dwr4wxyppkyxwekk25yc';

    	 curl_setopt($session, CURLOPT_URL, $url);
        curl_setopt($session, CURLOPT_HTTPGET, 1);
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_VERBOSE, 0);
        $retMakes = curl_exec($session);
        $oMakes = json_decode($retMakes);

        //Update Make Data
        foreach($oMakes->makes as $Item)
		{
			$dbRec = SOE\DB\VehicleMake::where("edmunds_id","=",$Item->id)->first();
			if(empty($dbRec)) //Add Record
			{
				$makes = new SOE\DB\VehicleMake;
				$makes->name = $Item->name;
				$makes->slug = $Item->niceName;
				$makes->edmunds_id = $Item->id;
				$makes->save();
				$makeCounter++;
			} else {
				$makes = $dbRec;
			}
			// Insert Make
			foreach($Item->models as $Model)
			{
				//Insert Model Data
				$dbRecModel = SOE\DB\VehicleModel::where("slug","=",$Model->niceName)->where('make_id','=',$makes->id)->first();
				if(empty($dbRecModel)) //Add Record
				{
					$models = new SOE\DB\VehicleModel;
					$models->name = $Model->name;
					$models->slug = $Model->niceName;
					$models->make_name = $Item->name;
					$models->make_id = $makes->id;
					$models->make_slug = $makes->slug;
					$models->save();
					$modelCounter++;
				} else {
					$models = $dbRecModel;
				}

				foreach($Model->years as $Year)
				{
					$yearState = '';
					if ((!empty($Year->states)) && (in_array("NEW",$Year->states)))
					{
						$yearState = 'NEW';
					}
					elseif ((!empty($Year->states)) && (in_array("USED",$Year->states)))
					{
						$yearState = 'USED';
					} 
					elseif ((!empty($Year->states)) && (in_array("FUTURE",$Year->states)))
					{
						$yearState = 'FUTURE';
					}
					
					$dbRecYear = SOE\DB\VehicleYear::where("edmunds_id","=",$Year->id)->first();
					if(empty($dbRecYear)) //Add Record
					{
						$years = new SOE\DB\VehicleYear;
						$years->year = $Year->year;
						$years->model_name = $models->name;
						$years->model_id = $models->id;
						$years->make_name = $Item->name;
						$years->make_id = $makes->id;
						$years->edmunds_id = $Year->id;
						$years->model_slug = $models->slug;
						$years->make_slug = $makes->slug;
						$years->state = $yearState;
						$years->save();
						$yearCounter++;
					} else {
						$years = $dbRecYear;
						$years->state = $yearState;
						$years->save();
						$yearCounter++;
					}
				}
			}
		}

		$this->makeUpdate();
		$this->vehicles('new');

		$this->info("Loaded Info for $modelCounter Models and $yearCounter model years\n");

	}

	public function makeUpdate()
	{
		set_time_limit(30*60); // 30 Mins
		ini_set('memory_limit', '1024M');
		$this->info("Begin Updating Make State\n");
		$makeCounter=0;
    	$session = curl_init();

    	$makes = SOE\DB\VehicleMake::get();

    	foreach ($makes as $make)
    	{
    		$yearCount = SOE\DB\VehicleYear::where('year','>=',date("Y"))->where('make_id','=',$make->id)->count();
    		//$this->info("$make->name: $yearCount");
    		if ($yearCount != 0)
    		{
    			$make->is_active = '1';
    			$make->save();
    		} else {
    			$make->is_active = '0';
    			$make->save();
    		}
    		$makeCounter++;
    	}

    	$this->info("Updated states of $makeCounter makes\n");

	}

	public function vehicles($new = null)
	{
        set_time_limit(30*60); // 30 Mins
		ini_set('memory_limit', '1024M');
		$this->info("Begin Loading Edmunds Vehicle Data\n\n");
		$styleCounter=0;
    	$session = curl_init();

    	$history = SOE\DB\VehicleCommandHistory::where("command_name","=","vehicles")->where("is_finished","=","0")->first();
		if(empty($history))
		{
			$commandHistory = new SOE\DB\VehicleCommandHistory;
			$commandHistory->command_name = "vehicles";
			$commandHistory->save();
			$startPos = 0;
		} else {
			$commandHistory = $history;
			$startPos = $commandHistory->last_query_id - 1;
		}

    	$makes = SOE\DB\VehicleMake::skip($startPos)->take(100)->get();

    	foreach ($makes as $make)
    	{
			$commandHistory->last_query = $make->slug;
			$commandHistory->last_query_id = $make->id;
			$commandHistory->save();

			if ($new)
			{
				$url = 'https://api.edmunds.com/api/vehicle/v2/'.$make->slug.'/models?state=new&view=full&fmt=json&api_key=93t4dwr4wxyppkyxwekk25yc';
			} else {
				$url = 'https://api.edmunds.com/api/vehicle/v2/'.$make->slug.'/models?view=full&fmt=json&api_key=93t4dwr4wxyppkyxwekk25yc';
			}
    		curl_setopt($session, CURLOPT_URL, $url);
	        curl_setopt($session, CURLOPT_HTTPGET, 1);
	        curl_setopt($session, CURLOPT_HEADER, false);
	        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($session, CURLOPT_VERBOSE, 0);
	        $retModels = curl_exec($session);
	        $oModels = json_decode($retModels);
	        foreach($oModels->models as $Model)
			{
				$dbModels = SOE\DB\VehicleModel::where("slug","=",$Model->niceName)->where('make_id','=',$make->id)->first();
				foreach ($Model->years as $year) {
					$dbYears = SOE\DB\VehicleYear::where("edmunds_id","=",$year->id)->first();
					if (!empty($dbYears))
					{
						foreach ($year->styles as $style) {
							$dbStyle = SOE\DB\VehicleStyle::where("edmunds_id","=",$style->id)->first();

							if(empty($dbStyle)) //Add Record
							{
								$styles = new SOE\DB\VehicleStyle;
								$styles->name = $style->name;
								$styles->year = $dbYears->year;
								$styles->model_name = $dbYears->model_name;
								$styles->model_id = $dbYears->model_id;
								$styles->make_name = $dbYears->make_name;
								$styles->make_id = $dbYears->make_id;
								$styles->model_year_id = $dbYears->id;
								if (!empty($style->submodel)) {
									$styles->primary_body_type = $style->submodel->body;
                                    $styles->body_type = $this->getBodyType($style->submodel->body);
								}
								$styles->edmunds_id = $style->id;
								$styles->slug = SoeHelper::getSlug($this->removeHtml($style->name));
								$styles->make_slug = $dbModels->make_slug;
								$styles->model_slug = $dbModels->slug;
								$styles->save();
								$styleCounter++;
							} else {
								$styles = $dbStyle;
								$styles->model_year_id = $dbYears->id;
								$styles->slug = SoeHelper::getSlug($this->removeHtml($style->name));
								$styles->make_slug = $dbModels->make_slug;
								$styles->model_slug = $dbModels->slug;
                                $styles->body_type = $this->getBodyType($styles->primary_body_type);
								$styles->save();
								$styleCounter++;
							}
						}
					}
				}
				//$this->info("$make->name $Model->name\n");
			}
    	}
    	$commandHistory->is_finished = 1;
    	$commandHistory->save();
    	//$url = 'https://api.edmunds.com/api/vehicle/v2/'.$makes->slug'/models?view=full&fmt=json&api_key=93t4dwr4wxyppkyxwekk25yc';

    	$this->info("Loaded Info for $styleCounter Styles\n");
    }

    public function incentives()
	{
        set_time_limit(60*60); // 60 Mins
		ini_set('memory_limit', '1024M');
		$this->info("Begin Loading Edmunds Incentive Data\n\n");
		$incentiveCounter='';
    	$session = curl_init();

    	$history = SOE\DB\VehicleCommandHistory::where("command_name","=","incentives")->where("is_finished","=","0")->first();
		if(empty($history))
		{
			$commandHistory = new SOE\DB\VehicleCommandHistory;
			$commandHistory->command_name = "incentives";
			$commandHistory->save();
			$startPos = 0;
		} else {
			$commandHistory = $history;
			$startPos = $commandHistory->last_query_id - 1;
		}
		//$startPos = 0;
    	$makes = SOE\DB\VehicleMake::where('is_active','=','1')->skip($startPos)->take(100)->get();

    	foreach ($makes as $make)
    	{
			$commandHistory->last_query = $make->slug;
			$commandHistory->last_query_id = $make->id;
			$commandHistory->save();

    		$url = 'https://api.edmunds.com/v1/api/incentive/incentiverepository/findincentivesbymakeid?makeid='.$make->edmunds_id.'&fmt=json&api_key=93t4dwr4wxyppkyxwekk25yc';
    		curl_setopt($session, CURLOPT_URL, $url);
	        curl_setopt($session, CURLOPT_HTTPGET, 1);
	        curl_setopt($session, CURLOPT_HEADER, false);
	        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($session, CURLOPT_VERBOSE, 0);
	        $retIncentives = curl_exec($session);
	        $oIncentives = json_decode($retIncentives);
	        foreach($oIncentives->incentiveHolder as $Incentive)
			{
				if ($Incentive->type == 'DEALER_CASH')
				{
					$name = '$'.$Incentive->dealerCashAmount.' in Dealer Cash';
					$rebateAmount = $Incentive->dealerCashAmount;
				}
				elseif ($Incentive->type == 'CASH_REBATE')
				{
					$name = '$'.$Incentive->rebateAmount.' in Cash Rebate';
					$rebateAmount = $Incentive->rebateAmount;
				}
				elseif($Incentive->type == 'LOW_APR')
				{
					$name = $Incentive->apr.'% APR for '.$Incentive->termMonths.' Month Loan';
					$rebateAmount = '';
				}
				elseif ($Incentive->type == 'LEASE')
				{
					$name = '$'.$Incentive->monthlyPayment.' a Month for New Leases';
					$rebateAmount = '';
				}

				$incentives = SOE\DB\VehicleIncentive::where("edmunds_id","=",$Incentive->id)->first();
				if(empty($incentives)) //Add Incentive
				{
					$vehicleIncentive = new SOE\DB\VehicleIncentive;
					$vehicleIncentive->name = $name;
					$vehicleIncentive->rebate_amount = $rebateAmount;
					$vehicleIncentive->edmunds_id = $Incentive->id;
					$vehicleIncentive->type = $Incentive->type;
					$vehicleIncentive->content_type = $Incentive->contentType;
					$vehicleIncentive->incentiveType = $Incentive->incentiveType;
					$vehicleIncentive->starts_at = $Incentive->startDate;
					$vehicleIncentive->expires_at = $Incentive->endDate;
					$vehicleIncentive->description = $Incentive->restrictions;
					$vehicleIncentive->restrictions = $Incentive->restrictions;
					$vehicleIncentive->make_name = $make->name;
					$vehicleIncentive->make_id = $make->id;
					$vehicleIncentive->save();
					$incentiveCounter++;
				} else {
					$vehicleIncentive = $incentives;
				}

				foreach ($Incentive->styleIds as $styleId) {
					$edmunds_style_id = str_replace('/api/vehicle/style/', '', $styleId->link);
					$style = SOE\DB\VehicleStyle::where("edmunds_id","=",$edmunds_style_id)->first();
					if (!empty($style))
					{
						$styleIncentiveCheck = SOE\DB\VehicleIncentiveStyle::where("vehicle_incentive_id","=",$vehicleIncentive->id)->where("vehicle_style_id","=",$style->id)->first();
						if (empty($styleIncentiveCheck))
						{
							$vehicleIncentiveStyle = new SOE\DB\VehicleIncentiveStyle;
							$vehicleIncentiveStyle->vehicle_incentive_id = $vehicleIncentive->id;
							$vehicleIncentiveStyle->vehicle_style_id = $style->id;
							$vehicleIncentiveStyle->save();
						}
						
					}
					//$this->info("$edmunds_style_id\n");
				}
				
			}
		}
		$commandHistory->is_finished = 1;
    	$commandHistory->save();
		$this->info("Loaded Info for $incentiveCounter Incentives\n");
    }

    public function assets()
	{
		set_time_limit(30*60); // 30 Mins
		ini_set('memory_limit', '1024M');
		$this->info("Begin Loading Edmunds Asset Data\n\n");
		$pictureCounter=0;
		$styleCounter=0;
		$deleteCounter=0;
    	$session = curl_init();

    	$setYear = $this->option('year');
    	if ((empty($setYear)) || (!checkdate(1,1,$setYear)))
    	{
    		$setYear = 'all';
    	}

    	$history = SOE\DB\VehicleCommandHistory::where("command_name","=","assets")->where('last_query','=',$setYear)->where("is_finished","=","0")->first();
		if(empty($history))
		{
			$commandHistory = new SOE\DB\VehicleCommandHistory;
			$commandHistory->command_name = "assets";
			$commandHistory->last_query = $setYear;
			$commandHistory->save();
			$startPos = 0;
		} else {
			$commandHistory = $history;
			$commandHistory->last_query = $setYear;
			$commandHistory->save();
			$startPos = $commandHistory->last_query_id - 1;
		}
    	$styles = SOE\DB\VehicleStyle::skip($startPos);
    	if ($setYear == 'all') {
    		$styles = $styles->where('year','>=',date("Y"))->take(1000);
    	} else {
    		$styles = $styles->where('year','=',$setYear)->take(5000);
    	}
    	$styles = $styles->orderBy('year','desc')->orderBy('make_name')->get();

    	if(empty($styles))
    	{
    		$commandHistory->delete();
    	}
    	$styleCounter = $startPos;
    	foreach ($styles as $style) 
    	{

    		$dbCurrentAssets = SOE\DB\VehicleAsset::where("style_id","=",$style->id)->get();
    		/*foreach ($dbCurrentAssets as $dbCurrentAsset) {
    			if(@getimagesize('$dbCurrentAsset->path') === false)
    			{
    				$this->info("$dbCurrentAsset->path\n");
    				$deleteCounter++;
    			}
    		}*/
    		//$this->info("$deleteCounter\n");
    		//exit;
	        $styleCounter++;
    		if(count($dbCurrentAssets) != 0)
    		{
    			$commandHistory->last_query_id = $styleCounter;
				$commandHistory->save();
    			continue;
    		}

    		$url = 'http://api.edmunds.com/v1/api/vehiclephoto/service/findphotosbystyleid?styleId='.$style->edmunds_id.'&api_key=93t4dwr4wxyppkyxwekk25yc&fmt=json';
			curl_setopt($session, CURLOPT_URL, $url);
	        curl_setopt($session, CURLOPT_HTTPGET, 1);
	        curl_setopt($session, CURLOPT_HEADER, false);
	        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($session, CURLOPT_VERBOSE, 0);
	        $retModelPics = curl_exec($session);
	        $oModelPics = json_decode($retModelPics);

	        if(!empty($oModelPics))
	        {
		        foreach($oModelPics as $ModelPics)
				{
					if (!empty($ModelPics->photoSrcs)) {
						$picSizes = array();
						$picSrcs = array();
						foreach($ModelPics->photoSrcs as $carPics)
						{
							$pathParts = explode('_',str_replace('.jpg','',$carPics));
    						$carPicSize = end($pathParts);
							$picSizes[] = $carPicSize;
							$picSrcs[] = 'http://media.ed.edmunds-media.com'.$carPics;
						}
						$bestSize = $this->getClosest(800,$picSizes);
						$searchPics = array_search($bestSize, $picSizes);
						$bestPic = $picSrcs[$searchPics];

						$dbAsset = SOE\DB\VehicleAsset::where("path","=",$bestPic)->where("style_id","=",$style->id)->first();

						if(!$dbAsset) //Add Record
						{
							$styleAsset = new SOE\DB\VehicleAsset;
							$styleAsset->type = "image";
							$styleAsset->path = $bestPic;
							$styleAsset->name = $ModelPics->id;
							$styleAsset->description = $ModelPics->captionTranscript;
							$styleAsset->short_description = !empty($ModelPics->subType)?$ModelPics->subType:" ";
							$styleAsset->edmunds_style_id = $style->edmunds_id;
							$styleAsset->style_id = $style->id;
							$styleAsset->shot_type = !empty($ModelPics->shotTypeAbbreviation)?$ModelPics->shotTypeAbbreviation:" ";
							$styleAsset->pic_size = $bestSize;
							$styleAsset->save();
							$pictureCounter++;
						}
					}
				}
				
	        }

			//$commandHistory->last_query = $style->year.' '.$style->make_name.' '.$style->model_name;
			$commandHistory->last_query_id = $styleCounter;
			$commandHistory->save();
    	}

    	$styleCount = SOE\DB\VehicleStyle::orderBy('year','desc')->orderBy('make_name');
    	if ($setYear == 'all') {
    		$styleCount = $styleCount->where('year','>=',date("Y"));
    	} else {
    		$styleCount = $styleCount->where('year','=',$setYear);
    	}
    	$styleCount = $styleCount->count();
    	//= SOE\DB\VehicleStyle::orderBy('year','desc')
    	if ($styleCount == $commandHistory->last_query_id)
    	{
    		$commandHistory->is_finished = 1;
    		$commandHistory->save();
    	}
		$this->info("Loaded $pictureCounter Pictures for $styleCounter Styles\n");

	}

	public function assetCleanup()
	{
		set_time_limit(30*60); // 30 Mins
		ini_set('memory_limit', '1024M');
		$this->info("Begin Cleaning Up Edmunds Asset Data\n\n");
		$pictureCounter=0;
		$styleCounter=0;
		$deleteCounter=0;
    	$session = curl_init();

    	$setYear = $this->option('year');
    	if ((empty($setYear)) || (!checkdate(1,1,$setYear)))
    	{
    		$setYear = 'all';
    	}

    	$history = SOE\DB\VehicleCommandHistory::where("command_name","=","assetCleanup")->where('last_query','=',$setYear)->where("is_finished","=","0")->first();
		if(empty($history))
		{
			$commandHistory = new SOE\DB\VehicleCommandHistory;
			$commandHistory->command_name = "assetCleanup";
			$commandHistory->last_query = $setYear;
			$commandHistory->save();
			$startPos = 0;
		} else {
			$commandHistory = $history;
			$commandHistory->last_query = $setYear;
			$commandHistory->save();
			$startPos = $commandHistory->last_query_id;
		}

    	$styles = SOE\DB\VehicleStyle::skip($startPos)->take(100)->orderBy('year','desc')->orderBy('make_name');
    	if ($setYear == 'all') {
    		$styles = $styles->where('year','>=',date("Y"));
    	} else {
    		$styles = $styles->where('year','=',$setYear);
    	}
    	$styles = $styles->get();

    	if(empty($styles))
    	{
    		$commandHistory->delete();
    	}
    	$styleCounter = $startPos;
    	foreach ($styles as $style) 
    	{
    		$styleCounter++;
    		$dbCurrentAssets = SOE\DB\VehicleAsset::where("style_id","=",$style->id)->get();
    		foreach ($dbCurrentAssets as $asset) 
    		{
				$pictureCounter++;
    			$handle = curl_init($asset->path);
				curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

				/* Get the HTML or whatever is linked in $url. */
				$response = curl_exec($handle);

				/* Check for 404 (file not found). */
				$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
				if($httpCode == 404 || $httpCode == 403) {
				    //$this->info("$asset->path\n");
    				$deleteCounter++;
    				$asset->delete();
				}
				elseif ($asset->pic_size > 0 && $asset->pic_size < 310)
    			{
    				$deleteCounter++;
					$asset->delete();
    			}
				curl_close($handle);
    		}
    		$commandHistory->last_query_id = $styleCounter;
			$commandHistory->save();
    	}
    	$styleCount = SOE\DB\VehicleStyle::orderBy('year','desc')->orderBy('make_name');
    	if ($setYear == 'all') {
    		$styleCount = $styleCount->where('year','>=',date("Y"));
    	} else {
    		$styleCount = $styleCount->where('year','=',$setYear);
    	}
    	$styleCount = $styleCount->count();
    	//= SOE\DB\VehicleStyle::orderBy('year','desc')
    	$this->info("$deleteCounter Bad Pictures out of $pictureCounter.\n");
    	if ($styleCount == $commandHistory->last_query_id)
    	{
    		$commandHistory->is_finished = 1;
    		$commandHistory->save();
    		$this->info("Process has been completed.\n");
    	}
    	
    }

    public function styleAssetRefresh()
    {
    	set_time_limit(30*60); // 30 Mins
		ini_set('memory_limit', '1024M');
		$deleteCounter=0;
		$pictureCounter=0;
    	$session = curl_init();

		$styleID = $this->option('id');
		$style = SOE\DB\VehicleStyle::where('id','=',$styleID)->first();
		if(!$style)
		{
			$this->info("Vehicle Style Not Found \n");
			exit;
		}

		$this->info("Begin Refreshing Assets for $style->year $style->make_name $style->model_name $style->name. This command will use 1 API call.\n");

		$dbCurrentAssets = SOE\DB\VehicleAsset::where("style_id","=",$style->id)->get();
		foreach ($dbCurrentAssets as $asset) 
		{
			$handle = curl_init($asset->path);
			curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

			/* Get the HTML or whatever is linked in $url. */
			$response = curl_exec($handle);

			/* Check for 404 (file not found). */
			$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
			if($httpCode == 404 || $httpCode == 403) {
			    //$this->info("$asset->path\n");
				$deleteCounter++;
				$asset->delete();
			}
			elseif ($asset->pic_size > 0 && $asset->pic_size < 310)
			{
				$deleteCounter++;
				$asset->delete();
			}
			curl_close($handle);
		}

		$url = 'http://api.edmunds.com/v1/api/vehiclephoto/service/findphotosbystyleid?styleId='.$style->edmunds_id.'&api_key=93t4dwr4wxyppkyxwekk25yc&fmt=json';
		curl_setopt($session, CURLOPT_URL, $url);
        curl_setopt($session, CURLOPT_HTTPGET, 1);
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_VERBOSE, 0);
        $retModelPics = curl_exec($session);
        $oModelPics = json_decode($retModelPics);

        if(!empty($oModelPics))
        {
	        foreach($oModelPics as $ModelPics)
			{
				if (!empty($ModelPics->photoSrcs)) {
					$picSizes = array();
					$picSrcs = array();
					foreach($ModelPics->photoSrcs as $carPics)
					{
						$pathParts = explode('_',str_replace('.jpg','',$carPics));
						$carPicSize = end($pathParts);
						$picSizes[] = $carPicSize;
						$picSrcs[] = 'http://media.ed.edmunds-media.com'.$carPics;
					}
					$bestSize = $this->getClosest(800,$picSizes);
					$searchPics = array_search($bestSize, $picSizes);
					$bestPic = $picSrcs[$searchPics];

					$dbAsset = SOE\DB\VehicleAsset::where("path","=",$bestPic)->where("style_id","=",$style->id)->first();

					if(!$dbAsset) //Add Record
					{
						$styleAsset = new SOE\DB\VehicleAsset;
						$styleAsset->type = "image";
						$styleAsset->path = $bestPic;
						$styleAsset->name = $ModelPics->id;
						$styleAsset->description = $ModelPics->captionTranscript;
						$styleAsset->short_description = !empty($ModelPics->subType)?$ModelPics->subType:" ";
						$styleAsset->edmunds_style_id = $style->edmunds_id;
						$styleAsset->style_id = $style->id;
						$styleAsset->shot_type = !empty($ModelPics->shotTypeAbbreviation)?$ModelPics->shotTypeAbbreviation:" ";
						$styleAsset->pic_size = $bestSize;
						$styleAsset->save();
						$pictureCounter++;
					}
				}
			}
			
        }

        $this->info("$deleteCounter Bad Pictures Removed and $pictureCounter Pictures Added for $style->year $style->make_name $style->model_name $style->name\n");

    }

    public function assetAddSize()
    {
    	set_time_limit(30*60); // 30 Mins
		ini_set('memory_limit', '1024M');
		$this->info("Begin Adding Sizes for Edmunds Assets\n\n");
		$pictureCounter=0;
		$styleCounter=0;
		$deleteCounter=0;
    	$session = curl_init();

    	$assets = SOE\DB\VehicleAsset::get();
    	foreach ($assets as $asset) 
    	{
    		//$pic_size = str_replace("_", "", str_replace('.jpg','',substr($asset->path, -8)));
    		$pathParts = explode('_',str_replace('.jpg','',$asset->path));
    		$pic_size = end($pathParts);
    		$asset->pic_size = $pic_size;
    		$asset->save();
    		$pictureCounter++;
    	}
    	$this->info("Updated Size for $pictureCounter Pictures.\n");
    }


	public function styles()
	{
		set_time_limit(30*60); // 30 Mins
		ini_set('memory_limit', '1024M');
		$this->info("Begin Loading Edmunds Site Data\n\n");
		$styleCounter='';
    	$session = curl_init();

    	$year = $this->option('year');
    	$focus = $this->option('focus');
    	if((empty($year)) || (!checkdate(1,1,$year)) || !$focus)
    	{
	    	$history = SOE\DB\VehicleCommandHistory::where("command_name","=","styles")->where("is_finished","=","0")->first();
			if(empty($history))
			{
				$commandHistory = new SOE\DB\VehicleCommandHistory;
				$commandHistory->command_name = "styles";
				$commandHistory->save();
				$startPos = 0;
			} else {
				$commandHistory = $history;
				$startPos = $commandHistory->last_query_id - 1;
			}
	    	$styles = SOE\DB\VehicleStyle::skip($startPos)->orderBy('year','desc')->take(4000)->get();
	    } else {
	    	$styles = SOE\DB\VehicleStyle::where('year','=',$year)
	    		->where(function($where) use ($focus) {
    			$where->where($focus, '=', 0)->whereOr($focus, '=', '');
    		})
	    		->get();
	    }
    	foreach ($styles as $style) {
    		$url = 'https://api.edmunds.com/api/vehicle/v2/styles/'.$style->edmunds_id.'?view=full&fmt=json&api_key=93t4dwr4wxyppkyxwekk25yc';
			curl_setopt($session, CURLOPT_URL, $url);
	        curl_setopt($session, CURLOPT_HTTPGET, 1);
	        curl_setopt($session, CURLOPT_HEADER, false);
	        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($session, CURLOPT_VERBOSE, 0);
	        $retStyleSpecs = curl_exec($session);
	        $StyleSpecs = json_decode($retStyleSpecs);

    		if (isset($StyleSpecs->id))
    		{
    			if (isset($StyleSpecs->name))
		        {
		        	preg_match('#\((.*?)\)#', $StyleSpecs->name, $engineName);
		        	if(isset($engineName[1]))
		        	{
		        		$engineName = $engineName[1];
		        	} else {
		        		$engineName = '';
		        	}
		        } else {
		        	$engineName = '';
		        }

		        $dbStyle = SOE\DB\VehicleStyle::where("edmunds_id","=",$StyleSpecs->id)->first();
		        $dbStyle->price = (isset($StyleSpecs->price->baseMSRP))?$StyleSpecs->price->baseMSRP:'';
		        if((isset($StyleSpecs->MPG)) && (!empty($StyleSpecs->MPG)))
		        {
		        	$dbStyle->city_epa = (isset($StyleSpecs->MPG->city))?$StyleSpecs->MPG->city:'';
		        	$dbStyle->highway_epa = (isset($StyleSpecs->MPG->highway))?$StyleSpecs->MPG->highway:'';
		        
		        }
		        $dbStyle->engine_name = $engineName;
		        $dbStyle->transmission = $StyleSpecs->transmission->transmissionType;
                $dbStyle->body_type = $this->getBodyType($dbStyle->primary_body_type);
		        $dbStyle->save();
	    		$styleCounter++;
    		}
    		if((empty($year)) || (!checkdate(1,1,$year)) || !$focus)
    		{
				$commandHistory->last_query = $style->year.' '.$style->make_name.' '.$style->model_name;
				$commandHistory->last_query_id = $styleCounter;
				$commandHistory->save();
			}
    	}
    	if((empty($year)) || (!checkdate(1,1,$year)) || !$focus)
    	{
	    	$styleCount = SOE\DB\VehicleStyle::orderBy('year','desc')->count();
	    	if ($styleCount == $commandHistory->last_query_id)
	    	{
	    		$commandHistory->is_finished = 1;
	    		$commandHistory->save();
	    	}
	    }
		$this->info("Loaded Info for $styleCounter Styles\n");
    }

    public function loadCSVs()
	{
		$this->makeCSV();
		$this->modelCSV();
		$this->yearCSV();
		$this->styleCSV();
		$this->assetCSV();
		$this->incentiveCSV();
	}

	public function makeCSV ()
	{
		set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("Load Make Information\n");
        $makeCounter='';

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/vehicle-data/vehicle_makes.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("vehicle_makes.csv ".$num." columns\n");
            }
            
            $makeCounter=0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                
                if(count($data)!=$num)
                {
                    $this->info($makeCounter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    $makes = DB::table('vehicle_makes')->where('id','=',$data[2])->first();
                    if(empty($makes)) {
                    	DB::table('vehicle_makes')->insert(array(
                            'id' => $data[2],
                            'name' => $data[3],
                            'slug' => $data[4],
                            'edmunds_id' => $data[5],
                            'is_active' => $data[6],
                            'old_id' => $data[7],
                        ));
                        $makeCounter++;
                    }
                }
            }
        }
        $this->info("Loaded Info for $makeCounter Makes.\n");
	}

	public function modelCSV ()
	{
		set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("Load Model Information\n");
        $modelCounter='';

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/vehicle-data/vehicle_models.csv", "r")) !== FALSE)
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
                    $models = DB::table('vehicle_models')->where('id','=',$data[2])->first();
                    if(empty($models)) {
                    	DB::table('vehicle_models')->insert(array(
                            'id' => $data[2],
                            'name' => $data[3],
                            'edmunds_id' => $data[4],
                            'make_name' => $data[5],
                            'make_id' => $data[6],
                            'slug' => $data[8],
                            'make_slug' => $data[9],
                            'about' => $data[10],
                        ));
                        $modelCounter++;
                    }
                }
            }
        }
        $this->info("Loaded Info for $modelCounter Models.\n");
	}

	public function yearCSV ()
	{
		set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("Load Vehicle Year Information\n");
        $yearCounter='';

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/vehicle-data/vehicle_years.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("vehicle_years.csv ".$num." columns\n");
            }
            
            $yearCounter=0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                
                if(count($data)!=$num)
                {
                    $this->info($yearCounter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    $years = DB::table('vehicle_years')->where('id','=',$data[2])->first();
                    if(empty($years)) {
                    	DB::table('vehicle_years')->insert(array(
                            'id' => $data[2],
                            'year' => $data[3],
                            'model_name' => $data[4],
                            'model_id' => $data[5],
                            'make_name' => $data[6],
                            'make_id' => $data[7],
                            'edmunds_default_style' => $data[8],
                            'edmunds_link' => $data[9],
                            'edmunds_id' => $data[10],
                            'model_slug' => $data[11],
                            'make_slug' => $data[12],
                            'default_style_id' => $data[13],
                            'state' => $data[14],
                        ));
                        $yearCounter++;
                    }
                }
            }
        }
        $this->info("Loaded Info for $yearCounter Vehicle Years.\n");
	}

	public function styleCSV ()
	{
		set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("Load Vehicle Style Information\n");
        $styleCounter='';

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/vehicle-data/vehicle_styles.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("vehicle_styles.csv ".$num." columns\n");
            }
            
            $styleCounter=0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                
                if(count($data)!=$num)
                {
                    $this->info($styleCounter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    $setYear = $this->option('year');
                    if ((empty($setYear)) || (!checkdate(1,1,$setYear)))
    				{
    					$styles = DB::table('vehicle_styles')->where('id','=',$data[2])->first();
	                    if(empty($styles)) {
	                    	DB::table('vehicle_styles')->insert(array(
	                    		'created_at' => date('Y-m-d H:i:s'),
	                    		'updated_at' => date('Y-m-d H:i:s'),
	                            'id' => $data[2],
	                            'name' => $data[3],
	                            'year' => $data[4],
	                            'model_name' => $data[5],
	                            'model_id' => $data[6],
	                            'make_name' => $data[7],
	                            'make_id' => $data[8],
	                            'model_year_id' => $data[9],
	                            'price' => $data[10],
	                            'primary_body_type' => $data[11],
	                            'edmunds_link' => $data[12],
	                            'edmunds_id' => $data[13],
	                            'body_type_id' => $data[14],
	                            'style_type_id' => $data[15],
	                            'city_epa' => $data[16],
	                            'highway_epa' => $data[17],
	                            'combined_epa' => $data[18],
	                            'engine_name' => $data[19],
	                            'transmission' => $data[20],
	                            'slug' => $data[21],
	                            'make_slug' => $data[22],
	                            'model_slug' => $data[23],
	                            'body_type' => $this->getBodyType($data[11])
	                        ));
	                        $styleCounter++;
	                    } else {
	                    	DB::table('vehicle_styles')
	                    	->where('id',$data[2])
	                    	->update(array(
	                    		'updated_at' => date('Y-m-d H:i:s'),
	                            'name' => $data[3],
	                            'year' => $data[4],
	                            'model_name' => $data[5],
	                            'model_id' => $data[6],
	                            'make_name' => $data[7],
	                            'make_id' => $data[8],
	                            'model_year_id' => $data[9],
	                            'price' => $data[10],
	                            'primary_body_type' => $data[11],
	                            'edmunds_link' => $data[12],
	                            'edmunds_id' => $data[13],
	                            'body_type_id' => $data[14],
	                            'style_type_id' => $data[15],
	                            'city_epa' => $data[16],
	                            'highway_epa' => $data[17],
	                            'combined_epa' => $data[18],
	                            'engine_name' => $data[19],
	                            'transmission' => $data[20],
	                            'slug' => $data[21],
	                            'make_slug' => $data[22],
	                            'model_slug' => $data[23],
	                            'body_type' => $this->getBodyType($data[11])
	                        ));
	                    	$styleCounter++;
	                    }
	                } 
	                elseif($data[4] == $setYear)
	                {
	                	$styles = DB::table('vehicle_styles')->where('id','=',$data[2])->first();
	                	if(empty($styles)) {
	                    	DB::table('vehicle_styles')->insert(array(
	                    		'created_at' => date('Y-m-d H:i:s'),
	                    		'updated_at' => date('Y-m-d H:i:s'),
	                            'id' => $data[2],
	                            'name' => $data[3],
	                            'year' => $data[4],
	                            'model_name' => $data[5],
	                            'model_id' => $data[6],
	                            'make_name' => $data[7],
	                            'make_id' => $data[8],
	                            'model_year_id' => $data[9],
	                            'price' => $data[10],
	                            'primary_body_type' => $data[11],
	                            'edmunds_link' => $data[12],
	                            'edmunds_id' => $data[13],
	                            'body_type_id' => $data[14],
	                            'style_type_id' => $data[15],
	                            'city_epa' => $data[16],
	                            'highway_epa' => $data[17],
	                            'combined_epa' => $data[18],
	                            'engine_name' => $data[19],
	                            'transmission' => $data[20],
	                            'slug' => $data[21],
	                            'make_slug' => $data[22],
	                            'model_slug' => $data[23],
	                            'body_type' => $this->getBodyType($data[11])
	                        ));
	                        $styleCounter++;
	                    } else {
	                    	DB::table('vehicle_styles')
	                    	->where('id',$data[2])
	                    	->update(array(
	                    		'updated_at' => date('Y-m-d H:i:s'),
	                            'name' => $data[3],
	                            'year' => $data[4],
	                            'model_name' => $data[5],
	                            'model_id' => $data[6],
	                            'make_name' => $data[7],
	                            'make_id' => $data[8],
	                            'model_year_id' => $data[9],
	                            'price' => $data[10],
	                            'primary_body_type' => $data[11],
	                            'edmunds_link' => $data[12],
	                            'edmunds_id' => $data[13],
	                            'body_type_id' => $data[14],
	                            'style_type_id' => $data[15],
	                            'city_epa' => $data[16],
	                            'highway_epa' => $data[17],
	                            'combined_epa' => $data[18],
	                            'engine_name' => $data[19],
	                            'transmission' => $data[20],
	                            'slug' => $data[21],
	                            'make_slug' => $data[22],
	                            'model_slug' => $data[23],
	                            'body_type' => $this->getBodyType($data[11])
	                        ));
	                    	$styleCounter++;
	                    }
	                }
                }
            }
        }
        $this->info("Loaded Info for $styleCounter Vehicle Style.\n");
	}

	public function assetCSV ()
	{
		set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("Load Vehicle Asset Information\n");
        $assetCounter='';

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/vehicle-data/vehicle_assets.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("vehicle_assets.csv ".$num." columns\n");
            }
            
            $assetCounter=0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                
                if(count($data)!=$num)
                {
                    $this->info($assetCounter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    $assets = DB::table('vehicle_assets')->where('id','=',$data[2])->first();
                    if(empty($assets)) {
                    	DB::table('vehicle_assets')->insert(array(
                            'id' => $data[2],
                            'type' => $data[3],
                            'path' => $data[4],
                            'name' => $data[5],
                            'description' => $data[6],
                            'short_description' => $data[7],
                            'edmunds_style_id' => $data[8],
                            'style_id' => $data[9],
                            'shot_type' => $data[10],
                            'pic_size' => $data[11],
                        ));
                        $assetCounter++;
                    }
                }
            }
        }
        $this->info("Loaded Info for $assetCounter Vehicle Assets.\n");
	}

	public function incentiveCSV ()
	{
		set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '1024M');
        $this->info("Load Vehicle Incentive Information\n");
        $incentiveCounter='';

        DB::table("vehicle_incentives")->truncate();
        DB::table("vehicle_incentive_styles")->truncate();

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/vehicle-data/vehicle_incentives.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("vehicle_incentives.csv ".$num." columns\n");
            }
            
            $incentiveCounter=0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                
                if(count($data)!=$num)
                {
                    $this->info($incentiveCounter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    DB::table('vehicle_incentives')->insert(array(
                    	'created_at' => date('Y-m-d H:i:s'),
	                    'updated_at' => date('Y-m-d H:i:s'),
                        'id' => $data[2],
                        'name' => $data[3],
                        'slug' => $data[4],
                        'rebate_amount' => $data[5],
                        'edmunds_id' => $data[6],
                        'type' => $data[7],
                        'content_type' => $data[8],
                        'incentive_type' => $data[9],
                        'starts_at' => $data[10],
                        'expires_at' => $data[11],
                        'restrictions' => $data[12],
                        'make_name' => $data[13],
                        'make_id' => $data[14],
                        'is_active' => $data[15],
                        'description' => $data[16],
                    ));
                    $incentiveCounter++;
                }
            }
        }

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/vehicle-data/vehicle_incentive_styles.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("vehicle_incentive_styles.csv ".$num." columns\n");
            }
            
            $incentiveStyleCounter=0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                
                if(count($data)!=$num)
                {
                    $this->info($incentiveStyleCounter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    DB::table('vehicle_incentive_styles')->insert(array(
                    	'created_at' => date('Y-m-d H:i:s'),
	                    'updated_at' => date('Y-m-d H:i:s'),
                        'id' => $data[2],
                        'vehicle_incentive_id' => $data[3],
                        'vehicle_style_id' => $data[4],
                    ));
                    $incentiveStyleCounter++;
                }
            }
        }
        $this->info("Loaded Info for $incentiveCounter Vehicle Incentives.\n");
	}

	public function slugFix()
	{
        set_time_limit(30*60); // 30 Mins
		ini_set('memory_limit', '1024M');
		$this->info("Begin Loading Edmunds Vehicle Data\n\n");
		$styleCounter=0;
    	$session = curl_init();

    	$styles = SOE\DB\VehicleStyle::where('slug','=','')->orWhere('make_slug','=','')->orWhere('model_slug','=','')->get();

    	//print_r($styles);

    	foreach ($styles as $style) 
    	{
    		$style->slug = SoeHelper::getSlug($this->removeHtml($style->name));
    		$style->make_slug = SoeHelper::getSlug($this->removeHtml($style->make_name));
    		$style->model_slug = SoeHelper::getSlug($this->removeHtml($style->model_name));
    		$style->save();
    		$styleCounter++;
    	}
    	$this->info("Fixed slugs for $styleCounter Vehicle Styles.\n");
    }

	public function getClosest($search, $arr)
	{
	   	$closest = null;
	   	foreach($arr as $item) {
	      	if($closest == null || abs($search - $closest) > abs($item - $search)) {
	        	$closest = $item;
	      	}
	   	}
	   	return $closest;
	}

    protected function getBodyType($primary)
    {
        DB::table('vehicle_styles')->where('primary_body_type', 'LIKE', '%van%')->update(array('body_type' => 'van'));
        DB::table('vehicle_styles')->where('primary_body_type', 'LIKE', '%cab%')->update(array('body_type' => 'truck'));
        DB::table('vehicle_styles')->where('primary_body_type', 'LIKE', '%suv%')->update(array('body_type' => 'suv'));
        DB::table('vehicle_styles')->where('body_type', '=', '')->update(array('body_type' => 'car'));
        if(stristr($primary, 'van'))
        {
            $type = 'van';
        }
        else if(stristr($primary, 'cab'))
        {
            $type = 'truck';
        }
        else if(stristr($primary, 'suv'))
        {
            $type = 'suv';
        }
        else
        {
            $type = 'car';
        }

        return $type;
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

	protected function removePunctuation($string)
    {
        return preg_replace("/[^A-Za-z0-9 ]/", '', $string);
    }

    protected function removeHtml($string)
    {
        return str_replace("&#39;", "'", $string);
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
			array('year', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
			array('focus', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
			array('id', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
