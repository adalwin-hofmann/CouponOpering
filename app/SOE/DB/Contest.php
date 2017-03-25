<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class Contest extends Eloquent 
{
    protected $softDelete = true;
    
    public static function boot()
    {
        parent::boot();

        Contest::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }

    public function applications()
    {
        return $this->hasMany('\SOE\DB\ContestApplication')->groupBy('user_id');
    }

    public function winners()
    {
        return $this->hasMany('\SOE\DB\ContestWinner')->whereNotNull('verified_at');
    }

    public function merchant()
    {
        return $this->belongsTo('\SOE\DB\Merchant');
    }

    public function followUp()
    {
        return $this->morphTo();
    }
}