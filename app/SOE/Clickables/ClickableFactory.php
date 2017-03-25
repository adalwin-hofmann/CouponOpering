<?php namespace SOE\Clickables;

class ClickableFactory
{
    public function make($type, $object_id)
    {
        $pieces = explode('|', $type);
        $model = studly_case($pieces[0]);
        $clickable = '\SOE\Clickables\\'.$model.'Clickable';
        if(!class_exists($clickable))
            return false;

        $clickable = new $clickable($object_id, $pieces);

        return $clickable->getModel() ? $clickable : false;
    }
}