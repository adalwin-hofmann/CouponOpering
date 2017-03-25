<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class ProjectTag extends Eloquent 
{
    protected $table = 'project_tags';

    protected $softDelete = true;
    
    public static function boot()
    {
        parent::boot();

        ProjectTag::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    } 
}