<?php

class MerchantController extends BaseController {

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
        OfferRepositoryInterface $offers,
        ShareRepositoryInterface $shares,
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
        $this->offers = $offers;
        $this->shares = $shares;
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
                $found = $this->userRepository->checkType($user, 'merchant');
                $franchise = $this->userRepository->getFranchise($user->id);
                if(!$found || !$franchise)
                {
                    return Redirect::to('/login');
                }
            }

        }, array('except' => array('getLogin', 'postLogin', 'getLogout', 'getPasswordresetemail', 'getResetPassword', 'postResetPassword', 'getSignup', 'postSignup')));
    }

    public function getIndex()
    {
        $code = array();
        $code[] = View::make('admin.merchant.jscode.dashboard');
        $code[] = View::make('admin.merchant.jscode.tour');
        $vw = View::make('admin.merchant.dashboard')->with('code', implode(' ', $code));
        $vw->primary_nav = "merchant";
        $vw->secondary_nav = "dashboard";
        $franchise = $this->userRepository->getFranchise(Auth::User()->id);
        $merchant = $this->merchants->find($franchise->merchant_id);
        $locations = $this->locations->getActiveByFranchise($franchise->id);
        $vw->locations = $locations;
        $location = Input::get('location_id', 0);
        $range = Input::get('date-range');
        switch ($range) {
            case 'last-30-days':
                $start = date('Y-m-d 00:00:00', strtotime('-30 days'));
                $end = date('Y-m-d 23:59:59');
                $dateText = 'Last 30 Days';
                break;
            case 'last-90-days':
                $start = date('Y-m-d 00:00:00', strtotime('-90 days'));
                $end = date('Y-m-d 23:59:59');
                $dateText = 'Last 90 Days';
                break;
            case 'this-year':
                $start = date('Y-01-01 00:00:00');
                $end = date('Y-m-d 23:59:59');
                $dateText = 'This Year';
                break;
            case 'last-year':
                $start = date('Y-01-01 00:00:00', strtotime('-1 year'));
                $end = date('Y-12-31 23:59:59', strtotime('-1 year'));
                $dateText = 'Last Year';
                break;
            default:
                $start = date('Y-m-d 00:00:00', strtotime('-30 days'));
                $end = date('Y-m-d 23:59:59');
                $dateText = 'Last 30 Days';
                break;
        }
        $report = $this->franchises->franchiseDashReport($franchise->id, $start, $end, $location);
        $vw->merchant = $merchant;
        $vw->report = $report;
        $vw->offers = $location ? $this->offers->getActiveByLocation($location)['objects'] : $this->offers->getActiveByFranchise($franchise);
        $vw->franchise_id = $franchise->id;
        $vw->location_id = $location;
        $vw->rep = $this->franchises->getSalesRep($franchise->id);
        //echo $this->userRepository->query()->join('franchise_assignments', 'franchise_assignments.user_id', '=', 'users.id')->where('franchise_id', '=', $franchise->id)->where('assignment_type_id', '=', 1)->first();
        //exit;
        $vw->firstTimeModal = Session::get('firstTimeModal');

        return $vw;
    }

    public function getOfferStats()
    {
        $range = Input::get('date-range');
        switch ($range) {
            case 'last-30-days':
                $start = date('Y-m-d 00:00:00', strtotime('-30 days'));
                $end = date('Y-m-d 23:59:59');
                $dateText = 'Last 30 Days';
                break;
            case 'last-90-days':
                $start = date('Y-m-d 00:00:00', strtotime('-90 days'));
                $end = date('Y-m-d 23:59:59');
                $dateText = 'Last 90 Days';
                break;
            case 'this-year':
                $start = date('Y-m-01 00:00:00');
                $end = date('Y-m-d 23:59:59');
                $dateText = 'This Year';
                break;
            case 'last-year':
                $start = date('Y-01-01 00:00:00', strtotime('-1 year'));
                $end = date('Y-12-31 23:59:59', strtotime('-1 year'));
                $dateText = 'Last Year';
                break;
            default:
                $start = date('Y-m-d 00:00:00', strtotime('-30 days'));
                $end = date('Y-m-d 23:59:59');
                $dateText = 'Last 30 Days';
                break;
        }
        $franchise_id = Input::get('franchise_id');
        $location_id = Input::get('location_id', 0);
        $offers = $this->offers->statReport($franchise_id, $location_id, $start, $end);
        return $offers;
    }

    public function getFileupload()
    {
        $code = array();
        $vw = View::make('admin.merchant.fileupload')->with('code', implode(' ', $code));
        $vw->title = "FTP Upload | SaveOn";
        $vw->primary_nav = "merchant";
        $vw->secondary_nav = "upload";

        return $vw;

    }

    public function getContact()
    {
        $code = array();
        $code[] = View::make('home.jscode.contact');
        $vw = View::make('admin.merchant.contact')->with('code', implode(' ', $code));
        $vw->title = "Contact Us | SaveOn";
        $vw->primary_nav = "merchant";
        $vw->secondary_nav = "contact";

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
            if($this->userRepository->checkType($user, 'merchant'))
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

        $vw = View::make('home.fileupload');
        $vw->uploaded = $uploaded;
        $vw->error_msgs = $errors;
        return $vw;
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

    public function getSignup()
    {
        $code = array();
        $vw = View::make('admin.merchant.signup')->with('code', implode(' ', $code));
        $email = Input::old('email') ? Input::old('email') : Input::get('email', '');
        $uniq = Input::old('uniq') ? Input::old('uniq') : Input::get('uniq', '');
        $vw->uniq = $uniq;
        $vw->email = $email;
        $vw->user = $this->userRepository->findByEmail($email);
        $vw->title = "Merchant Portal Signup";
        $vw->description = "Sign up for the merchant portal.";
        $recovery = $this->userRepository->getRecovery($email, $uniq, 'signup');
        $vw->invalid = !$recovery;

        $vw->franchise = $this->franchises->find($recovery? $recovery->association_id : 0);

        return $vw;
    }

    public function postSignup()
    {
        $user = $this->userRepository->findByEmail(Input::get('email'));
        $input = Input::all();
        if($user)
            $input['password_match'] = Hash::check(Input::get('existing_password'), $user->password) ? 1 : 0;
        $validator = Validator::make(
            $input,
            array(
                'firstName' => 'sometimes|required',
                'lastName' => 'sometimes|required',
                'email' => 'required',
                'password' => 'sometimes|required|confirmed',
                'password_confirmation' => 'sometimes|required',
                'existing_password' => 'sometimes|required',
                'password_match' => 'sometimes|in:1'
            )
        );
        if ($validator->fails())
        {
            return Redirect::to('/signup')->withErrors($validator)->withInput();
        }

        $uniq = Input::get('uniq');
        $email = Input::get('email');
        $recovery = $this->userRepository->getRecovery($email, $uniq, 'signup');
        $valid = $this->userRepository->validateRecovery($email, $uniq, 'signup');
        if(!$valid)
        {
            return Redirect::to('/signup')->with('invalid', true)->withInput();
        }

        $user = $this->userRepository->findByEmail($email);
        if(!$user)
        {
            $user = $this->userRepository->blank();
            $user->name = Input::get('firstName').' '.Input::get('lastName');
            $user->email = Input::get('email');
            $user->password = Input::get('password');
            $user->is_email_valid = 0;
            $user->signup_reference = 'Merchant Portal';
            $user->save();
        }
        
        Auth::login($user);
        $this->userRepository->setFranchise($user->id, $recovery->association_id);
        $this->userRepository->addType($user, 'Merchant');
        return Redirect::to('/')->with(array('firstTimeModal' => '1'));;
    }
}