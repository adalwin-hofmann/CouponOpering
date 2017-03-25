<?php 


class EloquentUsedVehicleRepository extends BaseEloquentRepository implements UsedVehicleRepository, FavoritableInterface, ViewableInterface, ShareableInterface, RepositoryInterface
{
    protected $columns = array(
        'tnl_id',
        'dealer_name',
        'address',
        'city',
        'state',
        'zip',
        'phone',
        'latitude',
        'longitude',
        'stock_number',
        'vin',
        'year',
        'model_year_id',
        'make',
        'make_id',
        'model',
        'model_id',
        'mileage',
        'is_certified',
        'class',
        'body_style',
        'fuel',
        'engine',
        'cylinders',
        'transmission',
        'drive_type',
        'trim_level',
        'exterior_color',
        'interior_color',
        'dealer_specified_features',
        'standard_interior_features',
        'standard_exterior_features',
        'standard_safety_features',
        'dealer_comments',
        'msrp',
        'internet_price',
        'image_count',
        'image_urls',
        'merchant_id',
        'location_id',
        'latm',
        'lngm',
        'old_dealer_id',
        'vendor',
        'name',
        'style_id',
        'standard_mechanical_features',
    );

    protected $model = 'UsedVehicle';

    /**
     * Favorite this location for the given Person
     *
     * @param PersonInterface   $person
     * @return UserFavorite
     */
    public function favorite(PersonInterface $person)
    {
        $old = SOE\DB\UserFavorite::where('favoritable_type', '=', 'SOE\\DB\\UsedVehicle')
                                ->where('favoritable_id', '=', $this->primary_key)
                                ->where('user_id', '=', $person->id)
                                ->where('is_deleted', '=', '0')
                                ->first();
        if(!empty($old))
        {
            $fav = UserFavorite::blank();
            $fav = $fav->createFromModel($old);
            return $fav;
        }
        $fav = UserFavorite::blank();
        $fav->user_id = $person->id;
        $fav->favoritable_type = 'SOE\\DB\\UsedVehicle';
        $fav->favoritable_id = $this->primary_key;
        $fav->save();
        return $fav;
    }

    public function view(PersonInterface $viewer)
    {
        
    }

    /**
     * Share this entity.
     *
     * @param UserRepository    $sharer
     * @param string            $type The type of share to be done, email or facebook
     * @param array             $params An array of parameters
     * @return mixed
     */
    public function share(UserRepository $sharer, $type, $params = array())
    {
        if($this->primary_key)
        {
            switch ($type)
            {
                case 'email':
                    return $this->emailShare($sharer, $params);
                    break;

                case 'facebook':
                    return $this->facebookShare($sharer, $params);
                    break;
            }
        }
    }

    /**
     * Send and record a email share.
     *
     * @param UserRepository    $sharer
     * @param array             $params An array of parameters
     * @return mixed
     */
    public function emailShare(UserRepository $sharer, $params = array())
    {
        $share_object = Share::create(array(
            'user_id' => $sharer->id,
            'shareable_id' => $this->primary_key,
            'shareable_type' => 'SOE\\DB\\UsedVehicle',
            'type' => 'email'
        ));
        $geoip = json_decode(GeoIp::getGeoIp('json'));
        if(!isset($params['emails']))
            return 'no emails';
        $emails = $params['emails'];
        $message_text = isset($params['message']) ?  $params['message'] : 'Save money with coupons and deals from local and national merchants. Restaurants, Automotive and Repairs, Home and Travel.';
        $from_email = isset($params['from_email']) ? $params['from_email'] : $sharer->email;
        $sharer_name = isset($params['sharer_name']) ? $params['sharer_name'] : $sharer->name;
        $merchant = Merchant::find($this->merchant_id);
        $data = array(
            'sharer_name' => $sharer_name,
            'message_text' => $message_text,
            'vehicle' => $this,
            'geoip' => $geoip,
            'images' => explode('|',$this->image_urls),
            'merchant' => $merchant
        );
        $emails_to_share_array = explode(",",$emails);
        try
        {
            Mail::send('emails.soct.sharing-used', $data, function($message) use($emails_to_share_array, $sharer_name)
            {
                $message->to($emails_to_share_array[0]);
                $message->subject($sharer_name.' Shared a Vehicle With You!');
                $message->sender('webmaster@saveoneverything.com', 'Save On Everything');
                for($i=1; $i < count($emails_to_share_array); $i++)
                {
                    $message->bcc($emails_to_share_array[$i]);
                }
            });

            DB::table('share_emails')->insert(array(
                'share_id' => $share_object->id,
                'share_email' => $emails_to_share_array[0],
                'shareable_id' => $this->primary_key,
                'shareable_type' => 'SOE\\DB\\UsedVehicle',
                'created_at' => DB::raw('NOW()'),
                'updated_at' => DB::raw('NOW()')
            ));
            for($i = 1; $i < count($emails_to_share_array); $i++)
            {
                DB::table('share_emails')->insert(array(
                    'share_id' => $share_object->id,
                    'share_email' => trim($emails_to_share_array[$i]),
                    'shareable_id' => $this->primary_key,
                    'shareable_type' => 'SOE\\DB\\UsedVehicle',
                    'created_at' => DB::raw('NOW()'),
                    'updated_at' => DB::raw('NOW()')
                ));
            }
        }
        catch(Exception $e)
        {
            return 'sending error';
        }
        return $share_object;
    }

    /**
     * Record a facebook share.
     *
     * @param UserRepository    $sharer
     * @param array             $params An array of parameters
     * @return mixed
     */
    public function facebookShare(UserRepository $sharer, $params = array())
    {
        $share_object = Share::create(array(
            'user_id' => $sharer->id,
            'shareable_id' => $this->primary_key,
            'shareable_type' => 'SOE\\DB\\UsedVehicle',
            'type' => 'facebook'
        ));

        return $share_object;
        /*$facebook = new Facebook(array(
            'appId' => Config::get('integrations.facebook.app_id'),
            'secret' => Config::get('integrations.facebook.secret')
        ));
        $user = $facebook->getUser();

        if ($user) {
            try {
                $geoip = json_decode(GeoIp::getGeoIp('json'));
                // Proceed knowing you have a logged in user who's authenticated.
                $message = $message == '' ? 'Save money with coupons and deals from local and national merchants. Restaurants, Automotive and Repairs, Home and Travel.' : $message;
                $ret_obj = $facebook->api('/me/feed', 'POST',
                                array(
                                  'picture' => $this->path,
                                  'link' => URL::to('/coupons/'.$this->merchant_slug.'/'.$this->location_id.'/'.$geoip->city_name.'/printable/'.$this->primary_key),
                                  'message' => $message,
                                  'name' => $this->name,
                                  'caption' => strip_tags($this->entitiable()->description)
                            ));
            } catch (FacebookApiException $e) {
                error_log($e);
                $user = null;
            }
        }

        if ($user) {
            $url = $facebook->getLogoutUrl();
            $url = '/';
        } else {
            $statusUrl = $facebook->getLoginStatusUrl();
            $url = $facebook->getLoginUrl(array('scope' => 'publish_actions', 'redirect_uri' => URL::back().'?modal='));
        }

        return $url;*/
    }
}