<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class VehicleAsset extends Eloquent 
{
    /** Force database to use table "vehicle_models".  */
    protected $table = 'vehicle_assets';

    public static function boot()
    {
        parent::boot();

        VehicleAsset::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }

    /**
     * Scopes the query to models with the given make.
     * 
     * @param Illuminate\Database\Query\Builder $query
     * @param array $make
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeByStyle($query, $style)
    {
        return $query->where('style_id', '=', $style);
    }
}