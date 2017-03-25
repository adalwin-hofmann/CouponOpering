<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class UsedVehicle extends Eloquent implements \ViewableInterface
{
    /** Force database to use table "vehicle_makes".  */
    protected $table = 'used_vehicles';

    public static function boot()
    {
        parent::boot();

        UsedVehicle::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    } 

    public function merchant()
    {
        return $this->belongsTo('\SOE\DB\Merchant');
    }

    public function quotes()
    {
        return $this->morphMany('\SOE\DB\AutoQuote', 'quoteable');
    }

    public function vehicleMake()
    {
        return $this->belongsTo('\SOE\DB\VehicleMake', 'make_id');
    }

    public function view(\PersonInterface $viewer)
    {
        if($this->id)
        {
            $viewerType = $viewer->getType();
            $foreign = $viewer->getForeignKey();
            $merchant = \SOE\DB\Merchant::find($this->merchant_id);
            $location = \SOE\DB\Location::find($this->location_id);
            $franchise = \SOE\DB\Franchise::find($location->franchise_id);
            $view = new \SOE\DB\UserImpression;
            $view->user_id = 0;
            $view->nonmember_id = 0;
            $view->$foreign = $viewer->id;
            $view->location_id = $this->location_id;
            $view->merchant_id = $this->merchant_id;
            $view->used_vehicle_id = $this->id;
            $view->save();
            $geoip = json_decode(\GeoIp::getGeoIp('json'));
            $view_id = $view->id;
            if(\App::environment() == 'prod') {
                \Queue::push(function($job) use ($view_id, $geoip)
                {
                    $view = \SOE\DB\UserImpression::find($view_id);
                    $person = $view->user_id != 0 ? \SOE\DB\User::find($view->user_id) : \SOE\DB\Nonmember::find($view->nonmember_id);
                    $vehicle = \SOE\DB\UsedVehicle::find($view->used_vehicle_id);
                    if(empty($person) || empty($vehicle))
                    {
                        $job->delete();
                        return;
                    }
                    $identity = $view->user_id != 0 ? $person->email : 'non-'.$view->nonmember_id;
                    $location = \SOE\DB\Location::find($vehicle->location_id);
                    $merchant = \SOE\DB\Merchant::find($vehicle->merchant_id);
                    $category = \SOE\DB\Category::find($merchant->category_id);
                    $subcategory = \SOE\DB\Category::find($merchant->subcategory_id);
                    $mp = \Mixpanel::getInstance(\Config::get('integrations.mixpanel.token'));
                    $mp->identify($identity);
                    $mp->track('Used Vehicle Impression', array(
                        '$city' => $geoip->city_name,
                        'UsedVehicleId' => $view->offer_id,
                        'Environment' => \App::environment(),
                        'MerchantId' => $vehicle->merchant_id,
                        'MerchantName' => $merchant->display,
                        'MerchantNameAddress' => $merchant->display.' - '.$location->address,
                        'LocationId' => $location->id,
                        'FranchiseId' => $location->franchise_id,
                        '$region' => $geoip->region_name,
                        'Category' => !empty($category) ? $category->name : '',
                        'Subcategory' => !empty($subcategory) ? $subcategory->name : '',
                        'UserType' => ($view->user_id != 0 ? 'User' : 'Nonmember')
                    ));
                    $job->delete();
                });
            }

            $view->used_vehicle = $this->toArray();
            $favorite = array();
            if($viewerType == 'User')
                $favorite = \SOE\DB\UserFavorite::where('user_id', $viewer->id)
                                                ->where('favoritable_type', 'SOE\\DB\\UsedVehicle')
                                                ->where('favoritable_id', $this->id)
                                                ->first();
            $view->is_saved = !empty($favorite);
            $view->merchant = $merchant->toArray();

            return $view;
        }
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
            });
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
            $query->where('internet_price', '<=', $max)->where('internet_price', '!=', 0);
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
        $distance = \DB::raw('(sqrt(pow(used_vehicles.latm - '.$cartesian['latm'].', 2) + pow(used_vehicles.lngm - '.$cartesian['lngm'].', 2)))');
        return $query->where($distance, '<=', ($miles * 1609));
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
        $distance = \DB::raw('(sqrt(pow(used_vehicles.latm - '.$cartesian['latm'].', 2) + pow(used_vehicles.lngm - '.$cartesian['lngm'].', 2)))');
        $distanceCol = \DB::raw('(sqrt(pow(used_vehicles.latm - '.$cartesian['latm'].', 2) + pow(used_vehicles.lngm - '.$cartesian['lngm'].', 2))) as distance');
        return $query->addSelect($distanceCol)->orderBy($distance);
    }
}