<?php namespace SOE\Printables;

class EntityPrintable extends Printable implements PrintableInterface
{
    public function __construct($object_id, $params = array())
    {
        parent::__construct();
        $this->companies = \App::make('CompanyRepositoryInterface');
        $this->categories = \App::make('CategoryRepositoryInterface');
        $this->contests = \App::make('ContestRepositoryInterface');
        $this->franchises = \App::make('FranchiseRepositoryInterface');
        $this->locations = \App::make('LocationRepositoryInterface');
        $this->merchants = \App::make('MerchantRepositoryInterface');
        $this->model = $this->repository->find($object_id);
        $this->offers = \App::make('OfferRepositoryInterface');
        $this->reviews = \App::make('ReviewRepositoryInterface');
        $this->userImpressions = \App::make('UserImpressionRepositoryInterface');
        $this->userPrints = \App::make('UserPrintRepositoryInterface');
    }

    public function printItem(\SOE\Persons\PersonInterface $printer)
    {
        $printer_id = $printer->getId();
        $foreign = $printer->getForeignKey();
        $offer = $this->offers->find($this->model->entitiable_id);
        if(!isset($this->model->offer_rand) || $this->model->offer_rand == null)
            $this->model->offer_rand = date('md').bin2hex(openssl_random_pseudo_bytes(2));
        
        if($printer->exists() && $printer->shouldTrack() && !empty($printer_id))
        {
            $company = $this->companies->find($this->model->company_id);
            $franchise = $this->franchises->find($offer->franchise_id);
            $location = $this->locations->find($this->model->location_id);
            $merchant = $this->merchants->find($this->model->merchant_id);
            $category = $this->categories->find($merchant->category_id);
            $subcategory = $this->categories->find($merchant->subcategory_id);

            $print = $this->userPrints->blank();
            $print->code = $this->model->offer_rand;
            $print->$foreign = $printer_id;
            $print->offer_id = $offer->id;
            $print->entity_id = $this->model->id;
            $print->location_id = $this->model->location_id;
            $print->merchant_id = $this->model->location_id;
            $print->tracking_id = \Cookie::get('tracking_id');
            $print->url = \Cookie::get('tracking_url');
            $print->refer_id = \Cookie::get('tracking_referid');
            if($this->model->print_override != '')
            {
                $print->type = 'referral';
            }
            $print->save();
            $prints = $this->userPrints->query()
                ->where($foreign, '=', $printer_id)
                ->where('offer_id', '=', $offer->id)
                ->count();

            $geoip = json_decode(\GeoIp::getGeoIp('json'));
            $person = $printer->getModel();
            $identity = $print->user_id != 0 ? $person->email : 'non-'.$print->nonmember_id;
            $mp = \Mixpanel::getInstance(\Config::get('integrations.mixpanel.token'));
            $mp->identify($identity);
            $mp->track('Offer Print', array(
                '$city' => $geoip->city_name,
                'OfferId' => $offer->id,
                'OfferName' => $offer->name,
                'Environment' => \App::environment(),
                'MerchantId' => $offer->merchant_id,
                'MerchantName' => $merchant->display,
                'MerchantNameAddress' => $merchant->display.' - '.$location->address,
                'LocationId' => $location->id,
                'FranchiseId' => $location->franchise_id,
                '$region' => $geoip->region_name,
                'Category' => !empty($category) ? $category->name : '',
                'Subcategory' => !empty($subcategory) ? $subcategory->name : '',
                'CompanyID' => !empty($company) ? $company->id : '',
                'CompanyName' => !empty($company) ? $company->name : '',
                'UserType' => ($print->user_id != 0 ? 'User' : 'Nonmember')
            ));

            $print->offer = $offer;
            $print->entity = $this->model;
            if($print->user_id == 0)
                $clip = null;
            else
            {
                $clip = \SOE\DB\UserClipped::on('mysql-write')
                    ->where('user_id', '=', $printer_id)
                    ->where('offer_id', '=', $offer->id)
                    ->where('is_deleted', '=', '0')
                    ->first();
            }
            $print->is_clipped = !empty($clip);
            $print->can_print = ($prints+1) < $offer->max_prints ? 1 : 0;
            $reviews = \SOE\DB\Review::on('mysql-write')
                ->where('reviewable_id', '=', $offer->id)
                ->where('reviewable_type', '=', 'Offer')
                ->where('is_deleted', '=', '0')
                ->get(array('user_id', 'nonmember_id', 'upvotes'));
            $up_count = 0;
            $down_count = 0;
            $my_review = 0;
            foreach($reviews as $review)
            {
                if($review->upvotes == 1)
                {
                    $up_count++;
                }
                else
                {
                    $down_count++;
                }
                $my_review = $review->$foreign == $printer_id ? $review->upvotes : $my_review;
            }
            $print->down_count = $down_count;
            $print->up_count = $up_count;
            $print->my_review = $my_review;
            return $print;
        }
        else
        {
            $print = $this->userPrints->blank();
            $print->code = $this->model->offer_rand;
            $print->$foreign = $printer_id;
            $print->offer_id = $offer->id;
            $print->entity_id = $this->model->id;
            $print->location_id = $this->model->location_id;
            $print->merchant_id = $this->model->location_id;
            $print->tracking_id = \Cookie::get('tracking_id');
            $print->url = \Cookie::get('tracking_url');
            $print->refer_id = \Cookie::get('tracking_referid');

            $print->offer = $offer;
            $print->entity = $this->model;
            if($print->user_id == 0)
                $clip = null;
            else
            {
                $clip = \SOE\DB\UserClipped::on('mysql-write')
                    ->where('user_id', '=', $printer_id)
                    ->where('offer_id', '=', $offer->id)
                    ->where('is_deleted', '=', '0')
                    ->first();
            }
            $print->is_clipped = !empty($clip);
            $print->can_print = 1;
            $reviews = \SOE\DB\Review::on('mysql-write')
                ->where('reviewable_id', '=', $offer->id)
                ->where('reviewable_type', '=', 'Offer')
                ->where('is_deleted', '=', '0')
                ->get(array('user_id', 'nonmember_id', 'upvotes'));
            $up_count = 0;
            $down_count = 0;
            $my_review = 0;
            foreach($reviews as $review)
            {
                if($review->upvotes == 1)
                {
                    $up_count++;
                }
                else
                {
                    $down_count++;
                }
                $my_review = $review->$foreign == $printer_id ? $review->upvotes : $my_review;
            }
            $print->down_count = $down_count;
            $print->up_count = $up_count;
            $print->my_review = $my_review;
            return $print;
        }
    }
}