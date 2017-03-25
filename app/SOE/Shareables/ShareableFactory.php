<?php namespace SOE\Shareables;

class ShareableFactory
{
    public function make($type, $object_id)
    {
        $model = studly_case($type);
        $favoritable = '\SOE\Shareables\\'.$model.'Shareable';
        if(!class_exists($favoritable))
            return false;

        return new $favoritable($object_id);
    }
}