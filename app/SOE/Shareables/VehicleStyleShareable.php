<?php namespace SOE\Shareables;

class VehicleStyleShareable extends Shareable implements ShareableInterface
{
    public function __construct($object_id)
    {
        parent::__construct();
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
            'shareable_id' => $this->model->id,
            'shareable_type' => 'VehicleStyle',
            'type' => 'facebook'
        ));

        return $share_object;
    }

    /**
     * Send and record a email share.
     *
     * @param \SOE\Persons\PersonInterface    $sharer
     * @param array             $params An array of parameters
     * @return mixed
     */
    public function emailShare(\SOE\Persons\PersonInterface $person, $params = array())
    {
        $sharer = $this->users->find($person->getId());
        $share_object = $this->shares->create(array(
            'user_id' => $sharer->id,
            'shareable_id' => $this->model->id,
            'shareable_type' => 'VehicleStyle',
            'type' => 'email'
        ));
        $geoip = json_decode(\GeoIp::getGeoIp('json'));
        $emails = $params['emails'];
        $message_text = $params['message'] != '' ?  $params['message'] : 'Save money with coupons and deals from local and national merchants. Restaurants, Automotive and Repairs, Home and Travel.';
        $from_email = $params['from_email'] != '' ? $params['from_email'] : $sharer->email;
        $sharer_name = $params['sharer_name'] != '' ? $params['sharer_name'] : $sharer->name;
        $assets = $this->model->assets;
        $display = $this->model->displayImage;
        $incentives = $this->model->incentives()->orderBy('rebate_amount', 'desc')->first();
        $data = array(
            'sharer_name' => $sharer_name,
            'message_text' => $message_text,
            'vehicle' => $this->model,
            'geoip' => $geoip,
            'image' => count($display) ? $display[0] : (count($assets) ? $assets[0] : null),
            'incentives' => $incentives
        );
        $emails_to_share_array = explode(",",$emails);
        try
        {
            \Mail::send('emails.soct.sharing-new', $data, function($message) use($emails_to_share_array, $sharer_name)
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
                'shareable_id' => $this->model->id,
                'shareable_type' => 'VehicleStyle'
            ));
            for($i = 1; $i < count($emails_to_share_array); $i++)
            {
                $this->shareEmails->create(array(
                    'share_id' => $share_object->id,
                    'share_email' => trim($emails_to_share_array[$i]),
                    'shareable_id' => $this->model->id,
                    'shareable_type' => 'VehicleStyle'
                ));
            }
        }
        catch(Exception $e)
        {
            return 'sending error';
        }
        return $share_object;
    }
}