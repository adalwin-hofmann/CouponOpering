<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class VehicleEntity extends Eloquent 
{
    /** Force database to use table "vehicle_entities".  */
    protected $table = 'vehicle_entities';
    protected $softDelete = true;

    public function merchant()
    {
        return $this->belongsTo('\SOE\DB\Merchant');
    }

    /*public function entitiable()
    {
        $class = '\SOE\DB\\'.$this->entitiable_type;
        $instance = new $class;
        return new \Illuminate\Database\Eloquent\Relations\MorphTo(
            with($instance)->newQuery(),
            $this,
            'entitiable_id',
            $instance->getKeyName(),
            'entitiable_type',
            'entitiable'
        );
    }*/

    public function vehicleMake()
    {
        return $this->belongsTo('\SOE\DB\VehicleMake', 'make_id');
    }

    public function quotes()
    {
        return $this->morphMany('\SOE\DB\AutoQuote', 'quoteable');
    }

    public static function boot()
    {
        parent::boot();

        VehicleEntity::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }

    /**
     * Scopes the query to vehicles within the given array of years.
     * 
     * @param Illuminate\Database\Query\Builder $query
     * @param array $years
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeYears($query, $years)
    {
        if(!is_array($years))
            $years = array($years);
        return $query->whereIn('year', $years);
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
            $makes = array($makes);
        $column = is_numeric($makes[0]) ? 'id' : 'slug';
        $makeNames = \SOE\DB\VehicleMake::whereIn($column, $makes)->get(array('name'));
        $column = is_numeric($makes[0]) ? 'make_id' : 'make';
        return $query->where(function($query) use ($makes,$makeNames,$column) 
            {
                $query->whereIn($column, $makes);
                $query->orWhere(function($query) use ($makeNames)
                {
                    foreach($makeNames as $make)
                    {
                        $query->orWhere('make', 'LIKE', \DB::raw("'%".$make->name."%'"));
                    }
                });
            });
    }

    /**
     * Scopes the query to vehicles within the given array of models.
     * 
     * @param Illuminate\Database\Query\Builder $query
     * @param array $models Array of model ids.
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeModels($query, $models)
    {
        if(!is_array($models))
            $models = array($models);
        $column = 'slug';//is_numeric($models[0]) ? 'id' : 'slug';
        $modelNames = \SOE\DB\VehicleModel::whereIn($column, $models)->get(array('name'));
        $column = is_numeric($models[0]) ? 'model_id' : 'model_id';

        return $query->where(function($query) use ($models,$modelNames,$column) 
            {
                $query->whereIn($column, $models);
                $query->orWhere(function($query) use ($modelNames)
                {
                    foreach($modelNames as $model)
                    {
                        $query->orWhere('model', 'LIKE', \DB::raw("'%".$model->name."%'"));
                    }
                });
            })->where('model_id', '!=', 0);
    }

    /**
     * Scopes the query to vehicles within the given price range.
     * 
     * @param Illuminate\Database\Query\Builder $query
     * @param int $min
     * @param int $max
     * @return Illuminate\Database\Query\Builder
     */
    public function scopePrice($query, $min = null, $max = null)
    {
        if($min)
            $query->where('internet_price', '>=', $min);
        if($max)
            $query->where('internet_price', '<=', $max);
        $query->where('internet_price', '>', '500');
        return $query;
    }

    /**
     * Scopes the query to vehicles within the given radius of miles of the given point.
     * 
     * @param Illuminate\Database\Query\Builder $query
     * @param float $latitude
     * @param float $longitude
     * @param int $miles
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeDistance($query, $miles, $latitude = null, $longitude = null)
    {
        if(!$latitude || !$longitude)
        {
            $geoip = json_decode(\GeoIp::getGeoIp('json'));
            $latitude = $geoip->latitude;
            $longitude = $geoip->longitude;
        }
        $cartesian = \SoeHelper::getCartesian($latitude, $longitude);
        $distance = \DB::raw('(sqrt(pow(vehicle_entities.latm - '.$cartesian['latm'].', 2) + pow(vehicle_entities.lngm - '.$cartesian['lngm'].', 2)))');
        return $query->where($distance, '<=', \DB::raw("IF(`dealer_radius` = 0, ".($miles * 1609).", `dealer_radius`*1609)"));
    }

    public function scopeWithDistance($query)
    {
        $geoip = json_decode(\GeoIp::getGeoIp('json'));
        $cartesian = \SoeHelper::getCartesian($geoip->latitude, $geoip->longitude);
        $distanceCol = \DB::raw('(sqrt(pow(vehicle_entities.latm - '.$cartesian['latm'].', 2) + pow(vehicle_entities.lngm - '.$cartesian['lngm'].', 2))) as distance');
        return $query->addSelect($distanceCol);
    }

    public function scopeOrderByDistance($query, $latitude = null, $longitude = null)
    {
        if(!$latitude || !$longitude)
        {
            $geoip = json_decode(\GeoIp::getGeoIp('json'));
            $latitude = $geoip->latitude;
            $longitude = $geoip->longitude;
        }
        $cartesian = \SoeHelper::getCartesian($latitude, $longitude);
        $distance = \DB::raw('(sqrt(pow(vehicle_entities.latm - '.$cartesian['latm'].', 2) + pow(vehicle_entities.lngm - '.$cartesian['lngm'].', 2)))');
        $distanceCol = \DB::raw('(sqrt(pow(vehicle_entities.latm - '.$cartesian['latm'].', 2) + pow(vehicle_entities.lngm - '.$cartesian['lngm'].', 2))) as distance');
        return $query->addSelect($distanceCol)->orderBy($distance);
    }
}