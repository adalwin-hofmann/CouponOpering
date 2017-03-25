<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

/**
*
* @api
*/

class Offer extends Eloquent
{
    protected $softDelete = true;

    public static function boot()
    {
        parent::boot();

        Offer::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'offers';

    /**
    * Get the Merchant associated with this Offer.
    *
    * @return Merchant
    */
    public function merchant()
    {
        return $this->belongsTo('\SOE\DB\Merchant');
    }

    /**
    * Get the Location associated with this Offer.
    *
    * @return Location
    */
    public function location()
    {
        return $this->belongsTo('Location');
    }

    /**
     * Offers can be clipped by users
     *
     * @return User
     */
    public function clipped_by_users()
    {
        return $this->belongsToMany('User', 'user_clipped');
    }

    /**
     * Offers can be reviewed by users
     *
     * @return User
     */
    public function reviewed_by_users()
    {
        return $this->belongsToMany('User', 'reviews');
    }

    /**
     * Offers can be printed by users
     *
     * @return User
     */
    public function printed_by_users()
    {
        return $this->belongsToMany('User', 'user_prints');
    }

    /**
     * Offers can be viewed by users
     *
     * @return User
     */
    public function impression_by_users()
    {
        return $this->belongsToMany('User', 'user_impressions');
    }

    /**
     * Offers can be redeemed by users
     *
     * @return User
     */
    public function redeemed_by_users()
    {
        return $this->belongsToMany('User', 'user_redeems');
    }

    public function contests()
    {
        return $this->morphMany('\SOE\DB\Contest', 'follow_up');
    }

    public function entities()
    {
        return $this->hasMany('\SOE\DB\Entity', 'entitiable_id')->where('entitiable_type', 'Offer');
    }

    /**
     * Scope the query to only offers matching the given name.
     *
     * @param Illuminate\Database\Query\Builder $query
     * @param string $name
     * @return Illuminate\Database\Query\Builder $query
     */
    public function scopeLikeName($query, $name)
    {
        return $query->where('offers.name', 'LIKE', '%'.$name.'%');
    }

    /**
     * Scope the query to only offers matching the given name.
     *
     * @param Illuminate\Database\Query\Builder $query
     * @param string $name
     * @return Illuminate\Database\Query\Builder $query
     */
    public function scopeActive($query)
    {
        return $query->where('offers.starts_at', '<=', \DB::raw('NOW()'))
                    ->where('offers.expires_at', '>=', \DB::raw('NOW()'))
                    ->where('offers.is_active', '1');
    }
}
