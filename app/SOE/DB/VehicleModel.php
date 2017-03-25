<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class VehicleModel extends Eloquent 
{
    /** Force database to use table "vehicle_models".  */
    protected $table = 'vehicle_models';

    public static function boot()
    {
        parent::boot();

        VehicleModel::saving(function($model)
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
    public function scopeByMake($query, $make)
    {
        return $query->where('make_id', '=', $make);
    }

    /**
     * Scopes the query to models with the given make.
     * 
     * @param Illuminate\Database\Query\Builder $query
     * @param string $make_slug
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeByMakeSlug($query, $make_slug)
    {
        return $query->where('make_slug', '=', $make_slug);
    }
}