<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class AutoQuote extends Eloquent 
{
    protected $table = 'auto_quotes';

    public static function boot()
    {
        parent::boot();

        AutoQuote::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }

    public function quoteable()
    {
        //return $this->morphTo();
        $class = '\SOE\DB\\'.$this->quoteable_type;
        $instance = new $class;
        return new \Illuminate\Database\Eloquent\Relations\MorphTo(
            with($instance)->newQuery(),
            $this,
            'quoteable_id',
            $instance->getKeyName(),
            'quoteable_type',
            'quoteable'
        );
    }
}