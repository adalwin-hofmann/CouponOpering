<?php namespace SOE\Printables;

class PrintableFactory
{
    public function make($type, $object_id, $params = array())
    {
        $pieces = explode('|', $type);
        $model = studly_case($pieces[0]);
        $printable = '\SOE\Printables\\'.$model.'Printable';
        if(!class_exists($printable))
            return false;

        $printable = new $printable($object_id, $pieces);

        foreach($params as $key => $value)
        {
            $printable->$key = $value;
        }

        return $printable->getModel() ? $printable : false;
    }
}