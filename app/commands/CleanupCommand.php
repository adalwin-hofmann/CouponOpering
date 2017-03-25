<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CleanupCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'cleanup';

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
            case 'http':
                $this->httpFixer();
                break;
            case 'yipit':
                $this->yipit();
                break;
            case 'merchants':
                $this->inactiveMerchants();
                break;
            case 'sessions':
                $this->sessions();
                break;
            case 'newsletters':
                $this->newsletters();
                break;
            case 'admin_logs':
                $this->admin_logs();
                break;
            default:
                $this->info('Possible --type= values: http');
            	//$this->makeUpdate();
                break;
        }
	}

	protected function httpFixer()
    {
    	set_time_limit(30*60); // 30 Mins
		ini_set('memory_limit', '2048M');
		$this->info("Begin Changing HTTPS Links HTTP to S3\n");
		$httpsCounter=0;

		$assets = SOE\DB\Asset::get();
        foreach($assets as $asset)
        {
        	if(strpos($asset->path,'https://s3.amazonaws.com/') !== false)
        	{
        		$assetPath = str_replace('https://s3.amazonaws.com/','http://s3.amazonaws.com/',$asset->path);
        		DB::table('assets')->where('id', '=', $asset->id)->update(array('path' => $assetPath));
        		$httpsCounter++;
        	}
            
        }

        $offers = DB::table('offers')->where('path', 'LIKE', 'https://s3.amazonaws.com%')->get();
        foreach($offers as $offer)
        {
			$offerPath = str_replace('https://s3.amazonaws.com/','http://s3.amazonaws.com/',$offer->path);
			DB::table('offers')->where('id', '=', $offer->id)->update(array('path' => $offerPath));
			$httpsCounter++;
		}

        $offers = DB::table('offers')->where('merchant_logo', 'LIKE', 'https://s3.amazonaws.com%')->get();
        foreach($offers as $offer)
        {
    		$offerLogo = str_replace('https://s3.amazonaws.com/','http://s3.amazonaws.com/',$offer->merchant_logo);
    		DB::table('offers')->where('id', '=', $offer->id)->update(array('merchant_logo' => $offerLogo));
    		$httpsCounter++;
        }

        $contests = SOE\DB\Contest::get();
        foreach($contests as $contest)
        {
        	if(strpos($contest->path,'https://s3.amazonaws.com/') !== false)
        	{
        		$contestPath = str_replace('https://s3.amazonaws.com/','http://s3.amazonaws.com/',$contest->path);
        		DB::table('contests')->where('id', '=', $contest->id)->update(array('path' => $contestPath));
        		$httpsCounter++;
        	}
        	if(strpos($contest->banner,'https://s3.amazonaws.com/') !== false)
        	{
        		$bannerPath = str_replace('https://s3.amazonaws.com/','http://s3.amazonaws.com/',$contest->banner);
        		DB::table('contests')->where('id', '=', $contest->id)->update(array('banner' => $bannerPath));
        		$httpsCounter++;
        	}
        	if(strpos($contest->logo,'https://s3.amazonaws.com/') !== false)
        	{
        		$logoPath = str_replace('https://s3.amazonaws.com/','http://s3.amazonaws.com/',$contest->logo);
        		DB::table('contests')->where('id', '=', $contest->id)->update(array('logo' => $logoPath));
        		$httpsCounter++;
        	}
        	if(strpos($contest->landing,'https://s3.amazonaws.com/') !== false)
        	{
        		$landingPath = str_replace('https://s3.amazonaws.com/','http://s3.amazonaws.com/',$contest->landing);
        		DB::table('contests')->where('id', '=', $contest->id)->update(array('landing' => $landingPath));
        		$httpsCounter++;
        	}
        	if(strpos($contest->contest_logo,'https://s3.amazonaws.com/') !== false)
        	{
        		$contest_logoPath = str_replace('https://s3.amazonaws.com/','http://s3.amazonaws.com/',$contest->contest_logo);
        		DB::table('contests')->where('id', '=', $contest->id)->update(array('contest_logo' => $contest_logoPath));
        		$httpsCounter++;
        	}
            
        }
        $this->info("Updated $httpsCounter Links\n");
    }

    protected function yipit()
    {
    	set_time_limit(120*60); // 120 Mins
		ini_set('memory_limit', '2048M');
		date_default_timezone_set('America/Detroit');
		$timebegin = date('g:i');
		$this->info("Begin Removing Expired Yipit Deals at $timebegin.");
		if ($this->confirm('Do you wish to continue? [yes|no]'))
		{
			$yipitCounter=0;

			$yipitOffers = DB::table('offers')->where('yipitdeal_id', '!=', 0)
	                            ->where('expires_at', '<', \DB::raw('NOW()'))
	                            ->get();
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
	        $totalOldYipits = count($yipitOffers);
	        $totalActiveYipits = count($yipitActiveOffers);
	        $this->info("There are $totalOldYipits Expired Yipit Deals and $totalActiveYipits Active Yipit Deals.");
	        foreach ($yipitOffers as $yipitOffer)
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
	        $this->info("Finished removing $yipitCounter Yipit Deals and associated data.");
        }
    }

    protected function inactiveMerchants()
    {
    	set_time_limit(120*60); // 120 Mins
		ini_set('memory_limit', '2048M');
		date_default_timezone_set('America/Detroit');
		$timebegin = date('g:i');
		$this->info("Begin removing inactive Merchants at $timebegin.");
		$merchantCounter=0;
		$offerCounter=0;

		$activerOffers = DB::table('offers')
			->where('expires_at', '>', date("Y-m-d H:i:s",strtotime("13 months ago")))
			->groupBy('merchant_id')
			->get(array('merchant_id'));
		$activeMerchants = array();
		foreach ($activerOffers as $activerOffer) {
			$activeMerchants[] = $activerOffer->merchant_id;
		}

		$merchants = DB::table('merchants')
			->leftJoin('franchises', 'franchises.merchant_id', '=', 'merchants.id')
        	->leftJoin('franchise_assignments', 'franchise_assignments.franchise_id', '=', 'franchises.id')
        	->where('franchise_assignments.user_id','<>',4777)
        	->whereNotIn('merchants.id',$activeMerchants)
        	->where('franchises.created_at', '<', date("Y-m-d H:i:s",strtotime("13 months ago")))
        	->where('franchises.contract_end', '<', date("Y-m-d H:i:s",strtotime("13 months ago")))
        	->where('franchises.is_permanent','=',0)
        	->groupBy('merchants.id')
			->get(array(
	            'merchants.*',
	            \DB::raw('franchise_assignments.user_id as franchise_user_id'),
	        ));
		//$merchant_count = count($merchants);
		if ($this->confirm('This will remove '.count($merchants).' merchants and their data. Do you wish to continue? [yes|no]'))
		{
			$awsAccessKey = 'AKIAI6NWHQYFADWZVYLQ';
			$awsSecretKey = '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';
			$bucket = 'saveoneverything_assets';
			$s3path = "http://s3.amazonaws.com/saveoneverything_assets/";

	        foreach ($merchants as $merchant)
	        {
	        	$merchantCounter++;
	        	if(!App::environment('local'))
				{
		        	$assets = DB::table('assets')->where('assetable_id', $merchant->id)
                        ->where('assetable_type','Merchant')
                        ->where('path', 'LIKE', "'%s3.amazonaws.com/saveoneverything_assets%'")
                        ->get();
		        	foreach ($assets as $asset)
		        	{
		        		$s3 = new S3($awsAccessKey, $awsSecretKey);
						$s3->deleteObject($bucket, str_replace($s3path, '',$asset->path));
		        	}
	        	}
	        	DB::table('offers')->where('merchant_id', $merchant->id)->delete();
	        	DB::table('entities')->where('merchant_id', $merchant->id)->delete();
	        	DB::table('merchants')->where('id', $merchant->id)->delete();
	        	$locations = DB::table('locations')->where('merchant_id', $merchant->id)->get();
	        	foreach ($locations as $location)
	        	{
	        		if(!App::environment('local'))
					{
		        		$assets = DB::table('assets')->where('assetable_id', $location->id)
                            ->where('assetable_type','Location')
                            ->where('path', 'LIKE', "'%s3.amazonaws.com/saveoneverything_assets%'")
                            ->get();
		        		foreach ($assets as $asset)
			        	{
			        		$s3 = new S3($awsAccessKey, $awsSecretKey);
			        		$s3->deleteObject($bucket, str_replace($s3path, '',$asset->path));
			        	}
		        	}
	        		$assets = DB::table('assets')->where('assetable_id', $location->id)->where('assetable_type','Location')->delete();
	        	}
	        	DB::table('locations')->where('merchant_id', $merchant->id)->delete();
	        	DB::table('franchises')->where('merchant_id', $merchant->id)->delete();
	        	DB::table('assets')->where('assetable_id', $merchant->id)->where('assetable_type','Merchant')->delete();
	        	if($merchantCounter % 2000 == 0)
	        		$this->info("Removed $merchantCounter inactive Merchants so far...");
	        }
	        $this->info("Removed $merchantCounter inactive Merchants and associated data.");
    	}
    }

    protected function sessions()
    {
    	set_time_limit(120*60); // 120 Mins
		ini_set('memory_limit', '2048M');
		date_default_timezone_set('America/Detroit');
		$timebegin = date('g:i');
		$session_count = DB::table('sessions')->where('last_activity','<',strtotime('-2 months'))->orderBy('last_activity', 'asc')->count();
		$this->info("Begin removing $session_count unused session data.");
		$session_count = DB::table('sessions')->where('last_activity','<',strtotime('-2 months'))->orderBy('last_activity', 'asc')->delete();
		$this->info("Finished removing $session_count unused session data.");
	}

	protected function newsletters()
    {
    	set_time_limit(120*60); // 120 Mins
		ini_set('memory_limit', '2048M');
		date_default_timezone_set('America/Detroit');
		$timebegin = date('g:i');

		$newsletter_count = DB::table('newsletters')
			->where('updated_at','<',date('Y-m-d H:i:s',strtotime('-2 months')))
			->where(function ($query) {
				$query->where('sent_at','<',date('Y-m-d H:i:s',strtotime('-2 months')))
          		->orWhereNull('sent_at');
				})
			->count();
		$this->info("Begin removing $newsletter_count old newsletter data rows.");
		DB::table('newsletters')
			->where('updated_at','<',date('Y-m-d H:i:s',strtotime('-2 months')))
			->where(function ($query) {
				$query->where('sent_at','<',date('Y-m-d H:i:s',strtotime('-2 months')))
          		->orWhereNull('sent_at');
				})
			->delete();
		$this->info("Finished removing $newsletter_count old newsletter data rows.");
	}

	protected function admin_logs()
	{
		set_time_limit(120*60); // 120 Mins
		ini_set('memory_limit', '2048M');
		date_default_timezone_set('America/Detroit');
		$timebegin = date('g:i');

		$log_count = DB::table('action_logs')->where('updated_at','<',date('Y-m-d H:i:s',strtotime('-1 month')))->count();
		$this->info("Begin removing $log_count old admin logs.");
		DB::table('action_logs')->where('updated_at','<',date('Y-m-d H:i:s',strtotime('-1 month')))->delete();
		$this->info("Finished removing $log_count old admin logs.");
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
