<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class VehicleYear extends Eloquent 
{
    /** Force database to use table "vehicle_models".  */
    protected $table = 'vehicle_years';

    public static function boot()
    {
        parent::boot();

        VehicleYear::saving(function($model)
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
    public function scopeByModel($query, $model)
    {
        return $query->where('vehicle_years.model_id', '=', $model);
    }

    /**
     * Vehicle Years can have many styles.
     *
     * @return Style
     */
    public function vehicleStyles()
    {
        return $this->hasMany('\SOE\DB\VehicleStyle', 'model_year_id', 'id');
    }

    /**
     * Scopes the query to only grab only one style record per model.
     *
     * @param Illuminate\Database\Query\Builder $query
     * @return Illuminate\Database\Query\Builder $query
     */
    public function scopeDistinctModel($query)
    {
        return $query->groupBy('vehicle_years.model_id');
    }

    /**
     * Scopes the query to vehicles within the given array of makes.
     * 
     * @param Illuminate\Database\Query\Builder $query
     * @param array $makes Array of make ids.
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeMakes($query, $makes)
    {
        if(!is_array($makes))
            $makes = explode(',', $makes);
        $column = is_numeric($makes[0]) ? 'vehicle_years.make_id' : 'vehicle_years.make_slug';
        
        return $query->whereIn($column, $makes);
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