<?php

class ScheduledController extends BaseController {

    public function __construct(
        CjPostRepositoryInterface $cjposts,
        CjProductRepositoryInterface $cjproducts,
        \ContestAwardDateRepositoryInterface $contestAwardDates,
        \FranchiseRepositoryInterface $franchises,
        \SOE\Newsletters\NewsletterFactory $newsletterFactory,
        \NewsletterScheduleRepositoryInterface $schedules,
        SysLogRepositoryInterface $syslogs,
        \VehicleStyleRepositoryInterface $vehicleStyles
    )
    {
        $this->contestAwardDates = $contestAwardDates;
        $this->cjposts = $cjposts;
        $this->cjproducts = $cjproducts;
        $this->franchises = $franchises;
        $this->newsletterFactory = $newsletterFactory;
        $this->schedules = $schedules;
        $this->syslogs = $syslogs;
        $this->vehicleStyles = $vehicleStyles;
    }

    /**
     * Dispatch the given scheduled command.
     */
    public function dispatcher($command)
    {
        return $this->$command();
    }

    protected function cjproductpost()
    {
        /*$success = false;
 
        if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){
         
            $username = $_SERVER['PHP_AUTH_USER'];
            $password = $_SERVER['PHP_AUTH_PW'];
         
            if ($username == 'cjuser' && $password == 'cj1q2w3e'){
                $success = true;
            }
        }
         
        // Login passed successful?
        if(!$success)
        {
            header('WWW-Authenticate: Basic realm="CJ Product Post"');
            header('HTTP/1.0 401 Unauthorized');
         
            print "Login failed!\n";
            $this->syslogs->create(array('type' => 'cj_post_failure', 'message' => 'Authentication Failed - Username: '.(isset($_SERVER['PHP_AUTH_USER'])?$_SERVER['PHP_AUTH_USER']:'').' - Password: '.(isset($_SERVER['PHP_AUTH_PW'])?$_SERVER['PHP_AUTH_PW']:'')));
            return;
        }*/

        if (Input::hasFile('datafile'))
        {
            $file = Input::file('datafile');
            $fileStore = App::make('FileStoreInterface');
            $stored = $fileStore->store($file, 'commission_junction/');

            if($stored)
            {
                $cjpost = $this->cjposts->create(array(
                    'file_location' => $stored
                ));
            }           
        }
        else
        {
            $inputs = Input::all();
            $content = Input::getContent();
            $headers = Request::header();
            $this->syslogs->create(array('type' => 'cj_post_failure', 'message' => 'No datafile. Headers: '.json_encode($headers).' Params: '.json_encode($inputs).' Content: '.$content));
        }  
    }

    protected function cjparse()
    {
        $post = $this->cjposts->getForParsing();
        if(!$post)
            return;

        $this->cjproducts->createFromPost($post);
    }

    protected function create_newsletter_content()
    {
        $preppable = $this->schedules->getPreppable();
        foreach($preppable as $prep)
        {
            $type = explode('_', $prep->type)[0];
            try
            {
                $newsletter = $this->newsletterFactory->make($type);
                $newsletter->prep($prep->id);
            }
            catch(\SOE\Newsletters\NewsletterException $e)
            {
                return Response::error($e);
            }
        }
    }

    protected function send_newsletter()
    {
        $limit = Input::get('limit', 500);
        $sendable = $this->schedules->getSendable();
        foreach($sendable as $send)
        {
            $type = explode('_', $send->type)[0];
            try
            {
                $newsletter = $this->newsletterFactory->make($type);
                $newsletter->send($send->id, $limit);
            }
            catch(\SOE\Newsletters\NewsletterException $e)
            {
                return Response::error($e);
            }
        }
    }

    protected function check_winners()
    {
        $dates = $this->contestAwardDates->getReadyForAward();
        $awarder = new \SOE\Contests\ContestAwarder;
        $awarder->handleReady($dates);
    }

    protected function check_coupons()
    {
        $coupon = new \SOE\Coupons\CouponNotifier;
        $coupon->handleReady();
    }

    protected function dt_download()
    {
        Artisan::call('inventory_import', array('--type' => 'get_dt_file'));
    }

    protected function dt_dealers()
    {
        Artisan::call('inventory_import', array('--type' => 'dt_dealers'));
    }

    protected function dt_inventory()
    {
        Artisan::call('inventory_import', array('--type' => 'dt_inventory'));
    }

    protected function soct_dealers()
    {
        Artisan::call('inventory_import', array('--type' => 'soct_dealers'));
    }

    protected function soct_inventory()
    {
        Artisan::call('inventory_import', array('--type' => 'soct_inventory'));
    }

    protected function dealer_specialties()
    {
        Artisan::call('inventory_import', array('--type' => 'dealer_specialties'));
    }

    protected function vauto()
    {
        Artisan::call('inventory_import', array('--type' => 'vauto'));
    }

    protected function merge_vehicles()
    {
        Artisan::call('inventory_import', array('--type' => 'merge_vehicles'));
    }

    protected function rank()
    {
        $type = Input::get('type', 'user');
        $ranking = App::make('Ranking');
        $ranking->enqueue($type);
    }

    protected function yipit()
    {
        $yipit = App::make('Yipit');
        $yipit->enqueue();
    }

    protected function warmup()
    {
        $type = Input::get('type', '');
        Artisan::call('warmup', array('--type' => $type));
    }

    protected function search()
    {
        $type = Input::get('type', 'merchant');
        Artisan::call('search', array('--type' => $type));
    }

    protected function cache()
    {
        $type = Input::get('type', '');
        Artisan::call('cache', array('--type' => $type));
    }

    protected function trial_check()
    {
        Artisan::call('trial', array('--type' => 'trial_check'));
    }

    protected function broken_logos()
    {
        Artisan::call('images', array('--type' => 'check_logos_broken'));
    }

    protected function broken_vehicle_assets()
    {
        Artisan::call('images', array('--type' => 'check_vehicle_assets_broken'));
    }

    protected function import_live_inventory()
    {
        Artisan::call('soct', array('--type' => 'import_live_inventory'));
    }

    protected function calculate_soct_popularity()
    {
        $this->vehicleStyles->calculatePopularity();
    }

    protected function leads_purchased()
    {
        $this->franchises->updateLeadsPurchased();
    }

}