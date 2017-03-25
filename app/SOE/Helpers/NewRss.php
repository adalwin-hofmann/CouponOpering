<?php

class NewRss
{
    public function __construct()
    {
        $this->vehicleStyles = \App::make('VehicleStyleRepositoryInterface');
    }

    public function build($min_price = null, $max_price = null, $year = null, $make = null, $model = null, $body = null, $images = 0, $page = 0, $limit = 0)
    {
        $vehicles = $this->vehicleStyles->search($year, $make, $model, $min_price, $max_price, $page, $limit, null, $body, $images);

        $xml = new \SimpleXMLElement('<rss></rss>');
        $xml->addAttribute('version', '2.0');
        $channel = $xml->addChild('channel');
        foreach($vehicles['objects'] as $vehicle)
        {
            $item = $channel->addChild('item');
            $item->addChild('title', $vehicle->year.' '.$vehicle->make_name.' '.$vehicle->model_name);
            $item->addChild('link', htmlentities('http://www.saveon.com/cars/research/'.$vehicle->year.'/'.$vehicle->make_name.'/'.$vehicle->model_name.'/'.$vehicle->id));
            $item->addChild('description', $vehicle->year.' '.$vehicle->make_name.' '.$vehicle->model_name);
            $item->addChild('image', count($vehicle->assets) ? htmlentities($vehicle->assets[0]['path']) : '');
            $item->addChild('price', $vehicle->price);
            $item->addChild('type', $vehicle->body_type);
            $item->addChild('year', $vehicle->year);
            $item->addChild('make', $vehicle->make_name);
            $item->addChild('model', $vehicle->model_name);
            $item->addChild('form_js', 'http://www.saveon.com/cars/new/netlms.js?v='.$vehicle->id);
            if($images != 0)
            {
                $aImages = array();
                foreach($vehicle->all_assets as $image)
                {
                    $aImages[] = $image['path'];
                }
                $aImages = implode('|', $aImages);
                $item->addChild('all_images', htmlentities($aImages));
            }
        }
        return $xml;
    }
}