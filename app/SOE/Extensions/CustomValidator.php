<?php namespace SOE\Extensions;
 
class CustomValidator extends \Illuminate\Validation\Validator
{
 
    /**
     * Custom validation rule for zipcodes.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $parameters
     * @return boolean
     */ 
    public function validateZipcode($attribute, $value, $parameters)
    {
        $zipRepository = \App::make('ZipcodeRepositoryInterface');
        $zipcode = $zipRepository->findByZipcode($value);
        if( ! empty($zipcode) )
        {
            return true;
        }
   
        return false;
    }
}