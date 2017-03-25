<?php namespace SOE\Favoritables;

class FavoritableFactory
{
    public function make($type, $object_id)
    {
        $model = studly_case($type);
        $favoritable = '\SOE\Favoritables\\'.$model.'Favoritable';
        if(!class_exists($favoritable))
            return false;

        return new $favoritable($object_id);
    }
}