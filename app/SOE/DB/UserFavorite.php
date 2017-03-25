<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

/**
* UserFavorite.
*
* An object favorited by a User.
*
* @author Caleb Beery <cbeery@saveoneverything.com>
*
*/

class UserFavorite extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_favorites';

    public static function boot()
    {
        parent::boot();

        UserFavorite::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }
    
    /**
    * Get the User associated with this UserPrint.
    *
    * @return User
    */
    public function user()
    {
        return $this->belongsTo('User');
    }

    public function favoritable()
    {
        $class = '\SOE\DB\\'.$this->favoritable_type;
        $instance = new $class;
        return new \Illuminate\Database\Eloquent\Relations\MorphTo(
            with($instance)->newQuery(),
            $this,
            'favoritable_id',
            $instance->getKeyName(),
            'favoritable_type',
            'favoritable'
        );
        //return $this->morphTo();
    }
}