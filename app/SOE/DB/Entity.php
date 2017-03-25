<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class Entity extends Eloquent 
{
	/** Force database to use table "entities".  */
    protected $table = 'entities';

    protected $softDelete = true;

    public static function boot()
    {
        parent::boot();

        Entity::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }   

    public function scopeDealers($query)
    {
        $dealers = \SOE\DB\Category::where('name', '=', 'Auto Dealers')->remember(\Config::get('soe.cache', 60*24))->first();
        return $query->where('entities.subcategory_id', '=', ($dealers ? $dealers->id : 0));
    }  

    public function scopeActive($query)
    {
        return $query->where(function($query)
                    {
                        $query->where('entities.starts_year', '=', date('Y'));
                        $query->where('entities.starts_day', '<=', (date('z')+1));
                        $query->orWhere('entities.starts_year', '<', (date('Y')));
                    })
                    ->where(function($query)
                    {
                        $query->where('entities.expires_year', '=', date('Y'));
                        $query->where('entities.expires_day', '>=', (date('z')+1));
                        $query->orWhere('entities.expires_year', '>=', (date('Y')+1));
                    })
                    ->where('entities.is_active', '1')
                    ->where('entities.location_active', '1')
                    ->where('entities.franchise_active', '1');
    }

    public function scopeOrderByDistance($query, $cartesian)
    {
        $distance = \DB::raw('(sqrt(pow(entities.latm - '.$cartesian['latm'].', 2) + pow(entities.lngm - '.$cartesian['lngm'].', 2)))');
        return $query->orderBy($distance);
    }

    public function scopeLimitByBox($query, $cartesian, $miles)
    {
        return $query->where('entities.latm', '>', \DB::raw('('.$cartesian['latm'].' - '.($miles*1600).')'))
                    ->where('entities.latm', '<', \DB::raw('('.$cartesian['latm'].' + '.($miles*1600).')'))
                    ->where('entities.lngm', '>', \DB::raw('('.$cartesian['lngm'].' - '.($miles*1600).')'))
                    ->where('entities.lngm', '<', \DB::raw('('.$cartesian['lngm'].' + '.($miles*1600).')'));
    }

    public function scopeDemo($query, $show_demo)
    {
        if($show_demo)
            return $query;
        else
            return $query->where('entities.is_demo', '0')
                        ->where('entities.franchise_demo', '0');
    }
}