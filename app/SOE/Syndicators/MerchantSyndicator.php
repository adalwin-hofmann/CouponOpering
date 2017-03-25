<?php namespace SOE\Syndicators;

class MerchantSyndicator implements SyndicatorInterface
{
    public function __construct()
    {
        $this->franchises = \App::make('FranchiseRepositoryInterface');
        $this->locations = \App::make('LocationRepositoryInterface');
    }

    public function getSyndicatedObjects($format, $params = array())
    {
        set_time_limit(60*60); // 60 Mins
        ini_set('memory_limit', '2048M');
        $query = $this->franchises->query()
            ->join('merchants', 'franchises.merchant_id', '=', 'merchants.id')
            ->join('locations', 'locations.franchise_id', '=', 'franchises.id')
            ->where('locations.is_active', '1')
            ->where('locations.is_demo', '0')
            ->whereNull('locations.deleted_at')
            ->where('franchises.can_syndicate', '1')
            ->where(function($query)
            {
                $query->where('franchises.banner_728x90', '!=', '')
                    ->orWhere('franchises.banner_300x600', '!=', '')
                    ->orWhere('franchises.banner_300x250', '!=', '');
            })
            ->groupBy('franchises.id');
            if(isset($params['rating']))
                $query->where('franchises.syndication_rating', strtolower($params['rating']));
        $objects = $query->get(array(
                'franchises.id',
                'franchises.syndication_radius',
                'franchises.banner_728x90',
                'franchises.banner_300x600',
                'franchises.banner_300x250',
                'franchises.primary_contact',
                'franchises.click_pay_rate',
                'franchises.impression_pay_rate',
                'merchants.display',
                'merchants.slug',
                'merchants.catchphrase',
                \DB::raw("GROUP_CONCAT(locations.zip SEPARATOR ',') as zipcodes")
            ));

        foreach($objects as &$object)
        {
            $object->all_locations = $this->locations->query()
                ->where('locations.franchise_id', $object->id)
                ->where('locations.is_active', '1')
                ->where('locations.is_demo', '0')
                ->whereNull('locations.deleted_at')
                ->get(array(
                    'locations.id',
                    'locations.address',
                    'locations.address2',
                    'locations.city',
                    'locations.state',
                    'locations.phone',
                    'locations.zip'
                ));

            $entities = $this->franchises->getEntities($object->id, false, 0, 1);
            $object->coupons = $entities['objects'];
        }

        switch (strtolower($format)) {
            case 'rss':
                return $this->rssFormat($objects);
                break;
            default:
                throw new SyndicatorException('unknown format');
                break;
        }
    }

    protected function rssFormat($objects)
    {
        $xml = new \SimpleXMLElement('<rss></rss>');
        $xml->addAttribute('version', '2.0');
        $channel = $xml->addChild('channel');
        foreach($objects as $object)
        {
            if($object->banner_728x90 != '')
            {
                $item = $channel->addChild('item');
                $item->title = $object->display;
                $item->addChild('link', htmlentities('http://www.saveon.com/directions/'.$object->slug));
                $item->addChild('asset_path', $object->banner_728x90);
                $item->addChild('type', 'image');
                $item->addChild('dimensions', '728x90');
                $item->addChild('zipcodes', $object->zipcodes);
                $item->addChild('syndication_radius', $object->syndication_radius);
                $item->addChild('franchise_id', $object->id);
                $item->addChild('primary_contact', $object->primary_contact);
                $item->addChild('click_pay_rate', $object->click_pay_rate);
                $item->addChild('impression_pay_rate', $object->impression_pay_rate);
                $locations = $item->addChild('locations');
                foreach($object->all_locations as $location)
                {
                    $loc = $locations->addChild('location');
                    $loc->addChild('location_id', $location->id);
                    $loc->addChild('address', htmlentities($location->address));
                    $loc->addChild('address2', htmlentities($location->address2));
                    $loc->addChild('city', $location->city);
                    $loc->addChild('state', $location->state);
                    $loc->addChild('phone', $location->phone);
                    $loc->addChild('zip', $location->zip);
                }
                $coupons = $item->addChild('coupons');
                foreach($object->coupons as $coupon)
                {
                    $coup = $coupons->addChild('coupon');
                    $coup->addChild('name', htmlentities($coupon->name));
                    $coup->addChild('link', 'http://www.saveon.com/coupons/'.\SoeHelper::getSlug($coupon->state).'/'.\SoeHelper::getSlug($coupon->city).'/'.$coupon->category_slug.'/'.$coupon->subcategory_slug.'/'.$coupon->merchant_slug.'/'.$coupon->location_id);
                }
            }

            if($object->banner_300x600 != '')
            {
                $item = $channel->addChild('item');
                $item->title = $object->display;
                $item->addChild('link', htmlentities('http://www.saveon.com/directions/'.$object->slug));
                $item->addChild('asset_path', $object->banner_300x600);
                $item->addChild('type', 'image');
                $item->addChild('dimensions', '300x600');
                $item->addChild('zipcodes', $object->zipcodes);
                $item->addChild('syndication_radius', $object->syndication_radius);
                $item->addChild('franchise_id', $object->id);
                $item->addChild('primary_contact', $object->primary_contact);
                $item->addChild('click_pay_rate', $object->click_pay_rate);
                $item->addChild('impression_pay_rate', $object->impression_pay_rate);
                $locations = $item->addChild('locations');
                foreach($object->all_locations as $location)
                {
                    $loc = $locations->addChild('location');
                    $loc->addChild('location_id', $location->id);
                    $loc->addChild('address', htmlentities($location->address));
                    $loc->addChild('address2', htmlentities($location->address2));
                    $loc->addChild('city', $location->city);
                    $loc->addChild('state', $location->state);
                    $loc->addChild('phone', $location->phone);
                    $loc->addChild('zip', $location->zip);
                }
                $coupons = $item->addChild('coupons');
                foreach($object->coupons as $coupon)
                {
                    $coup = $coupons->addChild('coupon');
                    $coup->addChild('name', htmlentities($coupon->name));
                    $coup->addChild('link', 'http://www.saveon.com/coupons/'.\SoeHelper::getSlug($coupon->state).'/'.\SoeHelper::getSlug($coupon->city).'/'.$coupon->category_slug.'/'.$coupon->subcategory_slug.'/'.$coupon->merchant_slug.'/'.$coupon->location_id);
                }
            }

            if($object->banner_300x250 != '')
            {
                $item = $channel->addChild('item');
                $item->title = $object->display;
                $item->addChild('link', htmlentities('http://www.saveon.com/directions/'.$object->slug));
                $item->addChild('asset_path', $object->banner_300x250);
                $item->addChild('type', 'image');
                $item->addChild('dimensions', '300x250');
                $item->addChild('zipcodes', $object->zipcodes);
                $item->addChild('syndication_radius', $object->syndication_radius);
                $item->addChild('franchise_id', $object->id);
                $item->addChild('primary_contact', $object->primary_contact);
                $item->addChild('click_pay_rate', $object->click_pay_rate);
                $item->addChild('impression_pay_rate', $object->impression_pay_rate);
                $locations = $item->addChild('locations');
                foreach($object->all_locations as $location)
                {
                    $loc = $locations->addChild('location');
                    $loc->addChild('location_id', $location->id);
                    $loc->addChild('address', htmlentities($location->address));
                    $loc->addChild('address2', htmlentities($location->address2));
                    $loc->addChild('city', $location->city);
                    $loc->addChild('state', $location->state);
                    $loc->addChild('phone', $location->phone);
                    $loc->addChild('zip', $location->zip);
                }
                $coupons = $item->addChild('coupons');
                foreach($object->coupons as $coupon)
                {
                    $coup = $coupons->addChild('coupon');
                    $coup->addChild('name', htmlentities($coupon->name));
                    $coup->addChild('link', 'http://www.saveon.com/coupons/'.\SoeHelper::getSlug($coupon->state).'/'.\SoeHelper::getSlug($coupon->city).'/'.$coupon->category_slug.'/'.$coupon->subcategory_slug.'/'.$coupon->merchant_slug.'/'.$coupon->location_id);
                }
            }
        }
        $header['Content-Type'] = 'application/xml';
        return \Response::make($xml->asXML(), 200, $header);
    }
}