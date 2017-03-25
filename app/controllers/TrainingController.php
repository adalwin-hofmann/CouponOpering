<?php

class TrainingController extends BaseController {

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
                if($_ENV['APP_MODE'])
                {
                    $user_mode = strtolower($_ENV['APP_MODE']);
                }
                elseif (isset($_SERVER['PARAM2']))
                {
                    $user_mode = strtolower($_SERVER['PARAM2']);
                }

                $found = $this->userRepository->checkType($user, $user_mode);
                if(!$found)
                {
                    return Redirect::to('/login');
                }
            }

        }, array('except' => array('getLogin', 'postLogin', 'getLogout', 'getPasswordresetemail', 'getResetPassword', 'postResetPassword')));
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
        return Redirect::to('/onlinetraining/content-page/Home/home');
        $code = array();
        $code[] = View::make('admin.training.jscode.index');
        $vw = View::make('admin.training.index')->with('code', implode(' ', $code));
        $vw->primary_nav = "sales";
        $vw->secondary_nav = "training";
        $vw->active = true;

        $user = Auth::User();
        $vw->user_type = $user->type;
        $vw->availableSections = $this->trainingSections->listSections()['objects'];
        $vw->sections = $this->trainingSections->getUserSections($user);
        $vw->isAdmin = $this->userRepository->checkType($user, 'admin');

        if($_ENV['APP_MODE'])
        {
            $vw->user_mode = strtolower($_ENV['APP_MODE']);
        }
        elseif (isset($_SERVER['PARAM2']))
        {
            $vw->user_mode = strtolower($_SERVER['PARAM2']);
        }

        return $vw;
    }

    public function postCreatePage()
    {
        $api = App::make('TrainingPageApi');
        return $api->create();
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
                return Redirect::to('/onlinetraining/content-page/home');
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

    public function contentPage($one, $two, $three = null)
    {
        $user = Auth::User();
        $trainingPage = $this->trainingPages->findBySlug($user, $one, $two, $three);
        $code = array();
        $code[] = View::make('admin.training.jscode.index');
        $vw = View::make('admin.training.index')->with('code', implode(' ', $code));

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
        $vw->active = $two == 'home' ? true : false;
        $vw->user_type = $user->type;
        $vw->availableSections = $this->trainingSections->listSections()['objects'];
        $vw->sections = $this->trainingSections->getUserSections($user);
        $vw->activePage = $trainingPage;
        $vw->isAdmin = $this->userRepository->checkType($user, 'admin');

        return $vw;
    }

}