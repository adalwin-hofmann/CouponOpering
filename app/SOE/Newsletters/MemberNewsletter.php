<?php namespace SOE\Newsletters;

class MemberNewsletter implements NewsletterInterface
{
    public function  __construct(
        \ContestRepositoryInterface $contests,
        \FeatureRepositoryInterface $features,
        \LocationRepositoryInterface $locations,
        \MerchantRepositoryInterface $merchants,
        \NewsletterRepositoryInterface $newsletters,
        \NewsletterScheduleRepositoryInterface $schedules,
        \UserRepositoryInterface $users,
        \UserLocationRepositoryInterface $userLocations,
        \ZipcodeRepositoryInterface $zipcodes
    )
    {
        $this->contests = $contests;
        $this->features = $features;
        $this->geoip = json_decode(\GeoIp::getGeoIp('json'));
        $this->locations = $locations;
        $this->merchants = $merchants;
        $this->newsletters = $newsletters;
        $this->schedules = $schedules;
        $this->users = $users;
        $this->userLocations = $userLocations;
        $this->zipcodes = $zipcodes;
    }

    /**
     * Prepare the newsletter content for the given batch.
     * @param integer $schedule_id
     * @return void
     */
    public function prep($schedule_id)
    {
        set_time_limit(60*60); // 60 Mins
        ini_set('memory_limit', '2048M');

        $schedule = $this->schedules->find($schedule_id);
        $members = $this->newsletters->getMemberNewsletterUsers($schedule_id);
        foreach($members as $member)
        {
            $geoip = new \StdClass;
            $fav = $this->userLocations->getMostRecent($member);
            if($schedule->zipcode)
            {
                $zipcode = $this->zipcodes->findByZipcode($schedule->zipcode);
                $geoip->region_name = $zipcode->state;
                $geoip->city_name = $zipcode->city;
                $geoip->latitude = $zipcode->latitude;
                $geoip->longitude = $zipcode->longitude;
            }
            else if($fav)
            {
                $geoip->region_name = $fav->state;
                $geoip->city_name = $fav->city;
                $geoip->latitude = $fav->latitude;
                $geoip->longitude = $fav->longitude;
            }
            else
            {
                $geoip->region_name = $member->state;
                $geoip->city_name = $member->city;
                $geoip->latitude = $member->latitude;
                $geoip->longitude = $member->longitude;
            }

            $history = $this->newsletters->getMemberHistory($schedule->batch_id, $member->email, 2);

            $coupons = $this->users->getRecommendations($member, 15, $geoip, 'score', 'soe', 0, 'coupon', true, $history['coupons']);
            $deals = $this->users->getRecommendations($member, 2, $geoip, 'score', 'soe', 0, 'dailydeal', true, $history['deals']);
            $contests = $this->users->getRecommendations($member, 2, $geoip, 'score', 'soe', 0, 'contest', true, $history['contests']);

            /* Custom Featured Queries */
            $featuredLocation = $this->merchants->findNearestLocationByMerchantIdWithin50($schedule->featured_merchant_id, $geoip->latitude, $geoip->longitude);
            if($featuredLocation)
            {
                $featured = $this->locations->getFeaturedByLocation($featuredLocation, true);
            } else {
                $featured = 0;
            }

            $data = array('coupons' => array(), 'deals' => array(), 'contests' => array(), 'featured' => array());
            if(count($coupons['objects']) % 3 != 0 && count($coupons['objects'] > 0))
                $coupons['objects'] = array_slice($coupons['objects'], 0, count($coupons['objects']) - (count($coupons['objects']) % 3));
            foreach($coupons['objects'] as $coupon)
            {
                $data['coupons'][] = $coupon->toArray();
            }
            if(count($deals['objects']) < 2)
            {
                foreach($deals['objects'] as $deal)
                {
                    $data['deals'][] = $deal->toArray();
                }
            }
            foreach($contests['objects'] as $contest)
            {
                $c = $this->contests->find($contest->entitiable_id);
                $aContest = $contest->toArray();
                $aContest['display_name'] = $c->display_name;
                $data['contests'][] = $aContest;
            }
            if(is_object($featured))
            {
                $data['featured'] = $featured->toArray();
            }

            $this->newsletters->create(array(
                'batch_id' => $schedule_id, 
                'email' => $member->email, 
                'type' => 'member_newsletter',
                'data_objects' => json_encode($data)
            ));
        }

        if(!count($members))
        {
            // Done prepping content, log in schedule
            $this->schedules->preppingDone($schedule_id);
        }
    }

    /**
     * Send newsletters from the given schedule.
     * @param integer $schedule_id
     * @param integer $limit
     * @return void
     */
    public function send($schedule_id, $limit = 500)
    {
        $schedule = $this->schedules->find($schedule_id);
        $newsletters = $this->newsletters->getBatchToSend($schedule_id, $limit);
        foreach($newsletters as $newsletter)
        {
            $data = json_decode($newsletter->data_objects, true);
            if(!count($data['coupons']) && !count($data['deals']))
                continue;
            try
            {
                if($schedule->first_category)
                {
                    $ordered = array();
                    foreach($data['coupons'] as $coupon)
                    {
                        if($coupon['category_id'] == $schedule->first_category)
                        {
                            $ordered[] = $coupon;
                        }
                    }
                    foreach($data['coupons'] as $coupon)
                    {
                        if($coupon['category_id'] == $schedule->second_category)
                        {
                            $ordered[] = $coupon;
                        }
                    }
                    foreach($data['coupons'] as $coupon)
                    {
                        if($coupon['category_id'] == $schedule->third_category)
                        {
                            $ordered[] = $coupon;
                        }
                    }
                    foreach($data['coupons'] as $coupon)
                    {
                        if($coupon['category_id'] != $schedule->first_category && $coupon['category_id'] != $schedule->second_category && $coupon['category_id'] != $schedule->third_category)
                        {
                            $ordered[] = $coupon;
                        }
                    }
                    $data['coupons'] = $ordered;
                }
                $intro = $this->features->findByName('member_newsletter_intro', false);
                $intro = $schedule->intro_paragraph != '' ? $schedule->intro_paragraph : ($intro ? $intro->value : 'Hi SaveOn Member! We’ve done a little homework and found a few of our favorite deals just for you...');
                $email_data = array(
                    'coupons' => $data['coupons'],
                    'deals' => $data['deals'],
                    'contests' => $data['contests'],
                    'featured' => $data['featured'],
                    'intro' => $intro
                );
                $email = $newsletter->email;
                $subject = $this->features->findByName('member_newsletter_subject', false);
                $subject = $schedule->subject_line != '' ? $schedule->subject_line : ($subject ? $subject->value : 'SaveOn Local Deals Just For You');
                \Mail::queueOn('SOE_Tasks', 'emails.newsletter', $email_data, function($message) use ($email,$subject)
                {
                    $message->to($email)->subject($subject);
                    $message->sender('webmaster@saveoneverything.com', 'Save On Everything');
                    $message->getHeaders()->addTextHeader('X-SMTPAPI', '{"category":"member-newsletter"}');
                });
                $this->newsletters->markSent($newsletter->id);
            }
            catch(\Exception $e)
            {
                $this->newsletters->unlockNewsletter($newsletter->id);
            }
        }

        if(count($newsletters) < $limit)
        {
            // We are finished sending newsletters for this batch, schedule next batch
            $this->schedules->setSchedule($schedule->id);
        }
    }
}