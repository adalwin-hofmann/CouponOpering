<?php namespace SOE\Shareables;

class VehicleEntityShareable extends Shareable implements ShareableInterface
{
    public function __construct($object_id)
    {
        parent::__construct();
        $this->categories = \App::make('CategoryRepositoryInterface');
        $this->locations = \App::make('LocationRepositoryInterface');
        $this->merchants = \App::make('MerchantRepositoryInterface');
        $this->model = $this->repository->find($object_id);
        $this->shareEmails = \App::make('ShareEmailRepositoryInterface');
        $this->shares = \App::make('ShareRepositoryInterface');
        $this->users = \App::make('UserRepositoryInterface');
    }

    public function share(\SOE\Persons\PersonInterface $person, $type, $emails = '', $message = '', $from = '', $sharer = '')
    {
        if($person->getForeignKey() != 'user_id')
            return false;

        switch($type)
        {
            case 'email':
                $share = $this->emailShare($person, array(
                    'emails' => $emails, 
                    'message' => $message, 
                    'from_email' => $from, 
                    'sharer_name' => $sharer)
                );
                break;
            
            case 'facebook':
                $share = $this->facebookShare($person);
                break;

            case 'twitter':
                # code...
                break;
        }

        return $share;
    }

    protected function facebookShare(\SOE\Persons\PersonInterface $person)
    {
        $share_object = $this->shares->create(array(
            'user_id' => $person->getId(),
            'shareable_id' => $this->model->vendor_inventory_id,
            'shareable_type' => 'VehicleEntity-'.$this->model->vendor,
            'type' => 'facebook'
        ));
        $sharer = $this->users->find($person->getId());
        $this->trackShare($sharer);
        return $share_object;
    }

    /**
     * Send and record an email share.
     *
     * @param \SOE\Persons\PersonInterface    $person
     * @param array             $params An array of parameters
     * @return mixed
     */
    protected function emailShare(\SOE\Persons\PersonInterface $person, $params = array())
    {
        $share_object = $this->shares->create(array(
            'user_id' => $person->getId(),
            'shareable_id' => $this->model->vendor_inventory_id,
            'shareable_type' => 'VehicleEntity-'.$this->model->vendor,
            'type' => 'email'
        ));
        $sharer = $this->users->find($person->getId());
        $geoip = json_decode(\GeoIp::getGeoIp('json'));
        $emails = $params['emails'];
        $message_text = $params['message'] != '' ?  $params['message'] : 'Save money with coupons and deals from local and national merchants. Restaurants, Automotive and Repairs, Home and Travel.';
        $from_email = $params['from_email'] != '' ? $params['from_email'] : $sharer->email;
        $sharer_name = $params['sharer_name'] != '' ? $params['sharer_name'] : $sharer->name;
        $merchant = $this->merchants->find($this->model->merchant_id);
        $data = array(
            'sharer_name' => $sharer_name,
            'message_text' => $message_text,
            'vehicle' => $this->model,
            'geoip' => $geoip,
            'images' => explode('|',$this->model->image_urls),
            'merchant' => $merchant
        );
        $emails_to_share_array = explode(",",$emails);
        try
        {
            \Mail::send('emails.soct.sharing-used', $data, function($message) use($emails_to_share_array, $sharer_name)
            {
                $message->to($emails_to_share_array[0]);
                $message->subject($sharer_name.' Shared a Vehicle With You!');
                $message->sender('webmaster@saveoneverything.com', 'Save On Everything');
                for($i=1; $i < count($emails_to_share_array); $i++)
                {
                    $message->bcc($emails_to_share_array[$i]);
                }
            });

            $this->shareEmails->create(array(
                'share_id' => $share_object->id,
                'share_email' => $emails_to_share_array[0],
                'shareable_id' => $this->model->vendor_inventory_id,
                'shareable_type' => 'VehicleEntity-'.$this->model->vendor
            ));
            for($i = 1; $i < count($emails_to_share_array); $i++)
            {
                $this->shareEmails->create(array(
                    'share_id' => $share_object->id,
                    'share_email' => trim($emails_to_share_array[$i]),
                    'shareable_id' => $this->model->vendor_inventory_id,
                    'shareable_type' => 'VehicleEntity-'.$this->model->vendor
                ));
            }
            $this->trackShare($sharer);
        }
        catch(\Exception $e)
        {
            return false;
        }
        return $share_object;
    }

    private function trackShare($sharer)
    {
        $identity = $sharer->email;
        $geoip = json_decode(\GeoIp::getGeoIp('json'));
        $category = $this->categories->findBySlug('auto-transportation');
        $subcategory = $this->categories->findBySlug('auto-dealers');
        $location = $this->locations->find($this->model->location_id);
        $mp = \Mixpanel::getInstance(\Config::get('integrations.mixpanel.token'));
        $mp->identify($identity);
        $mp->track('User Share', array(
            '$city' => $geoip->city_name,
            'Identifier' => $this->model->vendor.'-'.$this->model->vendor_inventory_id,
            'Environment' => \App::environment(),
            'MerchantId' => $this->model->merchant_id,
            'MerchantName' => $this->model->dealer_name,
            'MerchantNameAddress' => $this->model->dealer_name.' - '.$this->model->address,
            'LocationId' => $this->model->location_id,
            'FranchiseId' => $location->franchise_id,
            '$region' => $geoip->region_name,
            'Category' => !empty($category) ? $category->name : '',
            'Subcategory' => !empty($subcategory) ? $subcategory->name : ''
        ));
    }
}