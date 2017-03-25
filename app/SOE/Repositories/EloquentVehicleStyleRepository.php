<?php 


class EloquentVehicleStyleRepository extends BaseEloquentRepository implements VehicleStyleRepository, FavoritableInterface, ReviewableInterface, ShareableInterface, RepositoryInterface
{
    protected $columns = array(
        'id',
        'name',
        'year',
        'model_name',
        'model_id',
        'make_name',
        'make_id',
        'model_year_id',
        'price',
        'primary_body_type',
        'edmunds_link',
        'edmunds_id',
        'body_type_id',
        'style_type_id',
        'city_epa',
        'highway_epa',
        'combined_epa',
        'engine_name',
        'transmission',
    );

    protected $model = 'VehicleStyle';

    /**
     * Favorite this location for the given Person
     *
     * @param PersonInterface   $person
     * @return UserFavorite
     */
    public function favorite(PersonInterface $person)
    {
        $old = SOE\DB\UserFavorite::where('favoritable_type', '=', 'SOE\\DB\\VehicleStyle')
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
        $fav->favoritable_type = 'SOE\\DB\\VehicleStyle';
        $fav->favoritable_id = $this->primary_key;
        $fav->save();
        return $fav;
    }

    /**
     * Review this location for a given user.
     *
     * @param PersonInterface $reviewer
     * 
     * @return Review
     */
    public function writeReview(PersonInterface $reviewer)
    {
        if($this->primary_key)
        {
            $review = Review::blank();
            $review->reviewable_id = $this->primary_key;
            $review->reviewable_type = 'SOE\DB\VehicleStyle';
            if($reviewer->getType() == 'User')
                $review->user_id = $reviewer->id;
            else
                $review->nonmember_id = $reviewer->id;
            $review->content = Input::get('content');
            $review->rating = Input::get('rating');
            $review->save();
            $reviewer_id = $reviewer->id;
            $reviewer_type = $reviewer->getType();
            $review_id = $review->id;
            Queue::push(function($job) use ($reviewer_id, $reviewer_type, $review_id)
            {
                $reviewer = $reviewer_type == 'User' ? User::find($reviewer_id) : Nonmember::find($reviewer_id);
                $review = Review::find($review_id);
                $rating = SOE\DB\Review::where('reviewable_type', '=', 'SOE\DB\VehicleStyle')
                                        ->where('reviewable_id', '=', $review->reviewable_id)
                                        ->where('is_deleted', '=', 0)
                                        ->avg('rating');
                $location = SOE\DB\VehicleStyle::find($review->reviewable_id);
                if(!empty($location) && !empty($rating))
                {
                    $location->rating = $rating;
                    $location->rating_count = $location->rating_count + 1;
                    $location->save();
                }
                $job->delete();
            });

            $review->upvotes = 0;
            $review->votes = 0;
            $review->user = User::find($review->user_id);
            return $review;
        }
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
        $vehicle = \SOE\DB\VehicleStyle::find($this->primary_key);
        $assets = $vehicle->assets;
        $display = $vehicle->displayImage;
        $incentives = $vehicle->incentives()->orderBy('rebate_amount', 'desc')->first();
        $data = array(
            'sharer_name' => $sharer_name,
            'message_text' => $message_text,
            'vehicle' => $vehicle,
            'geoip' => $geoip,
            'image' => count($display) ? $display[0] : (count($assets) ? $assets[0] : null),
            'incentives' => $incentives
        );
        $emails_to_share_array = explode(",",$emails);
        /*try
        {*/
            Mail::send('emails.soct.sharing-new', $data, function($message) use($emails_to_share_array, $sharer_name)
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
                'shareable_type' => 'SOE\\DB\\VehicleStyle',
                'created_at' => DB::raw('NOW()'),
                'updated_at' => DB::raw('NOW()')
            ));
            for($i = 1; $i < count($emails_to_share_array); $i++)
            {
                DB::table('share_emails')->insert(array(
                    'share_id' => $share_object->id,
                    'share_email' => trim($emails_to_share_array[$i]),
                    'shareable_id' => $this->primary_key,
                    'shareable_type' => 'SOE\\DB\\VehicleStyle',
                    'created_at' => DB::raw('NOW()'),
                    'updated_at' => DB::raw('NOW()')
                ));
            }
        /*}
        catch(Exception $e)
        {
            return 'sending error';
        }*/
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
            'shareable_type' => 'SOE\\DB\\VehicleStyle',
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