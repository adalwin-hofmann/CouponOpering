<?php namespace SOE\DB;

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use SOE\Extensions\Eloquent;

class User extends Eloquent implements UserInterface, RemindableInterface, \PersonInterface
{
    
    public static function boot()
    {
        parent::boot();

        User::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }  
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password');

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail()
    {
        return $this->email;
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = \Hash::make($value);
    }

    public function getRememberToken()
    {
        //return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        //$this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        //return 'remember_token';
    }

    public function favorites()
    {
        return $this->hasMany('\SOE\DB\UserFavorite');
    }

    /**
     * Scopes the query to only return users with a validated email address.
     *
     * @param Illuminate\Database\Query\Builder $query
     * @return Illuminate\Database\Query\Builder $query
     */
    public function scopeCanEmail($query)
    {
        return $query->where('users.is_email_valid', '1');
    }

    /**
     * Scopes the query to only return users with the given type.
     *
     * @param Illuminate\Database\Query\Builder $query
     * @param string $type
     * @return Illuminate\Database\Query\Builder $query
     */
    public function scopeType($query, $type)
    {
        return $query->where('users.type', 'LIKE', '%'.$type.'%');
    }

    /**
     * Scopes the query to only return users that have location information.
     *
     * @param Illuminate\Database\Query\Builder $query
     * @return Illuminate\Database\Query\Builder $query
     */
    public function scopeHasLocation($query)
    {
        return $query->where('users.city', '!=', '')
                    ->where('users.state', '!=', '')
                    ->where('users.longitude', '!=', '0')
                    ->where('users.latitude', '!=', '0');
    }

    /****** Person Interface Functions ******/

    /**
     * Return the type of this Person.
     *
     * @return string User
     */
    public function getType()
    {
        return 'User';
    }

    /**
     * Determine if this user should see demo data.
     *
     * @return boolean
     */
    public function showDemo()
    {
        $types = explode(',', $this->type);
        if((in_array('Employee', $types) || in_array('Demo', $types)))
        {
            return true;
        }
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
        return 'user_id';
    }

    /****** END Person Interface Functions ******/

}