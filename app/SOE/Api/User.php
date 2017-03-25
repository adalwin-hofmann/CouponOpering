<?php
namespace SOE\Api;

class User extends Api implements ApiInterface, UserApi
{
    public function __construct(
        \UserRepositoryInterface $repository
    )
    {
        $this->repository = $repository;
        $this->viewableFactory = new \SOE\Viewables\ViewableFactory;
        $this->favoritableFactory = new \SOE\Favoritables\FavoritableFactory;
        $this->printableFactory = new \SOE\Printables\PrintableFactory;
        $this->shareableFactory = new \SOE\Shareables\ShareableFactory;
    }

    public function find()
    {
        return $this->format($this->repository->find(\Input::get('id')));
    }

    public function create()
    {
    }

    public function get()
    {
    }

    public function update()
    {
    }

    /**
     * Get an array of recommended Entities based on user_id.
     *
     * @api
     *
     * @return mixed
     */
    public function getRecommendations()
    {
        $lat = \Input::get('latitude', '');
        $lng = \Input::get('longitude', '');

        if ($lat && $lng)
        {
            $zipcodeRepository = \App::make('ZipcodeRepositoryInterface');
            $zip = $zipcodeRepository->getClosest($lat, $lng);
            $geoip = new \StdClass;
            $geoip->city_name = $zip->city;
            $geoip->region_name = $zip->state;
            $geoip->latitude = $lat;
            $geoip->longitude = $lng;
        }
        else
        {
            $geoip = array();
        }

        $user_id = \Input::get("user_id");
        $limit = \Input::get('limit', 0);
        $ordering = \Input::get('order', 'rand');
        $type = \Input::get('type', 'soe');
        $user = $this->repository->find($user_id);
        $recommendations = $this->repository->getRecommendations($user, $limit, $geoip, $ordering, $type);
        return $this->format($recommendations);
    }

    /**
     * Send an email verification email.
     *
     * @api
     * @return mixed
     */
    public function setVerificationRecovery()
    {
        $email = \Input::get('email');
        $recovery = $this->repository->setVerificationRecovery($email);
        if(empty($recovery))
            return;
        $data = array(
            'key' => $recovery['uniq'],
            'email' => $email,
            'timer' => $recovery['timer']
        );
        \Mail::queueOn('SOE_Tasks', 'emails.emailverify', $data, function($message) use ($email)
        {
            $message->to($email)->subject('Verify Your Email');
            $message->sender('webmaster@saveoneverything.com', 'Save On Everything');
        });
        return json_encode(1);
    }

    /**
     * Send an email verification email.
     *
     * @api
     * @return mixed
     */
    public function getSearchEmail()
    {
        $email = \Input::get('email');
        $users = $this->repository->searchByEmail($email);
        return $this->format($users);
    }

    public function getByFilter()
    {
        return $this->format($this->repository->getByFilter(\Input::get('filter'), \Input::get('page', 0), \Input::get('limit', 0), \Input::get('type', '')));
    }

    public function favorites()
    {
        $type = \Input::get('type', null);
        if($type)
        {
            $type = explode(',', $type);
            array_walk($type, function(&$string){$string = studly_case($string);});
        }
        return $this->format($this->repository->favorites(
            \Input::get('user_id'),
            $type,
            \Input::get('favoritable_id', null),
            \Input::get('page', 0),
            \Input::get('limit', 0)
        ));
    }

    public function favorite()
    {
        $user = new \SOE\Persons\UserPerson(\Input::get('user_id'));
        $favoritable = $this->favoritableFactory->make(\Input::get('type'), \Input::get('object_id'));
        if(!$favoritable)
            return false;
        return $this->format($favoritable->favorite($user));
    }

    public function deleteFavorite()
    {
        $user = new \SOE\Persons\UserPerson(\Input::get('user_id'));
        $favoritable = $this->favoritableFactory->make(\Input::get('type'), \Input::get('object_id'));
        if(!$favoritable)
            return false;
        return $this->format($favoritable->unfavorite($user));
    }

    public function share()
    {
        $user = new \SOE\Persons\UserPerson(\Input::get('user_id'));
        $shareable = $this->shareableFactory->make(\Input::get('shareable_type'), \Input::get('shareable_id'));
        $type = \Input::get('type');
        $emails = \Input::get('emails', '');
        $message = \Input::get('message', '');
        $from = \Input::get('from_email', '');
        $sharer = \Input::get('sharer_name', '');
        if(!$shareable)
            return false;
        return $this->format($shareable->share($user, $type, $emails, $message, $from, $sharer));
    }

    public function view()
    {
        $user = new \SOE\Persons\UserPerson(\Input::get('user_id'));
        $viewable = $this->viewableFactory->make(\Input::get('type'), \Input::get('object_id'));
        if(!$viewable)
            return false;
        return $this->format($viewable->view($user));
    }

    public function printItem()
    {
        $user = new \SOE\Persons\UserPerson(\Input::get('user_id'));
        $printable = $this->printableFactory->make(\Input::get('type'), \Input::get('object_id'), \Input::get('params'));
        if(!$printable)
            return false;
        return $this->format($printable->printItem($user));
    }

    public function updatePreferences()
    {
        return $this->format($this->repository->updatePreferences(\Input::get('user_id'), \Input::all()));
    }

    public function getFranchises()
    {
        return $this->format($this->repository->getFranchise(\Input::get('user_id')));
    }
}
