<?php namespace SOE\Services\Validation;
 
use Illuminate\Support\ServiceProvider;
use SOE\Extensions\CustomValidator;
 
class ValidationServiceProvider extends ServiceProvider
{
 
    public function register(){}
 
    public function boot()
    {
        $this->app->validator->resolver(function($translator, $data, $rules, $messages)
        {
            return new CustomValidator($translator, $data, $rules, $messages);
        });
    }
 
}