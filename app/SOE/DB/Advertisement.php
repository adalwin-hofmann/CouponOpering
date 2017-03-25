<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class Advertisement extends Eloquent 
{
    /** Force database to use table "advertisements".  */
    protected $table = 'advertisements';

    public static function boot()
    {
        parent::boot();

        Advertisement::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }     

    public function merchant()
    {
        return $this->belongsTo('\SOE\DB\Merchant');
    }

    public function category()
    {
        return $this->belongsTo('\SOE\DB\Category');
    }

    public function subcategory()
    {
        return $this->belongsTo('\SOE\DB\Category', 'subcategory_id');
    }

    public function adable()
    {
        $class = '\SOE\DB\\'.$this->adable_type;
        $instance = new $class;
        return new \Illuminate\Database\Eloquent\Relations\MorphTo(
            with($instance)->newQuery(),
            $this,
            'adable_id',
            $instance->getKeyName(),
            'adable_type',
            'adable'
        );
    }
}