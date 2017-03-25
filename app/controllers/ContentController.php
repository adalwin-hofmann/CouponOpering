<?php

class ContentController extends BaseController {

    protected $assetCategoryRepository;
    protected $leadEmailRepository;
    protected $features;
    protected $franchiseRepository;
    protected $locationRepository;
    protected $merchantRepository;
    protected $projectTagRepository;
    protected $contestWinnerRepository;
    protected $userRepository;
    protected $zipcodeRepository;

    /**
    *
    * Create a new controller instance.
    *
    * @param UserRepository $users
    *
    * @return void
    */
    public function __construct(
        \AssetRepositoryInterface $assets,
        \AssetCategoryRepositoryInterface $assetCategoryRepository,
        \AssignmentTypeRepositoryInterface $assignmentTypes,
        \BannerEntityRepositoryInterface $bannerEntities,
        \BannerRepositoryInterface $banners,
        \CategoryRepositoryInterface $categories,
        \ContestRepositoryInterface $contests,
        \DealerRelationRepositoryInterface $dealerRelations,
        \DealerBrandRepositoryInterface $dealerBrands,
        \LeadEmailRepositoryInterface $leadEmailRepository,
        FeatureRepositoryInterface $features,
        \FranchiseRepositoryInterface $franchiseRepository,
        \LocationRepositoryInterface $locationRepository,
        \MerchantRepositoryInterface $merchantRepository,
        \ProjectTagRepositoryInterface $projectTagRepository,
        \ContestWinnerRepositoryInterface $contestWinnerRepository,
        \NewsletterScheduleRepositoryInterface $schedules,
        \UserAssignmentTypeRepositoryInterface $userAssignmentTypes,
        \UserRepositoryInterface $userRepository, 
        \VehicleMakeRepositoryInterface $vehicleMakes,
        \ZipcodeRepositoryInterface $zipcodeRepository,
        \ContestRepositoryInterface $contests,
        \CompanyEventRepositoryInterface $companyEvents,
        \CompanyEventAttendeeRepositoryInterface $companyEventAttendees,
        \ContestDisclaimerRepositoryInterface $contestDisclaimers,
        \YipitBannedMerchantRepositoryInterface $yipitBannedMerchant
    )
    {
        $this->beforeFilter(function()
        {
            if(!Auth::check())
            {
                return Redirect::to('/login');
            }
            else
            {
                $user = Auth::User();
                $found = $this->userRepository->checkType($user, 'content');
                if(!$found)
                {
                    return Redirect::to('/login');
                }
            }
                
        }, array('except' => array('getLogin', 'postLogin', 'getLogout', 'getConversion', 'postConversion', 'getPasswordresetemail', 'getResetPassword', 'postResetPassword')));

        $this->assets = $assets;
        $this->assetCategoryRepository = $assetCategoryRepository;
        $this->assignmentTypes = $assignmentTypes;
        $this->bannerEntities = $bannerEntities;
        $this->banners = $banners;
        $this->categories = $categories;
        $this->contests = $contests;
        $this->dealerRelations = $dealerRelations;
        $this->dealerBrands = $dealerBrands;
        $this->leadEmailRepository = $leadEmailRepository;
        $this->features = $features;
        $this->franchiseRepository = $franchiseRepository;
        $this->locationRepository = $locationRepository;
        $this->merchantRepository = $merchantRepository;
        $this->projectTagRepository = $projectTagRepository;
        $this->contestWinnerRepository = $contestWinnerRepository;
        $this->schedules = $schedules;
        $this->userAssignmentTypes = $userAssignmentTypes;
        $this->userRepository = $userRepository;
        $this->vehicleMakes = $vehicleMakes;
        $this->zipcodeRepository = $zipcodeRepository;
        $this->contests = $contests;
        $this->companyEvents = $companyEvents;
        $this->companyEventAttendees = $companyEventAttendees;
        $this->ContestDisclaimerRepository = $contestDisclaimers;
        $this->yipitBannedMerchant = $yipitBannedMerchant;
    }

    public function getConversion()
    {
        $code = array();
        $code[] = View::make('admin.content.jscode.conversion');
        $vw = View::make('admin.master.templates.conversion')->with('code', implode(' ', $code));
        $vw->navbar = false;
        $vw->sidebar = false;
        $vw->title = "Save On";
        $vw->description = "Save On";
        return $vw;
    }

    public function postConversion()
    {
        $csvs = Input::file();
        $inputData = Input::all();
        $aFiles = array();
        if(!empty($inputData))
        {
            foreach($csvs as $csv)
            {
                $temp_file = $csv->getRealPath();
                $filename = $csv->getClientOriginalName();
                if(preg_match('/.+\.(csv)/i', $filename))
                {
                    $aReturn = SoeHelper::csvToText($temp_file, $filename);
                    $aFiles = array_merge($aReturn, $aFiles);
                }
            }
        }
        return $aFiles;
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
        $vw = View::make('admin.content.dashboard')->with('code', implode(' ', $code));
        $vw->primary_nav = "dashboard";
        $vw->secondary_nav = "dashboard";
        $vw->viewing = 0;
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
            if($this->userRepository->checkType($user, 'content'))
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

    public function getNewsletterAdmin()
    {
        $code = array();
        $code[] = View::make('admin.content.jscode.newsletter-admin');
        $vw = View::make('admin.content.newsletter-admin')->with('code', implode(' ', $code));
        $schedules = $this->schedules->getAll();
        $categories = $this->categories->getByParentId(0);
        $vw->categories = $categories;
        $vw->schedules = $schedules;
        $vw->primary_nav = "newsletters";
        $vw->secondary_nav = "newsletters";
        
        return $vw;
    }

    public function getEvents()
    {
        $code = array();
        $code[] = View::make('admin.content.jscode.events');
        $vw = View::make('admin.content.events')->with('code', implode(' ', $code));

        $vw->primary_nav = "events";
        $vw->secondary_nav = "events";

        $vw->active_events = $this->companyEvents->getOrderDate();

        return $vw;
    }

    public function postWizardLeadsConfirmed()
    {
        $franchise = $this->franchiseRepository->find(\Input::get('franchise_id'));
        $confirmed = \Input::get('confirmed', 0);
        if($confirmed == 1 && $franchise->is_leads_confirmed == 0)
            \Event::fire('test-lead.confirmed', array($franchise));
        $franchise->is_leads_confirmed = $confirmed;
        $franchise->save();
    }

    public function postWizardReactivationNotice()
    {
        $franchise = $this->franchiseRepository->find(\Input::get('franchise_id'));
        if($franchise)
            \Event::fire('dealer.reactivated', array($franchise));
    }

    public function getWizard()
    {
        $code = array();
        $code[] = View::make('admin.content.jscode.wizard');
        $vw = View::make('admin.content.wizard')->with('code', implode(' ', $code));

        $franchise_id = Input::get('viewing', 0);
        $vw->franchise_id = $franchise_id;
        $franchise = Franchise::find($franchise_id, true);
        $vw->merchant_id = empty($franchise) ? 0 : $franchise->merchant_id;
        $sales = SOE\DB\AssignmentType::where('name', '=', 'Sales Person')->first();
        if(!empty($sales))
        {
            $sales_reps = SOE\DB\User::join('user_assignment_types', 'users.id', '=', 'user_assignment_types.user_id')
                                    ->where('user_assignment_types.assignment_type_id', '=', $sales->id)
                                    ->orderBy('users.name')
                                    ->get(array('users.id', 'users.name'));
            $vw->sales = $sales_reps;
        }
        else
        {
            $vw->sales = array();
        }
        $vw->categories = SOE\DB\Category::where('parent_id', '=', 0)
                                        ->orderBy('name')
                                        ->get();
        $vw->project_tags = $this->projectTagRepository->getChildTags();
        $vw->makes = $this->vehicleMakes->getActiveMakes();
        $vw->primary_nav = "wizard";
        $vw->secondary_nav = "wizard";
        $vw->viewing = Input::get('viewing', 0);
        $vw->districts = SOE\DB\District::where('is_active', '1')->orderBy('name')->get();
        $vw->companies = SOE\DB\Company::all();
        return $vw;
    }

    public function getDealerMatching()
    {
        $code = array();
        $code[] = View::make('admin.content.jscode.dealer-matching');
        $vw = View::make('admin.content.dealer-matching')->with('code', implode(' ', $code));

        $unlinked = $this->dealerRelations->getUnlinked();
        $dealers = $this->franchiseRepository->getLinkable();
        $vw->unlinked = $unlinked;
        $vw->dealers = $dealers;
        $vw->primary_nav = "wizard";
        $vw->secondary_nav = "dealer-matching";
        return $vw;
    }

    public function postDealerMatching()
    {
        $code = array();
        $code[] = View::make('admin.content.jscode.dealer-matching');
        $vw = View::make('admin.content.dealer-matching')->with('code', implode(' ', $code));

        if(Input::get('unlinked_id') != 0 && Input::get('franchise_id') != 0)
        {
            $this->dealerRelations->linkDealer(Input::get('unlinked_id'), Input::get('franchise_id'));
        }

        $unlinked = $this->dealerRelations->getUnlinked();
        $dealers = $this->franchiseRepository->getLinkable();
        $vw->unlinked = $unlinked;
        $vw->dealers = $dealers;
        $vw->primary_nav = "wizard";
        $vw->secondary_nav = "dealer-matching";
        return $vw;
    }

    public function getLocation()
    {
        $code = array();
        $code[] = View::make('admin.content.jscode.location-wizard');
        $vw = View::make('admin.content.location-wizard')->with('code', implode(' ', $code));
        $states = SoeHelper::states();
        $vw->states = $states['USA']['states'];
        $franchise_id = Input::get('viewing', 0);
        $vw->franchise_id = $franchise_id;
        $franchise = Franchise::find($franchise_id, true);
        $vw->merchant_id = $franchise->merchant_id;
        $vw->companies = SOE\DB\Company::where('is_active', '=', '1')->orderBy('name', 'asc')->get();

        $vw->primary_nav = "wizard";
        $vw->secondary_nav = "locations";
        $vw->viewing = Input::get('viewing', 0);
        return $vw;
    }

    public function getCoupon()
    {
        $code = array();
        $code[] = View::make('admin.content.jscode.coupon-wizard');
        $vw = View::make('admin.content.coupon-wizard')->with('code', implode(' ', $code));
        $franchise_id = Input::get('viewing', 0);
        $franchise = Franchise::find($franchise_id, true);
        $vw->franchise_id = $franchise_id;
        $vw->merchant_id = $franchise->merchant_id;
        $vw->categories = SOE\DB\Category::where('parent_id', '=', '0')->orderBy('name', 'asc')->get();
        $locations = SOE\DB\Location::where('franchise_id', '=', $franchise_id)->take(100)->orderBy('name', 'asc')->get();
        $vw->locations = empty($locations) ? array() : $locations;
        $vw->makes = $this->vehicleMakes->getActiveMakes();

        $vw->primary_nav = "wizard";
        $vw->secondary_nav = "coupons";
        $vw->viewing = Input::get('viewing', 0);
        return $vw;
    }

    public function getEvent()
    {
        $code = array();
        $code[] = View::make('admin.content.jscode.event-wizard');
        $vw = View::make('admin.content.event-wizard')->with('code', implode(' ', $code));
        $franchise_id = Input::get('viewing', 0);
        $franchise = Franchise::find($franchise_id, true);
        $vw->franchise_id = $franchise_id;
        $vw->merchant_id = $franchise->merchant_id;
        $vw->categories = SOE\DB\Category::where('parent_id', '=', '0')->orderBy('name', 'asc')->get();
        $locations = SOE\DB\Location::where('franchise_id', '=', $franchise_id)->take(100)->orderBy('name', 'asc')->get();
        $vw->locations = empty($locations) ? array() : $locations;

        $vw->primary_nav = "wizard";
        $vw->secondary_nav = "events";
        $vw->viewing = Input::get('viewing', 0);
        return $vw;
    }

    public function getBanner()
    {
        $code = array();
        $code[] = View::make('admin.content.jscode.banner-wizard');
        $vw = View::make('admin.content.banner-wizard')->with('code', implode(' ', $code));
        $franchise_id = Input::get('viewing', 0);
        $franchise = Franchise::find($franchise_id, true);
        $vw->franchise_id = $franchise_id;
        $vw->franchise = $franchise;
        $vw->merchant_id = $franchise->merchant_id;
        $locations = SOE\DB\Location::where('franchise_id', '=', $franchise_id)->take(100)->orderBy('name', 'asc')->get();
        $vw->locations = empty($locations) ? array() : $locations;

        $vw->primary_nav = "wizard";
        $vw->secondary_nav = "banners";
        $vw->viewing = $franchise_id;
        return $vw;
    }

    public function getAbout()
    {
        $code = array();
        $code[] = View::make('admin.content.jscode.about-wizard');
        $vw = View::make('admin.content.about-wizard')->with('code', implode(' ', $code));
        $franchise_id = Input::get('viewing', 0);
        //$franchise = Franchise::find($franchise_id, true);
        $franchise = $this->franchiseRepository->find($franchise_id, true);
        $locations = $this->locationRepository->getByFranchise($franchise);
        $vw->locations = $locations;
        $vw->merchant_id = $franchise->merchant_id;
        $vw->franchise_id = $franchise->id;
        $merchant = Merchant::find($franchise->merchant_id);
        $vw->about = empty($merchant) ? '' : $merchant->about;
        $vw->page_title = empty($merchant) ? '' : $merchant->page_title;
        $vw->keywords = empty($merchant) ? '' : $merchant->keywords;
        $vw->meta_description = empty($merchant) ? '' : $merchant->meta_description;
        $vw->subheading = $merchant->sub_heading;
        $vw->national_prospect = empty($merchant) ? 0 : ($merchant->type == 'PROSPECT' ? 1 : 0);// && $merchant->is_national ? 1 : 0);

        $vw->primary_nav = "wizard";
        $vw->secondary_nav = "about";
        $vw->viewing = Input::get('viewing', 0);
        return $vw;
    }

    public function getPictures()
    {
        $code = array();
        $code[] = View::make('admin.content.jscode.pictures-wizard');
        $vw = View::make('admin.content.pictures-wizard')->with('code', implode(' ', $code));
        $franchise_id = Input::get('viewing', 0);
        $franchise = $this->franchiseRepository->find($franchise_id, true);
        $vw->franchise_id = $franchise_id;
        $vw->merchant_id = $franchise->merchant_id;
        $vw->locations = $this->locationRepository->getByFranchise($franchise);

        $vw->primary_nav = "wizard";
        $vw->secondary_nav = "pictures";
        $vw->viewing = Input::get('viewing', 0);
        return $vw;
    }

    public function getVideo()
    {
        $code = array();
        $code[] = View::make('admin.content.jscode.video-wizard');
        $vw = View::make('admin.content.video-wizard')->with('code', implode(' ', $code));
        $franchise_id = Input::get('viewing', 0);
        $franchise = $this->franchiseRepository->find($franchise_id, true);
        $vw->franchise_id = $franchise->id;
        $vw->merchant_id = $franchise->merchant_id;
        $vw->locations = $this->locationRepository->getByFranchise($franchise);

        $vw->primary_nav = "wizard";
        $vw->secondary_nav = "video";
        $vw->viewing = Input::get('viewing', 0);
        return $vw;
    }

    public function getPdf()
    {
        $code = array();
        $code[] = View::make('admin.content.jscode.pdf-wizard');
        $vw = View::make('admin.content.pdf-wizard')->with('code', implode(' ', $code));
        $franchise_id = Input::get('viewing', 0);
        $franchise = $this->franchiseRepository->find($franchise_id, true);
        $vw->franchise_id = $franchise_id;
        $vw->merchant_id = $franchise->merchant_id;
        $vw->locations = $this->locationRepository->getByFranchise($franchise);

        $vw->primary_nav = "wizard";
        $vw->secondary_nav = "pdfs";
        $vw->viewing = Input::get('viewing', 0);
        return $vw;
    }

    public function getSyndication()
    {
        $code = array();
        $code[] = View::make('admin.content.jscode.syndication-wizard');
        $vw = View::make('admin.content.syndication-wizard')->with('code', implode(' ', $code));
        $franchise_id = Input::get('viewing', 0);
        $franchise = Franchise::find($franchise_id, true);
        $vw->franchise_id = $franchise->id;
        $vw->merchant_id = $franchise->merchant_id;
        $vw->franchise = $franchise;

        $vw->primary_nav = "wizard";
        $vw->secondary_nav = "syndication";
        $vw->viewing = Input::get('viewing', 0);
        return $vw;
    }


    public function getFinish()
    {
        $code = array();
        $code[] = View::make('admin.content.jscode.finish-wizard');
        $vw = View::make('admin.content.finish-wizard')->with('code', implode(' ', $code));
        $franchise_id = Input::get('viewing', 0);
        $franchise = Franchise::find($franchise_id, true);
        $vw->franchise_id = $franchise->id;
        $vw->merchant_id = $franchise->merchant_id;

        $vw->primary_nav = "wizard";
        $vw->secondary_nav = "finish";
        $vw->viewing = Input::get('viewing', 0);
        return $vw;
    }

    public function getUsers()
    {
        $code = array();
        $code[] = View::make('admin.content.jscode.users');
        $vw = View::make('admin.content.users')->with('code', implode(' ', $code));
        $vw->primary_nav = "users";
        $vw->secondary_nav = "users";
        $vw->assignmentTypes = $this->assignmentTypes->query()->orderBy('name')->get();
        return $vw;
    }

    public function postFranchiseUser()
    {
        $user_id = Input::get('user_id');
        $franchise_id = Input::get('franchise_id');
        $franchise = $this->userRepository->setFranchise($user_id, $franchise_id);
        return $franchise;
    }

    public function postDeleteFranchiseUser()
    {
        $user_id = Input::get('user_id');
        $franchise_id = Input::get('franchise_id');
        return $this->userRepository->removeFranchise($user_id, $franchise_id);
    }

    public function postWizardBannerPackage()
    {
        $franchise = $this->franchiseRepository->find(Input::get('franchise_id'));
        if(!$franchise)
            return;
        $franchise->banner_package = Input::get('banner_package', '');
        if($franchise->banner_package != '')
            $franchise->is_featured = 1;
        $franchise->save();
    }

    public function postWizardDeleteBanner()
    {
        $banner_id = Input::get('banner_id');
        $this->banners->query()->where('id', $banner_id)->delete();
        $this->bannerEntities->query()->where('banner_id', $banner_id)->delete();
    }

    public function postUpdateUser()
    {
        $user_id = Input::get('user_id');
        $type = Input::get('type');
        $name = Input::get('name');
        $password = Input::get('password', null);
        $user = $this->userRepository->find($user_id);
        $user->type = Input::get('type');
        $user->name = Input::get('name');
        $assignmentTypes = Input::get('assignment_types', '');
        $aTypes = explode(',', $assignmentTypes);
        $userTypes = $this->userAssignmentTypes->query()->where('user_id', $user_id)->get();
        $aUTypes = array();
        foreach($userTypes as $uType)
        {
            $aUTypes[] = $uType->assignment_type_id;
        }

        $removed = array_diff($aUTypes, $aTypes);
        $added = array_diff($aTypes, $aUTypes);
        if(count($removed))
        {
            $this->userAssignmentTypes->query()
                    ->where('user_id', $user_id)
                    ->whereIn('assignment_type_id', $removed)
                    ->delete();

            SOE\DB\FranchiseAssignment::where('user_id', $user_id)
                ->whereIn('assignment_type_id', $removed)
                ->delete();
        }
        foreach($added as $add)
        {
            $this->userAssignmentTypes->create(array(
                'user_id' => $user_id,
                'assignment_type_id' => $add
            ));
        }

        if($password)
        {
            $user->password = $password;
        }
        $user->save();
        Event::fire('backoffice.updated', array('user', $user->id, Auth::user()->id, $user, 'user updated'));
        return $user;
    }

    public function getContests()
    {
        $code = array();
        $code[] = View::make('admin.content.jscode.contests');
        $vw = View::make('admin.content.contests')->with('code', implode(' ', $code));
        $vw->primary_nav = "contests";
        $vw->secondary_nav = "contests";
        $vw->categories = SOE\DB\Category::where('parent_id', '=', '0')->orderBy('name', 'asc')->get();
        return $vw;
    }

    public function getWinners()
    {
        $code = array();
        $code[] = View::make('admin.content.jscode.winners');
        $vw = View::make('admin.content.winners')->with('code', implode(' ', $code));
        $vw->primary_nav = "contests";
        $vw->secondary_nav = "winners";
        return $vw;
    }

    public function getWinnersCsv()
    {
        $applicants = $this->contests->getApplicantUsers(
            \Input::get('contest_id'), 
            \Input::get('name', null), 
            \Input::get('start', null), 
            \Input::get('end', null), 
            0, 
            0
        );

        // Set CSV Headers
        $csv_data = array(array('Name', 'Email', 'Entered At'));

        foreach($applicants['objects'] as $applicant)
        {
            $csv_data[] = array(
                $applicant->user_name,
                $applicant->user_email,
                $applicant->created_at
            );
        }

        header('Content-Type: application/csv');
        header('Content-Disposition: attachement; filename="applicants.csv";');

        $file = fopen('php://output', 'w');
        foreach($csv_data as $row) {
            fputcsv($file, $row, ',');
        }
    }

    public function getContestReport()
    {
        $contests = $applicants = $this->contests->getAllWinnersDetail();

        $code = array();
        $code[] = View::make('admin.content.jscode.contest-report');
        $vw = View::make('admin.content.contest-report')->with('code', implode(' ', $code));
        $vw->primary_nav = "contests";
        $vw->secondary_nav = "contest-report";

        $vw->contests = $contests['objects'];
        return $vw;
    }

    public function postContestReportWinnerSave()
    {
        $winner_id = Input::get('winner_id');
        $first_name = Input::get('first_name');
        $last_name = Input::get('last_name');
        $email = Input::get('email');
        $address = Input::get('address');
        $city = Input::get('city');
        $state = Input::get('state');
        $zip = Input::get('zip');         
        $contestWinner = SOE\DB\ContestWinner::find($winner_id);
        if(!empty($contestWinner))
        {
            $contestWinner->first_name = $first_name;
            $contestWinner->last_name = $last_name;
            $contestWinner->email = $email;
            $contestWinner->address = $address;
            $contestWinner->city = $city;
            $contestWinner->state = $state;
            $contestWinner->zip = $zip;
            $contestWinner->save();
            $ContestDisclaimer = SOE\DB\ContestDisclaimer::where('contest_winner_id','=',$winner_id)->first();
            
            if(empty($ContestDisclaimer))
            {
                $disclaimer = $this->contestDisclaimers->blank();
            } else {
                $disclaimer = $ContestDisclaimer;
            }
            
            $disclaimer->name = $first_name.' '.$last_name;
            $disclaimer->winner_name = $first_name.' '.$last_name;
            $disclaimer->email = $email;
            $disclaimer->address = $address;
            $disclaimer->city_state_zip = $city.', '.$state.' '.$zip;
            $disclaimer->save();

            return $this->contests->getWinnerInfo($contestWinner->id);
        }
    }

    public function getGalleryUpload()
    {
        $code = array();
        $code[] = View::make('admin.content.jscode.gallery-upload');
        $code[] = View::make('home.jscode.masonry');
        $vw = View::make('admin.content.gallery-upload')->with('code', implode(' ', $code));
        $vw->primary_nav = "gallery";
        $vw->secondary_nav = "upload";
        $categories = SOE\DB\AssetCategory::where('parent_id', '=', 0)->orderBy('name')->get();
        $vw->categories = $categories;
        return $vw;
    }

    public function getWizardMerchant()
    {
        $franchise_id = Input::get('franchise_id', 0);
        $franch = $this->franchiseRepository->find($franchise_id, true);
        $merchant_id = empty($franch) ? Input::get('merchant_id', 0) : $franch->merchant_id;
        $merchant = SOE\DB\Merchant::find($merchant_id);
        if(empty($merchant))
        {
            return json_encode(array());
        }
        
        //Get Content Specialists
        $content = SOE\DB\AssignmentType::where('name', '=', 'Content Specialist')->first();
        if(empty($content))
        {
            $content_users = array();
            $assigned_content_users = array();
        }
        else
        {
            $content_users = SOE\DB\User::join('user_assignment_types', 'users.id', '=', 'user_assignment_types.user_id')->where('user_assignment_types.assignment_type_id', '=', $content->id)->get(array('users.id', 'users.name'));
            $assigned_content_users = SOE\DB\User::join('franchise_assignments', 'franchise_assignments.user_id', '=', 'users.id')->where('franchise_id', '=', $franchise_id)->where('assignment_type_id', '=', $content->id)->get(array('users.id', 'users.name'));
        }
        
        $aCU = array('objects' => array());
        $aACU = array('objects' => array());
        foreach($content_users as $content_user)
        {
            $cu = User::blank();
            $cu = $cu->createFromModel($content_user);
            $aCU['objects'][] = $cu;
        }
        foreach($assigned_content_users as $assigned_content_user)
        {
            $acuser = User::blank();
            $acuser = $acuser->createFromModel($assigned_content_user);
            $aACU['objects'][] = $acuser;
        }
        $merchant->content_users = $aCU;
        $merchant->assigned_content_users = $aACU;

        //Get Sales Reps
        $sales = SOE\DB\AssignmentType::where('name', '=', 'Sales Person')->first();
        if(empty($sales))
        {
            $sales_users = array();
            $assigned_sales_users = array();
        }
        else
        {
            $sales_users = SOE\DB\User::join('user_assignment_types', 'users.id', '=', 'user_assignment_types.user_id')->where('user_assignment_types.assignment_type_id', '=', $sales->id)->get(array('users.id', 'users.name'));
            $assigned_sales_users = SOE\DB\User::join('franchise_assignments', 'franchise_assignments.user_id', '=', 'users.id')->where('franchise_id', '=', $franchise_id)->where('assignment_type_id', '=', $sales->id)->get(array('users.id', 'users.name'));
        }
        
        $aSU = array('objects' => array());
        $aASU = array('objects' => array());
        foreach($sales_users as $sales_user)
        {
            $su = User::blank();
            $su = $su->createFromModel($sales_user);
            $aSU['objects'][] = $su;
        }
        foreach($assigned_sales_users as $asuser)
        {
            $asu = User::blank();
            $asu = $asu->createFromModel($asuser);
            $aASU['objects'][] = $asu;
        }
        $merchant->sales_users = $aSU;
        $merchant->assigned_sales_users = $aASU;

        //Get CSR's
        $csr = SOE\DB\AssignmentType::where('name', '=', 'CSR')->first();
        if(empty($csr))
        {
            $csr_users = array();
            $assigned_csr_users = array();
        }
        else
        {
            $csr_users = SOE\DB\User::join('user_assignment_types', 'users.id', '=', 'user_assignment_types.user_id')->where('user_assignment_types.assignment_type_id', '=', $csr->id)->get(array('users.id', 'users.name'));
            $assigned_csr_users = SOE\DB\User::join('franchise_assignments', 'franchise_assignments.user_id', '=', 'users.id')->where('franchise_id', '=', $franchise_id)->where('assignment_type_id', '=', $csr->id)->get(array('users.id', 'users.name'));
        }

        $aCSRU = array('objects' => array());
        $aACSRU = array('objects' => array());
        foreach($csr_users as $csr_user)
        {
            $csru = User::blank();
            $csru = $su->createFromModel($csr_user);
            $aCSRU['objects'][] = $csru;
        }
        foreach($assigned_csr_users as $acsruser)
        {
            $acsru = User::blank();
            $acsru = $asu->createFromModel($acsruser);
            $aACSRU['objects'][] = $acsru;
        }
        $merchant->csr_users = $aCSRU;
        $merchant->assigned_csr_users = $aACSRU;

        //Include created by user
        $c = SOE\DB\User::find($merchant->created_by);
        $merchant->created_by_user = empty($c) ? '' : $c->name;

        //Include updated by user
        $u = SOE\DB\User::find($merchant->updated_by);
        $merchant->updated_by_user = empty($u) ? '' : $u->name;
        //$merch = Merchant::blank();
        //$merch = $merch->createFromModel($merchant);
        $merchant->magazinemanager_id = empty($franch) ? '' : $franch->magazinemanager_id;
        $merchant->franchise = $franch ? $franch->toArray() : array();
        $merchant->franchise_active = empty($franch) ? '' :$franch->is_active;
        $merchant->franchise_deleted = empty($franch) ? 0 : ($franch->deleted_at ? 1 : 0);
        $leadEmails = $franch ? $this->leadEmailRepository->getByFranchise($franch) : array();
        $aEmails = array();
        foreach($leadEmails as $email)
        {
            $aEmails[] = $email->toArray();
        }
        $districts = \SOE\DB\FranchiseDistrict::where('franchise_id', $franchise_id)->lists('district_id');
        $merchant->districts = $districts;
        $merchant->lead_emails = $aEmails;
        $merchant->is_featured = empty($franch) ? 0 : $franch->is_featured;
        $merchant->primary_contact = empty($franch) ? '' : $franch->primary_contact;
        //$merchant->is_offer_notifications = empty($franch) ? 0 : $franch->is_offer_notifications;
        $merchant->contract_start = empty($franch) ? '' : $franch->contract_start;
        $merchant->contract_end = empty($franch) ? '' : $franch->contract_end;
        $merchant->is_permanent = empty($franch) ? '' : $franch->is_permanent;
        return $merchant->toJson();//$this->format($merch);
    }

    public function getWizardLocation($location_id)
    {
        $location = Location::find($location_id, true);
        if(empty($location))
        {
            return Response::json(0);
        }
        $created = User::find($location->created_by);
        if(empty($created))
        {
            $location->created_by_user = '';
        }
        else
        {
            $location->created_by_user = $created->name;
        }

        $updated = User::find($location->updated_by);
        if(empty($updated))
        {
            $location->updated_by_user = '';
        }
        else
        {
            $location->updated_by_user = $updated->name;
        }
        $hours = SOE\DB\LocationHour::where('location_id', '=', $location_id)->get();
        $aHours = array('objects' => array());
        foreach($hours as $hour)
        {
            $h = LocationHour::blank();
            $h = $h->createFromModel($hour);
            $aHours['objects'][] = $h;
        }
        $location->hours_array = $aHours;

        return $this->format($location);
    }

    public function getWizardSearch()
    {
        $merchant_search = Input::get('merchant_search', 0);
        if($merchant_search)
        {
            $query = SOE\DB\Merchant::where('merchants.id', '>', '0');    
        }
        else
        {
            $query = SOE\DB\Franchise::withTrashed()->join('merchants', 'merchants.id', '=', 'franchises.merchant_id')->where('merchants.id', '>', '0');
        }

        if($name = Input::get('name'))
        {
            $query = $query->where('merchants.display', 'LIKE', '%'.$name.'%');
        }

        if($category_id = Input::get('category'))
        {
            $query = $query->where('merchants.category_id', '=', $category_id);
        }

        if($subcategory_id = Input::get('subcategory'))
        {
            $query = $query->where('merchants.subcategory_id', '=', $subcategory_id);
        }

        if($status = Input::get('status'))
        {
            $query = $query->where('merchants.is_active', '=', $status);
        }

        if(Input::has('demo') || Input::get('demo') === '0')
        {
            $query = $query->where('merchants.is_demo', '=', Input::get('demo'));
        }

        if($date_range = Input::get('date_range'))
        {
            $date_type = Input::get('date_type');
            switch ($date_range) {
                case 'Today':
                    $query = $query->rawWhere('DAYOFYEAR(merchants.'.$date_type.')=DAYOFYEAR(NOW())');
                    break;
                case 'Yesterday':
                    $query = $query->where('merchants.'.$date_type, '<=', DB::raw("DATE_FORMAT(NOW(), '%Y-%m-%d 00:00:00')"))->where('merchants.'.$date_type, '>=', DB::raw("DATE_SUB(DATE_FORMAT(NOW(), '%Y-%m-%d 00:00:00'), INTERVAL 1 DAY)"));
                    break;
                case 'Last7':
                    $query = $query->where('merchants.'.$date_type, '<=', DB::raw("DATE_FORMAT(NOW(), '%Y-%m-%d 00:00:00')"))->where('merchants.'.$date_type, '>=', DB::raw("DATE_SUB(DATE_FORMAT(NOW(), '%Y-%m-%d 00:00:00'), INTERVAL 7 DAY)"));
                    break;
                case 'Last30':
                    $query = $query->where('merchants.'.$date_type, '<=', DB::raw("DATE_FORMAT(NOW(), '%Y-%m-%d 00:00:00')"))->where('merchants.'.$date_type, '>=', DB::raw("DATE_SUB(DATE_FORMAT(NOW(), '%Y-%m-%d 00:00:00'), INTERVAL 30 DAY)"));
                    break;
                default:
                    # code...
                    break;
            }
        }
        
        $query = $this->getOrder($query);
        $stats = $this->pagination_stats($query);
        $query = $this->paginate($query);
        
        if($merchant_search)
        {
            $merchants = $query->get(array('merchants.*'));
        }
        else
        {
            $merchants = $query->get(array('franchises.*', 'merchants.display', 'merchants.type', 'merchants.category_id', 'merchants.subcategory_id'));
        }

        $return = array('objects' => array());
        foreach($merchants as $merchant)
        {
            if($merchant_search)
            {
                $merch = Merchant::blank();
                $merch = $merch->createFromModel($merchant);
                $merch->total_locations = SOE\DB\Location::where('merchant_id', '=', $merchant->id)->count();
            }
            else
            {
                $merch = Franchise::blank();
                $merch = $merch->createFromModel($merchant);
                $merch->total_locations = SOE\DB\Location::where('merchant_id', '=', $merchant->merchant_id)->count();
                $sales = \SOE\DB\FranchiseAssignment::where('franchise_id', $merchant->id)
                    ->where('assignment_type_id', '1')
                    ->join('users', 'franchise_assignments.user_id', '=', 'users.id')
                    ->first(array('users.*'));
                $merch->sales = $sales ? $sales->name : '';
            }
            $cat = SOE\DB\Category::find($merchant->category_id);
            $subcat = SOE\DB\Category::find($merchant->subcategory_id);
            $merch->cat_name = empty($cat) ? '' : $cat->name;
            $merch->subcat_name = empty($subcat) ? '' : $subcat->name;
            
            $return['objects'][] = $merch;
        }

        $response = array_merge($return, $stats);
        
        return $this->format($response);
    }

    public function postWizardUpdateMerchant($franchise_id)
    {
        if($franchise_id == 0)
        {
            $partial = Input::get('partial', 0);
            $merchant = $this->createFranchise($partial);
            //Include updated by user
            $u = SOE\DB\User::find($merchant->updated_by);
            $merchant->updated_by_user = empty($u) ? '' : $u->name;
            $merchant->created_by_user = empty($u) ? '' : $u->name;

            return $this->format($merchant);
        }
        $franchise = $this->franchiseRepository->find($franchise_id, true);
        $merchant = $this->merchantRepository->find($franchise->merchant_id);

        if(empty($merchant))
        {
            return json_encode(0);
        }
        if(Input::has('display') || Input::get('display') === '')
        {
            $display = Input::get('display');
            $merchant->display = $display;
            $merchant->name = $this->removePunctuation($display);
            $merchant->slug = $this->getSlug($display);
            SOE\DB\Location::where('merchant_id', '=', $merchant->id)->update(array('merchant_name' => $display, 'merchant_slug' => $this->getSlug($display)));
            SOE\DB\Entity::where('merchant_id', '=', $merchant->id)->update(array('merchant_name' => $display, 'merchant_slug' => $this->getSlug($display)));
        }
        if(Input::has('merchant_type') || Input::get('merchant_type') === '')
        {
            $merchant_type = Input::get('merchant_type');
            $merchant->type = $merchant_type;
        }
        if(Input::has('catchphrase') || Input::get('catchphrase') === '')
        {
            $catchphrase = Input::get('catchphrase');
            $merchant->catchphrase = $catchphrase;
        }
        if(Input::has('about') || Input::get('about') === '')
        {
            $about = Input::get('about');
            $merchant->about = $about;
        }
        if(Input::has('keywords') || Input::get('keywords') === '')
        {
            $keywords = Input::get('keywords');
            $merchant->keywords = $keywords;
        }
        if(Input::has('status') || Input::get('status') === '0')
        {
            $status = Input::get('status');
            $franchise->is_active = $status;
            $merchant->is_active = $status;
            $offers = SOE\DB\Offer::where('franchise_id', '=', $franchise->id)->get(array('id'));
            $aOfferIds = array(0);
            foreach($offers as $offer)
            {
                $aOfferIds[] = $offer->id;
            }
            SOE\DB\Entity::whereIn('entitiable_id', $aOfferIds)->where('entitiable_type', '=', 'Offer')->update(array('franchise_active' => $status));
        }
        if(Input::has('is_deleted') || Input::get('is_deleted') === '0')
        {
            $is_deleted = Input::get('is_deleted');
            if($is_deleted == 1)
            {
                $offers = SOE\DB\Offer::where('franchise_id', '=', $franchise->id)->get(array('id'));
                $aOfferIds = array(0);
                foreach($offers as $offer)
                {
                    $aOfferIds[] = $offer->id;
                }
                SOE\DB\Entity::whereIn('entitiable_id', $aOfferIds)->where('entitiable_type', '=', 'Offer')->delete();

                $locations = SOE\DB\Location::where('franchise_id', '=', $franchise->id)->delete();
                Event::fire('backoffice.updated', array('franchise', $franchise->id, Auth::user()->id, $franchise, 'franchise deleted'));
                $franchise->delete();
                $u = User::find($merchant->updated_by);
                $merchant->updated_by = Auth::check() ? Auth::User()->id : 0;
                Event::fire('backoffice.updated', array('merchant', $merchant->id, Auth::user()->id, $merchant, 'merchant deleted'));
                $merchant->save();
                $merchant->updated_by_user = empty($u) ? '' : $u->name;
                $merch = Merchant::blank();
                $merch = $merch->createFromModel($merchant);

                return $this->format($merchant);
            }
            else
            {
                $offers = SOE\DB\Offer::where('franchise_id', '=', $franchise->id)->get(array('id'));
                $aOfferIds = array(0);
                foreach($offers as $offer)
                {
                    $aOfferIds[] = $offer->id;
                }
                SOE\DB\Entity::onlyTrashed()->whereIn('entitiable_id', $aOfferIds)->where('entitiable_type', '=', 'Offer')->restore();
                $locations = SOE\DB\Location::withTrashed()->where('franchise_id', '=', $franchise_id)->restore();
                $franchise->restore();
                Event::fire('backoffice.updated', array('franchise', $franchise->id, Auth::user()->id, $franchise, 'franchise restored'));
            }
        }
        if(Input::has('parentcategory_id') || Input::get('parentcategory_id') === '')
        {
            $parentcategory_id = Input::get('parentcategory_id');
            $merchant->category_id = $parentcategory_id;
        }
        if(Input::has('category_id') || Input::get('category_id') === '')
        {
            $category_id = Input::get('category_id');
            $merchant->subcategory_id = $category_id;
        }
        if(Input::has('facebook') || Input::get('facebook') === '')
        {
            $facebook = Input::get('facebook');
            $merchant->facebook = $facebook;
        }
        if(Input::has('twitter') || Input::get('twitter') === '')
        {
            $twitter = Input::get('twitter');
            $merchant->twitter = $twitter;
        }
        if(Input::has('coupon_tab_type') || Input::get('coupon_tab_type') === '')
        {
            $merchant->coupon_tab_type = Input::get('coupon_tab_type');
        }
        if(Input::has('mobile_redemption') || Input::get('mobile_redemption') === '0')
        {
            $merchant->mobile_redemption = Input::get('mobile_redemption');
        }
        if(Input::has('service_radius') || Input::get('service_radius') === '0')
        {
            $merchant->service_radius = Input::get('service_radius');
        }
        if(Input::has('entity_search_parse') || Input::get('entity_search_parse') === '0')
        {
            $merchant->entity_search_parse = Input::get('entity_search_parse');
        }
        if(Input::has('page_version') || Input::get('page_version') === '0')
        {
            $merchant->page_version = Input::get('page_version');
        }
        if(Input::has('is_offer_notifications') || Input::get('is_offer_notifications') === '0')
        {
            $merchant->is_offer_notifications = Input::get('is_offer_notifications');
        }
        if(Input::has('sponsor_level') || Input::get('sponsor_level') === '')
        {
            $franchise->sponsor_level = Input::get('sponsor_level');
        }
        if(Input::has('company_id'))
        {
            $franchise->company_id = Input::get('company_id');
            if(Input::get('company_id') != 2)
            {
                $merchant->yipitbusiness_id = 0;
            }
        }
        if(Input::has('is_permanent') || Input::get('is_permanent') === '0')
        {
            $franchise->is_permanent = Input::get('is_permanent');
        }
        if(Input::has('contract_start') || Input::get('contract_start') === '')
        {
            $franchise->contract_start = Input::get('contract_start');
        }
        if(Input::has('contract_end') || Input::get('contract_end') === '')
        {
            $franchise->contract_end = Input::get('contract_end');
        }
        if($districts = Input::get('districts'))
        {
            // Get existing entities
            $participating = SOE\DB\FranchiseDistrict::where('franchise_id', $franchise->id)->lists('district_id');

            $remove = array_diff($participating, $districts);
            $add = array_diff($districts, $participating);
            if(count($remove))
                SOE\DB\FranchiseDistrict::where('franchise_id', $franchise_id)->whereIn('district_id', $remove)->delete();
            foreach($add as $newDistrict)
            {
                $district = new SOE\DB\FranchiseDistrict;
                $district->district_id = $newDistrict;
                $district->franchise_id = $franchise->id;
                $district->save();
            }
        }
        if(Input::has('is_featured') || Input::get('is_featured') === false)
        {
            $franchise->is_featured = Input::get('is_featured', false);
        }
        $dirty = $merchant->getDirty();
        if(!empty($dirty))
        {
            $merchant->updated_by = Auth::check() ? Auth::User()->id : 0;
        }

        if(!empty($dirty) && (array_key_exists('service_radius', $dirty)))
        {
            SOE\DB\Entity::where('merchant_id', '=', $merchant->id)->update(array('service_radius' => $merchant->service_radius));
        }
        
        if(!empty($dirty) && (array_key_exists('category_id', $dirty) || array_key_exists('subcategory_id', $dirty)))
        {
            $category = Category::find($merchant->category_id);
            $subcategory = Category::find($merchant->subcategory_id);
            SOE\DB\Entity::where('merchant_id', '=', $merchant->id)->update(array('category_slug' => $category->slug, 'subcategory_slug' => $subcategory->slug, 'category_id' => $category->id, 'subcategory_id' => $subcategory->id));
        }

        $merchant->save();
        Event::fire('backoffice.updated', array('merchant', $merchant->id, Auth::user()->id, $merchant, 'merchant updated'));

        if(Input::has('magazinemanager_id') || Input::get('magazinemanager_id') === '0')
        {
            $franchise->magazinemanager_id = Input::get('magazinemanager_id');
        }

        if(Input::has('primary_contact') || Input::get('primary_contact') === '')
        {
            $franchise->primary_contact = Input::get('primary_contact');
        }

        if(Input::has('content') || Input::get('content') === '')
        {
            //Update the assigned content specialist
            if(Input::get('content') != '')
            {
                $content = SOE\DB\AssignmentType::where('name', '=', 'Content Specialist')->first();
                if(empty($content))
                {
                    return json_encode(0);
                }
                $user = SOE\DB\FranchiseAssignment::where('franchise_id', '=', $franchise->id)->where('assignment_type_id', '=', $content->id)->first();
                if(empty($user))
                {
                    $user = FranchiseAssignment::createinsert(array(
                            'franchise_id' => $franchise_id,
                            'user_id' => Input::get('content'),
                            'assignmenttype_id' => $content->id
                        ));
                }
                else
                {
                    $user->user_id = Input::get('content');
                    $user->save();
                }
                unset($user);
            }
            else
            {
                $content = SOE\DB\AssignmentType::where('name', '=', 'Content Specialist')->first();
                if(empty($content))
                {
                    return json_encode(0);
                }
                $user = SOE\DB\FranchiseAssignment::where('franchise_id', '=', $franchise->id)->where('assignment_type_id', '=', $content->id)->first();
                if(!empty($user))
                {
                    $user->delete();
                }
            }
        }

        if(Input::has('sales') || Input::get('sales') === '')
        {
            //Update the assigned sales person
            if(Input::get('sales') != '')
            {
                $sales = SOE\DB\AssignmentType::where('name', '=', 'Sales Person')->first();
                if(empty($sales))
                {
                    return json_encode(0);
                }
                $user = SOE\DB\FranchiseAssignment::where('franchise_id', '=', $franchise->id)->where('assignment_type_id', '=', $sales->id)->first();
                if(empty($user))
                {
                    $user = FranchiseAssignment::create(array(
                            'franchise_id' => $franchise_id,
                            'user_id' => Input::get('sales'),
                            'assignment_type_id' => $sales->id
                        ));
                }
                else
                {
                    $user->user_id = Input::get('sales');
                    $user->save();
                }
                Event::fire('backoffice.updated', array('franchise_assignment', $user->id, Auth::user()->id, $user, 'franchise assignment updated'));
                unset($user);
            }
            else
            {
                $sales = SOE\DB\AssignmentType::where('name', '=', 'Sales Person')->first();
                if(empty($sales))
                {
                    return json_encode(0);
                }
                $user = SOE\DB\FranchiseAssignment::where('franchise_id', '=', $franchise->id)->where('assignment_type_id', '=', $sales->id)->first();
                if(!empty($user))
                {
                    Event::fire('backoffice.updated', array('franchise_assignment', $user->id, Auth::user()->id, $user, 'franchise assignment deleted'));
                    $user->delete();
                }
            }
        }

        if(Input::has('csr') || Input::get('csr') === '')
        {
            //Update the assigned csr
            if(Input::get('csr') != '')
            {
                $csr = SOE\DB\AssignmentType::where('name', '=', 'CSR')->first();
                if(empty($csr))
                {
                    return json_encode(0);
                }
                $user = SOE\DB\FranchiseAssignment::where('franchise_id', '=', $franchise->id)->where('assignment_type_id', '=', $csr->id)->first();
                if(empty($user))
                {
                    $user = FranchiseAssignment::create(array(
                            'franchise_id' => $franchise_id,
                            'user_id' => Input::get('csr'),
                            'assignment_type_id' => $csr->id
                        ));
                }
                else
                {
                    $user->user_id = Input::get('csr');
                    $user->save();
                }
                unset($user);
            }
            else
            {
                $csr = SOE\DB\AssignmentType::where('name', '=', 'CSR')->first();
                if(empty($csr))
                {
                    return json_encode(0);
                }
                $user = SOE\DB\FranchiseAssignment::where('franchise_id', '=', $franchise->id)->where('assignment_type_id', '=', $csr->id)->first();
                if(!empty($user))
                {
                    $user->delete();
                }
            }
        }

        if(Input::has('is_certified') || Input::get('is_certified') == 'false')
        {
            $franchise = $this->franchiseRepository->setCertification($franchise, Input::get('is_certified') == 'true');
        }
        if(Input::has('allow_generic_leads'))
        {
            $franchise->allow_generic_leads = Input::get('allow_generic_leads');
        }
        if(Input::has('allow_directed_leads'))
        {
            $franchise->allow_directed_leads = Input::get('allow_directed_leads');
        }
        if(!$franchise->allow_directed_leads && !$franchise->allow_generic_leads)
        {
            $franchise->is_dealer = 0;
        }
        else if(!$franchise->is_certified && $merchant->type == 'PPL')
        {
            $franchise->is_dealer = 1;
        }
        if($lead_emails = Input::get('lead_emails'))
        {
            $this->franchiseRepository->updateLeadEmails($franchise, $lead_emails);
        }
        if($service_plan = Input::get('service_plan'))
        {
            $franchise->service_plan = $service_plan;
            if($service_plan == 'trial')
            {
                $franchise->trial_starts_at = Input::get('trial_starts_at');
                $franchise->trial_ends_at = Input::get('trial_ends_at');
                $franchise->trial_lead_cap = Input::get('trial_lead_cap');
            }
            else
            {
                $franchise->trial_starts_at = NULL;
                $franchise->trial_ends_at = NULL;
                $franchise->trial_lead_cap = 0;
            }
        }
        if($zipcode = Input::get('lead_zipcode'))
            $franchise->zipcode = $zipcode;
        if($radius = Input::get('lead_radius'))
            $franchise->radius = $radius;
        if($budget = Input::get('lead_budget'));
            $franchise->monthly_budget = $budget;
        if($contact_phone = Input::get('contact_phone'))
            $franchise->contact_phone = $contact_phone;
        $franchise->save();
        Event::fire('backoffice.updated', array('franchise', $franchise->id, Auth::user()->id, $franchise, 'franchise updated'));

        //Include updated by user
        $u = User::find($merchant->updated_by);
        $merchant->updated_by_user = empty($u) ? '' : $u->name;

        return $this->format($merchant);
    }

    public function getWizardGetYipitStatus()
    {
        $merchant_id = Input::get('merchant_id', 0);
        $banned = $this->yipitBannedMerchant->getByMerchantId($merchant_id);
        if(!empty($banned))
        {
            $result = true;
            return $this->format($result);
        } else {
            $result = false;
            return $this->format($result);
        }
    }

    public function postWizardBlockYipit()
    {
        $merchant_id = Input::get('merchant_id', 0);
        $merchant = $this->merchantRepository->find($merchant_id);
        $banned = new SOE\DB\YipitBannedMerchant;
        $banned->merchant_id = $merchant->id;
        $banned->yipitbusiness_id = $merchant->yipitbusiness_id;
        $banned->merchant_name = $merchant->name;
        $banned->save();
        return $this->format($banned);
    }

    public function postWizardUnblockYipit()
    {
        $merchant_id = Input::get('merchant_id', 0);
        $banned = $this->yipitBannedMerchant->getByMerchantId($merchant_id);
        if(!empty($banned))
        {
            $banned->delete();
        }
    }

    public function postWizardTags($franchise_id)
    {
        $franchise = $this->franchiseRepository->find($franchise_id, true);
        $fTags = $this->projectTagRepository->getFranchiseTags($franchise);
        $aExisting = array();
        foreach($fTags as $tag)
        {
            $aExisting[] = $tag->id;
        }
        $tags = Input::get('tags');
        $tags = trim($tags, ',');
        $aTags = explode(',', $tags);
        $this->franchiseRepository->setProjectTags($franchise, $aTags);
        Event::fire('backoffice.updated', array('franchise', $franchise->id, Auth::user()->id, $aTags, 'franchise tags updated'));
        return json_encode(1);
    }

    public function postWizardBrands($merchant_id)
    {
        $this->dealerBrands->setBrands($merchant_id, Input::get('brands'));
        $franchise = $this->franchiseRepository->find(Input::get('franchise_id'));
        $franchise->touch();
        Event::fire('backoffice.updated', array('franchise', $franchise->id, Auth::user()->id, Input::get('brands'), 'dealer brands updated'));
        return json_encode(1);
    }

    protected function createFranchise($partial = 0)
    {
        $merchant_id = Input::get('merchant_id', 0);
        if($merchant_id == 0)
        {
            $merch = Merchant::create(array(
                'is_active' => Input::get('status'),
                'display' => Input::get('display'),
                'name' => $this->removePunctuation(Input::get('display')),
                'type' => Input::get('merchant_type'),
                'keywords' => Input::get('keywords'),
                'catchphrase' => Input::get('catchphrase'),
                'about' => Input::get('about'),
                'demo' => Input::get('is_demo'),
                'facebook' => Input::get('facebook'),
                'twitter' => Input::get('twitter'),
                'category_id' => Input::get('parentcategory_id'),
                'subcategory_id' => Input::get('category_id'),
                'slug' => $this->getSlug(Input::get('display')),
                'created_by' => Auth::check() ? Auth::User()->id : 0,
                'updated_by' => Auth::check() ? Auth::User()->id : 0,
                'mobile_redemption' => 1,
                'max_prints' => 1
            ));
            Event::fire('backoffice.updated', array('merchant', $merch->id, Auth::user()->id, $merch, 'merchant created'));
        }
        else
        {
            $merch = Merchant::find($merchant_id);
            if(Input::has('display') || Input::get('display') === '')
            {
                $merch->display = Input::get('display');
                $merch->name = $this->removePunctuation($merch->display);
                $merch->slug = $this->getSlug($merch->display);
            }
            if(Input::has('merchant_type') || Input::get('merchant_type') === '')
            {
                $merch->type = Input::get('merchant_type');
            }
            if(Input::has('keywords') || Input::get('keywords') === '')
            {
                $merch->keywords = Input::get('keywords');
            }
            if(Input::has('catchphrase') || Input::get('catchphrase') === '')
            {
                $merch->catchphrase = Input::get('catchphrase');
            }
            if(Input::has('about') || Input::get('about') === '')
            {
                $merch->about = Input::get('about');
            }
            if(Input::has('facebook') || Input::get('facebook') === '')
            {
                $merch->facebook = Input::get('facebook');
            }
            if(Input::has('twitter') || Input::get('twitter') === '')
            {
                $merch->twitter = Input::get('twitter');
            }
            if(Input::has('parentcategory_id') || Input::get('parentcategory_id') === '0')
            {
                $merch->category_id = Input::get('parentcategory_id');
            }
            if(Input::has('category_id') || Input::get('category_id') === '0')
            {
                $merch->subcategory_id = Input::get('category_id');
            }
            $dirty = $merch->getDirty();
            if(!empty($dirty))
            {
                $merch->updated_by = Auth::check() ? Auth::User()->id : 0;
            }
            $merch->save();
            Event::fire('backoffice.updated', array('merchant', $merch->id, Auth::user()->id, $merch, 'merchant updated'));
        }

        $franch = $this->franchiseRepository->create(array(
            'magazinemanager_id' => Input::get('magazinemanager_id',0),
            'name' => $this->removePunctuation(Input::get('display')),
            'merchant_id' => $merch->id,
            'is_active' => Input::get('status', 1),
            'is_demo' => 1,
            'primary_contact' => Input::get('primary_contact'),
            'company_id' => Input::get('company_id', 1)
        ));
        Event::fire('backoffice.updated', array('franchise', $franch->id, Auth::user()->id, $franch, 'franchise created'));
        $merch->franchise_id = $franch->id;
        if(Input::get('merchant_type') == 'PPL')
        {
            if(Input::get('is_certified') == 'true' || Input::has('allow_generic_leads') || Input::has('allow_directed_leads'))
            {
                if(Input::get('is_certified') == 'true')
                {
                    $franch->is_certified = 1;
                    $franch->certified_at = date('Y-m-d H:i:s');
                }
                else
                {
                    $franch->is_dealer = 1;
                }
                $franch->allow_generic_leads = Input::get('allow_generic_leads');
                $franch->allow_directed_leads = Input::get('allow_directed_leads');
                $lead_emails = Input::get('lead_emails');
                $aEmails = explode(',', $lead_emails);
                foreach($aEmails as $email)
                {
                    $this->leadEmailRepository->create(array(
                        'franchise_id' => $franch->id,
                        'email_address' => $email
                    ));
                }
                $franch->service_plan = Input::get('service_plan');
                $franch->zipcode = Input::get('lead_zipcode');
                $franch->radius = Input::get('lead_radius');
                $franch->monthly_budget = Input::get('lead_budget');
                $franch->contact_phone = Input::get('contact_phone');
                $franch->is_featured = Input::get('is_featured', false);
                $franch->save();
                Event::fire('backoffice.updated', array('franchise', $franch->id, Auth::user()->id, $franch, 'franchise updated'));
            }
        }

        $content = SOE\DB\AssignmentType::where('name', '=', 'Content Specialist')->first();
        $sales = SOE\DB\AssignmentType::where('name', '=', 'Sales Person')->first();
        $csr = SOE\DB\AssignmentType::where('name', '=', 'CSR')->first();
        if(!empty($content) && Input::get('content') != '')
        {
            $user = FranchiseAssignment::create(array(
                'franchise_id' => $franch->id,
                'user_id' => Input::get('content'),
                'assignment_type_id' => $content->id
            ));
        }
        if(!empty($sales) && Input::get('sales') != '')
        {
            $user = FranchiseAssignment::create(array(
                'franchise_id' => $franch->id,
                'user_id' => Input::get('sales'),
                'assignment_type_id' => $sales->id
            ));
            Event::fire('backoffice.updated', array('franchise_assignment', $user->id, Auth::user()->id, $user, 'franchise assignment created'));
        }
        if(!empty($csr) && Input::get('content') != '')
        {
            $user = FranchiseAssignment::create(array(
                'franchise_id' => $franch->id,
                'user_id' => Input::get('content'),
                'assignment_type_id' => $csr->id
            ));
        }

        return $merch;
    }

    public function postWizardUpdateLocation($location_id)
    {
        if($location_id == 0)
        {
            $location = $this->createLocation();
            //Include updated by user
            $u = User::find($location->updated_by);
            $location->updated_by_user = empty($u) ? '' : $u->name;
            $location->created_by_user = empty($u) ? '' : $u->name;

            return $this->format($location);
        }

        $location = Location::find($location_id, true);
        if(empty($location))
        {
            return Response::json(0);
        }
        $franchise = Franchise::find($location->franchise_id, true);
        if(($hours_object = Input::get('hours_object')) || Input::get('hours') == '')
        {
            if(Input::get('hours') == '')
            {
                SOE\DB\LocationHour::where('location_id', '=', $location->id)->delete();
            }
            else
            {
                Eloquent::unguard();
                $days = array(0);
                foreach($hours_object as $hours)
                {
                    $weekday = SOE\DB\LocationHour::where('location_id', '=', $location->id)->where('weekday', '=', $hours['day'])->first();
                    if(empty($weekday))
                    {
                        $weekday = LocationHour::create(array(
                            'weekday' => $hours['day'],
                            'start_time' => $hours['start'],
                            'start_ampm' => $hours['start_ampm'],
                            'end_time' => $hours['end'],
                            'end_ampm' => $hours['end_ampm'],
                            'location_id' => $location->id
                        ));
                    }
                    else
                    {
                        $weekday->start_time = $hours['start'];
                        $weekday->start_ampm = $hours['start_ampm'];
                        $weekday->end_time = $hours['end'];
                        $weekday->end_ampm = $hours['end_ampm'];
                        $weekday->save();
                    }
                    Event::fire('backoffice.updated', array('location_hour', $weekday->id, Auth::user()->id, $weekday, 'location hours updated'));
                    $days[] = $weekday->id;
                }
                //Remove unnecessary entries
                SOE\DB\LocationHour::where('location_id', '=', $location->id)->whereNotIn('id', $days)->delete();
            }
        }

        if(Input::has('name') || Input::get('name') === '')
        {
            $name = Input::get('name');
            $location->name = $name;
            $location->slug = $this->getSlug($name);
        }
        if(Input::has('display_name') || Input::get('display_name') === '')
        {
            $display_name = Input::get('display_name');
            $location->display_name = $display_name;
        }
        if(Input::has('status') || Input::get('status') === '0')
        {
            $status = Input::get('status');
            $location->is_active = $status;
        }
        if(Input::has('is_about_specific') || Input::get('is_about_specific') === '0')
        {
            $is_about_specific = Input::get('is_about_specific');
            if($is_about_specific == 0)
            {
                $this->assets->query()
                    ->where('assetable_type', 'Location')
                    ->where('assetable_id', $location->id)
                    ->where('name', 'LIKE', 'smallImage%')
                    ->delete();
            }
            $location->is_about_specific = $is_about_specific;
        }
        if(Input::has('is_video_specific') || Input::get('is_video_specific') === '0')
        {
            $is_video_specific = Input::get('is_video_specific');
            if($is_video_specific === '0')
            {
                $this->assets->query()
                    ->where('assetable_type', 'Location')
                    ->where('assetable_id', $location->id)
                    ->where('name', 'clientVideoLink')
                    ->delete();
            }
            $location->is_video_specific = $is_video_specific;
        }
        if(Input::has('is_pdf_specific') || Input::get('is_pdf_specific') === '0')
        {
            $is_pdf_specific = Input::get('is_pdf_specific');
            if($is_pdf_specific === '0')
            {
                $this->assets->query()
                    ->where('assetable_type', 'Location')
                    ->where('assetable_id', $location->id)
                    ->where('type', 'pdf')
                    ->delete();
            }
            $location->is_pdf_specific = $is_pdf_specific;
        }
        if(Input::has('is_banner_specific') || Input::get('is_banner_specific') === '0')
        {
            $is_banner_specific = Input::get('is_banner_specific');
            if($is_banner_specific == 0)
            {
                $this->assets->query()
                    ->where('assetable_type', 'Location')
                    ->where('assetable_id', $location->id)
                    ->where('name', 'banner')
                    ->delete();
            }
            $location->is_banner_specific = $is_banner_specific;
        }
        if(Input::has('is_logo_specific') || Input::get('is_logo_specific') === '0')
        {
            $is_logo_specific = Input::get('is_logo_specific');
            if($is_logo_specific == 0)
            {
                $this->assets->query()
                    ->where('assetable_type', 'Location')
                    ->where('assetable_id', $location->id)
                    ->where('name', 'logo1')
                    ->delete();

                $logo = $this->assets->query()
                    ->where('assetable_id', $location->merchant_id)
                    ->where('assetable_type', 'Merchant')
                    ->where('name', 'logo1')
                    ->first();

                if($logo)
                {
                    // Revert entity images to default merchant logo
                    $offers = SOE\DB\Offer::where('path', '=', '')->where('franchise_id', '=', $location->franchise_id)->get();
                    $aOfferIds = array(0);
                    foreach($offers as $offer)
                    {
                        $aOfferIds[] = $offer->id;
                    }
                    SOE\DB\Entity::whereIn('entitiable_id', $aOfferIds)
                                ->where('entitiable_type', '=', 'Offer')
                                ->where('location_id', '=', $location->id)
                                ->update(array('path' => $logo->path));

                    $events = SOE\DB\Event::where('path', '=', '')->where('franchise_id', '=', $location->franchise_id)->get();
                    $aEventIds = array(0);
                    foreach($events as $event)
                    {
                        $aEventIds[] = $event->id;
                    }
                    SOE\DB\Entity::whereIn('entitiable_id', $aEventIds)
                                ->where('entitiable_type', '=', 'Event')
                                ->where('location_id', '=', $location->id)
                                ->update(array('path' => $logo->path));
                }
            }
            else if($is_logo_specific == 1)
            {
                $logo = $this->assets->query()
                    ->where('assetable_type', 'Location')
                    ->where('assetable_id', $location->id)
                    ->where('name', 'logo1')
                    ->first();

                if($logo)
                {
                    //Make sure location entities use the location logo
                    $offers = SOE\DB\Offer::where('path', '=', '')->where('franchise_id', '=', $location->franchise_id)->get();
                    $aOfferIds = array(0);
                    foreach($offers as $offer)
                    {
                        $aOfferIds[] = $offer->id;
                    }
                    SOE\DB\Entity::whereIn('entitiable_id', $aOfferIds)
                                ->where('entitiable_type', '=', 'Offer')
                                ->where('location_id', '=', $location->id)
                                ->update(array('path' => $logo->path));

                    $events = SOE\DB\Event::where('path', '=', '')->where('franchise_id', '=', $location->franchise_id)->get();
                    $aEventIds = array(0);
                    foreach($events as $event)
                    {
                        $aEventIds[] = $event->id;
                    }
                    SOE\DB\Entity::whereIn('entitiable_id', $aEventIds)
                                ->where('entitiable_type', '=', 'Event')
                                ->where('location_id', '=', $location->id)
                                ->update(array('path' => $logo->path));
                }
            }
            $location->is_logo_specific = $is_logo_specific;
        }
        if(Input::has('is_deleted') || Input::get('is_deleted') === '0')
        {
            $is_deleted = Input::get('is_deleted');
            if($is_deleted == 1)
            {
                SOE\DB\Entity::where('location_id', '=', $location->id)->delete();
                Event::fire('backoffice.updated', array('location', $location->id, Auth::user()->id, $location, 'location deleted'));
                $location->delete();
                $location->updated_by_user = User::find($location->updated_by)->name;
                return $this->format($location);
            }
            else
            {
                SOE\DB\Entity::onlyTrashed()->where('location_id', '=', $location->id)->restore();
                $location->restore();
                Event::fire('backoffice.updated', array('location', $location->id, Auth::user()->id, $location, 'location restored'));
            }
        }
        if(Input::has('company_id') || Input::get('company_id') === '')
        {
            $company_id = Input::get('company_id');
            $location->company_id = $company_id;
        }
        if(Input::has('is_address_hidden') || Input::get('is_address_hidden') === '')
        {
            $is_address_hidden = Input::get('is_address_hidden');
            $location->is_address_hidden = $is_address_hidden;
        }
        if(Input::has('custom_address_text') || Input::get('custom_address_text') === '')
        {
            $custom_address_text = Input::get('custom_address_text');
            $location->custom_address_text = $custom_address_text;
        }
        if(Input::has('address') || Input::get('address') === '')
        {
            $address = Input::get('address');
            $location->address = $address;
        }
        if(Input::has('address2') || Input::get('address2') === '')
        {
            $address = Input::get('address2');
            $location->address2 = $address;
        }
        if(Input::has('city') || Input::get('city') === '')
        {
            $city = Input::get('city');
            $location->city = $city;
        }
        if(Input::has('state') || Input::get('state') === '')
        {
            $state = Input::get('state');
            $location->state = $state;
        }
        if(Input::has('zipcode') || Input::get('zipcode') === '')
        {
            $zipcode = Input::get('zipcode');
            $location->zip = $zipcode;
        }
        if(Input::has('phone') || Input::get('phone') === '')
        {
            $phone = Input::get('phone');
            $location->phone = $phone;
        }
        if(Input::has('website') || Input::get('website') === '')
        {
            $website = Input::get('website');
            $location->website = $website;
        }
        if(Input::has('facebook') || Input::get('facebook') === '')
        {
            $facebook = Input::get('facebook');
            $location->facebook = $facebook;
        }
        if(Input::has('twitter') || Input::get('twitter') === '')
        {
            $twitter = Input::get('twitter');
            $location->twitter = $twitter;
        }
        if(Input::has('custom_website') || Input::get('custom_website') === '')
        {
            $custom_website = Input::get('custom_website');
            $location->custom_website = $custom_website;
        }
        if(Input::has('custom_website_text') || Input::get('custom_website_text') === '')
        {
            $custom_website_text = Input::get('custom_website_text');
            $location->custom_website_text = $custom_website_text;
        }
        if(Input::has('redirect_number') || Input::get('redirect_number') === '')
        {
            $redirect_number = Input::get('redirect_number');
            $location->redirect_number = $redirect_number;
        }
        if(Input::has('redirect_text') || Input::get('redirect_text') === '')
        {
            $redirect_text = Input::get('redirect_text');
            $location->redirect_text = $redirect_text;
        }
        if(Input::has('subheader') || Input::get('subheader') === '')
        {
            $subheader = Input::get('subheader');
            $location->subheader = $subheader;
        }
        if(Input::has('is_24_hours') || Input::get('is_24_hours') === '')
        {
            $is_24_hours = Input::get('is_24_hours');
            $location->is_24_hours = $is_24_hours;
        }
        if(Input::has('hours') || Input::get('hours') === '')
        {
            $hours = Input::get('hours');
            $location->hours = $hours;
        }
        if(Input::has('latitude') && Input::has('longitude'))
        {
            $location->latitude = Input::get('latitude');
            $location->latm = (Input::get('latitude')*111133);

            $location->longitude = Input::get('longitude');
            $location->lngm = (111133*cos(deg2rad(Input::get('latitude')))*Input::get('longitude'));
        }
        $dirty = $location->getDirty();
        if(!empty($dirty))
        {
            $location->updated_by = Auth::check() ? Auth::User()->id : 0;
            // Update all entities tied to this location
            SOE\DB\Entity::where('location_id', '=', $location->id)->update(array(
                'latitude' => $location->latitude,
                'longitude' => $location->longitude,
                'latm' => $location->latm,
                'lngm' => $location->lngm,
                'state' => $location->state,
                'location_active' => $location->is_active
            ));
        }
        $location->save();
        Event::fire('backoffice.updated', array('location', $location->id, Auth::user()->id, $location, 'location updated'));
        $location->updated_by_user = User::find($location->updated_by)->name;

        return $this->format($location);
    }

    protected function createLocation()
    {
        $lat = Input::get('latitude', '');
        $lng = Input::get('longitude', '');
        $cartesian = SoeHelper::getCartesian($lat, $lng);
        $franchise = Franchise::find(Input::get('franchise_id'), true);
        $merchant = Merchant::find(Input::get('merchant_id'));
        $category = Category::find($merchant->category_id);
        $subcategory = Category::find($merchant->subcategory_id);
        $company = SOE\DB\Company::find($franchise->company_id);
        $is_active = Input::get('status');
        $location = Location::create(array(
            'name' => Input::get('name'),
            'display_name' => Input::get('display_name', ''),
            'slug' => $this->getSlug(Input::get('name')),
            'hours' => Input::get('hours', ''),
            'is_active' => $is_active,
            'company_id' => Input::get('company_id'),
            'city' => Input::get('city'),
            'state' => Input::get('state'),
            'zip' => Input::get('zipcode'),
            'phone' => Input::get('phone', ''),
            'fax' => Input::get('fax', ''),
            'website' => Input::get('website', ''),
            'facebook' => Input::get('facebook', ''),
            'twitter' => Input::get('twitter', ''),
            'address' => Input::get('address', ''),
            'address2' => Input::get('address2', ''),
            'latitude' => $lat,
            'longitude' => $lng,
            'latm' => $cartesian['latm'],
            'lngm' => $cartesian['lngm'],
            'merchant_id' => Input::get('merchant_id'),
            'franchise_id' => Input::get('franchise_id'),
            'updated_by' => Auth::check() ? Auth::User()->id : 0,
            'created_by' => Auth::check() ? Auth::User()->id : 0,
            'merchant_name' => $merchant->display,
            'merchant_slug' => $merchant->slug,
            'redirect_number' => Input::get('redirect_number', ''),
            'redirect_text' => Input::get('redirect_text', ''),
            'custom_website' => Input::get('custom_website', ''),
            'custom_website_text' => Input::get('custom_website_text', ''),
            'subheader' => Input::get('subheader', ''),
            'is_address_hidden' => Input::get('is_address_hidden', ''),
            'custom_address_text' => Input::get('custom_address_text', ''),
            'is_24_hours' => Input::get('is_24_hours', '')
        ));
        Event::fire('backoffice.updated', array('location', $location->id, Auth::user()->id, $location, 'location created'));

        if($hours_object = Input::get('hours_object'))
        {
            foreach($hours_object as $hours)
            {
                $hour = LocationHour::create(array(
                    'weekday' => $hours['day'],
                    'start_time' => $hours['start'],
                    'start_ampm' => $hours['start_ampm'],
                    'end_time' => $hours['end'],
                    'end_ampm' => $hours['end_ampm'],
                    'location_id' => $location->id
                ));
                Event::fire('backoffice.updated', array('location_hour', $hour->id, Auth::user()->id, $hour, 'location hours updated'));
            }
        }

        // Create banner entities for all non-location-specific banners
        $banners = $this->banners->getByFranchise(Input::get('franchise_id'), false, false);
        foreach($banners['objects'] as $banner)
        {
            $this->bannerEntities->create(array(
                'banner_id' => $banner->id,
                'location_id' => $location->id
            ));
        }

        // Create entities for all non-location-specific offers
        $logo = Asset::getLogo($merchant);
        $offers = SOE\DB\Offer::where('franchise_id', '=', Input::get('franchise_id'))->where('is_location_specific', '=', '0')->where('is_active', '=', '1')->get();
        foreach($offers as $offer)
        {
            Entity::create(array(
                'entitiable_id' => $offer->id,
                'entitiable_type' => 'Offer',
                'name' => $offer->name,
                'slug' => $offer->slug,
                'location_id' => $location->id,
                'category_id' => $merchant->category_id,
                'subcategory_id' => $merchant->subcategory_id,
                'latitude' => $location->latitude,
                'longitude' => $location->longitude,
                'path' => $offer->path == '' ? (!empty($logo) ? $logo->path : '') : $offer->path,
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
                'latm' => $location->latm,
                'lngm' => $location->lngm,
                'merchant_id' => $offer->merchant_id,
                'merchant_slug' => $merchant->slug,
                'merchant_name' => $merchant->display,
                'is_featured' => $offer->is_featured,
                'state' => $location->state,
                'expires_year' => date('Y', strtotime($offer->expires_at)),
                'expires_day' => date('z', strtotime($offer->expires_at)) + 1,
                'starts_year' => date('Y', strtotime($offer->starts_at)),
                'starts_day' => date('z', strtotime($offer->starts_at)) + 1,
                'location_active' => $location->is_active,
                'franchise_active' => $franchise->is_active,
                'franchise_demo' => $franchise->is_demo,
                'category_slug' => $category->slug,
                'subcategory_slug' => $subcategory->slug,
                'company_id' => $franchise->company_id,
                'company_name' => $company->name,
                'secondary_type' => $offer->secondary_type,
                'service_radius' => $merchant->service_radius,
                'short_name_line1' => $offer->short_name_line1,
                'short_name_line2' => $offer->short_name_line2
            ));
        }

        return $location;
    }

    public function postWizardDeleteLocation($location_id)
    {
        $location = Location::find($location_id, true);

        if(empty($location))
        {
            return Response::json(0);
        }

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
        DB::table('assets')->where('assetable_id', $location->id)->where('assetable_type','Location')->delete();
        DB::table('entities')->where('location_id', $location->id)->delete();
        DB::table('locations')->where('id', $location->id)->delete();
        
        return Response::json('success');
    }

    public function getWizardCoupon($offer_id)
    {
        $offer = Offer::find($offer_id);
        
        if(empty($offer))
        {
            return;
        }

        $merchant = Merchant::find($offer->merchant_id);
        $aLocs = array();
        if($merchant->type != 'PROSPECT' && $offer->is_location_specific)
        {
            $locations = SOE\DB\Location::join('entities', 'locations.id', '=', 'entities.location_id')
                                        ->where('entities.entitiable_id', '=', $offer->id)
                                        ->where('entities.entitiable_type', '=', 'Offer')
                                        ->where('entities.is_active', '=', '1')
                                        ->get(array('locations.*'));
            
            foreach($locations as $loc)
            {
                $location = Location::blank();
                $location = $location->createFromModel($loc);
                $aLocs[] = $location;
            }
        }

        $offer->locations = $aLocs;

        $created = User::find($offer->created_by);
        if(empty($created))
        {
            $offer->created_by_user = '';
        }
        else
        {
            $offer->created_by_user = $created->name;
        }

        $updated = User::find($offer->updated_by);
        if(empty($updated))
        {
            $offer->updated_by_user = '';
        }
        else
        {
            $offer->updated_by_user = $updated->name;
        }
        
        return $this->format($offer);
    }

    public function getWizardEvent($event_id)
    {
        $event = SOE\DB\Event::find($event_id);
        if(empty($event))
        {
            return;
        }

        $merchant = SOE\DB\Merchant::find($event->merchant_id);
        $aLocs = array();
        if($merchant->type != 'PROSPECT' && $event->is_location_specific)
        {
            $locations = SOE\DB\Location::join('entities', 'locations.id', '=', 'entities.location_id')
                                        ->where('entities.entitiable_id', '=', $event->id)
                                        ->where('entities.entitiable_type', '=', 'Event')
                                        ->where('entities.is_active', '=', '1')
                                        ->get(array('locations.*'));
            
            foreach($locations as $loc)
            {
                $location = Location::blank();
                $location = $location->createFromModel($loc);
                $aLocs[] = $location;
            }
        }

        $event->locations = $aLocs;
        
        return $event;
    }

    public function postWizardUpdateCoupon($offer_id)
    {
        if($offer_id == 0)
        {
            $offer = $this->createCoupon();
            //Include updated by user
            $u = User::find($offer->updated_by);
            $offer->updated_by_user = empty($u) ? '' : $u->name;
            $offer->created_by_user = empty($u) ? '' : $u->name;
            $merchant = Merchant::find($offer->merchant_id);
            $offer->merchant = $merchant;

            return $this->format($offer);
        }

        $offer = Offer::find($offer_id);
        if(empty($offer))
        {
            return Response::json(0);
        }
        $franchise = Franchise::find($offer->franchise_id, true);
        $franchise->last_offer_notification = '0000-00-00 00:00:00';
        $franchise->save();

        if(Input::has('name') || Input::get('name') === '')
        {
            $name = Input::get('name');
            $offer->name = $name;
            $offer->slug = $this->getSlug($name);
        }
        if(Input::has('status') || Input::get('status') === '0')
        {
            $status = Input::get('status');
            $offer->is_active = $status;
            \SOE\DB\Entity::where('entitiable_id', $offer->id)->where('entitiable_type', 'Offer')->update(array('is_active' => $status));
        }
        if(Input::has('is_demo') || Input::get('is_demo') === '0')
        {
            $is_demo = Input::get('is_demo');
            $offer->is_demo = ($is_demo || $franchise->is_demo);
        }
        if(Input::has('max_prints') || Input::get('max_prints') === '')
        {
            $offer->max_prints = Input::get('max_prints');
            $offer->max_redeems = Input::get('max_prints');
        }
        if(Input::has('code') || Input::get('code') === '')
        {
            $code = Input::get('code');
            $offer->code = $code;
        }
        if(Input::has('savings') || Input::get('savings') === '')
        {
            $savings = Input::get('savings');
            $offer->savings = $savings;
        }
        if(Input::has('description') || Input::get('description') === '')
        {
            $description = Input::get('description');
            $offer->description = $description;
        }
        if(Input::has('path') || Input::get('path') === '')
        {
            $offer->path = Input::get('path');
        }
        if(Input::has('secondary_image') || Input::get('secondary_image') === '')
        {
            $offer->secondary_image = Input::get('secondary_image');
        }
        if(Input::has('starts_at') || Input::get('starts_at') === '')
        {
            $starts_at = Input::get('starts_at');
            $starts_at = date('Y-m-d 00:00:00', strtotime($starts_at));
            $offer->starts_at = $starts_at;
        }
        if(Input::has('expires_at') || Input::get('expires_at') === '')
        {
            $expires_at = Input::get('expires_at');
            $expires_at = date('Y-m-d 00:00:00', strtotime($expires_at));
            $offer->expires_at = $expires_at;
        }
        if(Input::has('hide_expiration') || Input::get('hide_expiration') === '0')
        {
            $hide_expiration = Input::get('hide_expiration');
            $offer->hide_expiration = $hide_expiration;
        }
        if(Input::has('is_reoccurring') || Input::get('is_reoccurring') === '0')
        {
            $is_reoccurring = Input::get('is_reoccurring');
            $offer->is_reoccurring = $is_reoccurring;
        }
        if(Input::has('is_location_specific') || Input::get('is_location_specific') === '0')
        {
            $is_location_specific = Input::get('is_location_specific');
            $offer->is_location_specific = $is_location_specific;
        }
        if(Input::has('regularprice') || Input::get('regularprice') === '0')
        {
            $regularprice = Input::get('regularprice');
            $offer->regular_price = $regularprice;
        }
        if(Input::has('specialprice') || Input::get('specialprice') === '0')
        {
            $specialprice = Input::get('specialprice');
            $offer->special_price = $specialprice;
        }
        if(Input::has('savetoday') || Input::get('savetoday') === '0')
        {
            $is_dailydeal = Input::get('savetoday');
            $offer->is_dailydeal = $is_dailydeal;
        }

        if(Input::has('print_override') || Input::get('print_override') === '')
        {
            $print_override = Input::get('print_override');
            $offer->print_override = $print_override;
        }
        if(Input::has('is_featured_offer') || Input::get('is_featured_offer') === '0')
        {
            $is_featured_offer = Input::get('is_featured_offer');
            $offer->is_featured = $is_featured_offer;
        }
        if(Input::has('requires_member') || Input::get('requires_member') === '0')
        {
            $requires_member = Input::get('requires_member');
            $offer->requires_member = $requires_member;
        }
        if(Input::has('is_mobile_only') || Input::get('is_mobile_only') === '0')
        {
            $is_mobile_only = Input::get('is_mobile_only');
            $offer->is_mobile_only = $is_mobile_only;
        }
        if(Input::has('secondary_type') || Input::get('secondary_type') === '')
        {
            $secondary_type = Input::get('secondary_type');
            $offer->secondary_type = $secondary_type;
        }
        if(Input::has('year') || Input::get('year') === '')
        {
            $offer->year = Input::get('year');
        }
        if(Input::has('make_id') || Input::get('make_id') === '')
        {
            $offer->make_id = Input::get('make_id');
            $make = \SOE\DB\VehicleMake::find($offer->make_id);
            $offer->make = $make ? $make->name : '';
        }
        if(Input::has('model_id') || Input::get('model_id') === '')
        {
            $offer->model_id = Input::get('model_id');
            $model = \SOE\DB\VehicleModel::find($offer->model_id);
            $offer->model = $model ? $model->name : '';
        }
        if(Input::has('short_name_line1') || Input::get('short_name_line1') === '')
        {
            $offer->short_name_line1 = Input::get('short_name_line1');
        }
        if(Input::has('short_name_line2') || Input::get('short_name_line2') === '')
        {
            $offer->short_name_line2 = Input::get('short_name_line2');
        }
        if(Input::has('custom_category_id') || Input::get('custom_category_id') === '0')
        {
            $custom_category_id = Input::get('custom_category_id');
            $offer->custom_category_id = $custom_category_id;
        }
        if(Input::has('custom_subcategory_id') || Input::get('custom_subcategory_id') === '0')
        {
            $custom_subcategory_id = Input::get('custom_subcategory_id');
            $offer->custom_subcategory_id = $custom_subcategory_id;
        }
        if(Input::has('category_visible') || Input::get('category_visible') === '0')
        {
            $category_visible = Input::get('category_visible');
            $offer->category_visible = $category_visible;
        }

        $dirty = $offer->getDirty();
        if(!empty($dirty))
        {
            $offer->updated_by = Auth::check() ? Auth::User()->id : 0;
        }
        // Get Merchant Info
        $merchant = Merchant::find($offer->merchant_id);
        $logo = Asset::getLogo($merchant);
        $offer->merchant_logo = (!empty($logo) ? $logo->path : '');
        // Save Offer
        $offer->save();
        Event::fire('backoffice.updated', array('offer', $offer->id, Auth::user()->id, $offer, 'offer updated'));
        $offer->updated_by_user = User::find($offer->updated_by)->name;

        $franch_locations = SOE\DB\Location::where('franchise_id', '=', $offer->franchise_id)->get();
        $aLocIDs = array();
        $aLocIDs[] = 0;
        $aLocs = array();
        foreach($franch_locations as $loc)
        {
            $aLocIDs[] = $loc->id;
            $aLocs[$loc->id] = $loc;
        }
        
        $category = Category::find($offer->custom_category_id == 0 ? $merchant->category_id : $offer->custom_category_id);
        $company = SOE\DB\Company::find($franchise->company_id);
        $subcategory = Category::find($offer->custom_subcategory_id == 0 ? $merchant->subcategory_id : $offer->custom_subcategory_id);
        
        $offer->merchant = $merchant;

        // Offer Keyword Logic
        $offer_name = $offer->name;
        // Remove Items with Dollar Signs and Percent Signs
        $offer_keywords = explode(" ", strtolower($offer_name));
        foreach($offer_keywords as $key => $one) {
            $offer_keywords[$key]=str_replace(',','',$one);
            if(strpos($one, '$') !== false)
                unset($offer_keywords[$key]);
            if(strpos($one, '%') !== false)
                unset($offer_keywords[$key]);
            if(strpos($one, '&') !== false)
                unset($offer_keywords[$key]);
        }
        // Remove Items from the Blacklist
        $blacklist_feature = $this->features->findByName('offer_keyword_blacklist');
        $blacklist_value = $blacklist_feature ? $blacklist_feature->value : '';
        $blacklist = explode(", ", $blacklist_value);
        $offer_keywords = array_udiff($offer_keywords, $blacklist, 'strcasecmp');
        // Remove Items Already in the Merchant Keywords
        $merchant_keywords = explode(", ", $merchant->keywords);
        $offer_keywords = array_udiff($offer_keywords, $merchant_keywords, 'strcasecmp');

        if ($offer->is_active == 1)
        {
            // Remove Items Already in the Offer Keywords
            $existing_offer_keywords = explode(", ", $merchant->offer_keywords);
            $offer_keywords = array_udiff($offer_keywords, $existing_offer_keywords, 'strcasecmp');
            // Merge offer keywords together
            $existing_offer_keywords = trim(implode(", ", $existing_offer_keywords), ',');
            $offer_keywords = rtrim(implode(", ",$offer_keywords), ',');
            $updated_offer_keywords = $existing_offer_keywords.', '.$offer_keywords;
            $updated_offer_keywords = str_replace(',,',',',$updated_offer_keywords);
            $updated_offer_keywords = explode(", ", $updated_offer_keywords);
            // Check if any duplicates still exists
            $updated_offer_keywords = array_unique($updated_offer_keywords);
            // Chage keywords back to comma list
            $updated_offer_keywords = rtrim(implode(', ', $updated_offer_keywords), ',');
            $updated_offer_keywords = trim($updated_offer_keywords);
            //echo $updated_offer_keywords.' '.$merchant->id.' '.$offer->is_active;
            // Save updated keywords
            $merchant->offer_keywords = $updated_offer_keywords;
            $merchant->save();
            Event::fire('backoffice.updated', array('merchant', $merchant->id, Auth::user()->id, $merchant, 'merchant keywords updated'));
        } else {
            $offers = $this->merchantRepository->getEntities($merchant->id);
            $other_offers = '';
            foreach($offers['objects'] as $offers) {
                $other_offers .= $offers->name.' ';
            }
            $other_offers = explode(" ", strtolower($other_offers));
            // Check if any duplicates still exists
            $other_offers = array_unique($other_offers);
            foreach($other_offers as $key => $one) {
                $other_offers[$key]=str_replace(',','',$one);
                if(strpos($one, '$') !== false)
                    unset($other_offers[$key]);
                if(strpos($one, '%') !== false)
                    unset($other_offers[$key]);
                if(strpos($one, '&') !== false)
                    unset($other_offers[$key]);
                if(strpos($one, '_') !== false)
                    unset($other_offers[$key]);
            }
            // Remove Items from the Blacklist
            $other_offers = array_udiff($other_offers, $blacklist, 'strcasecmp');
            // Filter Out Keywords from Other Offers
            $offer_keywords = array_udiff($offer_keywords, $other_offers, 'strcasecmp');

            // Remove Items The We Need To
            $existing_offer_keywords = explode(", ", $merchant->offer_keywords);
            $existing_offer_keywords = array_udiff($existing_offer_keywords, $offer_keywords, 'strcasecmp');
            $existing_offer_keywords = array_unique($existing_offer_keywords);
            $existing_offer_keywords = trim(implode(', ', $existing_offer_keywords), ',');
            $existing_offer_keywords = trim($existing_offer_keywords);

            // Save updated keywords
            $merchant->offer_keywords = $existing_offer_keywords;
            $merchant->save();
            Event::fire('backoffice.updated', array('merchant', $merchant->id, Auth::user()->id, $merchant, 'merchant keywords updated'));
        }
        
        if($offer->is_location_specific)
        {
            if($locations = Input::get('locations'))
            {
                // Get existing entities
                $participating = SOE\DB\Entity::where('entitiable_id', '=', $offer->id)->where('entitiable_type', '=', 'Offer')->get();
                $part_locations = array();
                foreach($participating as $p)
                {
                    // Deactivate any entities that are not longer have a participating location.
                    if(!in_array($p->location_id, $locations))
                    {
                        $p->is_active = 0;
                        $p->save();
                    }
                    else
                    {
                        $part_locations[] = $p->location_id;
                    }
                }

                $locs = SOE\DB\Location::whereIn('id', $locations)->get();
                $aLocs = array();
                foreach($locs as $loc)
                {
                    $aLocs[$loc->id] = $loc;
                }
                foreach($locations as $l)
                {
                    //Add new participating location entities
                    if(!in_array($l, $part_locations))
                    {
                        $added = Entity::create(array(
                            'entitiable_id' => $offer->id,
                            'entitiable_type' => 'Offer',
                            'name' => $offer->name,
                            'slug' => $offer->slug,
                            'location_id' => $l,
                            'category_id' => $category->id,
                            'subcategory_id' => $subcategory->id,
                            'latitude' => $aLocs[$l]->latitude,
                            'longitude' => $aLocs[$l]->longitude,
                            'path' => $offer->path == '' ? (!empty($logo) ? $logo->path : '') : $offer->path,
                            'is_dailydeal' => $offer->is_dailydeal,
                            'rating' => $offer->rating,
                            'special_price' => $offer->special_price,
                            'regular_price' => $offer->regular_price,
                            'is_demo' => $offer->is_demo,
                            'is_active' => $offer->is_active,
                            'starts_at' => $offer->starts_at,
                            'expires_at' => $offer->expires_at,
                            'hide_expiration' => $offer->hide_expiration,
                            'is_reoccuring' => $offer->is_reoccuring,
                            'rating_count' => $offer->rating_count,
                            'savings' => $offer->savings,
                            'url' => $offer->url,
                            'print_override' => $offer->print_override,
                            'latm' => $aLocs[$l]->latm,
                            'lngm' => $aLocs[$l]->lngm,
                            'merchant_id' => $offer->merchant_id,
                            'merchant_slug' => $merchant->slug,
                            'merchant_name' => $merchant->display,
                            'is_featured' => $offer->is_featured,
                            'state' => $aLocs[$l]->state,
                            'expires_year' => date('Y', strtotime($offer->expires_at)),
                            'expires_day' => date('z', strtotime($offer->expires_at)) + 1,
                            'starts_year' => date('Y', strtotime($offer->starts_at)),
                            'starts_day' => date('z', strtotime($offer->starts_at)) + 1,
                            'location_active' => $aLocs[$l]->is_active,
                            'franchise_active' => $franchise->is_active,
                            'franchise_demo' => $franchise->is_demo,
                            'category_slug' => $category->slug,
                            'subcategory_slug' => $subcategory->slug,
                            'company_id' => $franchise->company_id,
                            'company_name' => $company->name,
                            'secondary_type' => $offer->secondary_type,
                            'service_radius' => $merchant->service_radius,
                            'short_name_line1' => $offer->short_name_line1,
                            'short_name_line2' => $offer->short_name_line2,
                            'category_visible' => $offer->category_visible,
                            'merchant_logo' => (!empty($logo) ? $logo->path : ''),
                        ));
                    }
                    else
                    {
                        // Activate an old entity if its location is participating again
                        $entity = SOE\DB\Entity::where('entitiable_type', '=', 'Offer')->where('entitiable_id', '=', $offer->id)->where('location_id', '=', $l)->first();
                        $entity->is_active = $offer->is_active;
                        $entity->save();
                    }

                    // Update all entities with new offer data
                    if(!empty($dirty))
                    {
                        $result = SOE\DB\Entity::where('entitiable_id', '=', $offer->id)->where('entitiable_type', '=', 'Offer')->whereIn('location_id', $locations)->update(array(
                            'name' => $offer->name,
                            'slug' => $offer->slug,
                            'category_id' => $category->id,
                            'subcategory_id' => $subcategory->id,
                            'path' => $offer->path == '' ? (!empty($logo) ? $logo->path : '') : $offer->path,
                            'is_dailydeal' => $offer->is_dailydeal,
                            'rating' => $offer->rating,
                            'special_price' => $offer->special_price,
                            'regular_price' => $offer->regular_price,
                            'is_active' => $offer->is_active,
                            'is_demo' => $offer->is_demo,
                            'starts_at' => $offer->starts_at,
                            'expires_at' => $offer->expires_at,
                            'hide_expiration' => $offer->hide_expiration,
                            'is_reoccurring' => $offer->is_reoccurring,
                            'rating_count' => $offer->rating_count,
                            'savings' => $offer->savings,
                            'url' => $offer->url,
                            'print_override' => $offer->print_override,
                            'is_featured' => $offer->is_featured,
                            'expires_year' => date('Y', strtotime($offer->expires_at)),
                            'expires_day' => date('z', strtotime($offer->expires_at)) + 1,
                            'starts_year' => date('Y', strtotime($offer->starts_at)),
                            'starts_day' => date('z', strtotime($offer->starts_at)) + 1,
                            'category_slug' => $category->slug,
                            'subcategory_slug' => $subcategory->slug,
                            'company_id' => $franchise->company_id,
                            'company_name' => $company->name,
                            'secondary_type' => $offer->secondary_type,
                            'service_radius' => $merchant->service_radius,
                            'short_name_line1' => $offer->short_name_line1,
                            'short_name_line2' => $offer->short_name_line2,
                            'category_visible' => $offer->category_visible,
                            'merchant_logo' => (!empty($logo) ? $logo->path : ''),
                        ));
                    }
                }
            }
        }
        else
        {
            // Make sure that an entity exists for every location
            $entities = SOE\DB\Entity::where('entitiable_id', '=', $offer->id)->where('entitiable_type', '=', 'Offer')->get(array('location_id'));
            $aEntLocIDs = array();
            $aEntLocIDs[] = 0;
            foreach($entities as $ent)
            {
                $aEntLocIDs[] = $ent->location_id;
            }
            if(count($franch_locations) != count($entities))
            {
                // Add entities for missing locations
                $missing = array_diff($aLocIDs, $aEntLocIDs);
                foreach($missing as $loc_id)
                {
                    $added = Entity::create(array(
                            'entitiable_id' => $offer->id,
                            'entitiable_type' => 'Offer',
                            'name' => $offer->name,
                            'slug' => $offer->slug,
                            'location_id' => $loc_id,
                            'category_id' => $category->id,
                            'subcategory_id' => $subcategory->id,
                            'latitude' => $aLocs[$loc_id]->latitude,
                            'longitude' => $aLocs[$loc_id]->longitude,
                            'path' => $offer->path == '' ? (!empty($logo) ? $logo->path : '') : $offer->path,
                            'is_dailydeal' => $offer->is_dailydeal,
                            'rating' => $offer->rating,
                            'special_price' => $offer->special_price,
                            'regular_price' => $offer->regular_price,
                            'is_demo' => $offer->is_demo,
                            'is_active' => $offer->is_active,
                            'starts_at' => $offer->starts_at,
                            'expires_at' => $offer->expires_at,
                            'hide_expiration' => $offer->hide_expiration,
                            'is_reoccurring' => $offer->is_reoccurring,
                            'rating_count' => $offer->rating_count,
                            'savings' => $offer->savings,
                            'url' => $offer->url,
                            'print_override' => $offer->print_override,
                            'latm' => $aLocs[$loc_id]->latm,
                            'lngm' => $aLocs[$loc_id]->lngm,
                            'merchant_id' => $offer->merchant_id,
                            'merchant_slug' => $merchant->slug,
                            'merchant_name' => $merchant->display,
                            'is_featured' => $offer->is_featured,
                            'state' => $aLocs[$loc_id]->state,
                            'expires_year' => date('Y', strtotime($offer->expires_at)),
                            'expires_day' => date('z', strtotime($offer->expires_at)) + 1,
                            'starts_year' => date('Y', strtotime($offer->starts_at)),
                            'starts_day' => date('z', strtotime($offer->starts_at)) + 1,
                            'location_active' => $aLocs[$loc_id]->is_active,
                            'franchise_active' => $franchise->is_active,
                            'franchise_demo' => $franchise->is_demo,
                            'category_slug' => $category->slug,
                            'subcategory_slug' => $subcategory->slug,
                            'company_id' => $franchise->company_id,
                            'company_name' => $company->name,
                            'secondary_type' => $offer->secondary_type,
                            'service_radius' => $merchant->service_radius,
                            'short_name_line1' => $offer->short_name_line1,
                            'short_name_line2' => $offer->short_name_line2,
                            'category_visible' => $offer->category_visible,
                            'merchant_logo' => (!empty($logo) ? $logo->path : ''),
                        ));
                }
            }

            // Update all entities with new offer data
            if(!empty($dirty))
            {
                $result = SOE\DB\Entity::where('entitiable_id', '=', $offer->id)->where('entitiable_type', '=', 'Offer')->whereIn('location_id', $aLocIDs)->update(array(
                    'name' => $offer->name,
                    'slug' => $offer->slug,
                    'category_id' => $category->id,
                    'subcategory_id' => $subcategory->id,
                    'path' => $offer->path == '' ? (!empty($logo) ? $logo->path : '') : $offer->path,
                    'is_dailydeal' => $offer->is_dailydeal,
                    'rating' => $offer->rating,
                    'special_price' => $offer->special_price,
                    'regular_price' => $offer->regular_price,
                    'is_demo' => $offer->is_demo,
                    'is_active' => $offer->is_active,
                    'starts_at' => $offer->starts_at,
                    'expires_at' => $offer->expires_at,
                    'hide_expiration' => $offer->hide_expiration,
                    'is_reoccurring' => $offer->is_reoccurring,
                    'rating_count' => $offer->rating_count,
                    'savings' => $offer->savings,
                    'url' => $offer->url,
                    'print_override' => $offer->print_override,
                    'is_featured' => $offer->is_featured,
                    'expires_year' => date('Y', strtotime($offer->expires_at)),
                    'expires_day' => date('z', strtotime($offer->expires_at)) + 1,
                    'starts_year' => date('Y', strtotime($offer->starts_at)),
                    'starts_day' => date('z', strtotime($offer->starts_at)) + 1,
                    'category_slug' => $category->slug,
                    'subcategory_slug' => $subcategory->slug,
                    'company_id' => $franchise->company_id,
                    'company_name' => $company->name,
                    'secondary_type' => $offer->secondary_type,
                    'service_radius' => $merchant->service_radius,
                    'short_name_line1' => $offer->short_name_line1,
                    'short_name_line2' => $offer->short_name_line2,
                    'category_visible' => $offer->category_visible,
                    'merchant_logo' => (!empty($logo) ? $logo->path : ''),
                ));
            }
        }

        return $this->format($offer);
    }

    public function postWizardUpdateBanner()
    {
        $banner_id = \Input::get('banner_id', 0);
        $banner = $banner_id ? $this->banners->find($banner_id) : $this->banners->blank();

        if(\Input::has('merchant_id'))
        {
            $banner->merchant_id = \Input::get('merchant_id');
        }
        if(\Input::has('franchise_id'))
        {
            $banner->franchise_id = \Input::get('franchise_id');
        }
        if(\Input::has('path'))
        {
            $banner->path = \Input::get('path');
        }
        if(\Input::has('type'))
        {
            $banner->type = \Input::get('type');
        }
        $banner->is_active = 1;
        if(\Input::has('is_demo') || \Input::get('is_demo') === '0')
        {
            $banner->is_demo = \Input::get('is_demo');
        }
        if(\Input::has('custom_url') || \Input::get('custom_url') === '')
        {
            $banner->custom_url = \Input::get('custom_url');
        }
        if(\Input::has('service_radius'))
        {
            $banner->service_radius = \Input::get('service_radius');
        }
        if(\Input::has('keywords') || \Input::get('keywords') === '')
        {
            $banner->keywords = \Input::get('keywords');
        }
        if(\Input::has('is_location_specific') || \Input::get('is_location_specific') === '0')
        {
            $banner->is_location_specific = \Input::get('is_location_specific');
        }
        $banner->save();
        if($banner->is_location_specific)
        {
            $locations = \Input::get('locations');
            $participating = $this->bannerEntities->getByBanner($banner_id);
            $aPartIds = array();
            foreach($participating as $part)
            {
                $aPartIds[] = $part->location_id;
            }

            $aAdded = array_diff($locations, $aPartIds);
            $aRemoved = array_diff($aPartIds, $locations);
            foreach($aAdded as $added)
            {
                $this->bannerEntities->create(array(
                    'banner_id' => $banner->id,
                    'location_id' => $added
                ));
            }
            foreach($aRemoved as $removed)
            {
                $this->bannerEntities->query()
                    ->where('banner_id', $banner->id)
                    ->where('location_id', $removed)
                    ->delete();
            }
        }
        else
        {
            $locations = $this->locationRepository->getActiveByFranchise($banner->franchise_id);
            $aLocIds = array();
            foreach($locations as $location)
            {
                $aLocIds[] = $location->id;
            }
            $participating = $this->bannerEntities->getByBanner($banner_id);
            $aPartIds = array();
            foreach($participating as $part)
            {
                $aPartIds[] = $part->location_id;
            }
            $aAdded = array_diff($aLocIds, $aPartIds);
            $aRemoved = array_diff($aPartIds, $aLocIds);
            foreach($aAdded as $added)
            {
                $this->bannerEntities->create(array(
                    'banner_id' => $banner->id,
                    'location_id' => $added
                ));
            }
            foreach($aRemoved as $removed)
            {
                $this->bannerEntities->query()
                    ->where('banner_id', $banner->id)
                    ->where('location_id', $removed)
                    ->delete();
            }
        }
    }

    public function postWizardUpdateEvent($event_id)
    {
        if($event_id == 0)
        {
            $event = $this->createEvent();
            $merchant = SOE\DB\Merchant::find($event->merchant_id);
            $event->merchant = $merchant;

            return $this->format($event);
        }

        $event = SOE\DB\Event::find($event_id);
        if(empty($event))
        {
            return Response::json(0);
        }
        $franchise = $this->franchiseRepository->find($event->franchise_id, true);

        if(Input::has('name') || Input::get('name') === '')
        {
            $name = Input::get('name');
            $event->name = $name;
            $event->slug = $this->getSlug($name);
        }
        if(Input::has('status') || Input::get('status') === '')
        {
            $status = Input::get('status');
            $event->is_active = $status;
        }
        if(Input::has('is_demo') || Input::get('is_demo') === '0')
        {
            $is_demo = Input::get('is_demo');
            $event->is_demo = ($is_demo || $franchise->is_demo);
        }
        if(Input::has('description') || Input::get('description') === '')
        {
            $description = Input::get('description');
            $event->description = $description;
        }
        if(Input::has('path') || Input::get('path') === '')
        {
            $event->path = Input::get('path');
        }
        if(Input::has('website') || Input::get('website') === '')
        {
            $event->website = Input::get('website');
        }
        if(Input::has('starts_at') || Input::get('starts_at') === '')
        {
            $starts_at = Input::get('starts_at');
            $starts_at = date('Y-m-d 00:00:00', strtotime($starts_at));
            $event->starts_at = $starts_at;
        }
        if(Input::has('expires_at') || Input::get('expires_at') === '')
        {
            $expires_at = Input::get('expires_at');
            $expires_at = date('Y-m-d 00:00:00', strtotime($expires_at));
            $event->expires_at = $expires_at;
        }
        if(Input::has('event_start') || Input::get('event_start') === '')
        {
            $event_start = Input::get('event_start');
            $event_start = date('Y-m-d 00:00:00', strtotime($event_start));
            $event->event_start = $event_start;
        }
        if(Input::has('event_end') || Input::get('event_end') === '')
        {
            $event_end = Input::get('event_end');
            $event_end = date('Y-m-d 00:00:00', strtotime($event_end));
            $event->event_end = $event_end;
        }
        if(Input::has('is_location_specific') || Input::get('is_location_specific') === '0')
        {
            $is_location_specific = Input::get('is_location_specific');
            $event->is_location_specific = $is_location_specific;
        }
        if(Input::has('is_featured') || Input::get('is_featured') === '0')
        {
            $is_featured = Input::get('is_featured');
            $event->is_featured = $is_featured;
        }
        if(Input::has('short_name_line1') || Input::get('short_name_line1') === '')
        {
            $event->short_name_line1 = Input::get('short_name_line1');
        }
        if(Input::has('short_name_line2') || Input::get('short_name_line2') === '')
        {
            $event->short_name_line2 = Input::get('short_name_line2');
        }
        if(Input::has('custom_category_id') || Input::get('custom_category_id') === '0')
        {
            $custom_category_id = Input::get('custom_category_id');
            $event->custom_category_id = $custom_category_id;
        }
        if(Input::has('custom_subcategory_id') || Input::get('custom_subcategory_id') === '0')
        {
            $custom_subcategory_id = Input::get('custom_subcategory_id');
            $event->custom_subcategory_id = $custom_subcategory_id;
        }
        if(Input::has('category_visible') || Input::get('category_visible') === '0')
        {
            $category_visible = Input::get('category_visible');
            $event->category_visible = $category_visible;
        }

        $dirty = $event->getDirty();
        $event->save();
        Event::fire('backoffice.updated', array('event', $event->id, Auth::user()->id, $event, 'event updated'));

        $franch_locations = SOE\DB\Location::where('franchise_id', '=', $event->franchise_id)->get();
        $aLocIDs = array();
        $aLocs = array();
        foreach($franch_locations as $loc)
        {
            $aLocIDs[] = $loc->id;
            $aLocs[$loc->id] = $loc;
        }
        $merchant = SOE\DB\Merchant::find($event->merchant_id);
        $category = SOE\DB\Category::find($event->custom_category_id == 0 ? $merchant->category_id : $event->custom_category_id);
        $company = SOE\DB\Company::find($franchise->company_id);
        $subcategory = SOE\DB\Category::find($event->custom_subcategory_id == 0 ? $merchant->subcategory_id : $event->custom_subcategory_id);
        $logo = $this->assets->getLogo($merchant);
        $event->merchant = $merchant;

        if($event->is_location_specific)
        {
            if($locations = Input::get('locations'))
            {
                // Get existing entities
                $participating = SOE\DB\Entity::where('entitiable_id', '=', $event->id)->where('entitiable_type', '=', 'Event')->get();
                $part_locations = array();
                foreach($participating as $p)
                {
                    // Deactivate any entities that are not longer have a participating location.
                    if(!in_array($p->location_id, $locations))
                    {
                        $p->is_active = 0;
                        $p->save();
                    }
                    else
                    {
                        $part_locations[] = $p->location_id;
                    }
                }

                $locs = SOE\DB\Location::whereIn('id', $locations)->get();
                $aLocs = array();
                foreach($locs as $loc)
                {
                    $aLocs[$loc->id] = $loc;
                }
                foreach($locations as $l)
                {
                    //Add new participating location entities
                    if(!in_array($l, $part_locations))
                    {
                        $added = SOE\DB\Entity::create(array(
                            'entitiable_id' => $event->id,
                            'entitiable_type' => 'Event',
                            'name' => $event->name,
                            'slug' => $event->slug,
                            'location_id' => $l,
                            'category_id' => $category->id,
                            'subcategory_id' => $subcategory->id,
                            'latitude' => $aLocs[$l]->latitude,
                            'longitude' => $aLocs[$l]->longitude,
                            'path' => $event->path == '' ? (!empty($logo) ? $logo->path : '') : $event->path,
                            'is_demo' => $event->is_demo,
                            'is_active' => $event->is_active,
                            'starts_at' => $event->starts_at,
                            'expires_at' => $event->expires_at,
                            'url' => $event->website,
                            'latm' => $aLocs[$l]->latm,
                            'lngm' => $aLocs[$l]->lngm,
                            'merchant_id' => $event->merchant_id,
                            'merchant_slug' => $merchant->slug,
                            'merchant_name' => $merchant->display,
                            'is_featured' => $event->is_featured,
                            'state' => $aLocs[$l]->state,
                            'expires_year' => date('Y', strtotime($event->expires_at)),
                            'expires_day' => date('z', strtotime($event->expires_at)) + 1,
                            'starts_year' => date('Y', strtotime($event->starts_at)),
                            'starts_day' => date('z', strtotime($event->starts_at)) + 1,
                            'location_active' => $aLocs[$l]->is_active,
                            'franchise_active' => $franchise->is_active,
                            'franchise_demo' => $franchise->is_demo,
                            'category_slug' => $category->slug,
                            'subcategory_slug' => $subcategory->slug,
                            'company_id' => $franchise->company_id,
                            'company_name' => $company->name,
                            'service_radius' => $merchant->service_radius,
                            'short_name_line1' => $event->short_name_line1,
                            'short_name_line2' => $event->short_name_line2,
                            'category_visible' => $event->category_visible
                        ));
                    }
                    else
                    {
                        // Activate an old entity if its location is participating again
                        $entity = SOE\DB\Entity::where('entitiable_type', '=', 'Event')->where('entitiable_id', '=', $event->id)->where('location_id', '=', $l)->first();
                        $entity->is_active = $event->is_active;
                        $entity->save();
                    }

                    // Update all entities with new Event data
                    if(!empty($dirty))
                    {
                        $result = SOE\DB\Entity::where('entitiable_id', '=', $event->id)->where('entitiable_type', '=', 'Event')->whereIn('location_id', $locations)->update(array(
                            'name' => $event->name,
                            'slug' => $event->slug,
                            'category_id' => $category->id,
                            'subcategory_id' => $subcategory->id,
                            'path' => $event->path == '' ? (!empty($logo) ? $logo->path : '') : $event->path,
                            'is_active' => $event->is_active,
                            'is_demo' => $event->is_demo,
                            'starts_at' => $event->starts_at,
                            'expires_at' => $event->expires_at,
                            'url' => $event->website,
                            'is_featured' => $event->is_featured,
                            'expires_year' => date('Y', strtotime($event->expires_at)),
                            'expires_day' => date('z', strtotime($event->expires_at)) + 1,
                            'starts_year' => date('Y', strtotime($event->starts_at)),
                            'starts_day' => date('z', strtotime($event->starts_at)) + 1,
                            'category_slug' => $category->slug,
                            'subcategory_slug' => $subcategory->slug,
                            'service_radius' => $merchant->service_radius,
                            'short_name_line1' => $event->short_name_line1,
                            'short_name_line2' => $event->short_name_line2,
                            'category_visible' => $event->category_visible
                        ));
                    }
                }
            }
        }
        else
        {
            // Make sure that an entity exists for every location
            $entities = SOE\DB\Entity::where('entitiable_id', '=', $event->id)->where('entitiable_type', '=', 'Event')->get(array('location_id'));
            $aEntLocIDs = array();
            foreach($entities as $ent)
            {
                $aEntLocIDs[] = $ent->location_id;
            }
            if(count($franch_locations) != count($entities))
            {
                // Add entities for missing locations
                $missing = array_diff($aLocIDs, $aEntLocIDs);
                foreach($missing as $loc_id)
                {
                    $added = Entity::create(array(
                            'entitiable_id' => $event->id,
                            'entitiable_type' => 'Event',
                            'name' => $event->name,
                            'slug' => $event->slug,
                            'location_id' => $loc_id,
                            'category_id' => $category->id,
                            'subcategory_id' => $subcategory->id,
                            'latitude' => $aLocs[$loc_id]->latitude,
                            'longitude' => $aLocs[$loc_id]->longitude,
                            'path' => $event->path == '' ? (!empty($logo) ? $logo->path : '') : $event->path,
                            'is_demo' => $event->is_demo,
                            'is_active' => $event->is_active,
                            'starts_at' => $event->starts_at,
                            'expires_at' => $event->expires_at,
                            'url' => $event->website,
                            'latm' => $aLocs[$loc_id]->latm,
                            'lngm' => $aLocs[$loc_id]->lngm,
                            'merchant_id' => $event->merchant_id,
                            'merchant_slug' => $merchant->slug,
                            'merchant_name' => $merchant->display,
                            'is_featured' => $event->is_featured,
                            'state' => $aLocs[$loc_id]->state,
                            'expires_year' => date('Y', strtotime($event->expires_at)),
                            'expires_day' => date('z', strtotime($event->expires_at)) + 1,
                            'starts_year' => date('Y', strtotime($event->starts_at)),
                            'starts_day' => date('z', strtotime($event->starts_at)) + 1,
                            'location_active' => $aLocs[$loc_id]->is_active,
                            'franchise_active' => $franchise->is_active,
                            'franchise_demo' => $franchise->is_demo,
                            'category_slug' => $category->slug,
                            'subcategory_slug' => $subcategory->slug,
                            'company_id' => $franchise->company_id,
                            'company_name' => $company->name,
                            'service_radius' => $merchant->service_radius,
                            'short_name_line1' => $event->short_name_line1,
                            'short_name_line2' => $event->short_name_line2,
                            'category_visible' => $event->category_visible
                        ));
                }
            }

            // Update all entities with new event data
            if(!empty($dirty))
            {
                $result = SOE\DB\Entity::where('entitiable_id', '=', $event->id)->where('entitiable_type', '=', 'Event')->whereIn('location_id', $aLocIDs)->update(array(
                    'name' => $event->name,
                    'slug' => $event->slug,
                    'category_id' => $category->id,
                    'subcategory_id' => $subcategory->id,
                    'path' => $event->path == '' ? (!empty($logo) ? $logo->path : '') : $event->path,
                    'is_demo' => $event->is_demo,
                    'is_active' => $event->is_active,
                    'starts_at' => $event->starts_at,
                    'expires_at' => $event->expires_at,
                    'url' => $event->website,
                    'is_featured' => $event->is_featured,
                    'expires_year' => date('Y', strtotime($event->expires_at)),
                    'expires_day' => date('z', strtotime($event->expires_at)) + 1,
                    'starts_year' => date('Y', strtotime($event->starts_at)),
                    'starts_day' => date('z', strtotime($event->starts_at)) + 1,
                    'category_slug' => $category->slug,
                    'subcategory_slug' => $subcategory->slug,
                    'service_radius' => $merchant->service_radius,
                    'short_name_line1' => $event->short_name_line1,
                    'short_name_line2' => $event->short_name_line2,
                    'category_visible' => $event->category_visible
                ));
            }
        }

        return $this->format($event);
    }

    protected function createCoupon()
    {
        set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '1024M');
        Eloquent::unguard();
        $franchise = $this->franchiseRepository->find(Input::get('franchise_id'), true);
        $tags = $this->projectTagRepository->getFranchiseTags($franchise);
        $aTags = array();
        foreach($tags as $tag)
        {
            $aTags[] = $tag->id;
        }
        $sTags = implode(',', $aTags);
        $is_demo = Input::get('is_demo', 0);
        $is_active = Input::get('status');
        $offer = SOE\DB\Offer::create(array(
            'merchant_id' => $franchise->merchant_id,
            'name' => Input::get('name'),
            'slug' => $this->getSlug(Input::get('name')),
            'is_active' => ($is_active && $franchise->is_active),
            'is_demo' => ($is_demo || $franchise->is_demo),
            'code' => Input::get('code', ''),
            'savings' => Input::get('savings',''),
            'description' => Input::get('description'),
            'path' => Input::get('path',''),
            'secondary_image' => Input::get('secondary_image',''),
            'special_price' => Input::get('specialprice'),
            'regular_price' => Input::get('regularprice'),
            'max_prints' => Input::get('max_prints'),
            'max_redeems' => Input::get('max_prints'),
            'starts_at' => date('Y-m-d 00:00:00', strtotime(Input::get('starts_at'))),
            'expires_at' => date('Y-m-d 00:00:00', strtotime(Input::get('expires_at'))),
            'hide_expiration' => Input::get('hide_expiration', 0),
            'is_reoccurring' => Input::get('is_reoccurring', 0),
            'is_location_specific' => Input::get('is_location_specific'),
            'is_dailydeal' => Input::get('savetoday'),
            'created_by' => Auth::check() ? Auth::User()->id : 0,
            'updated_by' => Auth::check() ? Auth::User()->id : 0,
            'print_override' => Input::get('print_override', ''),
            'franchise_id' => Input::get('franchise_id'),
            'requires_member' => Input::get('requires_member'),
            'is_mobile_only' => Input::get('is_mobile_only'),
            'secondary_type' => Input::get('secondary_type'),
            'short_name_line1' => Input::get('short_name_line1'),
            'short_name_line2' => Input::get('short_name_line2'),
            'custom_category_id' => Input::get('custom_category_id'),
            'custom_subcategory_id' => Input::get('custom_subcategory_id'),
            'category_visible' => Input::get('category_visible')
        ));
        Event::fire('backoffice.updated', array('offer', $offer->id, Auth::user()->id, $offer, 'offer created'));
        $offer = Offer::find($offer->id);

        $merchant = Merchant::find($offer->merchant_id);
        $category = Category::find($offer->custom_category_id == 0 ? $merchant->category_id : $offer->custom_category_id);
        $subcategory = Category::find($offer->custom_subcategory_id == 0 ? $merchant->subcategory_id : $offer->custom_subcategory_id);
        $company = SOE\DB\Company::find($franchise->company_id);
        $logo = Asset::getLogo($merchant);
        $locations = SOE\DB\Location::where('franchise_id', '=', $offer->franchise_id)->get();
        $aLocs = array();
        foreach($locations as $loc)
        {
            $aLocs[$loc->id] = $loc;
        }
        if($offer->is_location_specific)
        {
            if($locations = Input::get('locations'))
            {
                foreach($locations as $l)
                {
                    $added = SOE\DB\Entity::create(array(
                        'entitiable_id' => $offer->id,
                        'entitiable_type' => 'Offer',
                        'name' => $offer->name,
                        'slug' => $offer->slug,
                        'location_id' => $l,
                        'category_id' => $category->id,
                        'subcategory_id' => $subcategory->id,
                        'latitude' => $aLocs[$l]->latitude,
                        'longitude' => $aLocs[$l]->longitude,
                        'path' => $offer->path == '' ? (!empty($logo) ? $logo->path : '') : $offer->path,
                        'is_dailydeal' => $offer->is_dailydeal,
                        'rating' => 0,
                        'special_price' => $offer->special_price,
                        'regular_price' => $offer->regular_price,
                        'is_demo' => $offer->is_demo,
                        'is_active' => $offer->is_active,
                        'starts_at' => $offer->starts_at,
                        'expires_at' => $offer->expires_at,
                        'hide_expiration' => $offer->hide_expiration,
                        'is_reoccurring' => $offer->is_reoccurring,
                        'rating_count' => 0,
                        'savings' => $offer->savings,
                        'url' => $offer->url,
                        'print_override' => $offer->print_override,
                        'latm' => $aLocs[$l]->latm,
                        'lngm' => $aLocs[$l]->lngm,
                        'merchant_id' => $offer->merchant_id,
                        'merchant_slug' => $merchant->slug,
                        'merchant_name' => $merchant->display,
                        'is_featured' => $offer->is_featured,
                        'state' => $aLocs[$l]->state,
                        'expires_year' => date('Y', strtotime($offer->expires_at)),
                        'expires_day' => date('z', strtotime($offer->expires_at)) + 1,
                        'starts_year' => date('Y', strtotime($offer->starts_at)),
                        'starts_day' => date('z', strtotime($offer->starts_at)) + 1,
                        'location_active' => $aLocs[$l]->is_active,
                        'franchise_active' => $franchise->is_active,
                        'franchise_demo' => $franchise->is_demo,
                        'category_slug' => $category->slug,
                        'subcategory_slug' => $subcategory->slug,
                        'company_id' => $franchise->company_id,
                        'company_name' => $company->name,
                        'project_tags' => $sTags,
                        'is_certified' => $franchise->is_certified,
                        'sohi_trial_starts_at' => $franchise->sohi_trial_starts_at,
                        'sohi_trial_ends_at' => $franchise->sohi_trial_ends_at,
                        'secondary_type' => $offer->secondary_type,
                        'service_radius' => $merchant->service_radius,
                        'short_name_line1' => $offer->short_name_line1,
                        'short_name_line2' => $offer->short_name_line2,
                        'category_visible' => $offer->category_visible
                    ));
                }
            }
        }
        else
        {
            foreach($aLocs as $loc)
            {
                $added = SOE\DB\Entity::create(array(
                    'entitiable_id' => $offer->id,
                    'entitiable_type' => 'Offer',
                    'name' => $offer->name,
                    'slug' => $offer->slug,
                    'location_id' => $loc->id,
                    'category_id' => $category->id,
                    'subcategory_id' => $subcategory->id,
                    'latitude' => $loc->latitude,
                    'longitude' => $loc->longitude,
                    'path' => $offer->path == '' ? (!empty($logo) ? $logo->path : '') : $offer->path,
                    'is_dailydeal' => $offer->is_dailydeal,
                    'rating' => 0,
                    'special_price' => $offer->special_price,
                    'regular_price' => $offer->regular_price,
                    'is_demo' => $offer->is_demo,
                    'is_active' => $offer->is_active,
                    'starts_at' => $offer->starts_at,
                    'expires_at' => $offer->expires_at,
                    'hide_expiration' => $offer->hide_expiration,
                    'is_reoccurring' => $offer->is_reoccurring,
                    'rating_count' => 0,
                    'savings' => $offer->savings ? $offer->savings : '',
                    'url' => $offer->url,
                    'print_override' => $offer->print_override,
                    'latm' => $loc->latm,
                    'lngm' => $loc->lngm,
                    'merchant_id' => $offer->merchant_id,
                    'merchant_slug' => $merchant->slug,
                    'merchant_name' => $merchant->display,
                    'is_featured' => $offer->is_featured,
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
                    'company_name' => $company->name,
                    'project_tags' => $sTags,
                    'is_certified' => $franchise->is_certified,
                    'sohi_trial_starts_at' => $franchise->sohi_trial_starts_at,
                    'sohi_trial_ends_at' => $franchise->sohi_trial_ends_at,
                    'secondary_type' => $offer->secondary_type,
                    'service_radius' => $merchant->service_radius,
                    'short_name_line1' => $offer->short_name_line1,
                    'short_name_line2' => $offer->short_name_line2,
                    'category_visible' => $offer->category_visible
                ));
            }
        }
        /*$off = Offer::blank();
        $off = $off->createFromModel($offer);*/

        return $offer;
    }

    protected function createEvent()
    {
        set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '1024M');
        Eloquent::unguard();
        $franchise = $this->franchiseRepository->find(Input::get('franchise_id'), true);
        $tags = $this->projectTagRepository->getFranchiseTags($franchise);
        $aTags = array();
        foreach($tags as $tag)
        {
            $aTags[] = $tag->id;
        }
        $sTags = implode(',', $aTags);
        $is_demo = Input::get('is_demo', 0);
        $is_active = Input::get('status');
        $event = SOE\DB\Event::create(array(
            'merchant_id' => $franchise->merchant_id,
            'name' => Input::get('name'),
            'slug' => $this->getSlug(Input::get('name')),
            'is_active' => ($is_active && $franchise->is_active),
            'is_demo' => ($is_demo || $franchise->is_demo),
            'description' => Input::get('description'),
            'path' => Input::get('path',''),
            'starts_at' => date('Y-m-d 00:00:00', strtotime(Input::get('starts_at'))),
            'expires_at' => date('Y-m-d 00:00:00', strtotime(Input::get('expires_at'))),
            'event_start' => date('Y-m-d 00:00:00', strtotime(Input::get('event_start'))),
            'event_end' => date('Y-m-d 00:00:00', strtotime(Input::get('event_end'))),
            'website' => Input::get('website', ''),
            'is_location_specific' => Input::get('is_location_specific'),
            'franchise_id' => Input::get('franchise_id'),
            'short_name_line1' => Input::get('short_name_line1'),
            'short_name_line2' => Input::get('short_name_line2'),
            'custom_category_id' => Input::get('custom_category_id'),
            'custom_subcategory_id' => Input::get('custom_subcategory_id'),
            'category_visible' => Input::get('category_visible')
        ));
        Event::fire('backoffice.updated', array('event', $event->id, Auth::user()->id, $event, 'event created'));
        $event = SOE\DB\Event::find($event->id);

        $merchant = SOE\DB\Merchant::find($event->merchant_id);
        $category = SOE\DB\Category::find($event->custom_category_id == 0 ? $merchant->category_id : $event->custom_category_id);
        $subcategory = SOE\DB\Category::find($event->custom_subcategory_id == 0 ? $merchant->subcategory_id : $event->custom_subcategory_id);
        $company = SOE\DB\Company::find($franchise->company_id);
        $logo = $this->assets->getLogo($merchant);
        $locations = SOE\DB\Location::where('franchise_id', '=', $event->franchise_id)->get();
        $aLocs = array();
        foreach($locations as $loc)
        {
            $aLocs[$loc->id] = $loc;
        }
        if($event->is_location_specific)
        {
            if($locations = Input::get('locations'))
            {
                foreach($locations as $l)
                {
                    $added = SOE\DB\Entity::create(array(
                        'entitiable_id' => $event->id,
                        'entitiable_type' => 'Event',
                        'name' => $event->name,
                        'slug' => $event->slug,
                        'location_id' => $l,
                        'category_id' => $category->id,
                        'subcategory_id' => $subcategory->id,
                        'latitude' => $aLocs[$l]->latitude,
                        'longitude' => $aLocs[$l]->longitude,
                        'path' => $event->path == '' ? (!empty($logo) ? $logo->path : '') : $event->path,
                        'is_demo' => $event->is_demo,
                        'is_active' => $event->is_active,
                        'starts_at' => $event->starts_at,
                        'expires_at' => $event->expires_at,
                        'url' => $event->url,
                        'latm' => $aLocs[$l]->latm,
                        'lngm' => $aLocs[$l]->lngm,
                        'merchant_id' => $event->merchant_id,
                        'merchant_slug' => $merchant->slug,
                        'merchant_name' => $merchant->display,
                        'is_featured' => $event->is_featured,
                        'state' => $aLocs[$l]->state,
                        'expires_year' => date('Y', strtotime($event->expires_at)),
                        'expires_day' => date('z', strtotime($event->expires_at)) + 1,
                        'starts_year' => date('Y', strtotime($event->starts_at)),
                        'starts_day' => date('z', strtotime($event->starts_at)) + 1,
                        'location_active' => $aLocs[$l]->is_active,
                        'franchise_active' => $franchise->is_active,
                        'franchise_demo' => $franchise->is_demo,
                        'category_slug' => $category->slug,
                        'subcategory_slug' => $subcategory->slug,
                        'company_id' => $franchise->company_id,
                        'company_name' => $company->name,
                        'project_tags' => $sTags,
                        'is_certified' => $franchise->is_certified,
                        'sohi_trial_starts_at' => $franchise->sohi_trial_starts_at,
                        'sohi_trial_ends_at' => $franchise->sohi_trial_ends_at,
                        'service_radius' => $merchant->service_radius,
                        'short_name_line1' => $event->short_name_line1,
                        'short_name_line2' => $event->short_name_line2,
                        'category_visible' => $event->category_visible
                    ));
                }
            }
        }
        else
        {
            foreach($aLocs as $loc)
            {
                $added = SOE\DB\Entity::create(array(
                    'entitiable_id' => $event->id,
                    'entitiable_type' => 'Offer',
                    'name' => $event->name,
                    'slug' => $event->slug,
                    'location_id' => $loc->id,
                    'category_id' => $category->id,
                    'subcategory_id' => $subcategory->id,
                    'latitude' => $loc->latitude,
                    'longitude' => $loc->longitude,
                    'path' => $event->path == '' ? (!empty($logo) ? $logo->path : '') : $event->path,
                    'is_demo' => $event->is_demo,
                    'is_active' => $event->is_active,
                    'starts_at' => $event->starts_at,
                    'expires_at' => $event->expires_at,
                    'url' => $event->website,
                    'latm' => $loc->latm,
                    'lngm' => $loc->lngm,
                    'merchant_id' => $event->merchant_id,
                    'merchant_slug' => $merchant->slug,
                    'merchant_name' => $merchant->display,
                    'is_featured' => $event->is_featured,
                    'state' => $loc->state,
                    'expires_year' => date('Y', strtotime($event->expires_at)),
                    'expires_day' => date('z', strtotime($event->expires_at)) + 1,
                    'starts_year' => date('Y', strtotime($event->starts_at)),
                    'starts_day' => date('z', strtotime($event->starts_at)) + 1,
                    'location_active' => $loc->is_active,
                    'franchise_active' => $franchise->is_active,
                    'franchise_demo' => $franchise->is_demo,
                    'category_slug' => $category->slug,
                    'subcategory_slug' => $subcategory->slug,
                    'company_id' => $franchise->company_id,
                    'company_name' => $company->name,
                    'project_tags' => $sTags,
                    'is_certified' => $franchise->is_certified,
                    'sohi_trial_starts_at' => $franchise->sohi_trial_starts_at,
                    'sohi_trial_ends_at' => $franchise->sohi_trial_ends_at,
                    'service_radius' => $merchant->service_radius,
                    'short_name_line1' => $event->short_name_line1,
                    'short_name_line2' => $event->short_name_line2,
                    'category_visible' => $event->category_visible
                ));
            }
        }

        return $event;
    }

    public function postDuplicate($offer_id)
    {
        set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '1024M');
        $offer = Offer::find($offer_id);
        if(empty($offer))
        {
            return Response::json(0);
        }
        Eloquent::unguard();
        $dup = SOE\DB\Offer::create(array(
                    'name' => $offer->name,
                    'slug' => $offer->slug,
                    'merchant_id' => $offer->merchant_id,
                    'path' => $offer->path,
                    'path_small' => $offer->path_small,
                    'is_dailydeal' => $offer->is_dailydeal,
                    'special_price' => $offer->special_price,
                    'regular_price' => $offer->regular_price,
                    'code' => $offer->code,
                    'description' => $offer->description,
                    'starts_at' => $offer->starts_at,
                    'expires_at' => $offer->expires_at,
                    'max_redeems' => $offer->max_redeems,
                    'max_prints' => $offer->max_prints,
                    'url' => $offer->url,
                    'print_override' => $offer->print_override,
                    'is_demo' => $offer->is_demo,
                    'is_active' => $offer->is_active,
                    'created_by' => Auth::check() ? Auth::User()->id : 0,
                    'updated_by' => Auth::check() ? Auth::User()->id : 0,
                    'savings' => $offer->savings,
                    'is_featured' => $offer->is_featured,
                    'franchise_id' => $offer->franchise_id,
                    'is_location_specific' => $offer->is_location_specific,
                    'is_mobile_only' => $offer->is_mobile_only,
                    'requires_member' => $offer->requires_member,
                    'secondary_type' => $offer->secondary_type,
                    'short_name_line1' => $offer->short_name_line1,
                    'short_name_line2' => $offer->short_name_line2,
                    'custom_category_id' => $offer->custom_category_id,
                    'custom_subcategory_id' => $offer->custom_subcategory_id,
                    'category_visible' => $offer->category_visible,
                    'secondary_image' => $offer->secondary_image,
                    'hide_expiration' => $offer->hide_expiration,
                    'year' => $offer->year,
                    'make' => $offer->make,
                    'make_id' => $offer->make_id,
                    'model' => $offer->model,
                    'model_id' => $offer->model_id
            ));
        Event::fire('backoffice.updated', array('offer', $dup->id, Auth::user()->id, $dup, 'offer duplicated'));

        $entities = SOE\DB\Entity::where('entities.entitiable_id', '=', $offer->id)
                                    ->where('entities.entitiable_type', '=', 'Offer')
                                    ->get();
        if(count($entities) == 0)
        {
            $franchise = $this->franchiseRepository->find($offer->franchise_id);
            $tags = $this->projectTagRepository->getFranchiseTags($franchise);
            $aTags = array();
            foreach($tags as $tag)
            {
                $aTags[] = $tag->id;
            }
            $sTags = implode(',', $aTags);
            $locations = $this->locationRepository->getByFranchise($franchise);
            $company = SOE\DB\Company::find($franchise->company_id);
            $merchant = Merchant::find($offer->merchant_id);
            $category = Category::find($offer->custom_category_id == 0 ? $merchant->category_id : $offer->custom_category_id);
            $subcategory = Category::find($offer->custom_subcategory_id == 0 ? $merchant->subcategory_id : $offer->custom_subcategory_id);
            $logo = $merchant->logo();
            foreach($locations as $location)
            {
                Entity::create(array(
                    'entitiable_id' => $dup->id,
                    'entitiable_type' => 'Offer',
                    'name' => $dup->name,
                    'slug' => $dup->slug,
                    'location_id' => $location->id,
                    'category_id' => $category->id,
                    'subcategory_id' => $subcategory->id,
                    'latitude' => $location->latitude,
                    'longitude' => $location->longitude,
                    'path' => $dup->path == '' ? (empty($logo) ? '' : $logo->path) : $dup->path,
                    'is_dailydeal' => $dup->is_dailydeal,
                    'special_price' => $dup->special_price,
                    'regular_price' => $dup->regular_price,
                    'is_demo' => $dup->is_demo,
                    'is_active' => $dup->is_active,
                    'starts_at' => $dup->starts_at,
                    'expires_at' => $dup->expires_at,
                    'savings' => $dup->savings,
                    'url' => $dup->url,
                    'print_override' => $dup->print_override,
                    'secondary_type' => $dup->secondary_type,
                    'latm' => $location->latm,
                    'lngm' => $location->lngm,
                    'merchant_id' => $merchant->id,
                    'merchant_slug' => $merchant->slug,
                    'merchant_name' => $merchant->name,
                    'is_featured' => $dup->is_featured,
                    'state' => $location->state,
                    'expires_year' => date('Y', strtotime($dup->expires_at)),
                    'expires_day' => date('z', strtotime($dup->expires_at)) + 1,
                    'starts_year' => date('Y', strtotime($dup->starts_at)),
                    'starts_day' => date('z', strtotime($dup->starts_at)) + 1,
                    'location_active' => $location->is_active,
                    'franchise_active' => $franchise->is_active,
                    'franchise_demo' => $franchise->is_demo,
                    'category_slug' => $category->slug,
                    'subcategory_slug' => $subcategory->slug,
                    'company_id' => $company->id,
                    'company_name' => $company->name,
                    'project_tags' => $sTags,
                    'is_certified' => $franchise->is_certified,
                    'sohi_trial_starts_at' => $franchise->sohi_trial_starts_at,
                    'sohi_trial_ends_at' => $franchise->sohi_trial_ends_at,
                    'service_radius' => $merchant->service_radius,
                    'short_name_line1' => $dup->short_name_line1,
                    'short_name_line2' => $dup->short_name_line2,
                    'category_visible' => $offer->category_visible,
                    'hide_expiration' => $dup->hide_expiration
                ));
            }
        }
        foreach($entities as $ent)
        {
            $columns = Entity::getColumns();
            foreach($columns as $column)
            {
                $data[$column] = $ent->$column;
            }
            $data['entitiable_id'] = $dup->id;
            if($data['state'] == '')
            {
                $location = Location::find($data['location_id']);
                $data['state'] = empty($location) ? '' : $location->state;    
            }
            $data['expires_year'] = date('Y', strtotime($dup->expires_at));
            $data['expires_day'] = date('z', strtotime($dup->expires_at)) + 1;
            $data['starts_year'] = date('Y', strtotime($dup->starts_at));
            $data['starts_day'] = date('z', strtotime($dup->starts_at)) + 1;
            Entity::create($data);
        }
        
        $duplicate = Offer::blank();
        $duplicate = $duplicate->createFromModel($dup);
        return $this->format($duplicate);
    }

    public function postDuplicateEvent($event_id)
    {
        set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '1024M');
        $event = SOE\DB\Event::find($event_id);
        if(empty($event))
        {
            return Response::json(0);
        }
        Eloquent::unguard();
        $dup = SOE\DB\Event::create(array(
                    'name' => $event->name,
                    'slug' => $event->slug,
                    'merchant_id' => $event->merchant_id,
                    'path' => $event->path,
                    'description' => $event->description,
                    'starts_at' => $event->starts_at,
                    'expires_at' => $event->expires_at,
                    'website' => $event->website,
                    'is_demo' => $event->is_demo,
                    'is_active' => $event->is_active,
                    'is_featured' => $event->is_featured,
                    'franchise_id' => $event->franchise_id,
                    'is_location_specific' => $event->is_location_specific,
                    'short_name_line1' => $event->short_name_line1,
                    'short_name_line2' => $event->short_name_line2,
                    'custom_category_id' => $event->custom_category_id,
                    'custom_subcategory_id' => $event->custom_subcategory_id,
                    'category_visible' => $event->category_visible
            ));
        Event::fire('backoffice.updated', array('event', $dup->id, Auth::user()->id, $dup, 'event duplicated'));

        $entities = SOE\DB\Entity::where('entities.entitiable_id', '=', $event->id)
                                    ->where('entities.entitiable_type', '=', 'Event')
                                    ->get();
        if(count($entities) == 0)
        {
            $franchise = $this->franchiseRepository->find($event->franchise_id);
            $tags = $this->projectTagRepository->getFranchiseTags($franchise);
            $aTags = array();
            foreach($tags as $tag)
            {
                $aTags[] = $tag->id;
            }
            $sTags = implode(',', $aTags);
            $locations = $this->locationRepository->getByFranchise($franchise);
            $company = SOE\DB\Company::find($franchise->company_id);
            $merchant = SOE\DB\Merchant::find($event->merchant_id);
            $category = SOE\DB\Category::find($event->custom_category_id == 0 ? $merchant->category_id : $event->custom_category_id);
            $subcategory = SOE\DB\Category::find($event->custom_subcategory_id == 0 ? $merchant->subcategory_id : $event->custom_subcategory_id);
            $logo = $merchant->logo();
            foreach($locations as $location)
            {
                Entity::create(array(
                    'entitiable_id' => $dup->id,
                    'entitiable_type' => 'Event',
                    'name' => $dup->name,
                    'slug' => $dup->slug,
                    'location_id' => $location->id,
                    'category_id' => $category->id,
                    'subcategory_id' => $subcategory->id,
                    'latitude' => $location->latitude,
                    'longitude' => $location->longitude,
                    'path' => $dup->path == '' ? (empty($logo) ? '' : $logo->path) : $dup->path,
                    'is_demo' => $dup->is_demo,
                    'is_active' => $dup->is_active,
                    'starts_at' => $dup->starts_at,
                    'expires_at' => $dup->expires_at,
                    'url' => $dup->website,
                    'latm' => $location->latm,
                    'lngm' => $location->lngm,
                    'merchant_id' => $merchant->id,
                    'merchant_slug' => $merchant->slug,
                    'merchant_name' => $merchant->name,
                    'is_featured' => $dup->is_featured,
                    'state' => $location->state,
                    'expires_year' => date('Y', strtotime($dup->expires_at)),
                    'expires_day' => date('z', strtotime($dup->expires_at)) + 1,
                    'starts_year' => date('Y', strtotime($dup->starts_at)),
                    'starts_day' => date('z', strtotime($dup->starts_at)) + 1,
                    'location_active' => $location->is_active,
                    'franchise_active' => $franchise->is_active,
                    'franchise_demo' => $franchise->is_demo,
                    'category_slug' => $category->slug,
                    'subcategory_slug' => $subcategory->slug,
                    'company_id' => $company->id,
                    'company_name' => $company->name,
                    'project_tags' => $sTags,
                    'is_certified' => $franchise->is_certified,
                    'sohi_trial_starts_at' => $franchise->sohi_trial_starts_at,
                    'sohi_trial_ends_at' => $franchise->sohi_trial_ends_at,
                    'service_radius' => $merchant->service_radius,
                    'short_name_line1' => $dup->short_name_line1,
                    'short_name_line2' => $dup->short_name_line2,
                    'category_visible' => $offer->category_visible
                ));
            }
        }
        foreach($entities as $ent)
        {
            $columns = Entity::getColumns();
            foreach($columns as $column)
            {
                $data[$column] = $ent->$column;
            }
            $data['entitiable_id'] = $dup->id;
            if($data['state'] == '')
            {
                $location = Location::find($data['location_id']);
                $data['state'] = empty($location) ? '' : $location->state;    
            }
            $data['expires_year'] = date('Y', strtotime($dup->expires_at));
            $data['expires_day'] = date('z', strtotime($dup->expires_at)) + 1;
            $data['starts_year'] = date('Y', strtotime($dup->starts_at));
            $data['starts_day'] = date('z', strtotime($dup->starts_at)) + 1;
            Entity::create($data);
        }
        return $this->format($dup);
    }

    public function getGallery($image_id = null)
    {
        if($image_id)
        {
            $image = Asset::find($image_id);
            return $this->format($image);
        }

        $query = SOE\DB\Asset::where('assetable_type', '=', 'category_asset');
        if($category_id = Input::get('category_id'))
        {
            $query = $query->where('category_id', '=', $category_id);
        }
        if($subcategory_id = Input::get('subcategory_id'))
        {
            $query = $query->where('subcategory_id', '=', $subcategory_id);
        }
        $stats = $this->pagination_stats($query);
        $query = $this->paginate($query);
        $images = $query->get();
        $return = array('objects' => array());
        foreach($images as $image)
        {
            $asset = Asset::blank();
            $asset = $asset->createFromModel($image);
            $return['objects'][] = $asset;
        }
        $response = array_merge($return, $stats);

        return $this->format($response);
    }

    public function getMedia($merchant_id)
    {
        $location_id = Input::get('location_id', 0);
        if($location_id != 0)
        {
            $location = $this->locationRepository->find($location_id);
            $thumbs_type = $location->is_about_specific ? 'Location' : 'Merchant';
            $pdfs_type = $location->is_pdf_specific ? 'Location' : 'Merchant';
            $videos_type = $location->is_video_specific ? 'Location' : 'Merchant';

            $thumbs_id = $location->is_about_specific ? $location->id : $merchant_id;
            $pdfs_id = $location->is_pdf_specific ? $location->id : $merchant_id;
            $videos_id = $location->is_video_specific ? $location->id : $merchant_id;
        }
        else
        {
            $thumbs_type = 'Merchant';
            $pdfs_type = 'Merchant';
            $videos_type = 'Merchant';

            $thumbs_id = $merchant_id;
            $pdfs_id = $merchant_id;
            $videos_id = $merchant_id;
        }
        $thumbs = SOE\DB\Asset::where('name', 'LIKE', '%smallImage%')->where('assetable_id', '=', $thumbs_id)->where('assetable_type', '=', $thumbs_type)->get();
        $video = SOE\DB\Asset::where('name', 'LIKE', '%clientVideoLink%')->where('assetable_id', '=', $videos_id)->where('assetable_type', '=', $videos_type)->first();
        $pdfs = SOE\DB\Asset::where('type', '=', 'pdf')->where('assetable_id', '=', $pdfs_id)->where('assetable_type', '=', $pdfs_type)->orderBy('name', 'asc')->get();
        $logo = SOE\DB\Asset::where('name', '=', 'logo1')->where('assetable_id', '=', $merchant_id)->where('assetable_type', '=', 'Merchant')->first();
        $banner = SOE\DB\Asset::where('name', '=', 'banner')->where('assetable_id', '=', $merchant_id)->where('assetable_type', '=', 'Merchant')->first();
        /*$aThumbs = array();
        $aPdfs = array();
        foreach($thumbs as $thumb)
        {
            $t = Asset::blank();
            $t = $t->createFromModel($thumb);
            $aThumbs[] = $t;
        }
        foreach($pdfs as $pdf)
        {
            $p = Asset::blank();
            $p = $p->createFromModel($pdf);
            $aPdfs[] = $p;
        }*/
        /*$v = Asset::blank();
        $v = empty($video) ? array() : $v->createFromModel($video);
        $l = Asset::blank();
        $l = empty($logo) ? array() : $l->createFromModel($logo);
        $b = Asset::blank();
        $b = empty($banner) ? array() : $b->createFromModel($banner);*/

        $response = array(array(
            'thumbs' => $thumbs->toArray(),
            'video' => $video ? $video->toArray() : array(),
            'pdfs' => $pdfs->toArray(),
            'logo' => $logo ? $logo->toArray() : array(),
            'banner' => $banner ? $banner->toArray() : array()
            ));

        return $response;//$this->format($response);
    }

    public function postSaveAbout()
    {
        $franchise = Franchise::find(Input::get('franchise_id', 0), true);
        if(!empty($franchise))
        {
            $location_id = Input::get('location_id');
            if($location_id)
            {
                $location = $this->locationRepository->find($location_id);
                if(!empty($location))
                {
                    $location->about = Input::get('about', '');
                    $location->page_title = Input::get('page_title', '');
                    $location->keywords = Input::get('keywords');
                    $location->meta_description = Input::get('meta_description');
                    $location->save();
                    Event::fire('backoffice.updated', array('location', $location->id, Auth::user()->id, $location, 'location about updated'));
                    return json_encode($location->toArray());
                }
                return json_encode(1);
            }
            else
            {
                $merchant = Merchant::find($franchise->merchant_id);
                $merchant->about = Input::get('about', '');
                $merchant->page_title = Input::get('page_title', '');
                $merchant->keywords = Input::get('keywords');
                $merchant->meta_description = Input::get('meta_description');
                $merchant->sub_heading = Input::get('subheading');
                $merchant->save();
                Event::fire('backoffice.updated', array('merchant', $merchant->id, Auth::user()->id, $merchant, 'merchant about updated'));
            }
        }
        return Redirect::to('/pictures?viewing='.Input::get('franchise_id', 0));
    }

    /**
     * Pages query results
     *
     * Pages query results, uses input or default constants
     *
     * @param Query $query the passed partially-constructed query
     * @return Query modified query
     */
    protected function paginate($query)
    {
        $take = Input::get('limit', 10);
        $page = Input::get('page', 0);

        $query = $query->take($take)->skip($take * $page);

        return $query;
    }

    /**
     * Retrieve pagination statistics from query
     */
    protected function pagination_stats($query)
    {
        $stats = array('stats' => array());

        $count = $query->count();

        $stats['stats']['total'] = $count;
        $stats['stats']['page'] = Input::get('page', 0);
        $stats['stats']['take'] = Input::get('limit', 10);

        return $stats;
    }

    protected function getOrder($query)
    {
        $order_column = Input::get('sort_by', 'display');
        $direction = Input::get('sort_type', 'asc');
        switch ($order_column) {
            case 'display':
                $query = $query->orderBy('merchants.display', $direction);
                break;
            case 'city':
                $query->join('location', 'merchant.default_location_id', '=', 'location.id');
                $query = $query->orderBy('locations.city', $direction);
                break;
            case 'category':
                $query->join('categories', 'merchant.category_id', '=', 'categories.id');
                $query = $query->orderBy('categories.name', $direction);
                break;
            case 'subcategory':
                $query->join('categories', 'merchant.subcategory_id', '=', 'categories.id');
                $query = $query->orderBy('categories.name', $direction);
                break;
        }

        return $query;
    }

    protected function removePunctuation($string)
    {
        return str_replace(array("'", "."), "", $string);
    }

    protected function getSlug($string)
    {
        return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
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

    public function postUploadLocationImage()
    {
        $awsAccessKey = 'AKIAI6NWHQYFADWZVYLQ';
        $awsSecretKey = '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';
        $bucketName = 'saveoneverything_assets';
        $inputData = Input::all();
        $s3path = "http://s3.amazonaws.com/saveoneverything_assets/";

        if(!empty($inputData))
        {
            $type = $inputData['type'];
            if (Input::hasFile('upload_img')) 
            {
                $temp_file = Input::file('upload_img')->getRealPath();
                $extension = Input::file('upload_img')->getClientOriginalExtension();
                $assets_path = "images/".time()."-".$type.".".$extension;
                
                $s3 = new S3($awsAccessKey, $awsSecretKey);
                $location_id = $inputData['location_id'];
                $location = $this->locationRepository->find($location_id);
                if(!$location)
                    return;

                // Put our file (also with public read access)
                if ($s3->putObjectFile($temp_file, $bucketName, $assets_path, S3::ACL_PUBLIC_READ)) 
                {
                    switch ($type)
                    {
                        case 'logo1':
                            $asset = $this->assets->query()->where('assetable_id', '=', $location_id)
                                                ->where('assetable_type', '=', 'Location')
                                                ->where('name', '=', 'logo1')
                                                ->first();
                            if(empty($asset))
                            {
                                $asset = $this->assets->create(array(
                                        'type' => 'image',
                                        'path' => $this->httpImage($s3path.$assets_path),
                                        'name' => 'logo1',
                                        'assetable_id' => $location_id,
                                        'assetable_type' => 'Location'
                                    ));
                            }
                            else
                            {
                                $asset->path = $this->httpImage($s3path.$assets_path);
                                $asset->save();
                            }
                            Event::fire('backoffice.updated', array('asset', $asset->id, Auth::user()->id, $asset, 'location logo updated'));
                            $offers = SOE\DB\Offer::where('path', '=', '')->where('franchise_id', '=', $location->franchise_id)->get();
                            $aOfferIds = array(0);
                            foreach($offers as $offer)
                            {
                                $aOfferIds[] = $offer->id;
                            }
                            SOE\DB\Entity::whereIn('entitiable_id', $aOfferIds)
                                        ->where('entitiable_type', '=', 'Offer')
                                        ->where('location_id', '=', $location_id)
                                        ->update(array('path' => $this->httpImage($s3path.$assets_path)));

                            $events = SOE\DB\Event::where('path', '=', '')->where('franchise_id', '=', $location->franchise_id)->get();
                            $aEventIds = array(0);
                            foreach($events as $event)
                            {
                                $aEventIds[] = $event->id;
                            }
                            SOE\DB\Entity::whereIn('entitiable_id', $aEventIds)
                                        ->where('entitiable_type', '=', 'Event')
                                        ->where('location_id', '=', $location->id)
                                        ->update(array('path' => $this->httpImage($s3path.$assets_path)));
                            unset($asset);
                            break;
                        case 'banner':
                            $asset = $this->assets->query()->where('assetable_id', '=', $location_id)
                                                ->where('assetable_type', '=', 'Location')
                                                ->where('name', '=', 'banner')
                                                ->first();
                            if(empty($asset))
                            {
                                $asset = $this->assets->create(array(
                                        'type' => 'image',
                                        'path' => $this->httpImage($s3path.$assets_path),
                                        'name' => 'banner',
                                        'assetable_id' => $location_id,
                                        'assetable_type' => 'Location'
                                    ));
                            }
                            else
                            {
                                $asset->path = $this->httpImage($s3path.$assets_path);
                                $asset->save();
                            }
                            Event::fire('backoffice.updated', array('asset', $asset->id, Auth::user()->id, $asset, 'location banner updated'));
                            unset($asset);
                            break;
                    }
                }

                echo '<script> top.document.getElementById("ieDoneButton").click(); </script>';
            }
        }
    }

    public function postWizardImgupload()
    {
        $awsAccessKey = 'AKIAI6NWHQYFADWZVYLQ';
        $awsSecretKey = '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';
        $bucketName = 'saveoneverything_assets';
        $inputData = Input::all();
        $s3path = "http://s3.amazonaws.com/saveoneverything_assets/";

        if(!empty($inputData))
        {
            $count = 0;
            $type = $inputData['type'];
            $location_id = Input::get('location_id', 0);
            $location = $this->locationRepository->find($location_id);
            //Ajax Upload

            if (Input::hasFile('upload_img')) 
            {
                $temp_file = Input::file('upload_img')->getRealPath();
                $extension = Input::file('upload_img')->getClientOriginalExtension();
                $assets_path = "images/".time()."-".$type.".".$extension;
                $assets[$count]['name'] = 'upload_img';
                
                $s3 = new S3($awsAccessKey, $awsSecretKey);

                //Remove the old file if it exists
                switch ($type) {
                    case 'coupon':
                        $coupon_id = $inputData['coupon_id'];
                        $old_asset = SOE\DB\Offer::find($coupon_id);
                        if(empty($old_asset))
                        {
                            return json_encode(0);
                        }
                        break;
                    case 'logo':
                        $assets[$count]['asset_name'] = 'logo1';
                        $merchant_id = $inputData['merchant_id'];
                        $old_asset = SOE\DB\Asset::where('assetable_id', '=', $merchant_id)
                                                ->where('assetable_type', '=', 'Merchant')
                                                ->where('name', '=', 'logo1')
                                                ->first();
                        break;
                    case 'banner':
                        $assets[$count]['asset_name'] = 'banner';
                        $merchant_id = $inputData['merchant_id'];
                        $old_asset = SOE\DB\Asset::where('assetable_id', '=', $merchant_id)
                                                ->where('assetable_type', '=', 'Merchant')
                                                ->where('name', '=', 'banner')
                                                ->first();
                        break;
                    case 'sponsor_banner':
                        $assets[$count]['asset_name'] = 'banner';
                        $franchise_id = $inputData['franchise_id'];
                        break;
                }

                // Put our file (also with public read access)
                if ($s3->putObjectFile($temp_file, $bucketName, $assets_path, S3::ACL_PUBLIC_READ)) 
                {
                    $assets[$count]['status'] = 'success';
                    switch ($type) {
                        case 'asset':
                            if($location && $location->is_about_specific)
                            {
                                $assetable_id = $location->id;
                                $assetable_type = 'Location';
                            }
                            else
                            {
                                $assetable_id = $merchant_id;
                                $assetable_type = 'Merchant';
                            }
                            $merch_asset = SOE\DB\Asset::where('assetable_id', '=', $assetable_id)
                                                ->where('assetable_type', '=', $assetable_type)
                                                ->where('name', '=', 'smallImage'.$pieces[2])->first();
                            if(empty($merch_asset))
                            {
                                $merch_asset = Asset::create(array(
                                        'type' => 'image',
                                        'path' => $this->httpImage($s3path.$assets_path),
                                        'name' => 'smallImage'.$pieces[2],
                                        'assetable_id' => $assetable_id,
                                        'long_description' => Input::get('long_description', ''),
                                        'short_description' => Input::get('short_description', ''),
                                        'assetable_type' => $assetable_type
                                    ));
                            }
                            else
                            {
                                $merch_asset->path = $this->httpImage($s3path.$assets_path);
                                $merch_asset->save();
                            }
                            Event::fire('backoffice.updated', array('asset', $merch_asset->id, Auth::user()->id, $merch_asset, 'asset updated'));
                            unset($merch_asset);
                            break;
                        case 'coupon':
                            $offer = Offer::find($coupon_id);
                            $offer->path = $this->httpImage($s3path.$assets_path);
                            $offer->save();
                            Event::fire('backoffice.updated', array('offer', $offer->id, Auth::user()->id, $offer, 'offer updated'));
                            unset($offer);
                            break;
                        case 'secondary_image':
                            echo '<script>top.document.getElementById("ieSecondaryDoneButton").innerHTML="'.$this->httpImage($s3path.$assets_path).'";top.document.getElementById("ieSecondaryDoneButton").click(); </script>';
                            return;
                            break;
                        case 'gallery':
                            $cat_asset = Asset::blank();
                            $cat_asset->name = $assets[$count]['name'];
                            $cat_asset->assetable_type = 'category_asset';
                            $cat_asset->path = $this->httpImage($s3path.$assets_path);
                            $cat_asset->category_id = Input::get('category_id');
                            $cat_asset->subcategory_id = Input::get('subcategory_id');
                            $cat_asset->save();
                            Event::fire('backoffice.updated', array('asset', $cat_asset->id, Auth::user()->id, $cat_asset, 'gallery asset created'));
                            echo '<script>top.document.getElementById("ieDoneButton").innerHTML="'.$cat_asset->path.'";top.document.getElementById("ieDoneButton").click(); </script>';
                            return;
                            break;
                        case 'logo':
                            $merch_asset = SOE\DB\Asset::where('assetable_id', '=', $merchant_id)
                                                ->where('assetable_type', '=', 'Merchant')
                                                ->where('name', '=', 'logo1')
                                                ->first();
                            if(empty($merch_asset))
                            {
                                $merch_asset = Asset::create(array(
                                        'type' => 'image',
                                        'path' => $this->httpImage($s3path.$assets_path),
                                        'name' => 'logo1',
                                        'assetable_id' => $merchant_id,
                                        'assetable_type' => 'Merchant'
                                    ));
                            }
                            else
                            {
                                $merch_asset->path = $this->httpImage($s3path.$assets_path);
                                $merch_asset->save();
                            }
                            Event::fire('backoffice.updated', array('asset', $merch_asset->id, Auth::user()->id, $merch_asset, 'merchant logo updated'));
                            $offers = SOE\DB\Offer::where('path', '=', '')->where('merchant_id', '=', $merchant_id)->get();
                            $aOfferIds = array(0);
                            foreach($offers as $offer)
                            {
                                $aOfferIds[] = $offer->id;
                            }
                            SOE\DB\Entity::whereIn('entitiable_id', $aOfferIds)
                                        ->where('entitiable_type', '=', 'Offer')
                                        ->where('merchant_id', '=', $merchant_id)
                                        ->update(array('path' => $this->httpImage($s3path.$assets_path)));
                            unset($merch_asset);
                            break;
                        case 'banner':
                            $merch_asset = SOE\DB\Asset::where('assetable_id', '=', $merchant_id)
                                                ->where('assetable_type', '=', 'Merchant')
                                                ->where('name', '=', 'banner')
                                                ->first();
                            if(empty($merch_asset))
                            {
                                $merch_asset = Asset::create(array(
                                        'type' => 'image',
                                        'path' => $this->httpImage($s3path.$assets_path),
                                        'name' => 'banner',
                                        'assetable_id' => $merchant_id,
                                        'assetable_type' => 'Merchant'
                                    ));
                            }
                            else
                            {
                                $merch_asset->path = $this->httpImage($s3path.$assets_path);
                                $merch_asset->save();
                            }
                            Event::fire('backoffice.updated', array('asset', $merch_asset->id, Auth::user()->id, $merch_asset, 'merchant banner updated'));
                            unset($merch_asset);
                            break;
                        case 'syndication_banner':
                            $franchise = $this->franchiseRepository->find(Input::get('franchise_id'));
                            $banner_type = Input::get('banner_type');
                            $franchise->{$banner_type} = $this->httpImage($s3path.$assets_path);
                            $franchise->save();
                            Event::fire('backoffice.updated', array('franchise', $franchise->id, Auth::user()->id, $franchise, 'franchise syndication asset updated'));
                            break;
                        case 'sponsor_banner':
                            $franchise = $this->franchiseRepository->find(Input::get('franchise_id'));
                            $franchise->sponsor_banner = $this->httpImage($s3path.$assets_path);
                            $franchise->save();
                            Event::fire('backoffice.updated', array('franchise', $franchise->id, Auth::user()->id, $franchise, 'franchise sponsor banner updated'));
                            echo '<script> top.document.getElementById("ieSponsorBannerDoneButton").click(); </script>';
                            return;
                            break;
                    }
                } 
                else 
                {
                    $assets[$count]['status'] = 'error';
                }
            }
            else
            {
                $found = 0;
                for($i=1; $i <= 10; $i++)
                {
                    if(Input::hasFile('file_Thumb_'.$i))
                        $found = $i;
                }
                if($found != 0)
                {
                    $temp_file = Input::file('file_Thumb_'.$found)->getRealPath();
                    $extension = Input::file('file_Thumb_'.$found)->getClientOriginalExtension();
                    $assets_path = "images/".time()."-asset.".$extension;
                    $assets[$count]['name'] = 'file_Thumb_'.$found;
                    
                    $s3 = new S3($awsAccessKey, $awsSecretKey);

                    $assets[$count]['asset_name'] = 'smallImage'.$found;
                    $merchant_id = $inputData['merchant_id'];
                    if($location && $location->is_about_specific)
                    {
                        $assetable_id = $location->id;
                        $assetable_type = 'Location';
                    }
                    else
                    {
                        $assetable_id = $merchant_id;
                        $assetable_type = 'Merchant';
                    }
                    
                    $old_asset = SOE\DB\Asset::where('assetable_id', '=', $assetable_id)
                                            ->where('assetable_type', '=', $assetable_type)
                                            ->where('name', '=', 'smallImage'.$found)
                                            ->first();

                    // Put our file (also with public read access)
                    if ($s3->putObjectFile($temp_file, $bucketName, $assets_path, S3::ACL_PUBLIC_READ)) 
                    {
                        $assets[$count]['status'] = 'success';
                        $merch_asset = SOE\DB\Asset::where('assetable_id', '=', $assetable_id)
                                            ->where('assetable_type', '=', $assetable_type)
                                            ->where('name', '=', 'smallImage'.$found)->first();
                        if(empty($merch_asset))
                        {
                            $merch_asset = Asset::create(array(
                                    'type' => 'image',
                                    'path' => $this->httpImage($s3path.$assets_path),
                                    'name' => 'smallImage'.$found,
                                    'assetable_id' => $assetable_id,
                                    'assetable_type' => $assetable_type
                                ));
                        }
                        else
                        {
                            $merch_asset->path = $this->httpImage($s3path.$assets_path);
                            $merch_asset->save();
                        }
                        Event::fire('backoffice.updated', array('asset', $merch_asset->id, Auth::user()->id, $merch_asset, 'asset updated'));
                        unset($merch_asset);
                    } 
                    else 
                    {
                        $assets[$count]['status'] = 'error';
                    }
                }
            }
            echo '<script> top.document.getElementById("ieDoneButton").click(); </script>';
        }
    }

    protected function httpImage($path)
    {
        return str_replace('https', 'http', $path);
    }

    public function getRemoveMedia($merchant_id)
    {
        $type = Input::get('type');
        $ident = Input::get('identifier');
        $awsAccessKey = 'AKIAI6NWHQYFADWZVYLQ';
        $awsSecretKey = '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';
        $bucketName = 'saveoneverything_assets';
        $s3path = "http://s3.amazonaws.com/saveoneverything_assets/";
        $s3 = new S3($awsAccessKey, $awsSecretKey);

        $location_id = Input::get('location_id', 0);
        $location = $this->locationRepository->find($location_id);
        if($location)
        {
            $image_id = $location->is_about_specific ? $location->id : $merchant_id;
            $pdf_id = $location->is_pdf_specific ? $location->id : $merchant_id;
            $logo_id = $location->is_logo_specific ? $location->id : $merchant_id;

            $image_type = $location->is_about_specific ? 'Location' : 'Merchant';
            $pdf_type = $location->is_about_specific ? 'Location' : 'Merchant';
            $logo_type = $location->is_about_specific ? 'Location' : 'Merchant';
        }
        else
        {
            $image_id = $merchant_id;
            $pdf_id = $merchant_id;
            $logo_id = $merchant_id;

            $image_type = 'Merchant';
            $pdf_type = 'Merchant';
            $logo_type = 'Merchant';
        }

        //Remove the old file if it exists
        switch ($type) {
            case 'image':
                $old_asset = SOE\DB\Asset::where('assetable_id', '=', $image_id)
                                        ->where('assetable_type', '=', $image_type)
                                        ->where('name', '=', 'smallImage'.$ident)->first();
                break;
            case 'pdf':
                $old_asset = SOE\DB\Asset::where('assetable_id', '=', $pdf_id)
                                        ->where('assetable_type', '=', $pdf_type)
                                        ->where('name', '=', 'pdf'.$ident)->first();
                break;
            case 'logo':
                $old_asset = SOE\DB\Asset::where('assetable_id', '=', $logo_id)
                                        ->where('assetable_type', '=', $logo_type)
                                        ->where('name', '=', 'logo1')->first();
                break;
        }
        
        if(!empty($old_asset))
        {
            /*$myPath = str_replace($s3path, '',$old_asset->path);
            $s3->deleteObject($bucketName, $myPath);*/
            $old_asset->delete();
            $asset = Asset::blank();
            $asset = $asset->createFromModel($old_asset);
            return $this->format($asset);
        }
    }

    public function getGetAsset($merchant_id)
    {
        $number = Input::get('number');
        $type = Input::get('type');
        $location_id = Input::get('location_id', 0);
        $location = $this->locationRepository->find($location_id);
        if($location && $location->is_about_specific)
        {
            $assetable_id = $location->id;
            $assetable_type = 'Location';
        }
        else
        {
            $assetable_id = $merchant_id;
            $assetable_type = 'Merchant';
        }
        switch ($type) {
            case 'image':
                $asset = SOE\DB\Asset::where('assetable_id', '=', $assetable_id)
                                    ->where('assetable_type', '=', $assetable_type)
                                    ->where('name', '=', 'smallImage'.$number)->first();
                break;
        }
        $a = Asset::blank();
        $a = $a->createFromModel($asset);
        return $this->format($a);
    }

    public function postEditAsset($merchant_id)
    {
        $number = Input::get('number');
        $type = Input::get('type');
        $location_id = Input::get('location_id');
        $location = $this->locationRepository->find($location_id);
        if($location && $location->is_about_specific)
        {
            $assetable_id = $location->id;
            $assetable_type = 'Location';
        }
        else
        {
            $assetable_id = $merchant_id;
            $assetable_type = 'Merchant';
        }
        switch ($type) {
            case 'image':
                $asset = SOE\DB\Asset::where('assetable_id', '=', $assetable_id)
                                    ->where('assetable_type', '=', $assetable_type)
                                    ->where('name', '=', 'smallImage'.$number)->first();
                if(empty($asset))
                {
                    return Response::json(0);
                }
                $asset->long_description = Input::get('long_description');
                $asset->short_description = Input::get('short_description');
                $asset->save();
                Event::fire('backoffice.updated', array('asset', $asset->id, Auth::user()->id, $asset, 'asset updated'));
                break;
        }

        $a = Asset::blank();
        $a = $a->createFromModel($asset);
        return $this->format($a);
    }

    public function getPreviewVideo($merchant_id)
    {
        $location_id = Input::get('location_id', 0);
        $location = $this->locationRepository->find($location_id);
        if($location && $location->is_video_specific)
        {
            $assetable_id = $location->id;
            $assetable_type = 'Location';
        }
        else
        {
            $assetable_id = $merchant_id;
            $assetable_type = 'Merchant';
        }
        $video = SOE\DB\Asset::where('assetable_id', '=', $assetable_id)
                            ->where('assetable_type', '=', $assetable_type)
                            ->where('name', '=', 'clientVideoLink')->first();
        if(empty($video))
            return;
        return $video->path;
    }

    public function postSaveVideo($merchant_id)
    {
        $location_id = Input::get('location_id', 0);
        $location = $this->locationRepository->find($location_id);
        if($location && $location->is_video_specific)
        {
            $assetable_id = $location->id;
            $assetable_type = 'Location';
        }
        else
        {
            $assetable_id = $merchant_id;
            $assetable_type = 'Merchant';
        }

        $video_code = Input::get('video');
        $video = SOE\DB\Asset::where('assetable_id', '=', $assetable_id)
                            ->where('assetable_type', '=', $assetable_type)
                            ->where('name', '=', 'clientVideoLink')->first();
        if(empty($video))
        {
            if($video_code != '')
            {
                $video = Asset::create(array(
                    'type' => 'video',
                    'path' => $video_code,
                    'name' => 'clientVideoLink',
                    'assetable_id' => $assetable_id,
                    'assetable_type' => $assetable_type,
                    'long_description' => Input::get('title')
                ));
                Event::fire('backoffice.updated', array('asset', $video->id, Auth::user()->id, $video, 'asset created'));
            }
        }
        else
        {
            if($video_code != '')
            {
                $video->long_description = Input::get('title', '');
                $video->path = $video_code;
                $video->save();
                Event::fire('backoffice.updated', array('asset', $video->id, Auth::user()->id, $video, 'asset updated'));
            }
            else
            {
                Event::fire('backoffice.updated', array('asset', $video->id, Auth::user()->id, $video, 'asset deleted'));
                $video->delete();
            }
        }
        return Response::json(1);
    }

    public function postPdfUpload()
    {
        $awsAccessKey = 'AKIAI6NWHQYFADWZVYLQ';
        $awsSecretKey = '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';
        $bucketName = 'saveoneverything_assets';
        $inputData = Input::all();
        $s3path = "http://s3.amazonaws.com/saveoneverything_assets/";

        if(!empty($inputData))
        {
            $count = 0;
            $merchant_id = $inputData['merchant_id'];
            $location_id = Input::get('location_id', 0);
            $location = $this->locationRepository->find($location_id);

            //Ajax Upload
            foreach ($inputData as $key => $data) 
            {  
                if($key != 'merchant_id')
                {
                    if (Input::hasFile($key)) 
                    {   
                        if($location && $location->is_pdf_specific)
                        {
                            $assetable_id = $location_id;
                            $assetable_type = 'Location';
                        }
                        else
                        {
                            $assetable_id = $merchant_id;
                            $assetable_type = 'Merchant';
                        }
                        $pieces = explode('_', $key);
                        $insert_num = 0;
                        for($i = 1; $i <= $pieces[2]; $i++)
                        {
                            $filled = SOE\DB\Asset::where('assetable_id', '=', $assetable_id)
                                                ->where('assetable_type', '=', $assetable_type)
                                                ->where('name', '=', 'pdf'.$i)->first();
                            if(empty($filled))
                            {
                                $insert_num = $i;
                            }
                        }
                        $insert_num = $insert_num == 0 ? $pieces[2] : $insert_num;
                        $assets[$count]['path'] = "pdfs/".time()."-".$key.'.'.$data->getClientOriginalExtension();
                        $assets[$count]['name'] = $key;
                        $assets[$count]['asset_name'] = 'pdf'.$insert_num;
                        $s3 = new S3($awsAccessKey, $awsSecretKey);

                        //Remove the old file if it exists
                        /*$old_asset = SOE\DB\Asset::where('assetable_id', '=', $merchant_id)
                                                ->where('assetable_type', '=', 'Merchant')
                                                ->where('name', '=', 'pdf'.$insert_num)->first();
                        if(!empty($old_asset))
                        {
                            $myPath = str_replace($s3path, '', $old_asset->path);
                            $s3->deleteObject($bucketName, $myPath);
                        }*/

                        // Put our file (also with public read access)
                        if ($s3->putObjectFile(Input::file($key)->getRealPath(), $bucketName, $assets[$count]['path'], S3::ACL_PUBLIC_READ)) 
                        {
                            $assets[$count]['status'] = 'success';
                            $merch_asset = SOE\DB\Asset::where('assetable_id', '=', $assetable_id)
                                                ->where('assetable_type', '=', $assetable_type)
                                                ->where('name', '=', 'pdf'.$insert_num)->first();
                            if(empty($merch_asset))
                            {
                                $merch_asset = Asset::create(array(
                                        'type' => 'pdf',
                                        'path' => $this->httpImage($s3path.$assets[$count]['path']),
                                        'name' => 'pdf'.$insert_num,
                                        'assetable_id' => $assetable_id,
                                        'assetable_type' => $assetable_type
                                    ));
                            }
                            else
                            {
                                $merch_asset->path = $this->httpImage($s3path.$assets[$count]['path']);
                                $merch_asset->save();
                            }
                            Event::fire('backoffice.updated', array('asset', $merch_asset->id, Auth::user()->id, $merch_asset, 'pdf asset updated'));
                        } 
                        else 
                        {
                            $assets[$count]['status'] = 'error';
                        }
                    }
                    $count++; 
                }
            }
            echo '<script> top.document.getElementById("ieDoneButton").click(); </script>';
        }
    }

    public function postPdfSave($merchant_id)
    {
        $pdf_id = Input::get('pdf_id');
        $title = Input::get('long_description', '');
        $pdf = Asset::find($pdf_id);
        if(!empty($pdf))
        {
            $pdf->long_description = $title;
            $pdf->save();
            Event::fire('backoffice.updated', array('asset', $pdf->id, Auth::user()->id, $pdf, 'pdf asset updated'));
            return $this->format($pdf);
        }
    }

    public function postGoLive($franchise_id)
    {
        $is_demo = Input::get('is_demo', '0');
        SOE\DB\Offer::where('franchise_id', '=', $franchise_id)->update(array('is_demo' => $is_demo));
        $offers = SOE\DB\Offer::where('franchise_id', '=', $franchise_id)->get(array('id'));
        $aOfferIds = array(0);
        foreach($offers as $offer)
        {
            $aOfferIds[] = $offer->id;
        }
        SOE\DB\Entity::whereIn('entitiable_id', $aOfferIds)->where('entitiable_type', '=', 'Offer')->update(array('is_demo' => $is_demo, 'franchise_demo' => $is_demo));
        SOE\DB\Location::where('franchise_id', '=', $franchise_id)->update(array('is_demo' =>  $is_demo));
        $franchise = SOE\DB\Franchise::find($franchise_id);
        Event::fire('backoffice.updated', array('franchise', $franchise->id, Auth::user()->id, $franchise, 'franchise live'));
        return $this->format(array('Live'));
    }

    public function postUpdateContest()
    {
        $input = Input::all();
        $franchise = Franchise::find(Input::get('franchise_id'));
        if(!empty($franchise))
            $input['merchant_id'] = $franchise->merchant_id;
        else
            $input['merchant_id'] = 0;
        if($contest_id = Input::get('contest_id'))
        {
            $contest = $this->contests->update($contest_id, $input);
            Event::fire('backoffice.updated', array('contest', $contest->id, Auth::user()->id, $contest, 'contest updated'));
        }
        else
        {
            $contest = $this->contests->create($input);
            Event::fire('backoffice.updated', array('contest', $contest->id, Auth::user()->id, $contest, 'contest created'));
        }
        if(method_exists($contest, 'toArray'))
            $return = $contest->toArray();
        else
            $return = $contest;
        return json_encode($return);
    }

    public function postUpdateWinner()
    {
        $applicationID = Input::get('application_id');
        $application = SOE\DB\ContestApplication::where('id','=',$applicationID)->first();
        $user = SOE\DB\User::where('id','=',$application->user_id)->first();
        $zip = $this->zipcodeRepository->findByZipcode($application->zip);
        $contestWinner = SOE\DB\ContestWinner::where('contest_id','=',$application->contest_id)
            ->where('user_id', $user->id)
            ->first();
        if (empty($contestWinner)) {
            $winner = $this->contestWinnerRepository->blank();
            $winner->contest_id = $application->contest_id;
        } 
        else {
            $winner = $contestWinner;
        }
        $winner->user_id = $user->id;
        $aNames = explode(' ', $user->name);
        $winner->first_name = isset($aNames[0]) ? $aNames[0] : '';
        $winner->last_name = isset($aNames[1]) ? $aNames[1] : '';
        $winner->city = $zip->city;
        $winner->state = $zip->state;
        $winner->selected_at = date('Y-m-d H:i:s');
        $winner->verified_at = date('Y-m-d H:i:s');
        $winner->save();

        $contest = $this->contests->find($application->contest_id);
        if($contest->current_inventory != 0)
        {
            $contest->current_inventory = $contest->current_inventory - 1;
            $contest->save();
        }
        if($contest->total_inventory != 0 && $contest->current_inventory == 0)
        {
            $contest->date_ended = date("Y-m-d H:i:s");
            $contest->save();
        }

        Event::fire('backoffice.updated', array('contest_winner', $winner->id, Auth::user()->id, $winner, 'contest winner updated'));
        return $this->format($winner);
    }

    public function postNewWinner()
    {
        $userID = Input::get('user_id');
        $contestID = Input::get('contest_id');
        $user = SOE\DB\User::where('id','=',$userID)->first();
        $zip = $this->zipcodeRepository->findByZipcode($user->zipcode);
        $application = SOE\DB\ContestApplication::where('user_id','=',$user->id)->where('contest_id','=',$contestID)->first();
        if (empty($application))
        {
            $applicant = ContestApplication::blank();
            $applicant->user_id = $user->id;
            $applicant->contest_id = $contestID;
        } else {
            $applicant = $application;
        }
        $applicant->zip = $user->zipcode;
        $applicant->email = $user->email;
        $applicant->save();
        $contestWinner = SOE\DB\ContestWinner::where('contest_id','=',$applicant->contest_id)->first();
        if (empty($contestWinner)) {
            $winner = $this->contestWinnerRepository->blank();
            $winner->contest_id = $applicant->contest_id;
        } else {
            $winner = $contestWinner;
        }
        $winner->user_id = $user->id;
        $aNames = explode(' ', $user->name);
        $winner->first_name = isset($aNames[0]) ? $aNames[0] : '';
        $winner->last_name = isset($aNames[1]) ? $aNames[1] : '';
        $winner->city = empty($zip) ? $user->city : $zip->city;
        $winner->state = empty($zip) ? $user->state : $zip->state;
        $winner->selected_at = date('Y-m-d H:i:s');
        $winner->verified_at = date('Y-m-d H:i:s');
        $winner->save();
        Event::fire('backoffice.updated', array('contest_winner', $winner->id, Auth::user()->id, $winner, 'contest winner updated'));
        
        return $this->format($winner);
    }

    public function postCustomWinner()
    {
        $userID = Input::get('user_id');
        $contestID = Input::get('contest_id');
        $first_name = Input::get('first_name');
        $last_name = Input::get('last_name');
        $city = Input::get('city');
        $state = Input::get('state');

        $contestWinner = SOE\DB\ContestWinner::where('contest_id','=',$contestID)->first();
        if (empty($contestWinner)) {
            $winner = $this->contestWinnerRepository->blank();
            $winner->contest_id = $contestID;
        } else {
            $winner = $contestWinner;
        }
        $winner->user_id = $userID;
        $winner->first_name = $first_name;
        $winner->last_name = $last_name;
        $winner->city = $city;
        $winner->state = $state;
        $winner->selected_at = date('Y-m-d H:i:s');
        $winner->verified_at = date('Y-m-d H:i:s');
        $winner->save();
        Event::fire('backoffice.updated', array('contest_winner', $winner->id, Auth::user()->id, $winner, 'contest winner updated'));
        return $this->format($winner);

    }

    public function getFindFranchise()
    {
        $franchise_id = Input::get('franchise_id');
        $franchise = Franchise::find($franchise_id);
        if(empty($franchise))
            return;
        $merchant = Merchant::find($franchise->merchant_id);
        $franchise->display = $merchant->display;
        return $this->format($franchise);
    }

    public function postContestImageUpload()
    {
        $awsAccessKey = 'AKIAI6NWHQYFADWZVYLQ';
        $awsSecretKey = '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';
        $bucketName = 'saveoneverything_assets';
        $inputData = Input::all();
        $s3path = "http://s3.amazonaws.com/saveoneverything_assets/";
        if(!empty($inputData))
        {
            $count = 0;
            $type = $inputData['type'];
            $object_id = $inputData['object_id'];
            /*switch ($type) {
                case 'banner':
                    $object = Banner::find($object_id);
                    break;
                
                default:
                    $object = Contest::find($object_id);
                    break;
            }
            if(empty($object))
            {
                return Response::json(array('path' => '', 'error' => '1'));
            }*/

            //Ajax Upload
            foreach ($inputData as $key => $data) 
            {  
                if($key != 'object_id' && $key != 'type')
                {
                    if(Input::hasFile('name')) 
                    {
                        $temp_file = Input::file('name')->getRealPath();
                        $extension = Input::file('name')->getClientOriginalExtension();
                        $path = "images/".time()."-Contest.".$extension;
                        //$file_name = $data['name'];
                        
                        $s3 = new S3($awsAccessKey, $awsSecretKey);

                        //Remove the old file if it exists
                        switch ($type) 
                        {
                            case 'banner':
                                if($s3->putObjectFile($temp_file, $bucketName, $path, S3::ACL_PUBLIC_READ)) 
                                {
                                    //$object->path = $s3path.$path;
                                    //$object->save();
                                }
                                else
                                {
                                    return Response::json(array('path' => '', 'error' => '0'));
                                }
                                break;
                            case 'contestBanner':
                                if($s3->putObjectFile($temp_file, $bucketName, $path, S3::ACL_PUBLIC_READ)) 
                                {
                                    //$object->banner = $s3path.$path;
                                    //$object->save();
                                }
                                else
                                {
                                    return Response::json(array('path' => '', 'error' => '0'));
                                }
                                break;
                            case 'logoButton':
                                if($s3->putObjectFile($temp_file, $bucketName, $path, S3::ACL_PUBLIC_READ)) 
                                {
                                    //$object->contest_logo = $s3path.$path;
                                    //$object->save();
                                }
                                else
                                {
                                    return Response::json(array('path' => '', 'error' => '0'));
                                }
                                break;
                            case 'companyButton':
                                if($s3->putObjectFile($temp_file, $bucketName, $path, S3::ACL_PUBLIC_READ)) 
                                {
                                    //$object->logo = $s3path.$path;
                                    //$object->save();
                                }
                                else
                                {
                                    return Response::json(array('path' => '', 'error' => '0'));
                                }
                                break;
                            case 'landing':
                                if($s3->putObjectFile($temp_file, $bucketName, $path, S3::ACL_PUBLIC_READ)) 
                                {
                                    //$object->landing = $s3path.$path;
                                    //$object->save();
                                }
                                else
                                {
                                    return Response::json(array('path' => '', 'error' => '0'));
                                }
                                break;
                            case 'listing':
                                if($s3->putObjectFile($temp_file, $bucketName, $path, S3::ACL_PUBLIC_READ)) 
                                {
                                    //$object->asset_path = $s3path.$path;
                                    //$object->save();
                                }
                                else
                                {
                                    return Response::json(array('path' => '', 'error' => '0'));
                                }
                                break;
                        }
                    }

                }
            }
            return Response::json(array('path' => $s3path.$path, 'error' => '0'));
        }
        else
        {
            return Response::json(array('path' => '', 'error' => '1'));
        }

    }

    public function getFranchiseBannerClear()
    {
        $franchise = $this->franchiseRepository->find(Input::get('franchise_id'));
        $franchise->{Input::get('banner')} = '';
        $franchise->save();
    }

    public function postSyndicationUpdate()
    {
        $franchise = $this->franchiseRepository->find(Input::get('franchise_id'));
        $franchise->banner_728x90 = Input::get('banner_728x90');
        $franchise->banner_300x600 = Input::get('banner_300x600');
        $franchise->banner_300x250 = Input::get('banner_300x250');
        $franchise->can_syndicate = Input::get('can_syndicate');
        $franchise->syndication_radius = Input::get('syndication_radius');
        $franchise->syndication_rating = Input::get('syndication_rating');
        $franchise->click_pay_rate = Input::get('click_pay_rate') * 100;
        $franchise->impression_pay_rate = Input::get('impression_pay_rate') * 100;
        $franchise->save();
        Event::fire('backoffice.updated', array('franchise', $franchise->id, Auth::user()->id, $franchise, 'franchise syndication updated'));
    }

    public function postWizardBannerUpload()
    {
        $awsAccessKey = 'AKIAI6NWHQYFADWZVYLQ';
        $awsSecretKey = '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';
        $bucketName = 'saveoneverything_assets';
        $inputData = Input::all();
        $s3path = "http://s3.amazonaws.com/saveoneverything_assets/";

        if(!empty($inputData))
        {
            $count = 0;
            $type = $inputData['type'];
            $location_id = Input::get('location_id', 0);
            $location = $this->locationRepository->find($location_id);

            if (Input::hasFile('upload_img')) 
            {
                $temp_file = Input::file('upload_img')->getRealPath();
                $extension = Input::file('upload_img')->getClientOriginalExtension();
                $assets_path = "banners/".time()."-".$type.".".$extension;
                $s3 = new S3($awsAccessKey, $awsSecretKey);

                // Put our file (also with public read access)
                if ($s3->putObjectFile($temp_file, $bucketName, $assets_path, S3::ACL_PUBLIC_READ)) 
                {
                    echo '<script>top.document.getElementById("'.$type.'DoneButton").innerHTML="'.$this->httpImage($s3path.$assets_path).'";top.document.getElementById("'.$type.'DoneButton").click(); </script>';
                }
            }
        }
    }

    /**
     * Resize and import images
     *
     * @return void
     */
    public function postGalleryUpload()
    {
        $awsAccessKey = 'AKIAI6NWHQYFADWZVYLQ';
        $awsSecretKey = '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';
        $bucketName = 'saveoneverything_gallery';
        $images = Input::file();
        $inputData = Input::all();
        $s3path = "http://s3.amazonaws.com/saveoneverything_gallery/";
        $s3 = new S3($awsAccessKey, $awsSecretKey);

        if(!empty($inputData))
        {
            $aSuccess = array();
            $category_id = Input::get('category_id');
            $subcategory_id = Input::get('subcategory_id');
            $subsubcategory_id = Input::get('subsubcategory_id');
            $category = $this->assetCategoryRepository->find($category_id);
            $subcategory = $this->assetCategoryRepository->find($subcategory_id);
            $subsubcategory = $this->assetCategoryRepository->find($subsubcategory_id);

            foreach($images as $image)
            {
                $temp_file = $image->getRealPath();
                $filename = $image->getClientOriginalName();
                $extension = $image->getClientOriginalExtension();

                $path = 'Watermarked/';
                $path .= $category ? $category->name.'/' : '';
                $path .= $subcategory ? $subcategory->name.'/' : '';
                $path .= $subsubcategory ? $subsubcategory->name.'/' : '';
                if(preg_match('/.+\.(jpg|jpeg|png|gif)/i', $filename))
                {
                    $size = getimagesize($temp_file, $info);
                    $tagsArr = array();

                    // Put our file (also with public read access)
                    if ($s3->putObjectFile($temp_file, $bucketName, $path.$filename.'.'.$extension, S3::ACL_PUBLIC_READ)) 
                    {
                        if(isset($info['APP13']))
                        {
                            $iptc = iptcparse($info['APP13']);

                            if(isset($iptc['2#025']))
                            {
                                $tagsArr = $iptc['2#025'];
                            }
                        }

                        if(!empty($tagsArr))
                        {
                            $tagList = array();
                            foreach($tagsArr as $tag)
                            {
                                if($this->check_utf8($tag))
                                {
                                    $tagRow = \DB::table('tags')->where('name', '=', $tag)->first();
                                    if($tagRow)
                                    {
                                        $tagList[] = $tagRow->id;
                                    }
                                    else
                                    {
                                        try
                                        {
                                            $tagRow = \DB::table('tags')->insert(array('name' => $tag));
                                            $tagList[] = $tagRow->id;
                                        }
                                        catch (Exception $e)
                                        {
                                            continue;
                                        }
                                    }
                                }
                            }
                        }

                        try
                        {
                            $asset = \SOE\DB\Asset::where('name', '=', $filename)
                                                    ->where('assetable_type', '=', 'sales_gallery_asset')
                                                    ->where('category_id', '=', empty($category) ? 0 : $category->id)
                                                    ->where('subcategory_id', '=', empty($subcategory) ? 0 : $subcategory->id)
                                                    ->where('sub_subcategory_id', '=', empty($subsubcategory) ? 0 : $subsubcategory->id)
                                                    ->first();
                            if(!$asset)
                            {
                                Eloquent::unguard();
                                $asset = \SOE\DB\Asset::create(array(
                                    'type' => 'image',
                                    'path' => $this->httpImage($s3path.$path.$filename),
                                    'name' => $filename,
                                    'assetable_id' => 0,
                                    'assetable_type' => 'sales_gallery_asset',
                                    'category_id' => empty($category) ? 0 : $category->id,
                                    'subcategory_id' => empty($subcategory) ? 0 : $subcategory->id,
                                    'sub_subcategory_id' => empty($subsubcategory) ? 0 : $subsubcategory->id
                                ));
                                Event::fire('backoffice.updated', array('asset', $asset->id, Auth::user()->id, $asset, 'sales gallery asset updated'));
                                foreach($tagList as $tag)
                                {
                                    DB::table('asset_tags')->insert(array(
                                        'asset_id' => $asset->id,
                                        'tag_id' => $tag,
                                    ));
                                }
                            }
                            $aSuccess[] = $this->httpImage($s3path.$path.$filename);
                        }
                        catch (Exception $e)
                        {
                            continue;
                        }
                    }
                }
            }
            return Response::json($aSuccess);
        }
    }

    private function check_utf8($str)
    {
        $len = strlen($str);
        for($i = 0; $i < $len; $i++)
        {
            $c = ord($str[$i]);
            if ($c > 128)
            {
                if (($c > 247)) return false;
                elseif ($c > 239) $bytes = 4;
                elseif ($c > 223) $bytes = 3;
                elseif ($c > 191) $bytes = 2;
                else return false;

                if (($i + $bytes) > $len) return false;

                while ($bytes > 1)
                {
                    $i++;
                    $b = ord($str[$i]);
                    if ($b < 128 || $b > 191) return false;
                    $bytes--;
                }
            }
        }
        return true;
    }

    private function FileExt($contentType)
    {
        $map = array(
            'application/pdf'   => '.pdf',
            'application/zip'   => '.zip',
            'image/gif'         => '.gif',
            'image/jpeg'        => '.jpg',
            'image/png'         => '.png',
            'text/css'          => '.css',
            'text/html'         => '.html',
            'text/javascript'   => '.js',
            'text/plain'        => '.txt',
            'text/xml'          => '.xml',
        );
        if (isset($map[$contentType]))
        {
            return $map[$contentType];
        }

        // HACKISH CATCH ALL (WHICH IN MY CASE IS
        // PREFERRED OVER THROWING AN EXCEPTION)
        $pieces = explode('/', $contentType);
        return '.' . array_pop($pieces);
    }

}
