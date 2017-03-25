<?php namespace SOE\Services\Validation\Laravel;
 
use SOE\Services\Validation\ValidableInterface;
 
class AutoQuoteCreateValidator extends LaravelValidator implements ValidableInterface {
 
    /**
     * Validation for creating a new User
     *
     * @var array
     */
    protected $rules = array(
        'quoteable_id' => 'required',
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|email',
        'phone' => 'required',
        'zip' => 'required|zipcode'
    );
 
}