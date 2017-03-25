<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class ContestWinner extends Eloquent 
{
    public static function boot()
    {
        parent::boot();

        ContestWinner::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    } 

    /** Force database to use table "contest_winners".  */
    protected $table = 'contest_winners';

	/**
     * Each ContestWinner belongs to a single Contest
     *
     * @return Contest
     */
    public function contest()
    {
        return $this->belongsTo('Contest');
    }

    /**
     * ContestWinner can have one User
     *
     * @return User
     */
    public function user()
    {
        return User::find($this->user_id)->get();
    }
}