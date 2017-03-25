<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class Category extends Eloquent 
{
    protected $softDelete = true;
    
    public static function boot()
    {
        parent::boot();

        Category::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    } 

    /** Force database to use table "categories".  */
    protected $table = 'categories';

	/**
     * A category may have a parent category
     *
     * @return Category
     */
    public function parent()
    {
        return $this->belongsTo('Category', 'parent_id');
    }

    /**
     * A category may have a child categories
     *
     * @return Category
     */
    public function children()
    {
        return $this->hasMany('Category', 'parent_id');
    }

    /**
     * A category may have many merchants
     *
     * @return Merchant
     */
    public function merchants()
    {
        return $this->hasMany('Merchant');
    }
}