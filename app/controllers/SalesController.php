<?php

class SalesController extends BaseController {

    protected $contestRepository;
    protected $userRepository;

    /**
    *
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct(
        AssetRepositoryInterface $assets,
        CategoryRepositoryInterface $categories,
        ContestRepositoryInterface $contestRepository,
        FranchiseRepositoryInterface $franchises,
        LocationRepositoryInterface $locations,
        MerchantRepositoryInterface $merchants,
        ShareRepositoryInterface $shares,
        TrainingPageRepositoryInterface $trainingPages,
        TrainingSectionRepositoryInterface $trainingSections,
        UserFavoriteRepositoryInterface $userFavorites,
        UserPrintRepositoryInterface $userPrints,
        UserRepositoryInterface $userRepository
    )
    {
        $this->assets = $assets;
        $this->categories = $categories;
        $this->contestRepository = $contestRepository;
        $this->franchises = $franchises;
        $this->locations = $locations;
        $this->merchants = $merchants;
        $this->shares = $shares;
        $this->trainingPages = $trainingPages;
        $this->trainingSections = $trainingSections;
        $this->userFavorites = $userFavorites;
        $this->userRepository = $userRepository;
        $this->userPrints = $userPrints;
        $this->beforeFilter(function()
        {
            if(!Auth::check())
            {
                return Redirect::to('/login');
            }
            else
            {
                $user = Auth::User();
                $found = $this->userRepository->checkType($user, 'sales');
                if(!$found)
                {
                    return Redirect::to('/login');
                }
            }

        }, array('except' => array('getLogin', 'postLogin', 'getLogout', 'getPasswordresetemail', 'getResetPassword', 'postResetPassword')));

        $this->beforeFilter(function()
        {
            $user = Auth::User();
            $found = $this->userRepository->checkType($user, 'leads');
            if(!$found)
            {
                return Redirect::to('/login');
            }
        }, array('only' => array('getLeads')));
    }

    public function getPasswordresetemail()
    {
        $email = Input::get('email');
        $recovery = $this->userRepository->setPasswordRecovery($email);
        if(empty($recovery))
            return;
        $data = array(
            'key' => $recovery['uniq'],
            'timer' => $recovery['timer'],
        );
        Mail::send('emails.passwordreset', $data, function($message) use ($email)
        {
            $message->to($email)->subject('Reset Your Password');
            $message->sender('webmaster@saveoneverything.com', 'Save On Everything');
        });
    }

    public function getResetPassword()
    {
        $code = array();
        $vw = View::make('admin.master.templates.reset-password')->with('code', implode(' ', $code));
        $vw->uniq = Input::get('uniq');
        $vw->title = "Password Reset";
        $vw->description = "Reset your SaveOn password to continue receiving fabulous online coupons and deals.";

        return $vw;
    }

    public function postResetPassword()
    {
        $validator = Validator::make(
            Input::all(),
            array(
                'email' => 'required',
                'password' => 'required|confirmed',
                'password_confirmation' => 'required'
            )
        );
        if ($validator->fails())
        {
            return Redirect::to('/reset-password')->withErrors($validator)->withInput();
        }
        $uniq = Input::get('uniq');
        $email = Input::get('email');
        $valid = $this->userRepository->validateRecovery($email, $uniq, 'password');
        if(!$valid)
        {
            return Redirect::to('/reset-password')->with('invalid', true)->withInput();
        }

        $user = $this->userRepository->findByEmail($email);
        $user->password = Input::get('password');
        $user->save();
        return Redirect::to('/');
    }

    public function getIndex()
    {
        $code = array();
        $code[] = View::make('admin.sales.jscode.dashboard');
        $vw = View::make('admin.sales.dashboard')->with('code', implode(' ', $code));
        $vw->primary_nav = "sales";
        $vw->secondary_nav = "dashboard";
        $vw->viewing = 0;

        $custStart = '';
        $custEnd = '';
        switch (Input::get('start')) {
            case 'last-3-months':
                $start = new \DateTime(date('Y-m-01', strtotime('-3 months')));
                $end = new \DateTime(date('Y-m-t', strtotime('-1 months')));
                $duration = (int)date('t', strtotime('-1 months')) + (int)date('t', strtotime('-2 months')) + (int)date('t', strtotime('-3 months'));
                $completion = $duration;
                $prevStart = new \DateTime(date('Y-m-01', strtotime('-6 months')));
                $prevEnd = new \DateTime(date('Y-m-t', strtotime('-4 months')));
                $prevDuration = (int)date('t', strtotime('-4 months')) + (int)date('t', strtotime('-5 months')) + (int)date('t', strtotime('-6 months'));
                $prevCompletion = $duration;
                break;
            case 'this-month':
                $start = new \DateTime(date('Y-m-01'));
                $end = (int)date('d') == 1 ? new \DateTime() : new \DateTime(date('Y-m-d', strtotime('-1 days')));
                $duration = (int)date('t');
                $completion = (int)date('d') == 1 ? 1 : (int)date('d') - 1;
                $prevStart = new \DateTime(date('Y-m-01', strtotime('-1 months')));
                $prevEnd = new \DateTime(date('Y-m-t', strtotime('-1 months')));
                $prevDuration = (int)date('t', strtotime('-1 months'));
                $prevCompletion = $prevDuration;
                break;
            case 'this-year':
                $start = new \DateTime(date('Y-01-01'));
                $end = (int)date('z') == 0 ? new \DateTime('Y-m-d') : new \DateTime(date('Y-m-d', strtotime('-1 days')));
                $duration = (int)date('L') ? 365 : 366;
                $completion = (int)date('z') ? (int)date('z') : 1;
                $prevStart = new \DateTime(date('Y-01-01', strtotime('-1 years')));
                $prevEnd = new \DateTime(date('Y-12-31', strtotime('-1 years')));
                $prevDuration = (int)date('L', strtotime('-1 years')) ? 365 : 366;
                $prevCompletion = $prevDuration;
                break;
            case 'last-year':
                $start = new \DateTime((date('Y')-1).'-01-01');
                $end = new \DateTime((date('Y')-1).'-12-31');
                $duration = (int)date('L', strtotime('-1 years')) ? 365 : 366;
                $completion = $duration;
                $prevStart = new \DateTime(date('Y-01-01', strtotime('-2 years')));
                $prevEnd = new \DateTime(date('Y-12-31', strtotime('-2 years')));
                $prevDuration = (int)date('L', strtotime('-2 years')) ? 365 : 366;
                $prevCompletion = $prevDuration;
                break;
            case 'custom':
                $start = new \DateTime(Input::get('cust-start'));
                $end = new \DateTime(Input::get('cust-end'));
                $diff = $end->diff($start);
                $now = new \DateTime();
                $duration = (int)$diff->format('%a');
                $completion = (int)$end->diff($now)->format('%a') >= 0 ? (int)$now->diff($start)->format('%a') : $duration;
                $prevStart = new \DateTime(date('Y-m-d', strtotime(2*$diff->format('%R%a').' days')));
                $prevEnd = new \DateTime(date('Y-m-d', strtotime($diff->format('%R%a days'))));
                $prevDuration = (int)$diff->format('%a');
                $prevCompletion = $prevDuration;
                $custStart = $start->format('m/d/Y');
                $custEnd = $end->format('m/d/Y');
                break;
            default:
                $start = new \DateTime(date('Y-m-01', strtotime('-1 months')));
                $end = new \DateTime(date('Y-m-t', strtotime('-1 months')));
                $duration = (int)date('t', strtotime('-1 months'));
                $completion = $duration;
                $prevStart = new \DateTime(date('Y-m-01', strtotime('-2 months')));
                $prevEnd = new \DateTime(date('Y-m-t', strtotime('-2 months')));
                $prevDuration = (int)date('t', strtotime('-2 months'));
                $prevCompletion = $prevDuration;
                break;
        }
        $market = Input::get('market', null);
        switch ($market) {
            case 'michigan':
                $marketText = "Detroit Market";
                $state = 'MI';
                break;
            case 'illinois':
                $marketText = "Chicago Market";
                $state = 'IL';
                break;
            case 'minnesota':
                $marketText = "Minneapolis Market";
                $state = 'MN';
                break;
            default:
                $marketText = "All Markets";
                $state = '';
                break;
        }

        if(Input::get('nocache','false') != 'true' && Cache::has('dash_cache-'.Input::get('start').'-'.Input::get('market').'-'.Input::get('cust-start').'-'.Input::get('cust-end')))
        {
            $cache = Cache::get('dash_cache-'.Input::get('start').'-'.Input::get('market').'-'.Input::get('cust-start').'-'.Input::get('cust-end'));
        }
        else
        {
            $dateText = $start->format('m/d/Y').' - '.$end->format('m/d/Y');
            $startDate = $start->format('Y-m-d 00:00:00');
            $endDate = $end->format('Y-m-d 23:59:59');
            $prevStartDate = $prevStart->format('Y-m-d 00:00:00');
            $prevEndDate = $prevEnd->format('Y-m-d 23:59:59');
            $prints = $this->userPrints->getRangePrints($startDate, $endDate, 'all', $market);
            $prevPrints = $this->userPrints->getRangePrints($prevStartDate, $prevEndDate, 'all', $market);
            $printsAvg = $prints / ($start->diff($end)->format('%a') ? $start->diff($end)->format('%a') : 1);
            $prevPrintsAvg = $prevPrints / ($prevStart->diff($prevEnd)->format('%a') ? $prevStart->diff($prevEnd)->format('%a') : 1);
            $printsTrend = $prevPrintsAvg ? ($printsAvg - $prevPrintsAvg) / $prevPrintsAvg * 100 : 0;
            $redemptions = $this->userPrints->getRangePrints($startDate, $endDate, 'redemptions', $market);
            $prevRedemptions = $this->userPrints->getRangePrints($prevStartDate, $prevEndDate, 'redemptions', $market);
            $redemptionsAvg = $redemptions / ($start->diff($end)->format('%a') ? $start->diff($end)->format('%a') : 1);
            $prevRedemptionsAvg = $prevRedemptions / ($prevStart->diff($prevEnd)->format('%a') ? $prevStart->diff($prevEnd)->format('%a') : 1);
            $redemptionsTrend = $prevRedemptionsAvg ? ($redemptionsAvg - $prevRedemptionsAvg) / $prevRedemptionsAvg * 100 : 0;
            $merchants = $this->merchants->getActive(null, !$market, $market);
            $repeats = $this->userPrints->getRepeatRangePrints($startDate, $endDate, $market);
            $repeaters = $this->userPrints->getRepeatRangePrinters($startDate, $endDate, $market);
            $applicants = $this->contestRepository->getApplicantCount(null, $startDate, $endDate, $market);
            $contests = $this->contestRepository->getContestsRun($startDate, $endDate, $market);
            $contests = count($contests);
            $members = $this->userRepository->getMemberCount(null, null, $market);
            $newMembers = $this->userRepository->getMemberCount($startDate, $endDate, $market);
            $newMemAvg = $newMembers / ($start->diff($end)->format('%a') ? $start->diff($end)->format('%a') : 1);
            $prevMembers = $this->userRepository->getMemberCount($prevStart->format('Y-m-d 00:00:00'), $prevEnd->format('Y-m-d 23:59:59'), $market);
            $prevMemAvg = $prevMembers / ($prevStart->diff($prevEnd)->format('%a') ? $prevStart->diff($prevEnd)->format('%a') : 1);
            $memTrend = $prevMemAvg ? ($newMemAvg - $prevMemAvg) / $prevMemAvg * 100 : 0;
            $newFavorites = $this->userFavorites->getFavoriteStats('Location', $startDate, $endDate, $market);
            $newFavAvg = $newFavorites / ($start->diff($end)->format('%a') ? $start->diff($end)->format('%a') : 1);
            $prevFavorites = $this->userFavorites->getFavoriteStats('Location', $prevStart->format('Y-m-d 00:00:00'), $prevEnd->format('Y-m-d 23:59:59'), $market);
            $prevFavAvg = $prevFavorites / ($prevStart->diff($prevEnd)->format('%a') ? $prevStart->diff($prevEnd)->format('%a') : 1);
            $favTrend = $prevFavAvg ? ($newFavAvg - $prevFavAvg) / $prevFavAvg * 100 : 0;
            $newShares = $this->shares->getShareStats(null, $startDate, $endDate, $market);
            $newShareAvg = $newShares / ($start->diff($end)->format('%a') ? $start->diff($end)->format('%a') : 1);
            $prevShares = $this->shares->getShareStats(null, $prevStart->format('Y-m-d 00:00:00'), $prevEnd->format('Y-m-d 23:59:59'), $market);
            $prevShareAvg = $prevShares / ($prevStart->diff($prevEnd)->format('%a') ? $prevStart->diff($prevEnd)->format('%a') : 1);
            $shareTrend = $prevShareAvg ? ($newShareAvg - $prevShareAvg) / $prevShareAvg * 100 : 0;

            $base_url = 'https://api.sendgrid.com/api/stats.get.json?api_user='.Config::get('mail.username').'&api_key='.Config::get('mail.password');
            $tail_url = '&start_date='.$start->format('Y-m-d').'&end_date='.$end->format('Y-m-d');
            $params = '&category=member-newsletter';

            $ch = curl_init($base_url.$tail_url.$params);                                                                      
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");                                                                
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $results = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $memberMailStats = array(
                'clicks' => 0,
                'delivered' => 0,
                'opens' => 0
            );
            if($status == 200)
            {
                $results = json_decode($results);
                foreach($results as $day)
                {
                    $memberMailStats['clicks'] += $day->unique_clicks;
                    $memberMailStats['delivered'] += $day->delivered;
                    $memberMailStats['opens'] += $day->unique_opens;
                }
            } 

            try
            {
                $profileId = Config::get('integrations.google_analytics.profile_id');
                $query = new Widop\GoogleAnalytics\Query($profileId);
                $query->setStartDate($start);
                $query->setEndDate($end);
                $query->setMetrics(array('ga:users','ga:avgSessionDuration','ga:sessions','ga:percentNewSessions'));
                if($market)
                    $query->setFilters(array('ga:region=='.ucwords($market)));
                $clientId = Config::get('integrations.google_analytics.service_email');
                $privateKeyFile = Config::get('integrations.google_analytics.pk_location');
                $httpAdapter = new Widop\HttpAdapter\CurlHttpAdapter();
                $client = new Widop\GoogleAnalytics\Client($clientId, $privateKeyFile, $httpAdapter);
                $token = $client->getAccessToken();
                $service = new Widop\GoogleAnalytics\Service($client);
                $response = $service->query($query);
                $totals = $response->getTotalsForAllResults();

                $profileId = Config::get('integrations.google_analytics.profile_id');
                $query = new Widop\GoogleAnalytics\Query($profileId);
                $query->setStartDate($prevStart);
                $query->setEndDate($prevEnd);
                $query->setMetrics(array('ga:users','ga:avgSessionDuration','ga:sessions','ga:percentNewSessions'));
                if($market)
                    $query->setFilters(array('ga:region=='.ucwords($market)));
                $clientId = Config::get('integrations.google_analytics.service_email');
                $privateKeyFile = Config::get('integrations.google_analytics.pk_location');
                $httpAdapter = new Widop\HttpAdapter\CurlHttpAdapter();
                $client = new Widop\GoogleAnalytics\Client($clientId, $privateKeyFile, $httpAdapter);
                $token = $client->getAccessToken();
                $service = new Widop\GoogleAnalytics\Service($client);
                $response = $service->query($query);
                $prevTotals = $response->getTotalsForAllResults();

                $query = new Widop\GoogleAnalytics\Query($profileId);
                $query->setStartDate(new \DateTime('-7days'));
                $query->setEndDate(new \DateTime());
                $query->setMetrics(array('ga:sessions'));
                if($market)
                    $query->setFilters(array('ga:region=='.ucwords($market)));
                $httpAdapter = new Widop\HttpAdapter\CurlHttpAdapter();
                $client = new Widop\GoogleAnalytics\Client($clientId, $privateKeyFile, $httpAdapter);
                $token = $client->getAccessToken();
                $service = new Widop\GoogleAnalytics\Service($client);
                $response = $service->query($query);
                $weekTraffic = $response->getTotalsForAllResults();

                $query = new Widop\GoogleAnalytics\Query($profileId);
                $query->setStartDate(new \DateTime('-14days'));
                $query->setEndDate(new \DateTime('-7days'));
                $query->setMetrics(array('ga:sessions'));
                if($market)
                    $query->setFilters(array('ga:region=='.ucwords($market)));
                $httpAdapter = new Widop\HttpAdapter\CurlHttpAdapter();
                $client = new Widop\GoogleAnalytics\Client($clientId, $privateKeyFile, $httpAdapter);
                $token = $client->getAccessToken();
                $service = new Widop\GoogleAnalytics\Service($client);
                $response = $service->query($query);
                $prevWeekTraffic = $response->getTotalsForAllResults();

                $query = new Widop\GoogleAnalytics\Query($profileId);
                $query->setStartDate(new \DateTime('-1months'));
                $query->setEndDate(new \DateTime());
                $query->setMetrics(array('ga:sessions'));
                if($market)
                    $query->setFilters(array('ga:region=='.ucwords($market)));
                $httpAdapter = new Widop\HttpAdapter\CurlHttpAdapter();
                $client = new Widop\GoogleAnalytics\Client($clientId, $privateKeyFile, $httpAdapter);
                $token = $client->getAccessToken();
                $service = new Widop\GoogleAnalytics\Service($client);
                $response = $service->query($query);
                $monthTraffic = $response->getTotalsForAllResults();

                $query = new Widop\GoogleAnalytics\Query($profileId);
                $query->setStartDate(new \DateTime('-2months'));
                $query->setEndDate(new \DateTime('-1months'));
                $query->setMetrics(array('ga:sessions'));
                if($market)
                    $query->setFilters(array('ga:region=='.ucwords($market)));
                $httpAdapter = new Widop\HttpAdapter\CurlHttpAdapter();
                $client = new Widop\GoogleAnalytics\Client($clientId, $privateKeyFile, $httpAdapter);
                $token = $client->getAccessToken();
                $service = new Widop\GoogleAnalytics\Service($client);
                $response = $service->query($query);
                $prevMonthTraffic = $response->getTotalsForAllResults();

                $query = new Widop\GoogleAnalytics\Query($profileId);
                $query->setStartDate($start);
                $query->setEndDate($end);
                $query->setMetrics(array('ga:sessions'));
                if($market)
                    $query->setFilters(array('ga:region=='.ucwords($market)));
                $httpAdapter = new Widop\HttpAdapter\CurlHttpAdapter();
                $client = new Widop\GoogleAnalytics\Client($clientId, $privateKeyFile, $httpAdapter);
                $token = $client->getAccessToken();
                $service = new Widop\GoogleAnalytics\Service($client);
                $response = $service->query($query);
                $currentTraffic = $response->getTotalsForAllResults();
                $avgVisits = $currentTraffic['ga:sessions'] / ($completion ? $completion : 1);
                $trendVisits = $avgVisits * $duration;

                $query = new Widop\GoogleAnalytics\Query($profileId);
                $query->setStartDate($prevStart);
                $query->setEndDate($prevEnd);
                $query->setMetrics(array('ga:sessions'));
                if($market)
                    $query->setFilters(array('ga:region=='.ucwords($market)));
                $httpAdapter = new Widop\HttpAdapter\CurlHttpAdapter();
                $client = new Widop\GoogleAnalytics\Client($clientId, $privateKeyFile, $httpAdapter);
                $token = $client->getAccessToken();
                $service = new Widop\GoogleAnalytics\Service($client);
                $response = $service->query($query);
                $prevTraffic = $response->getTotalsForAllResults();
                $trendPrevVisits = $prevTraffic['ga:sessions'];
                $avgPrevVisits = $trendPrevVisits / ($prevCompletion ? $prevCompletion : 1);
                $error = false;
            }
            catch(\Exception $e)
            {
                $totals = array(
                    'ga:sessions' => 0,
                    'ga:users' => 0,
                    'ga:avgSessionDuration' => 0,
                    'ga:percentNewSessions' => 0
                );
                $prevTotals = array(
                    'ga:sessions' => 0,
                    'ga:users' => 0,
                    'ga:avgSessionDuration' => 0,
                    'ga:percentNewSessions' => 0
                );
                $weekTraffic = array(
                    'ga:sessions' => 0
                );
                $prevWeekTraffic = array(
                    'ga:sessions' => 0
                );
                $monthTraffic = array(
                    'ga:sessions' => 0
                );
                $prevMonthTraffic = array(
                    'ga:sessions' => 0
                );
                $trendVisits = 0;
                $trendPrevVisits = 0;
                $error = true;
            }

            $start_day = $start->format('Y-m-d');//date('Y-m-01');
            $end_day = $end->format('Y-m-d');//date('Y-m-d');
            $api_key = \Config::get('integrations.mixpanel.key');
            $api_secret = \Config::get('integrations.mixpanel.secret');
            if($state != '')
            {
                $where = '"'.$state.'" == properties["$region"] and ';
            }
            else
                $where = '';
            $mp = new \MPData($api_key, $api_secret);
            $data = $mp->request(array('segmentation'), array(
                'event' => 'Location View',
                'from_date' => $start_day,
                'to_date' => $end_day,
                'unit' => 'day',//'month',
                'where' => $where.'properties["Environment"] == "prod"',
                'type' => 'general',
            ));
            //$totalLocationViews = $data->data->values->{'Location View'}->{date('Y-m-01')};
            $totalLocationViews = 0;
            if(isset($data->data->values->{'Location View'}))
            {
                foreach($data->data->values->{'Location View'} as $key => $value)
                {
                    $totalLocationViews += $value;
                }
            }
            $avgLocationViews = $totalLocationViews / ($completion ? $completion : 1);
            $trendLocationViews = $avgLocationViews * $duration;

            $start_day = $prevStart->format('Y-m-d');//date('Y-m-01', strtotime('-1 months'));
            $end_day = $prevEnd->format('Y-m-d');//date('Y-m-t', strtotime('-1 months'));
            if($state != '')
            {
                $where = '"'.$state.'" == properties["$region"] and ';
            }
            else
                $where = '';
            $mp = new \MPData($api_key, $api_secret);
            $data = $mp->request(array('segmentation'), array(
                'event' => 'Location View',
                'from_date' => $start_day,
                'to_date' => $end_day,
                'unit' => 'day',//'month',
                'where' => $where.'properties["Environment"] == "prod"',
                'type' => 'general',
            ));
            $prevTotalLocationViews = 0;
            if(isset($data->data->values->{'Location View'}))
            {
                foreach($data->data->values->{'Location View'} as $key => $value)
                {
                    $prevTotalLocationViews += $value;
                }
            }
            $avgPrevLocationViews = $prevTotalLocationViews / ($prevCompletion ? $prevCompletion : 1);
            $trendPrevLocationViews = $avgPrevLocationViews * $prevDuration;//$data->data->values->{'Location View'}->{date('Y-m-01', strtotime('-1 months'))};

            $visitsAvg = $totals['ga:sessions'] / $completion;
            $prevVisitsAvg = $prevTotals['ga:sessions'] / ($prevCompletion ? $prevCompletion : 1);

            $cache = array(
                'error' => $error,
                'visits' => $totals['ga:sessions'],
                'visitsTrend' => $prevVisitsAvg ? ($visitsAvg - $prevVisitsAvg) / $prevVisitsAvg * 100 : 0,
                'users' => $totals['ga:users'],
                'avgDuration' => $totals['ga:avgSessionDuration'],
                'newSessions' => $totals['ga:percentNewSessions'],
                'prints' => $prints,
                'printsTrend' => $printsTrend,
                'redemptions' => $redemptions,
                'redemptionsTrend' => $redemptionsTrend,
                'merchants' => $merchants,
                'repeaters' => $repeaters,
                'repeats' => $repeats,
                'contests' => $contests,
                'applicants' => $applicants,
                'dateText' => $dateText,
                'marketText' => $marketText,
                'members' => $members,
                'newMembers' => $newMembers,
                'prevMembers' => $prevMembers,
                'memberChange' => $newMembers - $prevMembers,
                'memTrend' => $memTrend,
                'newFavorites' => $newFavorites,
                'prevFavorites' => $prevFavorites,
                'favoritesChange' => $newFavorites - $prevFavorites,
                'favTrend' => $favTrend,
                'newShares' => $newShares,
                'prevShares' => $prevShares,
                'sharesChange' => $newShares - $prevShares,
                'shareTrend' => $shareTrend,
                'custStart' => $custStart,
                'custEnd' => $custEnd,
                'weekTrafficChange' => $weekTraffic['ga:sessions'] - $prevWeekTraffic['ga:sessions'],
                'monthTrafficChange' => $monthTraffic['ga:sessions'] - $prevMonthTraffic['ga:sessions'],
                'prevWeekTraffic' => $prevWeekTraffic['ga:sessions'],
                'prevMonthTraffic' => $prevMonthTraffic['ga:sessions'],
                'favorites' => $this->userRepository->getFavoritesCount(null, date('Y-m-d', strtotime('-3 months'))),
                'shares' => $this->userRepository->getSharesCount(null, date('Y-m-d', strtotime('-3 months'))),
                'trendVisits' => $trendVisits,
                'trendLocationViews' => $trendLocationViews,
                'trendVisitsChange' => $avgPrevVisits ? ($avgVisits - $avgPrevVisits) / $avgPrevVisits * 100 : 0,
                'trendLocationViewsChange' => $avgPrevLocationViews ? ($avgLocationViews - $avgPrevLocationViews) / $avgPrevLocationViews * 100 : 0,
                'memberMailStats' => $memberMailStats
            );

            Cache::put('dash_cache-'.Input::get('start').'-'.Input::get('market').'-'.Input::get('cust-start').'-'.Input::get('cust-end'), $cache, 360);
        }

        $vw->error = $cache['error'];
        $vw->visits = $cache['visits'];
        $vw->visitsTrend = $cache['visitsTrend'];
        $vw->users = $cache['users'];
        $vw->avgDuration = $cache['avgDuration'];
        $vw->newSessions = $cache['newSessions'];
        $vw->prints = $cache['prints'];
        $vw->printsTrend = $cache['printsTrend'];
        $vw->redemptions = $cache['redemptions'];
        $vw->redemptionsTrend = $cache['redemptionsTrend'];
        $vw->merchants = $cache['merchants'];
        $vw->repeaters = $cache['repeaters'];
        $vw->repeats = $cache['repeats'];
        $vw->contests = $cache['contests'];
        $vw->applicants = $cache['applicants'];
        $vw->dateText = $cache['dateText'];
        $vw->marketText = $cache['marketText'];
        $vw->members = $cache['members'];
        $vw->newMembers = $cache['newMembers'];
        $vw->prevMembers = $cache['prevMembers'];
        $vw->memberChange = $cache['memberChange'];
        $vw->memTrend = $cache['memTrend'];
        $vw->newFavorites = $cache['newFavorites'];
        $vw->prevFavorites = $cache['prevFavorites'];
        $vw->favoritesChange = $cache['favoritesChange'];
        $vw->favTrend = $cache['favTrend'];
        $vw->newShares = $cache['newShares'];
        $vw->prevShares = $cache['prevShares'];
        $vw->sharesChange = $cache['sharesChange'];
        $vw->shareTrend = $cache['shareTrend'];
        $vw->custStart = $cache['custStart'];
        $vw->custEnd = $cache['custEnd'];
        $vw->weekTrafficChange = $cache['weekTrafficChange'];
        $vw->monthTrafficChange = $cache['monthTrafficChange'];
        $vw->prevWeekTraffic = $cache['prevWeekTraffic'];
        $vw->prevMonthTraffic = $cache['prevMonthTraffic'];
        $vw->favorites = $cache['favorites'];
        $vw->shares = $cache['shares'];
        $vw->trendVisits = $cache['trendVisits'];
        $vw->trendLocationViews = $cache['trendLocationViews'];
        $vw->trendVisitsChange = $cache['trendVisitsChange'];
        $vw->trendLocationViewsChange = $cache['trendLocationViewsChange'];
        $vw->memberMailStats = isset($cache['memberMailStats']) ? $cache['memberMailStats'] : array('delivered' => 'Results Not Cached', 'opens' => 'Results Not Cached', 'clicks' => 'Results Not Cached');

        return $vw;
    }

    public function getFileupload()
    {
        $code = array();
        $vw = View::make('admin.sales.fileupload')->with('code', implode(' ', $code));
        $vw->title = "FTP Upload | SaveOn";
        $vw->description = "Merchants can upload files to SaveOn to facilitate the process of reaching consumers with coupons and deals.";
        $vw->primary_nav = "sales";
        $vw->secondary_nav = "fileupload";

        return $vw;

    }

    public function postFileupload()
    {
        $awsAccessKey = 'AKIAI6NWHQYFADWZVYLQ';
        $awsSecretKey = '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';
        $bucketName = 'saveoneverything_artwork';
        $month = date('M');
        $errors = array();
        $uploaded = array();
        $assets = array();
        
        /*
        inputName
        inputClientContact
        inputPhone
        inputAdvertiser
        inputCity
        inputMonth
        inputSalesRep
        inputEmail
        fileattachment1
        fileattachment2
        fileattachment3
        fileattachment4
        */
        $inputData = Input::all();
        if(empty($inputData['inputCity']))
        {
            $errors["inputCity"] = "Market (City) required.";
        }
        else
        {
            switch($inputData['inputCity'])
            {
                case 'Chicago':
                    $sCity = 'CHI';
                    $aEmailRecipient[] = 'lhoffman@saveoneverything.com';
                    $aEmailRecipient[] = 'art.chicago@saveoneverything.com';
                    break;
                case 'Detroit':
                    $sCity = 'DET';
                    $aEmailRecipient[] = 'kschuck@saveoneverything.com';
                    $aEmailRecipient[] = 'art.detroit@saveoneverything.com';
                    break;
                case 'Minneapolis':
                    $sCity = 'MN';
                    $aEmailRecipient[] = 'lgutzke@saveoneverything.com';
                    $aEmailRecipient[] = 'art.twincities@saveoneverything.com';
                    break;
                case 'Grand Rapids':
                    $sCity = 'GR';
                    $aEmailRecipient[] = 'art.grandrapids@saveon.com';
                    break;
                case 'Lansing':
                    $sCity = 'LAN';
                    $aEmailRecipient[] = 'art.lansing@saveon.com';
                    break;
                case 'Lakeshore':
                    $sCity = 'LK';
                    $aEmailRecipient[] = 'art.lakeshore@saveon.com';
                    break;
                case 'Kalamazoo':
                    $sCity = 'KZ';
                    $aEmailRecipient[] = 'art.kalamazoo@saveon.com';
                    break;
                case 'Toledo':
                    $sCity = 'TOL';
                    $aEmailRecipient[] = 'art.toledo@saveon.com';
                    break;
                case 'Print.MI.GR':
                    $sCity = 'P.MI.GR';
                    $aEmailRecipient[] = 'printfiles@saveon.com';
                    break;
                case 'Print.MI.LK':
                    $sCity = 'P.MI.LK';
                    $aEmailRecipient[] = 'printfiles@saveon.com';
                    break;
                case 'Print.MI.KZ':
                    $sCity = 'P.MI.KZ';
                    $aEmailRecipient[] = 'printfiles@saveon.com';
                    break;
            }
        }

        if(empty($inputData['inputEmail']))
        {
            $errors["inputEmail"] = "Email required.";
        }

        if(isset($sCity) && empty($errors))
        {
            if (Input::hasFile('fileattachment1')) 
            {
                $temp_file = Input::file('fileattachment1')->getRealPath();
                $asset = $month."/".time()."-".Input::file('fileattachment1')->getClientOriginalName();

                $art = new SOE\DB\Artwork;
                $art->name = $inputData["inputName"];
                $art->client_contact = $inputData["inputClientContact"];
                $art->phone = $inputData["inputPhone"];
                $art->advertiser = $inputData["inputAdvertiser"];
                $art->city = $inputData["inputCity"];
                $art->month = $inputData["inputMonth"];
                $art->sales_rep = $inputData["inputSalesRep"];
                $art->email = $inputData["inputEmail"];
                $art->filename = Input::file('fileattachment1')->getClientOriginalName();
                $art->s3_link = "http://s3.amazonaws.com/saveoneverything_artwork/".$asset;

                $s3 = new S3($awsAccessKey, $awsSecretKey);
                // Put our file (also with public read access)
                if ($s3->putObjectFile($temp_file, $bucketName, $asset, S3::ACL_PUBLIC_READ)) 
                {
                    $uploaded[] = array("message" => Input::file('fileattachment1')->getClientOriginalName()." was uploaded successfully!", "name" => Input::file('fileattachment1')->getClientOriginalName(), "status" => "success");
                    $art->status = "success";
                    
                } 
                else 
                {
                    $uploaded[] = array("message" => "There was an error uploading ".Input::file('fileattachment1')->getClientOriginalName(), "name" => Input::file('fileattachment1')->getClientOriginalName(), "status" => "error");
                    $art->status = "error";
                }

                $art->save();
            }   
            if(count($uploaded) == 0)
            {
                $errors["no_files"] = "At least one file is required.";
            }   
        }

        if(isset($aEmailRecipient) && empty($errors))
        {
            $data = array(
                'input_data' => $inputData,
                'uploaded' => $uploaded,
                'link' => "http://s3.amazonaws.com/saveoneverything_artwork/".$asset
            );

            Mail::send('emails.ftpupload', $data, function($message) use($inputData, $aEmailRecipient)
            {
                $message->to($inputData['inputEmail'])->subject('FTP Upload');
                foreach($aEmailRecipient as $recip)
                {
                    $message->bcc($recip);
                }
                $message->bcc('wfobbs@saveoneverything.com');
                $message->bcc('cbeery@saveoneverything.com');
            });
        }

        $vw = View::make('admin.sales.fileupload');
        $vw->uploaded = $uploaded;
        $vw->error_msgs = $errors;
        $vw->primary_nav = "sales";
        $vw->secondary_nav = "fileupload";
        return $vw;
    }

    public function getUserLtv()
    {
        $code = array();
        $code[] = View::make('admin.sales.reports.jscode.user-ltv');
        $vw = View::make('admin.sales.reports.user-ltv')->with('code', implode(' ', $code));
        $vw->primary_nav = 'reports';
        $vw->secondary_nav = 'user_ltv';
        $data = Session::has('data') ? Session::get('data') : $this->userRepository->getLtvReport();
        $vw->members = $data['members'];
        $stats = $data['stats'];
        $vw->stats = $stats;
        $vw->lastpage = (ceil($stats[0]->total_members / 10)-1) < 0 ? 0 : (ceil($stats[0]->total_members / 10)-1);

        return $vw;
    }

    public function postUserLtv()
    {
        $data = $this->userRepository->getLtvReport(
            Input::get('start', null),
            Input::get('end', null),
            Input::get('sortBy', null), 
            Input::get('sortDir', null),
            Input::get('page', 0),
            Input::get('limit', 10)
        );

        /*$code = array();
        //$code[] = View::make('admin.sales.jscode.leads-assign');
        $vw = View::make('admin.sales.reports.user-ltv')->with('code', implode(' ', $code));
        $vw->primary_nav = 'reports';
        $vw->secondary_nav = 'user_ltv';
        $vw->members = $users;*/
        return Redirect::to('/user-ltv')->with('data', $data)->withInput();

        return $vw;
    }

    public function getLeads()
    {
        $code = array();
        $code[] = View::make('admin.sales.jscode.leads-assign');
        $vw = View::make('admin.sales.leads-assign')->with('code', implode(' ', $code));
        $vw->primary_nav = 'leads';
        $vw->secondary_nav = 'assign';

        return $vw;
    }

    public function getLeadReport()
    {
        $code = array();
        $code[] = View::make('admin.sales.jscode.lead-report');
        $vw = View::make('admin.sales.lead-report')->with('code', implode(' ', $code));
        $vw->primary_nav = 'sales';
        $vw->secondary_nav = 'leads';

        return $vw;
    }

    public function getRevenueReport()
    {
        $code = array();
        $code[] = View::make('admin.sales.jscode.revenue');
        $vw = View::make('admin.sales.revenue')->with('code', implode(' ', $code));
        $vw->primary_nav = 'sales';
        $vw->secondary_nav = 'revenue';

        $custStart = '';
        $custEnd = '';
        $range = Input::get('date-range');
        switch ($range) {
            case 'today':
                $start = date('Y-m-d 00:00:00');
                $end = date('Y-m-d 23:59:59');
                $dateText = 'Today';
                break;
            case 'yesterday':
                $start = date('Y-m-d 00:00:00', strtotime('-1 days'));
                $end = date('Y-m-d 00:00:00');
                $dateText = 'Yesterday';
                break;
            case 'this-week':
                $start = date('Y-m-d 00:00:00', strtotime('last sunday', strtotime('tomorrow')));
                $end = date('Y-m-d 23:59:59');
                $dateText = 'This Week';
                break;
            case 'last-7':
                $start = date('Y-m-d 00:00:00', strtotime('-7 days'));
                $end = date('Y-m-d 23:59:59');
                $dateText = 'Last 7 Days';
                break;
            case 'last-3-months':
                $start = date('Y-m-d 00:00:00', strtotime('-3 months'));
                $end = date('Y-m-d 23:59:59');
                $dateText = 'Last 3 Months';
                break;
            case 'this-month':
                $start = date('Y-m-01 00:00:00');
                $end = date('Y-m-d H:i:s');
                $dateText = 'This Month';
                break;
            case 'last-month':
                $start = date('Y-m-01 00:00:00', strtotime('-1 months'));
                $end = date('Y-m-t 23:59:59', strtotime('-1 months'));
                $dateText = 'Last Month';
                break;
            case 'this-year':
                $start = date('Y-01-01 00:00:00');
                $end = date('Y-m-d H:i:s');
                $dateText = 'This Year';
                break;
            case 'custom':
                $start = date('Y-m-d H:i:s', strtotime(Input::get('cust-start')));
                $end = date('Y-m-d H:i:s', strtotime(Input::get('cust-end')));
                $custStart = Input::get('cust-start');
                $custEnd = Input::get('cust-end');
                $dateText = 'Custom Range';
                break;
            default:
                $start = date('Y-m-d 00:00:00', strtotime('-3 months'));
                $end = date('Y-m-d H:i:s');
                $dateText = 'Last 3 Months';
                break;
        }
        $vw->dateText = $dateText;

        $market = Input::get('market', null);
        switch ($market) {
            case 'michigan':
                $marketText = "Detroit Market";
                $apiMarket = 'detroit';
                $state = 'MI';
                break;
            case 'illinois':
                $marketText = "Chicago Market";
                $apiMarket = 'chicago';
                $state = 'IL';
                break;
            case 'minnesota':
                $marketText = "Minneapolis Market";
                $apiMarket = 'minneapolis';
                $state = 'MN';
                break;
            default:
                $marketText = "All Markets";
                $apiMarket = '';
                $state = '';
                break;
        }

        $data = array(
            'start' => $start, 
            'end' => $end
        );
        if($apiMarket != '')
            $data['market'] = $apiMarket;

        $api = \App::make('NetLMSAPIInterface');
        $response = $api->curl(
            'GET', 
            'report', 
            null,
            $data, 
            'revenue-report'
        );
        $leads = $response['response'];
        $usedTotal = 0;
        $newTotal = 0;
        $leadTotal = 0;
        $leadPurchaseTotal = 0;
        foreach($leads as $lead)
        {
            if($lead->vehicle_status == 'used')
                $usedTotal += $lead->selling_price - ($lead->selling_price * $lead->split / 100);
            else
                $newTotal += $lead->selling_price - ($lead->selling_price * $lead->split / 100);
            $leadTotal += $lead->selling_price - ($lead->selling_price * $lead->split / 100);
            $leadPurchaseTotal += $lead->purchase_price;
        }

        $vw->marketText = $marketText;
        $vw->usedTotal = $usedTotal;
        $vw->newTotal = $newTotal;
        $vw->leadTotal = $leadTotal;
        $vw->leadPurchaseTotal = $leadPurchaseTotal;
        $vw->custStart = $custStart;
        $vw->custEnd = $custEnd;

        return $vw;
    }

    public function getGallery()
    {
        $code = array();
        $code[] = View::make('admin.sales.jscode.gallery');
        $code[] = View::make('home.jscode.masonry');
        $vw = View::make('admin.sales.gallery')->with('code', implode(' ', $code));
        $vw->primary_nav = "sales";
        $vw->secondary_nav = "gallery";
        $vw->viewing = 0;
        $categories = SOE\DB\AssetCategory::where('parent_id', '=', 0)->orderBy('name')->get();
        $vw->categories = $categories;
        return $vw;
    }

    public function getIntake1()
    {
        $code = array();
        $vw = View::make('admin.sales.intake1')->with('code', implode(' ', $code));
        $vw->title = "Save On - Download and Print Free Local and Retail Coupons";
        $vw->description = "Save On";
        $vw->primary_nav = "sales";
        $vw->secondary_nav = "intake";
        return $vw;
    }

    public function postIntake2()
    {
        return Redirect::to('/intake2');
    }

    public function getIntake2()
    {
        $code = array();
        $vw = View::make('admin.sales.intake2')->with('code', implode(' ', $code));
        $vw->title = "Save On - Download and Print Free Local and Retail Coupons";
        $vw->description = "Save On";
        $vw->primary_nav = "sales";
        $vw->secondary_nav = "intake";
        return $vw;
    }

    public function postIntake3()
    {
        return Redirect::to('/intake3');
    }

    public function getIntake3()
    {
        $code = array();
        $vw = View::make('admin.sales.intake3')->with('code', implode(' ', $code));
        $vw->title = "Save On - Download and Print Free Local and Retail Coupons";
        $vw->description = "Save On";
        $vw->primary_nav = "sales";
        $vw->secondary_nav = "intake";
        return $vw;
    }

    public function postIntake4()
    {
        return Redirect::to('/intake4');
    }

    public function getIntake4()
    {
        $code = array();
        $vw = View::make('admin.sales.intake4')->with('code', implode(' ', $code));
        $vw->title = "Save On - Download and Print Free Local and Retail Coupons";
        $vw->description = "Save On";
        $vw->primary_nav = "sales";
        $vw->secondary_nav = "intake";
        return $vw;
    }

    public function postIntake5()
    {
        return Redirect::to('/intake5');
    }

    public function getIntake5()
    {
        $code = array();
        $code[] = View::make('admin.sales.jscode.intake5');
        $vw = View::make('admin.sales.intake5')->with('code', implode(' ', $code));
        $vw->title = "Save On - Download and Print Free Local and Retail Coupons";
        $vw->description = "Save On";
        $vw->primary_nav = "sales";
        $vw->secondary_nav = "intake";
        return $vw;
    }

    public function postIntakecomplete()
    {
        return Redirect::to('/intakecomplete');
    }

    public function getIntakecomplete()
    {
        $code = array();
        $vw = View::make('admin.sales.intakecomplete')->with('code', implode(' ', $code));
        $vw->title = "Save On - Download and Print Free Local and Retail Coupons";
        $vw->description = "Save On";
        $vw->primary_nav = "sales";
        $vw->secondary_nav = "intake";
        return $vw;
    }

    public function getForms()
    {
        $code = array();
        $code[] = View::make('admin.sales.jscode.forms');
        $vw = View::make('admin.sales.forms')->with('code', implode(' ', $code));
        $vw->primary_nav = "sales";
        $vw->secondary_nav = "forms";
        return $vw;
    }

    public function getTrainingwelcome()
    {
        $code = array();
        $vw = View::make('admin.sales.trainingwelcome')->with('code', implode(' ', $code));
        $vw->title = "Save On - Download and Print Free Local and Retail Coupons";
        $vw->description = "Save On";
        $vw->primary_nav = "sales";
        $vw->secondary_nav = "training";
        return $vw;
    }

    public function getTrainingholder()
    {
        $code = array();
        $vw = View::make('admin.sales.training')->with('code', implode(' ', $code));
        $vw->title = "Save On - Download and Print Free Local and Retail Coupons";
        $vw->description = "Save On";
        $vw->primary_nav = "sales";
        $vw->secondary_nav = "training";
        return $vw;
    }

    public function getTrainingVideos()
    {
        $user = Auth::User();
        $code = array();
        $code[] = View::make('admin.sales.jscode.training');
        $vw = View::make('admin.sales.training-videos')->with('code', implode(' ', $code));
        $vw->title = "Save On - Download and Print Free Local and Retail Coupons";
        $vw->description = "Save On";
        $vw->primary_nav = "sales";
        $vw->secondary_nav = "training";
        if($_ENV['APP_MODE'])
        {
            $user_mode = strtolower($_ENV['APP_MODE']);
        }
        elseif (isset($_SERVER['PARAM2']))
        {
            $user_mode = strtolower($_SERVER['PARAM2']);
        }
        $vw->user_mode = $user_mode;
        if ($user_mode == 'content')
        {
            $vw->primary_nav = "dashboard";
        } else {
            $vw->primary_nav = $user_mode;
        }
        //$vw->primary_nav = "sales";
        $vw->secondary_nav = "training";
        $vw->user_type = $user->type;
        $vw->availableSections = $this->trainingSections->listSections()['objects'];
        $vw->sections = $this->trainingSections->getUserSections($user);
        $vw->isAdmin = $this->userRepository->checkType($user, 'admin');
        return $vw;
    }

    public function getTrainingGuides()
    {
        $user = Auth::User();
        $code = array();
        $code[] = View::make('admin.sales.jscode.training');
        $vw = View::make('admin.sales.training-guides')->with('code', implode(' ', $code));
        $vw->title = "Save On - Download and Print Free Local and Retail Coupons";
        $vw->description = "Save On";
        $vw->primary_nav = "sales";
        $vw->secondary_nav = "training";

        if($_ENV['APP_MODE'])
        {
            $user_mode = strtolower($_ENV['APP_MODE']);
        }
        elseif (isset($_SERVER['PARAM2']))
        {
            $user_mode = strtolower($_SERVER['PARAM2']);
        }
        $vw->user_mode = $user_mode;
        if ($user_mode == 'content')
        {
            $vw->primary_nav = "dashboard";
        } else {
            $vw->primary_nav = $user_mode;
        }
        //$vw->primary_nav = "sales";
        $vw->secondary_nav = "training";
        $vw->user_type = $user->type;
        $vw->availableSections = $this->trainingSections->listSections()['objects'];
        $vw->sections = $this->trainingSections->getUserSections($user);
        $vw->isAdmin = $this->userRepository->checkType($user, 'admin');
        return $vw;
    }

    public function getLogin()
    {
        $code = array();
        $code[] = View::make('admin.master.jscode.login');
        $vw = View::make('admin.master.templates.login')->with('code', implode(' ', $code));
        $vw->navbar = false;
        $vw->sidebar = false;
        $vw->title = "Save On";
        $vw->description = "Save On";
        return $vw;
    }

    public function postLogin()
    {
        $email = Input::get('signInEmail');
        $password = Input::get('signInPassword');
        if(Auth::attempt(array('email'=>$email,'password'=>$password)))
        {
            $user = Auth::User();
            if($this->userRepository->checkType($user, 'sales'))
                return Redirect::to('/');
            else
                return Redirect::to('/login')->with(array('signInError' => true));
        }
        else
        {
            return Redirect::to('/login')->with(array('signInError' => true));
        }
    }

    public function getLogout()
    {
        Auth::logout();
        return Redirect::to('/login');
    }

    public function getMerchantList()
    {
        $code = array();
        $code[] = View::make('admin.sales.jscode.merchant-list');
        $vw = View::make('admin.sales.merchant-list')->with('code', implode(' ', $code));
        $vw->primary_nav = "sales";
        $vw->secondary_nav = "merchants";

        $range = Input::get('date-range');
        switch ($range) {
            case 'today':
                $start = date('Y-m-d 00:00:00');
                $end = date('Y-m-d 23:59:59');
                break;
            case 'yesterday':
                $start = date('Y-m-d 00:00:00', strtotime('-1 days'));
                $end = date('Y-m-d 00:00:00');
                break;
            case 'this-week':
                $start = date('Y-m-d 00:00:00', strtotime('last sunday', strtotime('tomorrow')));
                $end = date('Y-m-d 23:59:59');
                break;
            case 'last-7':
                $start = date('Y-m-d 00:00:00', strtotime('-7 days'));
                $end = date('Y-m-d 23:59:59');
                break;
            case 'this-month':
                $start = date('Y-m-01 00:00:00');
                $end = date('Y-m-d 23:59:59');
                break;
            case 'last-month':
                $start = date('Y-m-01 00:00:00', strtotime('-1 months'));
                $end = date('Y-m-t 23:59:59', strtotime('-1 months'));
                break;
            case 'custom':
                $start = date('Y-m-d 00:00:00', strtotime(Input::get('custom-start')));
                $end = date('Y-m-d 23:59:59', strtotime(Input::get('custom-end')));
                break;
            default:
                $range = 'last-3-months';
                $start = date('Y-m-d 00:00:00', strtotime('-3 months'));
                $end = date('Y-m-d 23:59:59');
                break;
        }

        $vw->reps = $this->userRepository->getSalesReps();

        $page = Input::get('page', 0);
        $limit = Input::get('limit', 20);
        $order = Input::get('order', 'views');
        $direction = Input::get('direction', 'desc');
        $options = Input::get('options', null);
        $filter = Input::get('filter', null);
        $category = Input::get('category', null);
        $subcategory = Input::get('subcategory', null);
        $rep = Input::get('rep', null);
        $market = Input::get('market', null);
        $rowTitle = '';
        if(!$options)
        {
            $objects = $this->franchises->searchReport(
                $filter,
                $category,
                $subcategory,
                $rep,
                $market,
                $start,
                $end,
                $page,
                $limit,
                $order,
                $direction
            );
            $rowTitle = 'Merchant Name';
        }
        else if($options == 'group-cat')
        {
            $objects = $this->franchises->categorySearchReport(
                $category, 
                $rep, 
                $market,
                $start,
                $end,
                $page,
                $limit,
                $order,
                $direction
            );
            $rowTitle = 'Category Name';
        }
        $filters = '&p='.$page.'&l='.$limit.'&o='.$order.'&d='.$direction.'&op='.$options.'&f='.$filter.'&c='.$category.'&s='.$subcategory.'&r='.$rep.'&m='.$market;
        if($range == 'custom')
        {
            $filters .= '&cs='.Input::get('custom-start').'&ce='.Input::get('custom-end');
        }
        $vw->filters = $filters;
        $vw->rowTitle = $rowTitle;
        $vw->objects = $objects;
        $vw->dateRange = $range;
        $vw->page = $page;
        $vw->limit = $limit;
        $vw->order = $order;
        $vw->direction = $direction;
        $vw->options = $options;
        $vw->filter = $filter;
        $vw->cat = $category;
        $vw->subcat = $subcategory;
        $vw->salesRep = $rep;
        $vw->market = $market;
        $vw->customStart = Input::get('custom-start', '');
        $vw->customEnd = Input::get('custom-end', '');
        $vw->lastPage = $objects['stats']['returned'] < $limit ? $page : ceil($objects['stats']['total'] / $limit);
        $categories = $this->categories->getByParentId(0);
        $vw->categories = $categories;
        if($category)
            $subcategories = $this->categories->getByParentId($category);
        else
            $subcategories = array('objects' => array());
        $vw->subcategories = $subcategories;

        return $vw;
    }

    public function getMerchantListPdf()
    {
        $range = Input::get('date-range');
        switch ($range) {
            case 'today':
                $start = date('Y-m-d 00:00:00');
                $end = date('Y-m-d 23:59:59');
                break;
            case 'yesterday':
                $start = date('Y-m-d 00:00:00', strtotime('-1 days'));
                $end = date('Y-m-d 00:00:00');
                break;
            case 'this-week':
                $start = date('Y-m-d 00:00:00', strtotime('last sunday', strtotime('tomorrow')));
                $end = date('Y-m-d 23:59:59');
                break;
            case 'last-7':
                $start = date('Y-m-d 00:00:00', strtotime('-7 days'));
                $end = date('Y-m-d 23:59:59');
                break;
            case 'this-month':
                $start = date('Y-m-01 00:00:00');
                $end = date('Y-m-d 23:59:59');
                break;
            case 'last-month':
                $start = date('Y-m-01 00:00:00', strtotime('-1 months'));
                $end = date('Y-m-t 23:59:59', strtotime('-1 months'));
                break;
            case 'custom':
                $start = date('Y-m-d 00:00:00', strtotime(Input::get('custom-start')));
                $end = date('Y-m-d 23:59:59', strtotime(Input::get('custom-end')));
                break;
            default:
                $start = date('Y-m-d 00:00:00', strtotime('-3 months'));
                $end = date('Y-m-d 23:59:59');
                break;
        }

        $page = Input::get('page', 0);
        $limit = Input::get('limit', 20);
        $order = Input::get('order', 'views');
        $direction = Input::get('direction', 'desc');
        $options = Input::get('options', null);
        $filter = Input::get('filter', null);
        $category = Input::get('category', null);
        $subcategory = Input::get('subcategory', null);
        $rep = Input::get('rep', null);
        $market = Input::get('market', null);
        $rowTitle = '';
        if(!$options)
        {
            $objects = $this->franchises->searchReport(
                $filter,
                $category,
                $subcategory,
                $rep,
                $market,
                $start,
                $end,
                $page,
                $limit,
                $order,
                $direction
            );
            $rowTitle = 'Merchant Name';
        }
        else if($options == 'group-cat')
        {
            $objects = $this->franchises->categorySearchReport(
                $category, 
                $rep, 
                $market,
                $start,
                $end,
                $page,
                $limit,
                $order,
                $direction
            );
            $rowTitle = 'Category Name';
        }

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Add a page
        $pdf->AddPage();

        // Set some content to print
        //$pdf->Image(URL::asset('/img/logo-small.png'), 10, 15, '', '', '', false, 500, '',false, false, 0);
        $pdf->Image(URL::asset('/img/green-border.jpg'), 8, 37,  '', '', '', false, 500, '',false, false, 0);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Text(10, 40, 'Report For: '.date("m/d/Y", strtotime($start)). ' - ' .date("m/d/Y", strtotime($end)));
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFontSize(8);
        $pdf->Text(10, 65, $rowTitle);
        $pdf->Text(75, 65, 'Views');
        $pdf->Text(100, 65, 'Prints');
        $pdf->Text(125, 65, 'Contest Apps');
        $pdf->Text(150, 65, 'Current Offers');
        $pdf->Text(175, 65, 'Leads');
        $yCoord = 70;
        foreach($objects['objects'] as $object)
        {
            if(Input::get('options') == 'group-cat')
            {
                $pdf->Text(10, $yCoord, Input::has('category') ? $object->subcategory_name : $object->category_name);
                $pdf->Text(75, $yCoord, $object->views);
                $pdf->Text(100, $yCoord, $object->prints);
                $pdf->Text(125, $yCoord, $object->apps ? $object->apps : 0);
                $pdf->Text(150, $yCoord, $object->offers);
                $pdf->Text(175, $yCoord, 'N/A');
            }
            else
            {
                $pdf->Text(10, $yCoord, strlen($object->merchant_display) > 40 ? substr($object->merchant_display, 0, 37).'...' : $object->merchant_display);
                $pdf->Text(75, $yCoord, $object->views);
                $pdf->Text(100, $yCoord, $object->prints);
                $pdf->Text(125, $yCoord, $object->apps ? $object->apps : 0);
                $pdf->Text(150, $yCoord, $object->offers);
                $pdf->Text(175, $yCoord, $object->quotes ? $object->quotes : 0);
            }
            if($yCoord >= 270)
            {
                $pdf->AddPage();
                $pdf->Text(10, 25, $rowTitle);
                $pdf->Text(75, 25, 'Views');
                $pdf->Text(100, 25, 'Prints');
                $pdf->Text(125, 25, 'Contest Apps');
                $pdf->Text(150, 25, 'Current Offers');
                $pdf->Text(175, 25, 'Leads');
                $yCoord = 25;
            }
            $yCoord += 5;
        }
        
        $footerOffset = 0;
        if($yCoord >= 260)
        {
            $pdf->AddPage();
            $yCoord = 0;
            $footerOffset = 10;
        }
        

        $pdf->SetTextColor(255,255,255);
        $pdf->Image(URL::asset('/img/green-border.jpg'), 8, $yCoord+$footerOffset,'', '', '','', '', false, 500, '',false, false, 0, false, false, true);
        $pdf->Text(75, $yCoord+$footerOffset+7, "Copyright 2014, SaveOn. ".($yCoord+$footerOffset)." All rights reserved.");

        $pdf->Output('MerchantListReport.pdf', 'D');
    }

    public function getMerchantReport()
    {
        $code = array();
        $code[] = View::make('admin.sales.jscode.merchants');
        $vw = View::make('admin.sales.merchants')->with('code', implode(' ', $code));
        $vw->primary_nav = "sales";
        $vw->secondary_nav = "merchants";
        $franch = $this->franchises->find(Input::get('franchise'));
        if(!$franch)
            return Redirect::to('/merchant-list');

        $range = Input::get('date-range');
        switch ($range) {
            case 'today':
                $start = date('Y-m-d 00:00:00');
                $end = date('Y-m-d 23:59:59');
                $dateText = 'Today';
                break;
            case 'yesterday':
                $start = date('Y-m-d 00:00:00', strtotime('-1 days'));
                $end = date('Y-m-d 00:00:00');
                $dateText = 'Yesterday';
                break;
            case 'this-week':
                $start = date('Y-m-d 00:00:00', strtotime('last sunday', strtotime('tomorrow')));
                $end = date('Y-m-d 23:59:59');
                $dateText = 'This Week';
                break;
            case 'last-7':
                $start = date('Y-m-d 00:00:00', strtotime('-7 days'));
                $end = date('Y-m-d 23:59:59');
                $dateText = 'Last 7 Days';
                break;
            case 'this-month':
                $start = date('Y-m-01 00:00:00');
                $end = date('Y-m-d 23:59:59');
                $dateText = 'This Month';
                break;
            case 'last-month':
                $start = date('Y-m-01 00:00:00', strtotime('-1 months'));
                $end = date('Y-m-t 23:59:59', strtotime('-1 months'));
                $dateText = 'Last Month';
                break;
            case 'custom':
                $start = date('Y-m-d 00:00:00', strtotime(Input::get('cs')));
                $end = date('Y-m-d 23:59:59', strtotime(Input::get('ce')));
                $dateText = Input::get('cs').' - '.Input::get('ce');
                break;
            default:
                $start = date('Y-m-d 00:00:00', strtotime('-3 months'));
                $end = date('Y-m-d 23:59:59');
                $dateText = 'Last 3 Months';
                break;
        }

        $franchise = $this->franchises->franchiseReport(Input::get('franchise'), $start, $end, Input::get('offers', 10));        

        foreach ($franchise->contests as $contest)
        {
            //print_r($contest);
            //exit;
            $aWin = array();
            $contestData = $this->contestRepository->with('merchant', 'winners', 'applications')
                        ->find($contest->id);

            foreach($contestData->winners as $winner)
            {
                $aWin[$winner->user_id] = array(
                    'first' => $winner->first_name, 
                    'last' => $winner->last_name, 
                    'city' => $winner->city, 
                    'state' => $winner->state
                );
            }
            $aContest = array(
                'name' => $contest->display_name,
                'banner' => $contest->banner,
                'text' => $contest->follow_up_text
            );
            
            $data = array(
                'winners' => $aWin, 
                'contest' => $aContest, 
                'merchant' => $contest->merchant->toArray()
            );
            if($contest->follow_up_type != '')
            {
                $follow = $contest->followUp;
                if($follow)
                {
                    $entity = $follow->entities()->first();
                    $location = $this->locations->find($entity->location_id);
                    $data['entity'] = $entity->toArray();
                    $data['location'] = $location->toArray();
                }
            }
            $contest->data = $data;
        }

        /*$leadData = array();
        if($franchise->netlms_id)
        {
            $data = array(
                'start' => $start, 
                'end' => $end
            );
            $api = \App::make('NetLMSAPIInterface');
            $response = $api->curl(
                'GET', 
                'report', 
                $franchise->netlms_id, 
                $data, 
                'leads'
            );
            $leads = $response['response'];
            foreach($leads as $category => $data)
            {
                $leadData[] = array('category' => $category, 'leads' => $data);
            }
        }*/
        //print_r($leadData);exit;
        //$vw->leadData = $leadData;
        $vw->page = Input::get('p', 0);
        $vw->limit = Input::get('l', 12);
        $vw->order = Input::get('o', 'views');
        $vw->direction = Input::get('d', 'desc');
        $vw->options = Input::get('op', null);
        $vw->filter = Input::get('f', null);
        $vw->category = Input::get('c', null);
        $vw->subcategory = Input::get('s', null);
        $vw->rep = Input::get('r', null);
        $vw->market = Input::get('m', null);
        $vw->customStart = Input::get('cs');
        $vw->customEnd = Input::get('ce');
        $vw->range = $range;
        $vw->franchise = $franchise;
        $vw->merchant = $this->merchants->find($franchise->merchant_id);
        $vw->dateText = $dateText;
        $vw->start = $start;
        $vw->end = $end;

        return $vw;
    }

    public function getMerchantPdf()
    {
        $range = Input::get('date-range');
        switch ($range) {
            case 'today':
                $start = date('Y-m-d 00:00:00');
                $end = date('Y-m-d 23:59:59');
                $dateText = 'Today';
                break;
            case 'yesterday':
                $start = date('Y-m-d 00:00:00', strtotime('-1 days'));
                $end = date('Y-m-d 00:00:00');
                $dateText = 'Yesterday';
                break;
            case 'this-week':
                $start = date('Y-m-d 00:00:00', strtotime('last sunday', strtotime('tomorrow')));
                $end = date('Y-m-d 23:59:59');
                $dateText = 'This Week';
                break;
            case 'last-7':
                $start = date('Y-m-d 00:00:00', strtotime('-7 days'));
                $end = date('Y-m-d 23:59:59');
                $dateText = 'Last 7 Days';
                break;
            case 'this-month':
                $start = date('Y-m-01 00:00:00');
                $end = date('Y-m-d 23:59:59');
                $dateText = 'This Month';
                break;
            case 'last-month':
                $start = date('Y-m-01 00:00:00', strtotime('-1 months'));
                $end = date('Y-m-t 23:59:59', strtotime('-1 months'));
                $dateText = 'Last Month';
                break;
            case 'custom':
                $start = date('Y-m-d 00:00:00', strtotime(Input::get('custom-start')));
                $end = date('Y-m-d 23:59:59', strtotime(Input::get('custom-end')));
                $dateText = Input::get('custom-start').' - '.Input::get('custom-end');
                break;
            default:
                $start = date('Y-m-d 00:00:00', strtotime('-3 months'));
                $end = date('Y-m-d 23:59:59');
                $dateText = 'Last 3 Months';
                break;
        }
        $franchise = $this->franchises->franchiseReport(Input::get('franchise'), $start, $end, Input::get('offers', 10));
        $merchant = $this->merchants->find($franchise->merchant_id);
        $logo = $this->assets->getLogo($merchant);
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Add a page
        $pdf->AddPage();

        // Set some content to print
        //$pdf->Image(URL::asset('/img/logo-small.png'), 10, 15, '', '', '', false, 500, '',false, false, 0);
        $pdf->Image(URL::asset('/img/green-border.jpg'), 8, 37,  '', '', '', false, 500, '',false, false, 0);
        $pdf->Image($logo->path, 150, 39, 22, 13.16, '', '', '', false, 300);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Text(10, 40, 'Statistics for: ');
        $pdf->Text(10, 45, $franchise->merchant->name);
        $pdf->SetFontSize(8);
        $pdf->Text(10, 50, 'Metrics taken: '.date("m/d/Y", strtotime($start)). ' - ' .date("m/d/Y", strtotime($end)));
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Text(75, 65, $dateText);
        /**************/
        $pdf->Text (10, 70, 'Merchant Views');
        $pdf->Text(75, 70, $franchise->current_views);
        //$pdf->Image(URL::asset('/img/thin-gray-line.jpg'), 8, 75);
        /**************/
        $pdf->Text(10, 75, 'Prints');
        $pdf->Text(75, 75, $franchise->current_prints);
        //$pdf->Image(URL::asset('/img/thin-gray-line.jpg'), 8, 85);
        /**************/
        $pdf->Text(10, 80, 'Shares');
        $pdf->Text(75, 80, $franchise->current_shares);
        $pdf->Image(URL::asset('/img/thin-gray-line.jpg'), 8, 85);
        /**************/
        $pdf->Text(10, 95, 'Offer Name');
        $pdf->Text(75, 95, 'Type');
        $pdf->Text(100, 95, 'Offer Views');
        $pdf->Text(120, 95, 'Prints');
        $pdf->Text(135, 95, 'Entries');
        $pdf->Text(150, 95, 'Starts');
        $pdf->Text(175, 95, 'Expires');
        $yCoord = 100;
        foreach($franchise->offers as $offer)
        {
            $offer['offer']->name = $offer['offer']->is_followup_for == 0 ? $offer['offer']->name : '** '.$offer['offer']->name;
            $pdf->Text(10, $yCoord, strlen($offer['offer']->name) > 40 ? substr($offer['offer']->name, 0, 37).'...' : $offer['offer']->name);
            $pdf->Text(75, $yCoord, $offer['offer']->is_dailydeal ? 'Daily Deal' : 'Offer');
            $pdf->Text(100, $yCoord, $offer['total_views']);
            $pdf->Text(120, $yCoord, $offer['total_prints']);
            $pdf->Text(135, $yCoord, '---');
            $pdf->Text(150, $yCoord, '---');
            $pdf->Text(175, $yCoord, date('m/d/Y', strtotime($offer['offer']->expires_at)));
            if($yCoord >= 270)
            {
                $pdf->AddPage();
                $pdf->Text(10, 25, 'Offer Name');
                $pdf->Text(75, 25, 'Type');
                $pdf->Text(100, 25, 'Offer Views');
                $pdf->Text(120, 25, 'Prints');
                $pdf->Text(135, 25, 'Entries');
                $pdf->Text(150, 25, 'Starts');
                $pdf->Text(175, 25, 'Expires');
                $yCoord = 25;
            }
            $yCoord += 5;
        }
        foreach($franchise->contests as $contest)
        {
            $pdf->Text(10, $yCoord, strlen($contest->display_name) > 40 ? substr($contest->display_name, 0, 37).'...' : $contest->display_name);
            $pdf->Text(75, $yCoord, 'Contest');
            $pdf->Text(100, $yCoord, '---');
            $pdf->Text(120, $yCoord, '---');
            $pdf->Text(135, $yCoord, $contest->apps);
            $pdf->Text(150, $yCoord, date('m/d/Y', strtotime($contest->starts_at)));
            $pdf->Text(175, $yCoord, date('m/d/Y', strtotime($contest->expires_at)));
            if($yCoord >= 270)
            {
                $pdf->AddPage();
                $pdf->Text(10, 25, 'Offer Name');
                $pdf->Text(75, 25, 'Type');
                $pdf->Text(100, 25, 'Offer Views');
                $pdf->Text(120, 25, 'Prints');
                $pdf->Text(135, 25, 'Entries');
                $pdf->Text(150, 25, 'Starts');
                $pdf->Text(175, 25, 'Expires');
                $yCoord = 25;
            }
            $yCoord += 5;
        }
        $yCoord += 5;
        if($yCoord >= 255)
        {
            $pdf->AddPage();
            $yCoord = 25;
        }

        $pdf->Text(10, $yCoord, '** - Denotes Follow Up Offer');
        $yCoord += 10;
        $pdf->Image(URL::asset('/img/thin-gray-line.jpg'), 8, $yCoord);
        $yCoord += 10;
        $pdf->Text(10, $yCoord, 'Location');
        $pdf->Text(100, $yCoord, 'Location Views');
        $pdf->Text(175, $yCoord, 'Prints');
        $yCoord += 5;
        foreach($franchise->locations as $location)
        {
            $pdf->Text(10, $yCoord, $location['name']);
            $pdf->Text(100, $yCoord, $location['views']);
            $pdf->Text(175, $yCoord, $location['prints']);
            if($yCoord >= 270)
            {
                $pdf->AddPage();
                $pdf->Text(10, 25, 'Location');
                $pdf->Text(100, 25, 'Location Views');
                $pdf->Text(175, 25, 'Prints');
                $yCoord = 25;
            }
            $yCoord += 5;
        }
        $yCoord += 5;
        $footerOffset = 0;
        if($yCoord >= 260)
        {
            $pdf->AddPage();
            $yCoord = count($franchise->leads) ? 25 : 0;
            $footerOffset = 10;
        }

        if(count($franchise->leads))
        {
            $pdf->Image(URL::asset('/img/thin-gray-line.jpg'), 8, $yCoord);
            $yCoord += 10;
            $pdf->Text(10, $yCoord, 'Purchased Date');
            $pdf->Text(100, $yCoord, 'Lead Name');
            $pdf->Text(175, $yCoord, 'Price');
            $yCoord += 5;

            foreach($franchise->leads as $lead)
            {
                $pdf->Text(10, $yCoord, date('m/d/Y', strtotime($lead->purchased_at)));
                $pdf->Text(100, $yCoord, $lead->name);
                $pdf->Text(175, $yCoord, '$'.$lead->price);
                if($yCoord >= 270)
                {
                    $pdf->AddPage();
                    $pdf->Text(10, 25, 'Purchased Date');
                    $pdf->Text(100, 25, 'Lead Name');
                    $pdf->Text(175, 25, 'Price');
                    $yCoord = 25;
                }
                $yCoord += 5;
            }
            $yCoord += 5;
            $footerOffset = 0;
            if($yCoord >= 260)
            {
                $pdf->AddPage();
                $yCoord = 0;
                $footerOffset = 10;
            }
        }

        

        $pdf->SetTextColor(255,255,255);
        $pdf->Image(URL::asset('/img/green-border.jpg'), 8, $yCoord+$footerOffset,'', '', '','', '', false, 500, '',false, false, 0, false, false, true);
        $pdf->Text(75, $yCoord+$footerOffset+7, "Copyright 2014, SaveOn. ".($yCoord+$footerOffset)." All rights reserved.");

        $pdf->Output('MerchantReport.pdf', 'D');
    }

    public function getContestReport()
    {
        // TODO: pass the repo into the constructor
        $repo = new EloquentContestRepository;

        $contests = $repo->getSalesReport(
            Input::get('page', 0),
            Input::get('limit', 10),
            Input::get('orderby'),
            Input::get('search-query'),
            Input::get('show_expired')
        );

        $links = $contests->appends(
            array(
                'orderby' => Input::get('orderby'),
                'search-query' => Input::get('search-query'),
                'show_expired' => Input::get('show_expired')
            ))
            ->links();
        $code = array();
        $code[] = View::make('admin.sales.jscode.contest');
        $view = View::make('admin.sales.contests.main', array(
            'contests' => $contests,
            'links' => $links
        ))->with('code', implode(' ', $code));

        $view->variables = Input::all();
        $view->query_dir = Input::get('dir');

        $view->primary_nav = "sales";
        $view->secondary_nav = "contests";

        return $view;
    }

    public function getMerchantViews()
    {
        $merchant = Input::get('merchant');
        $start = Input::get('startDate');
        $end = Input::get('endDate');
        $old_site = Input::get('archive');

        if($start != '')
        {
            $aPieces = explode('/', $start);
            $start = date('Y-m-d', mktime(0, 0, 0, $aPieces[0], $aPieces[1], $aPieces[2]));
        }
        else
        {
            $start = date('Y-m-d', strtotime('-3 month'));
        }
        if($end != '')
        {
            $aPieces = explode('/', $end);
            $end = date('Y-m-d', mktime(0, 0, 0, $aPieces[0], $aPieces[1], $aPieces[2]));
            $end = $end > date('Y-m-d') ? date('Y-m-d') : $end;
        }
        else
        {
            $end = date('Y-m-d');
        }
        $merch = Merchant::find($merchant);
        // Get Mixpanel Data
        if($old_site == 'no')
        {
            $api_key = Config::get('integrations.mixpanel.key');
            $api_secret = Config::get('integrations.mixpanel.secret');
            $mp = new MPData($api_key, $api_secret);
            $data = $mp->request(array('segmentation'), array(
                'event' => 'Location View',
                'from_date' => $start,
                'to_date' => $end,
                'unit' => 'day',
                'where' => $merch->id.' == properties["MerchantId"] and properties["Environment"] == "prod"',
                'type' => 'general',
            ));

            $views = 0;
            foreach($data->data->values->{'Location View'} as $key => $value)
            {
                $views += $value;
            }
        }
        else
        {
            $api_key = '393ae20db7d1c43127def9aa6e36649d';
            $api_secret = 'bb510ec77d720f9267189442533ae149';
            $mp = new MPData($api_key, $api_secret);
            $data = $mp->request(array('segmentation'), array(
                'event' => 'Merchant View',
                'from_date' => $start,
                'to_date' => $end,
                'unit' => 'day',
                'where' => '"'.$merch->name.'" in properties["Merchant"]',
                'type' => 'general',
            ));

            $views = 0;
            foreach($data->data->values->{'Merchant View'} as $key => $value)
            {
                $views += $value;
            }
        }

        $aViews = array(array('views' => $views));

        return $this->format($aViews);
    }

    public function getAllViews($startDate, $endDate, $merchant, $old_site)
    {
        /*$merchant = Input::get('merchant');
        $startDate = Input::get('startDate');
        $endDate = Input::get('endDate');
        $old_site = Input::get('archive');*/

        /*if($start != '')
        {
            $mPieces = explode('/', $start);
            $start = date('Y-m-d', mktime(0, 0, 0, $aPieces[0], $aPieces[1], $aPieces[2]));
        }
        else
        {
            $start = date('Y-m-d', strtotime('-3 month'));
        }
        if($end != '')
        {
            $aPieces = explode('/', $end);
            $end = date('Y-m-d', mktime(0, 0, 0, $aPieces[0], $aPieces[1], $aPieces[2]));
            $end = $end > date('Y-m-d') ? date('Y-m-d') : $end;
        }
        else
        {
            $end = date('Y-m-d');
        }*/
        $merch = Merchant::find($merchant);
        // Get Mixpanel Data
        if($old_site == 'no')
        {
            $api_key = Config::get('integrations.mixpanel.key');
            $api_secret = Config::get('integrations.mixpanel.secret');
            $mp = new MPData($api_key, $api_secret);
            $data = $mp->request(array('segmentation'), array(
                'event' => 'Location View',
                'from_date' => $startDate,
                'to_date' => $endDate,
                'unit' => 'day',
                'where' => $merch->id.' == properties["MerchantId"] and properties["Environment"] == "prod"',
                'type' => 'general',
            ));
            /*print_r($data);
            exit;*/
            $views = 0;
            foreach($data->data->values->{'Location View'} as $key => $value)
            {
                $views += $value;
            }
        }
        else
        {
            $api_key = '393ae20db7d1c43127def9aa6e36649d';
            $api_secret = 'bb510ec77d720f9267189442533ae149';
            $mp = new MPData($api_key, $api_secret);
            $data = $mp->request(array('segmentation'), array(
                'event' => 'Merchant View',
                'from_date' => $startDate,
                'to_date' => $endDate,
                'unit' => 'day',
                'where' => '"'.$merch->name.'" in properties["Merchant"]',
                'type' => 'general',
            ));

            $views = 0;
            foreach($data->data->values->{'Merchant View'} as $key => $value)
            {
                $views += $value;
            }
        }

        $mViews = array(array('views' => $views));

        return $this->format($mViews);
    }

    public function getMerchantPrints()
    {
        $merchant = Input::get('merchant');
        $start = Input::get('startDate');
        $end = Input::get('endDate');
        $old_site = Input::get('archive');

        $api_key = Config::get('integrations.mixpanel.key');
        $api_secret = Config::get('integrations.mixpanel.secret');
        $mp = new MPData($api_key, $api_secret);

        if($start != '')
        {
            $aPieces = explode('/', $start);
            $start = date('Y-m-d', mktime(0, 0, 0, $aPieces[0], $aPieces[1], $aPieces[2]));
        }
        else
        {
            $start = date('Y-m-d', strtotime('-3 month'));
        }

        if($end != '')
        {
            $aPieces = explode('/', $end);
            $end = date('Y-m-d', mktime(0, 0, 0, $aPieces[0], $aPieces[1], $aPieces[2]));
            $end = $end > date('Y-m-d') ? date('Y-m-d') : $end;
        }
        else
        {
            $end = date('Y-m-d');
        }
        $merch = Merchant::find($merchant);
        // Get Mixpanel Data
        if($old_site == 'no')
        {
            $data = $mp->request(array('segmentation'), array(
                'event' => 'Offer Print',
                'from_date' => $start,
                'to_date' => $end,
                'unit' => 'day',
                'where' => $merch->id.' == properties["MerchantId"] and properties["Environment"] == "prod"',
                'type' => 'general',
            ));

            $prints = 0;
            foreach($data->data->values->{'Offer Print'} as $key => $value)
            {
                $prints += $value;
            }
        }
        else
        {
            /*$api_key = '393ae20db7d1c43127def9aa6e36649d';
            $api_secret = 'bb510ec77d720f9267189442533ae149';
            $mp = new MPData($api_key, $api_secret);
            $data = $mp->request(array('segmentation'), array(
                'event' => 'Print Coupon',
                'from_date' => $start,
                'to_date' => $end,
                'unit' => 'day',
                'where' => '"'.$merch->name.'" in properties["Merchant"]',
                'type' => 'general',
            ));

            $prints = 0;
            foreach($data->data->values->{'Print Coupon'} as $key => $value)
            {
                $prints += $value;
            }*/
            $profileId = 'ga:58594784';
            $clientId = '472301685541@developer.gserviceaccount.com';
            $privateKeyFile = storage_path().'/4c40248f4ad55e2d13d4ab942c5eeb38453d8e25-privatekey.p12';
            $total = 0;
            $filter = 'ga:pagePath=~^/print-coupon/'.$merch->id.'/,ga:pagePath=~^/national/print_coupon/'.$merch->id.'/';

            $query = new Widop\GoogleAnalytics\Query($profileId);
            $query->setStartDate(new \DateTime($start));
            $query->setEndDate(new \DateTime($end));
            $query->setMetrics(array('ga:pageviews'));
            $query->setFilters(array('ga:pagePath=~^/print-coupon/'.$merch->id.'/', 'ga:pagePath=~^/national/print_coupon/'.$merch->id.'/'));
            $httpAdapter = new Widop\HttpAdapter\CurlHttpAdapter();
            $client = new Widop\GoogleAnalytics\Client($clientId, $privateKeyFile, $httpAdapter);
            $token = $client->getAccessToken();
            $service = new Widop\GoogleAnalytics\Service($client);
            $response = $service->query($query);
            $total = $response->getTotalsForAllResults();
            $prints = $total['ga:pageviews'];
        }

        $aPrints = array(array('prints' => $prints));

        return $this->format($aPrints);
    }

    public function getAllPrints($startDate, $endDate, $merchant, $old_site)
    {
        /*$merchant = Input::get('merchant');
        $start = Input::get('startDate');
        $end = Input::get('endDate');
        $old_site = Input::get('archive');*/

        $api_key = Config::get('integrations.mixpanel.key');
        $api_secret = Config::get('integrations.mixpanel.secret');
        $mp = new MPData($api_key, $api_secret);

        /*if($start != '')
        {
            $aPieces = explode('/', $start);
            $start = date('Y-m-d', mktime(0, 0, 0, $aPieces[0], $aPieces[1], $aPieces[2]));
        }
        else
        {
            $start = date('Y-m-d', strtotime('-3 month'));
        }

        if($end != '')
        {
            $aPieces = explode('/', $end);
            $end = date('Y-m-d', mktime(0, 0, 0, $aPieces[0], $aPieces[1], $aPieces[2]));
            $end = $end > date('Y-m-d') ? date('Y-m-d') : $end;
        }
        else
        {
            $end = date('Y-m-d');
        }*/
        $merch = Merchant::find($merchant);
        // Get Mixpanel Data
        if($old_site == 'no')
        {
            $data = $mp->request(array('segmentation'), array(
                'event' => 'Offer Print',
                'from_date' => $startDate,
                'to_date' => $endDate,
                'unit' => 'day',
                'where' => $merch->id.' == properties["MerchantId"] and properties["Environment"] == "prod"',
                'type' => 'general',
            ));

            $prints = 0;
            foreach($data->data->values->{'Offer Print'} as $key => $value)
            {
                $prints += $value;
            }
        }
        else
        {
            /*$api_key = '393ae20db7d1c43127def9aa6e36649d';
            $api_secret = 'bb510ec77d720f9267189442533ae149';
            $mp = new MPData($api_key, $api_secret);
            $data = $mp->request(array('segmentation'), array(
                'event' => 'Print Coupon',
                'from_date' => $start,
                'to_date' => $end,
                'unit' => 'day',
                'where' => '"'.$merch->name.'" in properties["Merchant"]',
                'type' => 'general',
            ));

            $prints = 0;
            foreach($data->data->values->{'Print Coupon'} as $key => $value)
            {
                $prints += $value;
            }*/
            $profileId = 'ga:58594784';
            $clientId = '472301685541@developer.gserviceaccount.com';
            $privateKeyFile = storage_path().'/4c40248f4ad55e2d13d4ab942c5eeb38453d8e25-privatekey.p12';
            $total = 0;
            $filter = 'ga:pagePath=~^/print-coupon/'.$merch->id.'/,ga:pagePath=~^/national/print_coupon/'.$merch->id.'/';

            $query = new Widop\GoogleAnalytics\Query($profileId);
            $query->setStartDate(new \DateTime($startDate));
            $query->setEndDate(new \DateTime($endDate));
            $query->setMetrics(array('ga:pageviews'));
            $query->setFilters(array('ga:pagePath=~^/print-coupon/'.$merch->id.'/', 'ga:pagePath=~^/national/print_coupon/'.$merch->id.'/'));
            $httpAdapter = new Widop\HttpAdapter\CurlHttpAdapter();
            $client = new Widop\GoogleAnalytics\Client($clientId, $privateKeyFile, $httpAdapter);
            $token = $client->getAccessToken();
            $service = new Widop\GoogleAnalytics\Service($client);
            $response = $service->query($query);
            $total = $response->getTotalsForAllResults();
            $prints = $total['ga:pageviews'];
        }

        $mPrints = array(array('prints' => $prints));

        return $this->format($mPrints);
        print_r($old_site);
        exit;
    }
    public function getOfferStats($startDate, $endDate, $merchant, $old_site)
    {
        /*$merchant = Input::get('merchant');
        $merchant_id=$merchant;*/
        $old_site = Input::get('archive');
        $entities = SOE\DB\Entity::where('merchant_id', '=', Input::get('merchant'))
                                ->where('is_active', '=', '1')
                                ->where(function($query)
                                {
                                    $query->where('starts_year', '=', date('Y'));
                                    $query->where('starts_day', '<=', (date('z')+1));
                                    $query->orWhere('starts_year', '<', (date('Y')));
                                })
                                ->where(function($query)
                                {
                                    $query->where('expires_year', '=', date('Y'));
                                    $query->where('expires_day', '>=', (date('z')+1));
                                    $query->orWhere('expires_year', '>=', (date('Y')+1));
                                });
        if($merchant != '')
        {
            $entities = $entities->where('merchant_id', '=', $merchant);
        }
        $entities = $entities->groupBy('entitiable_id')
                            ->groupBy('entitiable_type')
                            ->orderBy('name', 'asc')
                            ->get();

        $aEntities = array('offers' => array(), 'contests' => array());
        foreach($entities as $entity)
        {
            if($entity->entitiable_type == 'Offer')
            {
                $aEntities['offers'][$entity->entitiable_id] = array('type' => 'offer', 'name' => $entity->name, 'expires' => date('m-d-Y', strtotime($entity->expires_at)), 'views' => 0, 'prints' => 0, 'merchant_id' => $entity->merchant_id);
            }
            else
            {
                $aEntities['contests'][$entity->entitiable_id] = array('type' => 'contest', 'name' => $entity->name, 'expires' => date('m-d-Y', strtotime($entity->expires_at)), 'signups' => 0);
            }
        }

        if($old_site == 'yes')
        {
            $aEntities['offers'] = $this->oldOfferData($aEntities['offers'], $startDate, $endDate);
            foreach($aEntities['contests'] as $id => &$data)
            {
                $applications = DB::connection('mysql_old')->select("select count(*) as total from contest_application where created_at >= '".$startDate."' and created_at <= '".$endDate."' and contest_id = ".$id);
                $data['signups'] = $applications[0]->total;
            }
        }
        else
        {
            $merch = Merchant::find(Input::get('merchant'));
            $api_key = Config::get('integrations.mixpanel.key');
            $api_secret = Config::get('integrations.mixpanel.secret');
            $mp = new MPData($api_key, $api_secret);

            $where = $merch->id.' == properties["MerchantId"] and properties["Environment"] == "prod"';
            if($merchant != '')
            {
                $where .= ' and properties["MerchantId"] == '.$merchant;
            }

            $mp = new MPData($api_key, $api_secret);
            $view_data = $mp->request(array('segmentation'), array(
                'event' => 'Offer Impression',
                'on' => 'properties["OfferId"]',
                'from_date' => $startDate,
                'to_date' => $endDate,
                'unit' => 'day',
                'where' => $where,
                'type' => 'general',
            ));
            $aViews = array();
            foreach($view_data->data->values as $offer => $views)
            {
                $aViews[$offer] = 0; 
                foreach($views as $view)
                {
                    $aViews[$offer] += $view; 
                }
            }

            $mp = new MPData($api_key, $api_secret);
            $print_data = $mp->request(array('segmentation'), array(
                'event' => 'Offer Print',
                'on' => 'properties["OfferId"]',
                'from_date' => $startDate,
                'to_date' => $endDate,
                'unit' => 'day',
                'where' => $where,
                'type' => 'general',
            ));
            $aPrints = array();
            foreach($print_data->data->values as $offer => $prints)
            {
                $aPrints[$offer] = 0; 
                foreach($prints as $print)
                {
                    $aPrints[$offer] += $print; 
                }
            }

            foreach($aViews as $offer => $views)
            {
                if(isset($aEntities['offers'][$offer]))
                {
                    $aEntities['offers'][$offer]['views'] = $views;
                }
            }

            foreach($aPrints as $offer => $prints)
            {
                if(isset($aEntities['offers'][$offer]))
                {
                    $aEntities['offers'][$offer]['prints'] = $prints;
                }
            }

            foreach($aEntities['contests'] as $contest_id => &$data)
            {
                $applications = SOE\DB\ContestApplication::where('contest_id', '=', $contest_id)->count();
                $data['signups'] = $applications;
            }
        }

        return $this->format(array_merge($aEntities['offers'], $aEntities['contests']));
    }
    public function getOfferData()
    {
        $location_id = Input::get('location');
        $startDate = Input::get('startDate');
        $endDate = Input::get('endDate');
        $old_site = Input::get('archive');
        /*getOfferStats();*/
        if($startDate != '')
        {
            $aPieces = explode('/', $startDate);
            $startDate = date('Y-m-d', mktime(0, 0, 0, $aPieces[0], $aPieces[1], $aPieces[2]));
        }
        else
        {
            $startDate = date('Y-m-d', strtotime('-3 month'));;
        }
        if($endDate)
        {
            $aPieces = explode('/', $endDate);
            $endDate = date('Y-m-d', mktime('23', '59', '59', $aPieces[0], $aPieces[1], $aPieces[2]));
            $endDate = $endDate > date('Y-m-d') ? date('Y-m-d') : $endDate;
        }
        else
        {
            $endDate = date('Y-m-d');
        }

        $entities = SOE\DB\Entity::where('merchant_id', '=', Input::get('merchant'))
                                ->where('is_active', '=', '1')
                                ->where(function($query)
                                {
                                    $query->where('starts_year', '=', date('Y'));
                                    $query->where('starts_day', '<=', (date('z')+1));
                                    $query->orWhere('starts_year', '<', (date('Y')));
                                })
                                ->where(function($query)
                                {
                                    $query->where('expires_year', '=', date('Y'));
                                    $query->where('expires_day', '>=', (date('z')+1));
                                    $query->orWhere('expires_year', '>=', (date('Y')+1));
                                });
        if($location_id != '')
        {
            $entities = $entities->where('location_id', '=', $location_id);
        }
        $entities = $entities->groupBy('entitiable_id')
                            ->groupBy('entitiable_type')
                            ->orderBy('name', 'asc')
                            ->get();

        $aEntities = array('offers' => array(), 'contests' => array());
        foreach($entities as $entity)
        {
            if($entity->entitiable_type == 'Offer')
            {
                $aEntities['offers'][$entity->entitiable_id] = array('type' => 'offer', 'name' => $entity->name, 'expires' => date('m-d-Y', strtotime($entity->expires_at)), 'views' => 0, 'prints' => 0, 'merchant_id' => $entity->merchant_id);
            }
            else
            {
                $aEntities['contests'][$entity->entitiable_id] = array('type' => 'contest', 'name' => $entity->name, 'expires' => date('m-d-Y', strtotime($entity->expires_at)), 'signups' => 0);
            }
        }

        if($old_site == 'yes')
        {
            $aEntities['offers'] = $this->oldOfferData($aEntities['offers'], $startDate, $endDate);
            foreach($aEntities['contests'] as $id => &$data)
            {
                $applications = DB::connection('mysql_old')->select("select count(*) as total from contest_application where created_at >= '".$startDate."' and created_at <= '".$endDate."' and contest_id = ".$id);
                $data['signups'] = $applications[0]->total;
            }
        }
        else
        {
            $merch = Merchant::find(Input::get('merchant'));
            $api_key = Config::get('integrations.mixpanel.key');
            $api_secret = Config::get('integrations.mixpanel.secret');
            $mp = new MPData($api_key, $api_secret);

            $where = $merch->id.' == properties["MerchantId"] and properties["Environment"] == "prod"';
            if($location_id != '')
            {
                $where .= ' and properties["LocationId"] == '.$location_id;
            }

            $mp = new MPData($api_key, $api_secret);
            $view_data = $mp->request(array('segmentation'), array(
                'event' => 'Offer Impression',
                'on' => 'properties["OfferId"]',
                'from_date' => $startDate,
                'to_date' => $endDate,
                'unit' => 'day',
                'where' => $where,
                'type' => 'general',
            ));
            $aViews = array();
            foreach($view_data->data->values as $offer => $views)
            {
                $aViews[$offer] = 0;
                foreach($views as $view)
                {
                    $aViews[$offer] += $view;
                }
            }

            $mp = new MPData($api_key, $api_secret);
            $print_data = $mp->request(array('segmentation'), array(
                'event' => 'Offer Print',
                'on' => 'properties["OfferId"]',
                'from_date' => $startDate,
                'to_date' => $endDate,
                'unit' => 'day',
                'where' => $where,
                'type' => 'general',
            ));
            $aPrints = array();
            foreach($print_data->data->values as $offer => $prints)
            {
                $aPrints[$offer] = 0;
                foreach($prints as $print)
                {
                    $aPrints[$offer] += $print;
                }
            }

            foreach($aViews as $offer => $views)
            {
                if(isset($aEntities['offers'][$offer]))
                {
                    $aEntities['offers'][$offer]['views'] = $views;
                }
            }

            foreach($aPrints as $offer => $prints)
            {
                if(isset($aEntities['offers'][$offer]))
                {
                    $aEntities['offers'][$offer]['prints'] = $prints;
                }
            }

            foreach($aEntities['contests'] as $contest_id => &$data)
            {
                $applications = SOE\DB\ContestApplication::where('contest_id', '=', $contest_id)->count();
                $data['signups'] = $applications;
            }
        }

        return $this->format(array_merge($aEntities['offers'], $aEntities['contests']));
    }
    protected function oldOfferData($offers, $startDate, $endDate)
    {
        $profileId = 'ga:58594784';
        $clientId = '472301685541@developer.gserviceaccount.com';
        $privateKeyFile = storage_path().'/4c40248f4ad55e2d13d4ab942c5eeb38453d8e25-privatekey.p12';

        foreach($offers as $id => &$data)
        {
            $total = 0;
            //$filter = 'ga:pagePath=~^/print-coupon/'.$data['merchant_id'].'/'.$id.',ga:pagePath=~^/national/print_coupon/'.$data['merchant_id'].'/[0-9]*/'.$id;
            $query = new Widop\GoogleAnalytics\Query($profileId);
            $query->setStartDate(new \DateTime($startDate));
            $query->setEndDate(new \DateTime($endDate));
            $query->setMetrics(array('ga:pageviews'));
            $query->setFilters(array('ga:pagePath=~^/print-coupon/'.$data['merchant_id'].'/'.$id, 'ga:pagePath=~^/national/print_coupon/'.$data['merchant_id'].'/[0-9]*/'.$id));
            $httpAdapter = new Widop\HttpAdapter\CurlHttpAdapter();
            $client = new Widop\GoogleAnalytics\Client($clientId, $privateKeyFile, $httpAdapter);
            $token = $client->getAccessToken();
            $service = new Widop\GoogleAnalytics\Service($client);
            $response = $service->query($query);
            $total = $response->getTotalsForAllResults();
            $prints = $total['ga:pageviews'];
            $data['prints'] = $prints;
        }

        return $offers;
    }

    public function getMerchantLocations()
    {
        $startDate = Input::get('startDate');
        $endDate = Input::get('endDate');
        if($startDate != '')
        {
            $aPieces = explode('/', $startDate);
            $startDate = date('Y-m-d', mktime(0, 0, 0, $aPieces[0], $aPieces[1], $aPieces[2]));
        }
        else
        {
            $startDate = date('Y-m-d', strtotime('-3 month'));;
        }
        if($endDate)
        {
            $aPieces = explode('/', $endDate);
            $endDate = date('Y-m-d', mktime('23', '59', '59', $aPieces[0], $aPieces[1], $aPieces[2]));
            $endDate = $endDate > date('Y-m-d') ? date('Y-m-d') : $endDate;
        }
        else
        {
            $endDate = date('Y-m-d');
        }

        $locations = SOE\DB\Location::where('merchant_id', '=', Input::get('merchant', 0));
        $stats = $this->getStats(clone $locations, 0, 0);
        $locations = $locations->get();
        $arr = array();
        $stats['stats']['returned'] = count($locations);

        $api_key = Config::get('integrations.mixpanel.key');
        $api_secret = Config::get('integrations.mixpanel.secret');
        $mp = new MPData($api_key, $api_secret);
        $print_data = $mp->request(array('segmentation'), array(
            'event' => 'Offer Print',
            'on' => 'properties["LocationId"]',
            'from_date' => $startDate,
            'to_date' => $endDate,
            'unit' => 'day',
            'where' => Input::get('merchant', 0).' == properties["MerchantId"] and properties["Environment"] == "prod"',
            'type' => 'general',
        ));
        $aPrints = array();
        foreach($print_data->data->values as $location => $prints)
        {
            $aPrints[$location] = 0;
            foreach($prints as $print)
            {
                $aPrints[$location] += $print;
            }
        }

        $mp = new MPData($api_key, $api_secret);
        $view_data = $mp->request(array('segmentation'), array(
            'event' => 'Location View',
            'on' => 'properties["LocationId"]',
            'from_date' => $startDate,
            'to_date' => $endDate,
            'unit' => 'day',
            'where' => Input::get('merchant', 0).' == properties["MerchantId"] and properties["Environment"] == "prod"',
            'type' => 'general',
        ));
        $aViews = array();
        foreach($view_data->data->values as $location => $views)
        {
            $aViews[$location] = 0;
            foreach($views as $view)
            {
                $aViews[$location] += $view;
            }
        }

        $return = array('objects' => array());
        foreach ($locations as $l)
        {
            $location = Location::blank();
            $location = $location->createFromModel($l);
            $location->views = isset($aViews[$l->id]) ? $aViews[$l->id] : 0;
            $location->prints = isset($aPrints[$l->id]) ? $aPrints[$l->id] : 0;
            $return['objects'][] = $location;
        }

        return $this->format(array_merge($return, $stats));
    }

    public function getMerchantData()
    {
        $startDate = Input::get('start', '');
        $endDate = Input::get('end', '');
        if($startDate != '')
        {
            $aPieces = explode('/', $startDate);
            $startDate = date('Y-m-d', mktime(0, 0, 0, $aPieces[0], $aPieces[1], $aPieces[2]));
        }
        else
        {
            $startDate = '';
        }
        if($endDate != '')
        {
            $aPieces = explode('/', $endDate);
            $endDate = date('Y-m-d', mktime('23', '59', '59', $aPieces[0], $aPieces[1], $aPieces[2]));
            $endDate = $endDate > date('Y-m-d') ? date('Y-m-d') : $endDate;
        }
        else
        {
            $endDate = '';
        }
        $merchant = Merchant::find(Input::get('merchant', 0));
        if($endDate != '')
        {
            $views = $merchant->viewed_by_users()->where('user_view.created_at', '>=', $startDate)->where('user_view.created_at', '<=', $endDate)->get();
        }
        else
        {
            $views = $merchant->viewed_by_users()->where('user_view.created_at', '>=', $startDate)->get();
        }
        $logo = $merchant->assets()->where('type','=','image')->get();
        $arr = array('id'=>$merchant->id,
                     'name'=>$merchant->name,
                     'views'=>count($views),
                     'logo'=>Config::get('save.image_url').$logo[0]->path);

        return json_encode($arr);
    }
    public function getPdfreport()
    {
        $range_title = Input::get('range');
        $merchant = Input::get('merchant');
        $start = Input::get('startDate');
        $end = Input::get('endDate');
        $location_id = Input::get('location');
        $startDate = Input::get('startDate');
        $endDate = Input::get('endDate');
        $old_site = Input::get('archive');
        if($startDate = Input::get('startDate'))
        {
            $aPieces = explode('/', $startDate);
            $startDate = date('Y-m-d', mktime(0, 0, 0, $aPieces[0], $aPieces[1], $aPieces[2]));
        }
        else
        {
            $startDate = '';
        }
        if($endDate = Input::get('endDate'))
        {
            $aPieces = explode('/', $endDate);
            $endDate = date('Y-m-d H:i:s', mktime('23', '59', '59', $aPieces[0], $aPieces[1], $aPieces[2]));
        }
        else
        {
            $endDate = '';
        }
        $merchantViews = $this->getAllViews($startDate, $endDate, $merchant, $old_site);
        $merchantViews = json_decode($merchantViews);
        $merchantViews = $merchantViews->data[0]->views;
        $userPrints = $this->getAllPrints($startDate, $endDate, $merchant, $old_site);
        $userPrints = json_decode($userPrints);
        $userPrints = $userPrints->data[0]->prints;
        //print_r($userPrints);
        $offerData = $this->getOfferStats($startDate, $endDate, $merchant, $old_site);
        $offerData = json_decode($offerData);
        $locations = SOE\DB\Location::where('merchant_id', '=', Input::get('merchant', 0));
        $stats = $this->getStats(clone $locations, 0, 0);
        $locations = $locations->get();
        $arr = array();
        $stats['stats']['returned'] = count($locations);
        $api_key = Config::get('integrations.mixpanel.key');
        $api_secret = Config::get('integrations.mixpanel.secret');
        $mp = new MPData($api_key, $api_secret);
        $print_data = $mp->request(array('segmentation'), array(
            'event' => 'Offer Print',
            'on' => 'properties["LocationId"]',
            'from_date' => $startDate,
            'to_date' => $endDate,
            'unit' => 'day',
            'where' => Input::get('merchant', 0).' == properties["MerchantId"] and properties["Environment"] == "prod"',
            'type' => 'general',
        ));
        $mPrints = array();
        foreach($print_data->data->values as $location => $prints)
        {
            $mPrints[$location] = 0; 
            foreach($prints as $print)
            {
                $mPrints[$location] += $print; 
            }
        }
        //print_r($mPrints);
        $mp = new MPData($api_key, $api_secret);
        $view_data = $mp->request(array('segmentation'), array(
            'event' => 'Location View',
            'on' => 'properties["LocationId"]',
            'from_date' => $startDate,
            'to_date' => $endDate,
            'unit' => 'day',
            'where' => Input::get('merchant', 0).' == properties["MerchantId"] and properties["Environment"] == "prod"',
            'type' => 'general',
        ));
        $mViews = array();
        foreach($view_data->data->values as $location => $views)
        {
            $mViews[$location] = 0; 
            foreach($views as $view)
            {
                $mViews[$location] += $view; 
            }
        }
        $return = array('objects' => array());
        //print_r($mViews);
        //exit;
        foreach ($locations as $l)
        {
            $location = Location::blank();
            $location = $location->createFromModel($l);
            $location->views = isset($mViews[$l->id]) ? $mViews[$l->id]: 0;
            $location->prints = isset($mPrints[$l->id]) ? $mPrints[$l->id] : 0;
            $return['objects'][] = $location;
        }
        /*print_r($location->views);
        exit;*/
        
        $merch = Merchant::find($merchant);

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

        // Set some content to print
        //$pdf->Image(URL::asset('/img/logo-small.png'), 10, 15, '', '', '', false, 500, '',false, false, 0);
        $pdf->Image(URL::asset('/img/green-border.jpg'), 8, 37,  '', '', '', false, 500, '',false, false, 0);
        $pdf->Image($merch->logo()->path, 150, 39, 22, 13.16, '', '', '', false, 300);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Text(10, 40, 'Statistics for: ');
        $pdf->Text(10, 45, $merch->name);
        $pdf->SetFontSize(8);
        //next line can be added into the pdf once the date picker bug has been worked out
        $pdf->Text(10, 50, 'Metrics taken: '.$start. ' - ' .$end);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Text (10, 65, 'Merchant Views');
        $pdf->Text(175, 65, $merchantViews);
        $pdf->Image(URL::asset('/img/thin-gray-line.jpg'), 8, 70);
        $y_coord=95;
        $y_axis=0;
        $y_header=$y_coord+175+$y_axis;
        $y_height=$y_header+5;
        $y_tall=$y_header+5;
        $pdf->Text(10, 80, 'Merchant Prints');
        $pdf->Text(175, 80, $userPrints);
        $pdf->Image(URL::asset('/img/thin-gray-line.jpg'), 8, 85);
        $pdf->Text(10, 95, 'Location Views');
        foreach($return['objects'] as $location)
        {
            $pdf->Text(95, $y_coord, $location->name);
            $pdf->Text(175, $y_coord, $location->views);
            if($y_coord >= 270)
            {
                $pdf->AddPage();
                $pdf->Text(10, 25, 'Location Views (Continued)');
                $y_coord = 20;
            }
            $y_coord += 5;
        }
        $y_coord += 5;
        if($y_coord >= 275)
        {
            $pdf->AddPage();
            $y_coord = 25;
        }
        $pdf->Image(URL::asset('/img/thin-gray-line.jpg'), 8, $y_coord);
        $y_coord += 15;
        $pdf->Text(10,$y_coord,'Location Prints');
        $i=1;
        foreach($return['objects'] as $location)
        {
            $pdf->Text(95, $y_coord, $location->name);
            $pdf->Text(175, $y_coord, $location->prints);
            if($y_coord >= 270)
            {
                $pdf->AddPage();
                $pdf->Text(10, 25, 'Location Prints (Continued)');
                $y_coord = 20;
            }
            $y_coord += 5;
        }
        if($y_coord >= 275)
        {
            $pdf->AddPage();
            $y_coord = 25;
        }
        $pdf->Image(URL::asset('/img/thin-gray-line.jpg'), 8, $y_coord);
        $y_coord += 15;
        $pdf->Text(10, $y_coord, 'OFFERS/CONTESTS');
        $pdf->Text(95, $y_coord, 'VIEWS');
        $pdf->Text(175, $y_coord, 'PRINTS/SIGNUPS');
        $y_coord += 5;
        foreach ($offerData->data as $offer)
        {
            $pdf->Text(10, $y_coord, $offer->name);
            if ($offer->type=='offer'){
               $pdf->Text(95, $y_coord, $offer->views);
                $pdf->Text(175, $y_coord, $offer->prints);
            }
            else{
                $pdf->text(175, $y_coord,$offer->signups);
            }
            if($y_coord >= 270)
            {
                $pdf->AddPage();
                $pdf->Text(10, 25, 'OFFERS/CONTESTS (Continued)');
                $pdf->Text(95, 25, 'VIEWS');
                $pdf->Text(175, 25, 'PRINTS/SIGNUPS');
                $y_coord = 25;
            }
            $y_coord += 5;
        }
        if($y_coord >= 210)
        {
            $pdf->AddPage();
            $y_coord = 25;
        }
        $pdf->SetTextColor(255,255,255);
        $pdf->Image(URL::asset('/img/green-border.jpg'), 8, $y_coord+20,'', '', '', false, 500, '',false, false, 0);
        $pdf->Text(75, $y_coord+27, "Copyright 2014, SaveOn.  All rights reserved.");

        $pdf->Output('MerchantReports.pdf', 'D');
    }

    public function getPdfreportOld()
    {
        $range_title = Input::get('range');
        $merchant = Input::get('merchant');
        $start = Input::get('startDate');
        $end = Input::get('endDate');
        $location_id = Input::get('location');
        $startDate = Input::get('startDate');
        $endDate = Input::get('endDate');
        $old_site = Input::get('archive');
        if($startDate = Input::get('startDate'))
        {
            $aPieces = explode('/', $startDate);
            $startDate = date('Y-m-d', mktime(0, 0, 0, $aPieces[0], $aPieces[1], $aPieces[2]));
        }
        else
        {
            $startDate = '';
        }
        if($endDate = Input::get('endDate'))
        {
            $aPieces = explode('/', $endDate);
            $endDate = date('Y-m-d H:i:s', mktime('23', '59', '59', $aPieces[0], $aPieces[1], $aPieces[2]));
        }
        else
        {
            $endDate = '';
        }
        $merchantViews = $this->getAllViews($startDate, $endDate, $merchant, $old_site);
        $merchantViews = json_decode($merchantViews);
        $merchantViews = $merchantViews->data[0]->views;
        $userPrints = $this->getAllPrints($startDate, $endDate, $merchant, $old_site);
        $userPrints = json_decode($userPrints);
        $userPrints = $userPrints->data[0]->prints;
        //print_r($userPrints);
        $offerData = $this->getOfferStats($startDate, $endDate, $merchant, $old_site);
        $offerData = json_decode($offerData);
        $locations = SOE\DB\Location::where('merchant_id', '=', Input::get('merchant', 0));
        $stats = $this->getStats(clone $locations, 0, 0);
        $locations = $locations->get();
        $arr = array();
        $stats['stats']['returned'] = count($locations);
        $api_key = Config::get('integrations.mixpanel.key');
        $api_secret = Config::get('integrations.mixpanel.secret');
        $mp = new MPData($api_key, $api_secret);
        $print_data = $mp->request(array('segmentation'), array(
            'event' => 'Offer Print',
            'on' => 'properties["LocationId"]',
            'from_date' => $startDate,
            'to_date' => $endDate,
            'unit' => 'day',
            'where' => Input::get('merchant', 0).' == properties["MerchantId"] and properties["Environment"] == "prod"',
            'type' => 'general',
        ));
        $mPrints = array();
        foreach($print_data->data->values as $location => $prints)
        {
            $mPrints[$location] = 0; 
            foreach($prints as $print)
            {
                $mPrints[$location] += $print; 
            }
        }
        //print_r($mPrints);
        $mp = new MPData($api_key, $api_secret);
        $view_data = $mp->request(array('segmentation'), array(
            'event' => 'Location View',
            'on' => 'properties["LocationId"]',
            'from_date' => $startDate,
            'to_date' => $endDate,
            'unit' => 'day',
            'where' => Input::get('merchant', 0).' == properties["MerchantId"] and properties["Environment"] == "prod"',
            'type' => 'general',
        ));
        $mViews = array();
        foreach($view_data->data->values as $location => $views)
        {
            $mViews[$location] = 0; 
            foreach($views as $view)
            {
                $mViews[$location] += $view; 
            }
        }
        $return = array('objects' => array());
        //print_r($mViews);
        //exit;
        foreach ($locations as $l)
        {
            $location = Location::blank();
            $location = $location->createFromModel($l);
            $location->views = isset($mViews[$l->id]) ? $mViews[$l->id]: 0;
            $location->prints = isset($mPrints[$l->id]) ? $mPrints[$l->id] : 0;
            $return['objects'][] = $location;
        }
        /*print_r($location->views);
        exit;*/
        
        $merch = Merchant::find($merchant);

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

        // Set some content to print
        //$pdf->Image(URL::asset('/img/logo-small.png'), 10, 15, '', '', '', false, 500, '',false, false, 0);
        $pdf->Image(URL::asset('/img/green-border.jpg'), 8, 37,  '', '', '', false, 500, '',false, false, 0);
        $pdf->Image($merch->logo()->path, 150, 39, 22, 13.16, '', '', '', false, 300);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Text(10, 40, 'Statistics for: ');
        $pdf->Text(10, 45, $merch->name);
        $pdf->SetFontSize(8);
        //next line can be added into the pdf once the date picker bug has been worked out
        $pdf->Text(10, 50, 'Metrics taken: '.$start. ' - ' .$end);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Text (10, 65, 'Merchant Views');
        $pdf->Text(150, 65, $merchantViews);
        $pdf->Image(URL::asset('/img/thin-gray-line.jpg'), 8, 70);
        $y_cord=0;
        $y_axis=0;
        $y_header=$y_cord+175+$y_axis;
        $y_height=$y_header+5;
        $y_tall=$y_header+5;
        $pdf->Text(10, 80, 'Merchant Prints');
        $pdf->Text(150, 80, $userPrints);
        $pdf->Image(URL::asset('/img/thin-gray-line.jpg'), 8, 85);
        $pdf->Text(10, 95, 'Location Views (only showing 5 locations)');
        foreach(array_slice($return['objects'], 0, 5) as $location)
        {
            $pdf->Text(95, 95+$y_cord, $location->name);
            $pdf->Text(150, 95+$y_cord, $location->views);
            $y_cord = $y_cord+5;
        }
        $pdf->Image(URL::asset('/img/thin-gray-line.jpg'), 8, 100+$y_cord);
        $pdf->Text(10,$y_cord+110,'Location Prints (only showing 5 locations)');
        $i=1;
        foreach(array_slice($return['objects'], 0, 5) as $location)
        {
            $pdf->Text(95, $y_cord+110+$y_axis, $location->name);
            $pdf->Text(150, $y_cord+110+$y_axis, $location->prints);
            $y_axis = $y_axis+5;
            
        }
        $pdf->Image(URL::asset('/img/thin-gray-line.jpg'), 8, $y_header-10);
        $pdf->Text(10, $y_header, 'OFFER (only showing 5 offers)');
        $pdf->Text(95, $y_header, 'VIEWS');
        $pdf->Text(150, $y_header, 'PRINTS/SIGNUPS');
        $j=1;
        foreach (array_slice($offerData->data, 0, 5) as $offer)
        {
            $pdf->Text(10, 5+$y_tall, $offer->name);
            $y_tall=$y_tall+5;
            if ($j++ == 5) break;
        }
        $k=1;
        foreach (array_slice($offerData->data, 0, 5) as $offer)
        {
            if ($offer->type=='offer'){
               $pdf->Text(95, 5+$y_height, $offer->views);
                $pdf->Text(150, 5+$y_height, $offer->prints);
                $y_height=$y_height+5;
            }
            else{
                $pdf->text(150, 5+$y_height,$offer->signups);
                $y_height=$y_height+5;
            }
            if ($k++ == 5) break;
        }
        $pdf->SetTextColor(255,255,255);
        $pdf->Image(URL::asset('/img/green-border.jpg'), 8, $y_header+40,'', '', '', false, 500, '',false, false, 0);
        $pdf->Text(75, $y_header+47, "Copyright 2014, SaveOn.  All rights reserved.");
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="MerchantReport.pdf";');
        header('Content-Length: '.filesize($pdf->Output('MerchantReport.pdf', 'D')));
        readfile($pdf->Output('MerchantReport.pdf', 'D'));
    }

    public function getGalleryTags()
    {
        $term = Input::get('term');
        $tags = DB::table('tags')->where('name', 'LIKE', $term . '%')->orderBy('name', 'asc')->get();
        $tagArr = array();
        foreach($tags as $tag)
        {
            $tagArr[] = array('name' => $tag->name);
        }
        return $this->format($tagArr);
    }

    public function getGalleryAssetTags()
    {
        $asset_id = Input::get('img_id');
        $asset_tags = DB::table('asset_tags')->where('asset_id', '=', $asset_id)->get();
        $tag_ids = array();
        foreach($asset_tags as $tag)
        {
            $tag_ids[] = $tag->tag_id;
        }
        $tags = SOE\DB\Tag::whereIn('id', $tag_ids);
        $stats = $this->getStats(clone $tags, 0, 0);
        $tags = $tags->get();
        $return = array('objects' => array());
        foreach($tags as $tag)
        {
            $t = Tag::blank();
            $t = $t->createFromModel($tag);
            $return['objects'][] = $t;
        }

        return $this->format(array_merge($return, $stats));
    }

    public function getGalleryAssetCategories()
    {
        $parent = Input::get('parent', '0');
        $categories = SOE\DB\AssetCategory::where('parent_id', '=', $parent)->orderBy('name');
        $stats = $this->getStats(clone $categories, 0, 0);
        $categories = $categories->get();
        $return = array('objects' => array());
        foreach($categories as $category)
        {
            $cat = AssetCategory::blank();
            $cat = $cat->createFromModel($category);
            $return['objects'][] = $cat;
        }
        return $this->format(array_merge($return, $stats));
    }

    public function getGalleryAssetCategorySearch()
    {
        $images = array();
        $imgArr = array();
        $cat = Input::get('cat', '');
        $subcat = Input::get('subcat', '');
        $subsubcat = Input::get('subsubcat','');
        $query = SOE\DB\Asset::where('assetable_type', '=', 'sales_gallery_asset')
                            ->where('category_id', '>=', '0');
        if($tagName = Input::get('search'))
        {
            $tagName = explode(',', $tagName);
            array_walk($tagName, create_function('&$val', '$val = trim($val);'));
            $tags = SOE\DB\Tag::whereIn('name', $tagName)->get();
            $tagId = array(0);
            foreach($tags as $tag)
            {
                $tagId[] = $tag->id;
            }
            $assetTags = DB::table('asset_tags')->whereIn('tag_id', $tagId)->groupBy('asset_id')->get(array('asset_id',DB::raw('count(*) as count')));
            $asset_ids = array();
            foreach($assetTags as $at)
            {
                $asset_ids[] = $at->asset_id;
            }
            $asset_ids = count($asset_ids) ? $asset_ids : array(0);

            $query = $query->whereIn('id', $asset_ids);
        }
        if($cat != '')
        {
            $query = $query->where('category_id', '=', $cat);
        }
        if($subcat != '')
        {
            $query = $query->where('subcategory_id', '=', $subcat);
        }
        if($subsubcat != '')
        {
            $query = $query->where('sub_subcategory_id', '=', $subsubcat);
        }
        $limit = Input::get('limit', 15);
        $page = Input::get('page', 0);
        $stats = $this->getStats(clone $query, $limit, $page);
        if($limit != 0)
        {
            $query = $query->take($limit)->skip($limit * $page);
        }
        $images = $query->get();

        $return = array('objects' => array());
        foreach($images as $img)
        {
            $asset = Asset::blank();
            $asset = $asset->createFromModel($img);
            /*if(!empty($img))
            {
                $imgArr[] = array(
                    'id' => $img->attributes['id'],
                    'name' => $img->attributes['name'],
                    'path'=> $img->attributes['path'],
                    'fullpath' => preg_replace('/^\/gallery/', '/gallery/Full', $img->attributes['path']),
                    );
            }*/
            $return['objects'][] = $asset;
        }

        return $this->format(array_merge($return, $stats));
    }

    /**
     * Format data for the response.
     *
     * @param mixed  $data
     * @return mixed $data
     */
    protected function format($data)
    {
        $return = new StdClass;
        if(is_array($data) && isset($data['objects']))
        {
            $return->data = array();
            foreach($data['objects'] as &$object)
            {
                $object = $this->checkForRepo($object);
                $return->data[] = $object->attributes;
            }
            $return->stats = $data['stats'];
        }
        else if($data instanceof BaseEloquentRepository)
        {
            $data = $this->checkForRepo($data);
            $return = $data->attributes;
        }
        else
        {
            $return->data = $data;
        }
        return json_encode($return);
    }

    /**
     * Recursively search through an objects attributes to convert all Repositories to an array of attributes.
     *
     * @param mixed $object
     * @return mixed $object
     */
    protected function checkForRepo($object)
    {
        foreach($object->attributes as &$attr)
        {
            if($attr instanceof BaseEloquentRepository)
            {
                $attr = $this->checkForRepo($attr);
                $attr = $attr->attributes;
            }
        }
        return $object;
    }

    /**
     * Get pagination stats for the given query.
     *
     * @param mixed $query
     * @param int   $limit
     * @param int   $page
     * @return array
     */
    protected function getStats($query, $limit, $page, $is_grouped = false)
    {
        $stats = array('stats' => array());
        if($is_grouped)
        {
            $objects = $query->get(array(DB::raw('1')));
            $stats['stats']['total'] = count($objects);
        }
        else
        {
            $stats['stats']['total'] = $query->count();
        }
        $stats['stats']['page'] = $page;
        $stats['stats']['take'] = $limit;
        return $stats;
    }
}
