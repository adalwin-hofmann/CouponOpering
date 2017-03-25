<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class Nonmember extends Eloquent implements \PersonInterface
{
    public static function boot()
    {
        parent::boot();

        Nonmember::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    } 

    /** Force database to use table "nonmembers".  */  
    protected $table = 'nonmembers';

	/**
     * Users can redeem many offers
     *
     * @return offers redeemed by this user
     */
    public function offers_redeemed()
    {
        return $this->belongsToMany('Offer', 'user_redeem', 'nonmember_id', 'offer_id');
    }

    /**
     * Users can print many offers
     *
     * @return offers printed by this user
     */
    public function offers_printed()
    {
        return $this->belongsToMany('Offer', 'user_print', 'nonmember_id', 'offer_id');
    }

    /**
     * Users can view many merchants
     *
     * @return merchants viewed by this user
     */
    public function merchants_viewed()
    {
        return $this->belongsToMany('Merchant', 'user_view', 'nonmember_id', 'merchant_id');
    }

    /**
     * Users can make many searches
     *
     * @return searches made by this user
     */
    public function searches()
    {
        return $this->hasMany('UserSearch', 'nonmember_id');
    }

    /****** Person Inteface Functions ******/

    /**
     * Return the type of this Person.
     *
     * @return string Nonmember
     */
    public function getType()
    {
        return 'Nonmember';
    }

    /**
     * Determine if this user should see demo data.
     *
     * @return boolean
     */
    public function showDemo()
    {
        return false;
    }

    /**
     * Return the category rankings for this Person.
     *
     * @return array
     */
    public function getRankings()
    {
        $cat_slugs = array('food_dining', 'home_services', 'health_beauty', 'auto_transportation', 'travel_entertainment', 'retail_fashion', 'special_services');
        $aRankings = array();
        foreach($cat_slugs as $slug)
        {
            $aRankings[$slug] = $this->{'rank_'.$slug};
        }
        return $aRankings;
    }

    /**
     * What is the foreign key going to be named for this person?
     * @return string
     */
    public function getForeignKey()
    {
        return 'nonmember_id';
    }

    /****** END Person Interface Functions ******/
}