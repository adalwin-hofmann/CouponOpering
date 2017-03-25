<?php namespace SOE\Services\AppEmail;

class InfusionSoftAppEmail implements AppEmailInterface
{
    protected $app;
    protected $featureRepository;
    const START_WELCOME_SEQUENCE = 107;

    public function __construct(
        \FeatureRepositoryInterface $featureRepository
    )
    {
        $this->featureRepository = $featureRepository;
        $this->app = $this->getConnection();
    }

    /**
     * Add a franchise to infusion soft's database.
     *
     * @param SOE\DB\Franchise   $franchise
     * @param boolean
     */
    public function addFranchise(\SOE\DB\Franchise $franchise)
    {
        if(!$this->checkFeature())
            return false;

        $data = array();
        $data['Email'] = $franchise->primary_contact;
        $merchant = \SOE\DB\Merchant::find($franchise->merchant_id);
        $data['Company'] = $merchant ? $merchant->name : '';

        $response = $this->app->addWithDupCheck($data, 'Email');
        return $this->checkValid($response);
    }

    /**
     * Add a user to infusion soft's database.
     *
     * @param SOE\DB\User   $user
     * @param boolean
     */
    public function addUser(\SOE\DB\User $user)
    {
        if(!$this->checkFeature())
            return false;

        $aName = explode(' ', $user->name);
        $data = array();
        $data['Email'] = $user->email;
        $data['FirstName'] = isset($aName[0]) ? $aName[0] : '';
        $data['LastName'] = isset($aName[1]) ? $aName[1] : '';
        $data['PostalCode'] = $user->zipcode;
        $data['State'] = $user->state;
        $data['City'] = $user->city;
        $data['Birthday'] = date('Y-m-d', strtotime($user->birthday));
        $data['_Gender'] = $user->sex;

        $response = $this->app->addWithDupCheck($data, 'Email');
        return $this->checkValid($response);
    }

    /**
     * Opt in an email address.
     *
     * @param string    $email
     * @return boolean
     */
    public function optIn($email)
    {
        $response = $this->app->optIn($email);
        return $this->checkValid($response);
    }

    /**
     * Update a users information in infusion soft's database.  This does not update email address.
     *
     * @param SOE\DB\User   $user
     * @return boolean
     */
    public function updateUser(\SOE\DB\User $user)
    {
        if(!$this->checkFeature())
            return false;

        $contact_id = $this->findByEmail($user->email);
        if(empty($contact_id))
            return $this->addUser($user);

        $aName = explode(' ', $user->name);
        $data = array();
        $data['FirstName'] = isset($aName[0]) ? $aName[0] : '';
        $data['LastName'] = isset($aName[1]) ? $aName[1] : '';
        $data['PostalCode'] = $user->zipcode;
        $data['State'] = $user->state;
        $data['City'] = $user->city;
        $data['Birthday'] = date('Y-m-d', strtotime($user->birthday));
        $data['_Gender'] = $user->sex;

        $response = $this->app->updateCon($contact_id, $data);
        return $this->checkValid($response);
    }

    /**
     * Change a user's email address.
     *
     * @param string    $original The original email address.
     * @param string    $new The new email address.
     * @return boolean
     */
    public function changeEmail($original, $new)
    {
        if(!$this->checkFeature())
            return false;

        $contact_id = $this->findByEmail($original);
        if(empty($contact_id))
            return false;

        $response = $this->app->updateCon($contact_id, array('Email' => $new));
        return $this->checkValid($response);
    }

    /**
     * Send an email with the given subject and html body to the given recipient.
     *
     * @param string    $subject
     * @param string    $html The HTML content of the email.
     * @param string    $recipient The email address to send to.
     * @param string    $from Defaults to webmaster@saveon.com.
     * @return boolean
     */
    public function sendEmail($subject, $html, $recipient, $from = 'webmaster@saveon.com')
    {
        if(!$this->checkFeature())
            return false;
        $contact_id = $this->findByEmail($recipient);
        if(empty($contact_id))
            return false;
        $response = $this->app->sendEmail(array($contact_id), $from, $recipient, '', '', 'HTML', $subject, $html, '');
        return $this->checkValid($response);
    }

    /**
     * Apply the given Infusion Soft tag to the given email.  If the tag does not exist, it will be created.
     *
     * @param string    $email
     * @param string    $tag
     * @return boolean
     */
    public function tagEmail($email, $tag)
    {
        if(!$this->checkFeature())
            return false;
        $contact_id = $this->findByEmail($email);
        if(empty($contact_id))
            return false;

        $tag_id = $this->getTag($tag);
        if(!$tag_id)
        {
            $tag_id = $this->createTag($tag);
        }
        if(!$tag_id)
            return false;
        $response = $this->app->grpAssign($contact_id, $tag_id);
        return $this->checkValid($response);
    }

    /**
     * Retrieve the given tag from the Infusion Soft database.
     *
     * @param string    $tag
     * @return int      Tag Id.
     */
    protected function getTag($tag)
    {
        $returnFields = array('Id', 'GroupName', 'GroupCategoryId', 'GroupDescription');
        $query = array('GroupName' => $tag);
        $response = $this->app->dsQuery("ContactGroup",1,0,$query,$returnFields);
        if(!$this->checkValid($response))
            return false;
        return isset($response[0]) ? $response[0]['Id'] : false;
    }

    /**
     * Create the given tag in the Infusion Soft database.
     *
     * @param string    $tag
     * @return int      The Id of the tag that was created.
     */
    protected function createTag($tag)
    {
        $existing = $this->getTag($tag);
        if(!$existing)
        {
            $tagData = array('GroupName' => $tag);
            $response = $this->app->dsAdd("ContactGroup", $tagData);
            return ($this->checkValid($response)) ? $response : 0;
        }
        return $existing;
    }

    /**
     * Create the iSDK instance.
     *
     * @return iSDK
     */
    protected function getConnection()
    {
        $application_name = \Config::get('integrations.infusion_soft.application_name');
        $api_key = \Config::get('integrations.infusion_soft.api_key');

        $app = new \iSDK();
        $app->cfgCon($application_name, $api_key);
        return $app;
    }

    /**
     * Find an infusion soft contact id by email address.
     *
     * @param string $email
     * @return int
     */
    public function findByEmail($email)
    {
        $response = $this->app->findByEmail($email, array('Id'));
        if($this->checkValid($response))
            return isset($response[0]) ? $response[0]['Id'] : 0;
        else
            return 0;
    }

    /**
     * Determine if the given response is valid.
     *
     * @param mixed     $response The response to the api call.
     * @return boolean
     */
    protected function checkValid($response)
    {
        if(is_array($response))
            return true;
        if(stristr($response, 'error'))
            return false;
        else
            return true;
    }

    /**
     * Determine if app email functions should be performed.
     *
     * @return boolean
     */
    protected function checkFeature()
    {
        $app_email = $this->featureRepository->findByName('app_email', false);
        $app_email = empty($app_email) ? 'none' : $app_email->value;
        if(\App::environment() == $app_email)
            return true;
        else
            return false;

    }
}