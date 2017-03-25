<?php namespace SOE\Viewables;

class ViewableFactory
{
    public function make($type, $object_id)
    {
        $pieces = explode('|', $type);
        $model = studly_case($pieces[0]);
        $viewable = '\SOE\Viewables\\'.$model.'Viewable';
        if(!class_exists($viewable))
            return false;

        $viewable = new $viewable($object_id, $pieces);

        return $viewable->getModel() ? $viewable : false;
    }
}