<?php

class FranchiseRss
{
    public function __construct()
    {
        $this->locations = \App::make('LocationRepositoryInterface');
        $this->franchises = \App::make('FranchiseRepositoryInterface');
        $this->offers = \App::make('OfferRepositoryInterface');
        $this->contests = \App::make('ContestRepositoryInterface');
        $this->merchants = \App::make('MerchantRepositoryInterface');
    }

    public function build($location_id, $location_only = false, $page = 0, $limit = 0)
    {
        $location = $this->locations->find($location_id);
        if(!$location)
            return false;
        $merchant = $this->merchants->find($location->merchant_id);

        $entities = $location_only ? $this->locations->getEntities($location) : $this->franchises->getEntities($location->franchise_id);

        $xml = new \SimpleXMLElement('<rss></rss>');
        $xml->addAttribute('version', '2.0');
        $channel = $xml->addChild('channel');
        $channel->addChild('title', htmlentities($merchant->display));
        $channel->addChild('link', 'www.saveon.com/coupons/franchise-rss/'.$location_id);
        $channel->addChild('description', 'Active offers for '.htmlentities($merchant->display).'.');
        foreach($entities['objects'] as $entity)
        {
            switch ($entity->entitiable_type)
            {
                case 'Offer':
                    $offer = $this->offers->find($entity->entitiable_id);
                    $description = $offer->description.'<br><br>Expires: '.date('m/d/Y', strtotime($offer->expires_at));
                    break;
                
                case 'Contest':
                    $contest = $this->contests->find($entity->entitiable_id);
                    $description = $contest->contest_description.'<br><br>Ends: '.date('m/d/Y', strtotime($contest->expires_at));
                    break;
                default: 
                    $description = '';
            }
            $item = $channel->addChild('item');
            $item->addChild('title', htmlentities($entity->name));
            $item->addChild('link', htmlentities('http://www.saveon.com/coupons/'.strtolower($location->state).'/'.SoeHelper::getSlug($location->city).'/'.$entity->category_slug.'/'.$entity->subcategory_slug.'/'.$entity->merchant_slug.'/'.$location_id.'?showeid='.$entity->id));
            $item->addChild('description', htmlentities($description));
            $enclosure = $item->addChild('enclosure');
            $enclosure->addAttribute('url', htmlentities($entity->path));
            $enclosure->addAttribute('type', 'image/jpeg');
            $item->addChild('pubDate', date('D, d M Y H:i:s -0500', strtotime($entity->starts_at)));
        }
        return $xml;
    }
}