<?php

class EloquentOfferRepository extends BaseEloquentRepository implements OfferRepository, ReviewableInterface, ShareableInterface, RepositoryInterface
{
    protected $columns = array(
        'name',
        'slug',
        'location_id',
        'merchant_id',
        'yipitdeal_id',
        'path',
        'path_small',
        'is_dailydeal',
        'special_price',
        'regular_price',
        'code',
        'description',
        'starts_at',
        'expires_at',
        'rating',
        'rating_count',
        'max_redeems',
        'max_prints',
        'url',
        'print_override',
        'is_demo',
        'is_active',
        'created_by',
        'updated_by',
        'savings',
        'is_featured',
        'franchise_id',
        'is_location_specific',
        'deleted_at',
        'requires_member',
        'is_mobile_only',
        'secondary_type',
        'short_name_line1',
        'short_name_line2',
        'is_followup_for',
        'custom_category_id',
        'custom_subcategory_id',
        'category_visible',
        'secondary_image',
        'hide_expiration',
        'year',
        'make',
        'make_id',
        'model',
        'model_id',
        'is_reoccurring',
        'merchant_logo',
    );

    protected $model = 'Offer';

    /**
     * Print this offer for a given user.
     *
     * @param mixed $printer Either a UserRepository or a NonmemberRepository.
     * 
     * @return UserPrint
     */
    public function printOffer($printer)
    {
        if($this->primary_key)
        {
            return $printer->printOffer($this);
        }
    }

    /**
     * Review this offer for a given user.
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
            $review->reviewable_type = 'Offer';
            if($reviewer->getType() == 'User')
                $review->user_id = $reviewer->id;
            else
                $review->nonmember_id = $reviewer->id;
            $review->upvotes = Input::get('content', 1);
            $review->save();
            return $review;
        }
    }

    /**
     * Share this offer for a given user.
     *
     * @param UserRepository $sharer
     * @param string            $type The type of share, email or facebook.
     * @param array             $params An array of parameters.
     * 
     * @return Share
     */
    public function share(UserRepository $sharer, $type, $params = array())
    {
        if($this->primary_key)
        {
            //TODO: Implement sharing
        }
    }

    /**
     * Get the merchant that this offer belongs to.
     *
     * @return MerchantRepository
     */
    public function getMerchant()
    {
        if($this->primary_key)
        {
            return Merchant::find($this->merchant_id);
        }
    }

    /**
     * Get locations by franchise_id
     *
     * @param int   $franchise_id
     * @param int   $page
     * @param int   $limit
     * @return mixed
     */
    public function getByFranchiseId($franchise_id, $page = 0, $limit = 0, $params = array())
    {
        $offers = SOE\DB\Offer::where('franchise_id', '=', $franchise_id)->orderBy('name', 'asc');
        if(isset($params['filter']))
        {
            $offers = $offers->where('name', 'LIKE', '%'.$params['filter'].'%');
        }
        if(isset($params['is_active']) && $params['is_active'] != -1)
        {
            $offers = $offers->where('is_active', '=', $params['is_active']);
        }
        $stats = $this->getStats(clone $offers, $limit, $page);
        if($limit)
        {
            $offers = $offers->take($limit)->skip($limit*$page);
        }
        $offers = $offers->get();
        $stats['stats']['returned'] = count($offers);
        $return = array('objects' => array());
        foreach($offers as $offer)
        {
            $loc = Offer::blank();
            $loc = $loc->createFromModel($offer);
            $return['objects'][] = $loc;
        }
        return array_merge($return, $stats);
    }

    /***** API METHODS *****/

    /**
     * Get locations by franchise_id.
     *
     * @api
     *
     * @return mixed Formatted locations.
     */
    public function apiGetByFranchiseId()
    {
        $franchise_id = Input::get('franchise_id');
        $params = Input::all();
        return $this->format($this->getByFranchiseId($franchise_id, Input::get('page', 0), Input::get('limit', 0), $params));
    }

}

/**
 * Handle the Offer creation event.
 *
 * @param SOE\DB\Offer $offer
 * @return void
 */
/*SOE\DB\Offer::created(function($offer)
{
    $o = Offer::find($offer->id);
    Event::fire('offer.created', array($o)); 
    Queue::push(function($job) use ($o)
    {
        $merchant = $o->getMerchant();
        $entity = Entity::blank();
        $entity->entitiable_id = $o->id;
        $entity->entitiable_type = 'Offer';
        $entity->name = $o->name;
        $entity->location_id = $o->location_id;
        $entity->category_id = $merchant->category_id;
        $entity->subcategory_id = $merchant->subcategory_id;
        $entity->path = $o->path;
        $entity->is_dailydeal = $o->is_dailydeal;
        $entity->special_price = $o->special_price;
        $entity->regular_price = $o->regular_price;
        $entity->is_demo = $o->is_demo;
        $entity->is_active = $o->is_active;
        $entity->starts_at = $o->starts_at;
        $entity->expires_at = $o->expires_at;
        $entity->save();
        $job->delete();
    });
});*/
