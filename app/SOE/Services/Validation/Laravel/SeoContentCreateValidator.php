<?php namespace SOE\Services\Validation\Laravel;
 
use SOE\Services\Validation\ValidableInterface;
 
class SeoContentCreateValidator extends LaravelValidator implements ValidableInterface {
 
    /**
     * Validation for creating a new SeoContent
     *
     * @var array
     */
    protected $rules = array(
        'page_url' => 'required',
        'content_type' => 'required'
    );
 
}