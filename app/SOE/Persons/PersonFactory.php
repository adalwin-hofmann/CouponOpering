<?php namespace SOE\Persons;

class PersonFactory
{
    public function make($id = null, $type = null)
    {
        if($id && $type)
        {
            $model = studly_case($type);
            $person = '\SOE\Persons\\'.$model.'Person';
            if(!class_exists($person))
                return false;
            return new $person($id);
        }

        $person = \Auth::check() ? \Auth::User() : \Auth::nonmember();
        if($person->id == 0)
            return false;

        $factory = new PersonFactory;
        $person = $factory->make($person->id, (\Auth::check() ? 'User' : 'Nonmember'));
        return $person;
    }
}