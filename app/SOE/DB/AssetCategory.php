<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

/**
* AssetCategory.
*
* A category for sales gallery assets.
*
* @author Caleb Beery <cbeery@saveoneverything.com>
*
*/
class AssetCategory extends Eloquent 
{
    protected $table = 'asset_categories';

    public static function boot()
    {
        parent::boot();

        AssetCategory::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }

    /**
     * Scopes the query to return only categories with the given parent id.
     *
     * @param Illuminate\Database\Query\Builder $query
     * @param int $parent_id
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeByParentId($query, $parent_id)
    {
        return $query->where('parent_id', $parent_id);
    }
}