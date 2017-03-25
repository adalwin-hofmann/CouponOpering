<?php
namespace SOE\Api;

abstract class Api
{
    const DEFAULT_LIMIT = 10;
    const DEFAULT_PAGE = 0;

    protected function format($results)
    {
        ini_set('memory_limit', '1024M');
        $return = new \StdClass;
        $return->data = array();
        $return->stats = isset($results['stats']) ? $results['stats'] : array('returned' => 0, 'page' => 0, 'limit' => 0, 'total' => 0);
        if(is_array($results))
        {
            if(isset($results['objects']))
            {
                foreach($results['objects'] as $object)
                {
                    if(method_exists($object, 'toArray'))
                        $return->data[] = $object->toArray();
                    else
                        $return->data[] = $object;
                }
            }
            else
            {
                foreach($results as $object)
                {
                    if(method_exists($object, 'toArray'))
                        $return->data[] = $object->toArray();
                    else
                        $return->data[] = $object;
                }
            }
        }
        else
        {
            if(method_exists($results, 'toArray'))
                $return = $results->toArray();
            else
                $return = $results;
        }
        return json_encode($return);
    }
}
