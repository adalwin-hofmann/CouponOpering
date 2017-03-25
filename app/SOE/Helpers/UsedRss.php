<?php

class UsedRss
{
    public function __construct()
    {
        $this->vehicleEntities = \App::make('VehicleEntityRepositoryInterface');
    }

    public function build($min_price = null, $max_price = null, $year = null, $make = null, $model = null, $trim = null, $zipcode = null, $radius = 75, $vin = null, $description = 0, $images = 0, $merchant_id = null, $page = 0, $limit = 0, $state = null, $include_new = 'no')
    {
        set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '2048M');

        $vehicles = $this->vehicleEntities->rssQuery($min_price, $max_price, $year, $make, $model, $trim, $zipcode, $radius, $vin, $description, $images, $merchant_id, $page, $limit, $state, $include_new);

        $xml = new \SimpleXMLElement('<rss></rss>');
        $xml->addAttribute('version', '2.0');
        $channel = $xml->addChild('channel');

        foreach($vehicles['objects'] as $vehicle)
        {
            $item = $channel->addChild('item');
            $item->addChild('title', $vehicle->year.' '.$vehicle->make.' '.$vehicle->model);
            $item->addChild('link', htmlentities('http://www.saveon.com/cars/used/'.strtolower($vehicle->state).'/'.\SoeHelper::getSlug($vehicle->city).'?showeid='.$vehicle->id.'&eidtype=usedvehicle'));
            $item->addChild('description', $vehicle->year.' '.$vehicle->make.' '.$vehicle->model);
            $item->addChild('image', htmlentities($vehicle->display_image));
            $item->addChild('price', $vehicle->internet_price);
            $item->addChild('mileage', $vehicle->mileage);
            $item->addChild('type', $vehicle->vehicle_type);
            $item->addChild('year', $vehicle->year);
            $item->addChild('make', $vehicle->make);
            $item->addChild('model', $vehicle->model);
            $item->addChild('trim', $vehicle->trim_level);
            $item->addChild('vin', $vehicle->vin);
            $item->addChild('dealer_id', $vehicle->merchant_id);
            $item->addChild('dealer_name', htmlentities($vehicle->dealer_name));
            $item->addChild('dealer_address', htmlentities($vehicle->address));
            $item->addChild('city', $vehicle->city);
            $item->addChild('state', $vehicle->state);
            $item->addChild('zipcode', $vehicle->zipcode);
            $item->addChild('vendor_inventory_id', $vehicle->vendor_inventory_id);
            $item->addChild('form_js', 'http://www.saveon.com/cars/netlms.js?v='.$vehicle->vin);
            if($description != 0)
            {
                $item->addChild('options', htmlentities($vehicle->options));
                $item->addChild('dealer_comments', htmlentities($vehicle->dealer_comments));
            }
            if($images != 0)
            {
                $vehicle->image_urls = str_replace('||', '|', $vehicle->image_urls);
                $item->addChild('all_images', htmlentities($vehicle->image_urls));
            }
            $item->addChild('condition', $vehicle->condition);
        }
        return $xml;
    }
}