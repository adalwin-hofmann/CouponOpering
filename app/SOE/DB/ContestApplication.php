<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class ContestApplication extends Eloquent 
{
    public static function boot()
    {
        parent::boot();

        ContestApplication::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    } 

    /** Force database to use table "contest_applications".  */
    protected $table = 'contest_applications';

	/**
     * Each ContestApplication belongs to a single Contest
     *
     * @return Contest
     */
    public function contest()
    {
        return $this->belongsTo('Contest');
    }

    /**
     * ContestApplication can have one User
     *
     * @return User
     */
    public function user()
    {
        return User::find($this->user_id)->get();
    }
}