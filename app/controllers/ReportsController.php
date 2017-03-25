<?php

class ReportsController extends BaseController {

  /*
  |--------------------------------------------------------------------------
  | Default Home Controller
  |--------------------------------------------------------------------------
  |
  | You may wish to use controllers instead of, or in addition to, Closure
  | based routes. That's great! Here is an example controller method to
  | get you started. To route to this controller, just add the route:
  |
  | Route::get('/', 'HomeController@showWelcome');
  |
  */

    protected $assignmentTypeRepository;
    protected $contestRepository;
    protected $franchiseRepository;
    protected $merchantsRepository;
    protected $mofferRepository;
    protected $reportRepository;
    protected $userRepository;

    // Limit the number merchants requested from mixpanel at a time
    const MIXPANEL_MERCHANT_LIMIT = 500;

    /**
    *
    * Create a new controller instance.
    *
    * @param AssignmentTypeRepository $assignmentTypeRepository
    * @param ContestRepositoryInterface $contestRepository
    * @param FranchiseRepositoryInterface $franchiseRepository
    * @param MerchantRepositoryInterface $merchantsRepository
    * @param OfferRepositoryInterface $offerRepository
    * @param ReportRepository $reportRepository
    * @param UserRepository $userRepository
    *
    * @return void
    */
    public function __construct(
        AssignmentTypeRepositoryInterface $assignmentTypeRepository,
        ContestRepositoryInterface $contestRepository,
        FranchiseRepositoryInterface $franchiseRepository,
        MerchantRepositoryInterface $merchantRepository,
        OfferRepositoryInterface $offerRepository,
        ReportRepositoryInterface $reportRepository,
        UserRepositoryInterface $userRepository
    )
    {
        $this->beforeFilter(function()
        {

            if(!Auth::check())
            {
                return Redirect::to('/');
            }
            else
            {
                $user = Auth::User();
                $found = $this->userRepository->checkType($user, 'Employee');
                if(!$found)
                {
                    return Redirect::to('/');
                }
            }
        }, array('except' => array('getSendSalesEmails', 'getSendLeadEmails')));

        $this->assignmentTypeRepository = $assignmentTypeRepository;
        $this->contestRepository = $contestRepository;
        $this->franchiseRepository = $franchiseRepository;
        $this->merchantRepository = $merchantRepository;
        $this->offerRepository = $offerRepository;
        $this->reportRepository = $reportRepository;
        $this->userRepository = $userRepository;

        $api_key = Config::get('integrations.mixpanel.key');
        $api_secret = Config::get('integrations.mixpanel.secret');
        $this->mixpanel = new MPData($api_key, $api_secret);
    }

/**
*This function retrieves the index page.
*/

    public function getIndex()
    {
        echo '<a href="/reports/active-members">Active Members Report</a><br />';
        echo '<a href="/reports/leadreport">Lead Report</a><br />';
        echo '<a href="/reports/keyword-missing">Popular Merchant Report</a><br />';
        echo '<a href="/reports/salesreport">Sales Report</a><br />';
        echo '<a href="/reports/subcategory-merchant">Subcategory Merchant Report</a><br />';
    }

    public function getSubcategoryMerchant()
    {
        $states = $this->franchiseRepository->getCategoryActiveReport();
        $vw = View::make('reports.category-merchant');
        $vw->states = $states;
        
        return $vw;
    }

    public function getActiveMembers($days = 30)
    {
        $totals = $this->userRepository->getActiveReport($days);
        $vw = View::make('reports.active-members');
        $vw->totals = $totals;
        $vw->days = $days;
        
        return $vw;
    }

    public function getSendLeadEmails($type = null)
    {
        $franchises = $this->franchiseRepository->getsLeads($type);
        foreach($franchises as $franchise)
        {
            $this->getLeadreport($franchise->id, 'email');
        }
    }

    public function getLeadreport($franchise_id = null, $output = null)
    {
        $type = Input::get('type', null);
        if(!$franchise_id)
        {
            $franchises = $this->franchiseRepository->getsLeads($type);
            echo '<a href="/reports">BACK</a><br/><br/>';
            echo '<a href="/reports/leadreport">ALL</a> | <a href="/reports/leadreport?type=sohi">SOHI</a> | <a href="/reports/leadreport?type=soct">SOCT</a><br/><br/>';
            foreach($franchises as $franchise)
            {
                $merchant = $this->franchiseRepository->getMerchant($franchise);
                echo '<a href="/reports/leadreport/'.$franchise->id.'">'.$merchant->display.'</a><br/>';
            }
            echo '<br/><a href="/reports/leadreport/full'.($type ? '?type='.$type : '').'">Download Full Report CSV</a>';
        }
        else if($franchise_id == 'full')
        {
            $netlms = App::make('NetLMSAPIInterface');
            $franchises = $this->franchiseRepository->getsLeads($type);
            $report = array();
            foreach($franchises as $franchise)
            {
                $merchant = $this->merchantRepository->find($franchise->merchant_id);
                $data = array('start' => date('Y-m-1 00:00:00'), 'end' => date('Y-m-25 23:59:59'));
                $result = $netlms->curl('GET', 'report', $franchise->netlms_id, $data, 'leads');
                if($result['status'] != 200)
                    continue;
                $reports[$franchise->id] = array('merchant' => $merchant, 'data' => json_decode($result['response']));
            }

            // Set CSV Headers
            $csv_data = array(array('Franchise Id', 'Merchant', 'Created At', 'Name', 'Category', 'Price'));

            foreach($reports as $franchise_id => $report)
            {
                foreach($report['data'] as $category => $leads)
                {
                    foreach($leads as $lead)
                    $csv_data[] = array(
                        $franchise_id,
                        $report['merchant']->name,
                        $lead->created_at,
                        $lead->first.' '.$lead->last,
                        $category,
                        $lead->price
                    );
                }
            }

            header('Content-Type: application/csv');
            header('Content-Disposition: attachement; filename="leadsreport.csv";');

            $file = fopen('php://output', 'w');
            foreach($csv_data as $row) {
                fputcsv($file, $row, ',');
            }
        }
        else
        {
            $franchise = $this->franchiseRepository->find($franchise_id);
            $merchant = $this->merchantRepository->find($franchise->merchant_id);
            $netlms = App::make('NetLMSAPIInterface');
            $data = array('start' => date('Y-m-1 00:00:00'), 'end' => date('Y-m-25 23:59:59'));
            $result = $netlms->curl('GET', 'report', $franchise->netlms_id, $data, 'leads');
            if($result['status'] != 200)
                return $result['response'];
            $categories = json_decode($result['response']);
            switch ($output)
            {
                case 'csv':
                    return $this->outputLeadsCsv($categories);
                    break;
                case 'email':
                    return $this->leadsEmail($categories, $franchise, $merchant);
                    break;
                default:
                    $vw =  View::make('emails.sohi.leadreport');
                    $vw->merchant = $merchant->toArray();
                    $vw->categories = json_decode($result['response'], true);
                    return $vw;
                    break;
            }
        }
    }

    public function getKeywordMissing($output = 'csv')
    {
        $merchants = DB::table('merchants')->join('locations', function($join)
        {
            $join->on('merchants.id', '=', 'locations.merchant_id');
            $join->on(DB::raw("locations.state IN ('MI', 'IL', 'MN')"), '=', DB::raw('1'));
        })
        ->join('franchises', 'merchants.id', '=', 'franchises.merchant_id')
        ->join(DB::raw("(SELECT COUNT(*) as total, merchant_id FROM user_views
                            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                                AND user_agent NOT LIKE '%bot%'
                                GROUP BY merchant_id) views"), 'merchants.id', '=', 'views.merchant_id')
        ->where('merchants.type', '!=', 'PROSPECT')
        ->where('franchises.is_demo', '0')
        ->groupBy('merchants.id')
        ->groupBy('locations.state')
        ->orderBy('views.total', 'desc')
        ->take(25)
        ->get(array('views.total', 'merchants.id', 'merchants.name', 'locations.state', 'merchants.keywords'));

        switch ($output)
        {
            case 'csv':
                // Set CSV Headers
                $csv_data = array(array('Total Views', 'Merchant Id', 'Merchant Name', 'State', 'Keywords'));

                foreach($merchants as $merchant)
                {
                    $csv_data[] = array(
                        $merchant->total,
                        $merchant->id,
                        $merchant->name,
                        $merchant->state,
                        $merchant->keywords
                    );
                }
                return $this->outputKeywordCSV($csv_data);
                break;
            case 'email':
            default:
                print_r($merchants);
                break;
        }
    }

    /**
     * Output the keywords report data as a CSV.
     *
     * @param array     $data
     * @return mixed
     */
    protected function outputKeywordCSV($data)
    {
        header('Content-Type: application/csv');
        header('Content-Disposition: attachement; filename="keywords.csv";');

        $file = fopen('php://output', 'w');
        foreach($data as $row) {
            fputcsv($file, $row, ',');
        }
    }

    protected function outputLeadsCsv($categories)
    {
        // Set CSV Headers
        $csv_data = array(array('Created At', 'Name', 'Category', 'Price'));

        foreach($categories as $category => $leads)
        {
            foreach($leads as $lead)
            $csv_data[] = array(
                $lead->created_at,
                $lead->first.' '.$lead->last,
                $category,
                $lead->price
            );
        }

        header('Content-Type: application/csv');
        header('Content-Disposition: attachement; filename="leadsreport.csv";');

        $file = fopen('php://output', 'w');
        foreach($csv_data as $row) {
            fputcsv($file, $row, ',');
        }
    }

    protected function leadsEmail($categories, $franchise, $merchant)
    {
        $data = array(
            'merchant' => $merchant->toArray(),
            'categories' => $categories
        );
        $rep = $this->franchiseRepository->getSalesRep($franchise->id);
        if($rep)
        {
            $address = $rep->email;
            Mail::queueOn('SOE_Tasks', 'emails.sohi.leadreport', $data, function($message) use ($address)
            {
                $message->to($address)->subject('Weekly Lead Report');
            });
        }

        if($franchise->primary_contact != '')
        {
            $address = $franchise->primary_contact;
            Mail::queueOn('SOE_Tasks', 'emails.sohi.leadreport', $data, function($message) use ($address)
            {
                $message->to($address)->subject('Weekly Lead Report');
            });
        }
    }

    public function getSalesreport($rep_id = null, $output = null)
    {
        if (strtolower($rep_id) == 'all') {
            return $this->salesReport(false, 'csv');
        }
        else if ($rep_id) {
            return $this->salesReport($rep_id, $output);
        }
        else {
            $reps = $this->getReps();
            if (!$reps) {
                return;
            }
            echo '<a href="/reports">BACK</a><br />';
            echo "<a href='/reports/salesreport/all'>Download Full Report (CSV)</a><br /><br />";
            foreach($reps as $rep) {
                echo '<a href="/reports/salesreport/'.$rep->id.'">'.$rep->name.'</a><br />';
            }
        }
    }

    private function getReps()
    {
        $rep_type = $this->assignmentTypeRepository->findByName('Sales Person');
        if ($rep_type) {
            return $this->userRepository->getByAssignmentType($rep_type);
        } else {
            return false;
        }
    }

    public function getSendSalesEmails()
    {
        set_time_limit(60*30); // 30 Mins
        ini_set('memory_limit', '4096M');
        $rep_type = $this->assignmentTypeRepository->findByName('Sales Person');
        if(!empty($rep_type))
        {
            $reps = $this->userRepository->getByAssignmentType($rep_type);
            foreach($reps as $rep)
            {
                $this->salesReport($rep->id, 'email');
            }
        }
    }

    private function getMixpanelDataByEvent($event, $where)
    {
        $start = date('Y-m-d', strtotime('-1 month'));
        $end = date('Y-m-d');
        return $this->mixpanel->request(array('segmentation'), array(
            'event' => $event,
            'on' => 'properties["MerchantId"]',
            'from_date' => $start,
            'to_date' => $end,
            'unit' => 'day',
            'where' => $where,
            'type' => 'general',
        ));
    }

    /**
     * Generate a sales report for all users or a specific user
     * MixPanel requests are limited to the const MIXPANEL_MERCHANT_LIMIT
     *
     * @param int/bool $rep_id the user id of the sales rep OR false OR 0
     * @param string $output '', csv, email
     */
    protected function salesReport($rep_id, $output)
    {
        $franchises = $this->franchiseRepository->getSalesReport($rep_id);

        if(empty($franchises)) {
            echo "No Merchants";
            return;
        }

        // Get a list of merchant ids to be used for the mixpanel query
        foreach($franchises as $franchise) {
            $merchant_ids[] = $franchise->merchant_id;
        }

        $views = array();
        $prints = array();

        // Do MIXPANEL_MERCHANT_LIMIT requests at a time
        // NOTE: if at some point this becomes slow, consider implementing
        // concurrent curl requests!
        for ($i = 0; $i < count($merchant_ids); $i+=self::MIXPANEL_MERCHANT_LIMIT) {
            $merch_subset = array_slice($merchant_ids, $i, self::MIXPANEL_MERCHANT_LIMIT);

            // Create where clause for mixpanel request
            $where = '(';
            $where .= implode(' == properties["MerchantId"] or ', $merch_subset);
            $where .= ' == properties["MerchantId"]) and properties["Environment"] == "prod"';

            // Get location view data and print data
            $view_data = $this->getMixpanelDataByEvent('Location View', $where);
            foreach($view_data->data->values as $merch => $data) {
                if (!array_key_exists($merch, $views)) $views[$merch] = 0;
                foreach($data as $value)
                {
                    $views[$merch] += $value;
                }
            }
            $print_data = $this->getMixpanelDataByEvent('Offer Print', $where);
            foreach($print_data->data->values as $merch => $data) {
                if (!array_key_exists($merch, $prints)) $prints[$merch] = 0;
                foreach($data as $value) {
                    $prints[$merch] += $value;
                }
            }
        }

        // Set CSV Headers
        $csv_data = array(array('Sales Person', 'Merchant Name', 'State', 'Prints', 'Views', 'Contest Signups', 'Offers Expiring (Next 10 Days)', 'Total Offers', 'Has Offers', 'Has About Us', 'Has SEO'));

        foreach($franchises as &$franchise)
        {
            $franchise->views = isset($views[$franchise->merchant_id]) ? $views[$franchise->merchant_id] : 0;
            $franchise->prints = isset($prints[$franchise->merchant_id]) ? $prints[$franchise->merchant_id] : 0;
            $csv_data[] = array(
                $franchise->user_name,
                $franchise->merchant_name,
                $franchise->state,
                $franchise->prints,
                $franchise->views,
                $franchise->applicants,
                $franchise->expiring_offers,
                $franchise->all_offers,
                $franchise->active ? 'Yes' : 'No',
                $franchise->merchant_about != '' ? 'Yes' : 'No',
                ($franchise->merchant_title != '' && $franchise->merchant_description != '') ? 'Yes' : 'No'
            );
        }

        if($output == 'csv') {
            return $this->outputSalesCSV($csv_data);
        } else if($output == 'email') {
            $user = $this->userRepository->find($rep_id);
            $start = date('Y-m-d', strtotime('-1 month'));
            $end = date('Y-m-d');
            return $this->outputSalesEmail($franchises, $user, $start, $end);
        }

        $vw = View::make('reports.sales-report');
        $vw->user_name = $franchises[0]['user_name'];
        $vw->user_id = $rep_id;
        $vw->franchises = $franchises;
        
        return $vw;
    }

    /**
     * Output the sales report data as a CSV.
     *
     * @param array     $franchises
     * @return mixed
     */
    protected function outputSalesCSV($data)
    {
        header('Content-Type: application/csv');
        header('Content-Disposition: attachement; filename="salesreport.csv";');

        $file = fopen('php://output', 'w');
        foreach($data as $row) {
            fputcsv($file, $row, ',');
        }
    }

    protected function outputSalesEmail($franchises, $user, $start, $end)
    {
        $vw = View::make('emails.salesreport');
        foreach($franchises as $franchise)
        {
            $list[] = array(
                'name' => $franchise->merchant_name,
                'prints' => $franchise->prints,
                'views' => $franchise->views,
                'signups' => $franchise->applicants,
                'expiring' => $franchise->expiring_offers,
                'active' => $franchise->active_offers == 0 ? 'No' : 'Yes',
                'about' => $franchise->merchant_about != '' ? 'Yes' : 'No',
                'seo' => ($franchise->merchant_title != '' && $franchise->merchant_description != '') ? 'Yes' : 'No'
            );
        }
        $data = array(
            'list' => $list,
            'user_id' => $user->id,
            'name' => $user->name,
            'start' => date('m/d/Y', strtotime($start)),
            'end' => date('m/d/Y', strtotime($end))
        );
        $address = $user->email;
        Mail::queueOn('SOE_Tasks', 'emails.salesreport', $data, function($message) use ($address)
        {
            $message->to($address)->subject('Weekly Sales Report');
        });
        $vw->list = $list;
        $vw->user_id = $user->id;
        $vw->name = $user->name;
        $vw->start = date('m/d/Y', strtotime($start));
        $vw->end = date('m/d/Y', strtotime($end));
        return $vw;
    }

}

