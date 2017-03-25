<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class VehicleStyle extends Eloquent implements \ViewableInterface
{
    /** Force database to use table "vehicle_models".  */
    protected $table = 'vehicle_styles';

    public static function boot()
    {
        parent::boot();

        VehicleStyle::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }

    /**
     * Retrieve the active incentives for this style.
     */
    public function incentives()
    {
        return $this->belongsToMany('\SOE\DB\VehicleIncentive', 'vehicle_incentive_styles')
                    ->where('vehicle_incentives.is_active', '=', '1')
                    ->where('vehicle_incentives.starts_at', '<=', \DB::raw('NOW()'))
                    ->where('vehicle_incentives.expires_at', '>=', \DB::raw('NOW()'));
    }

    public function assets()
    {
        return $this->hasMany('\SOE\DB\VehicleAsset', 'style_id');
    }

    public function displayImage()
    {
        return $this->hasMany('\SOE\DB\VehicleAsset', 'style_id')->where('shot_type', '=', 'FQ')->orderBy('pic_size','desc')->orderBy('path');
    }

    public function reviews()
    {
        return $this->hasMany('\SOE\DB\Reviews', 'reviewable_id')->where('reviewable_type', '=', 'SOE\DB\VehicleStyle');;
    }

    public function quotes()
    {
        return $this->hasMany('\SOE\DB\AutoQuote', 'quoteable');
    }

    public function vehicleMake()
    {
        return $this->belongsTo('\SOE\DB\VehicleMake', 'make_id');
    }

    /**
     * Scopes the query to only grab vehicles with active incentives.
     *
     * @param Illuminate\Database\Query\Builder $query
     * @return Illuminate\Database\Query\Builder $query
     */
    public function scopeActiveIncentives($query)
    {
        $query->join('vehicle_incentive_styles', 'vehicle_styles.id', '=', 'vehicle_incentive_styles.vehicle_style_id')
            ->join('vehicle_incentives', 'vehicle_incentive_styles.vehicle_incentive_id', '=', 'vehicle_incentives.id')
            ->where('vehicle_incentives.is_active', '=', '1')
            ->where('vehicle_incentives.starts_at', '<=', \DB::raw('NOW()'))
            ->where('vehicle_incentives.expires_at', '>=', \DB::raw('NOW()'));
        // Doing this eloquently is too slow for some reason...
        /*return $query->whereHas('incentives', function($query)
            {
                $query->where('is_active', '=', '1')
                        ->where('starts_at', '<=', \DB::raw('NOW()'))
                        ->where('expires_at', '>=', \DB::raw('NOW()'));
            });*/
    }

    /**
     * Scopes the query to only grab only one style record per model.
     *
     * @param Illuminate\Database\Query\Builder $query
     * @return Illuminate\Database\Query\Builder $query
     */
    public function scopeDistinctModel($query)
    {
        return $query->groupBy('vehicle_styles.model_id');
    }

    /**
     * Scopes the query to only grab only one style record per model.
     *
     * @param Illuminate\Database\Query\Builder $query
     * @return Illuminate\Database\Query\Builder $query
     */
    public function scopeDistinctYear($query)
    {
        return $query->groupBy('vehicle_styles.model_year_id');
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

        return $query->whereIn('vehicle_styles.year', $years);
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
        $column = is_numeric($makes[0]) ? 'vehicle_styles.make_id' : 'vehicle_styles.make_slug';
        
        return $query->whereIn($column, $makes);
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
        $column = 'vehicle_styles.model_slug';//is_numeric($models[0]) ? 'vehicle_styles.model_id' : 'vehicle_styles.model_slug';

        return $query->whereIn($column, $models);
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
            $query->where('vehicle_styles.price', '>=', $min);
        if($max)
            $query->where('vehicle_styles.price', '<=', $max);
        if($max || $min)
            $query->where('vehicle_styles.price', '!=', 0);
        return $query;
    }

    public function view(\PersonInterface $viewer)
    {
        if($this->id && $viewer->id)
        {
            $viewerType = $viewer->getType();
            $foreign = $viewer->getForeignKey();
            $view = new \SOE\DB\UserImpression;
            $view->user_id = 0;
            $view->nonmember_id = 0;
            $view->$foreign = $viewer->id;
            $view->vehicle_style_id = $this->id;
            $view->vehicle_model_id = $this->model_id;
            $view->save();

            return $view;
        }

    }
}