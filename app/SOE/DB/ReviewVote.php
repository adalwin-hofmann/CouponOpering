<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

/**
*
* @api
*/

class ReviewVote extends Eloquent 
{
    public static function boot()
    {
        parent::boot();

        ReviewVote::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    } 

	/**
     * Each ReviewVote belongs to a single Review
     *
     * @return Review
     */
    public function review()
    {
        return $this->belongsTo('Review', 'review_id');
    }

    /**
     * Each ReviewVote belongs to a single User
     *
     * @return User
     */
    public function user()
    {
        return $this->belongsTo('User', 'user_id');
    }
}