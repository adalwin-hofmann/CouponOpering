<?php

/**
*
* @api
*/

class BaseEloquentRepository
{
    protected $primary_key = null;
    public $attributes = array();
    protected $original = array();
    public $format = 'json';

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    /**
     * Dynamically set attributes on the model.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Get the array of model columns.
     *
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Get this objects model.
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Create a blank Object instance.
     *
     * @return $object
     */
    public function blank()
    {
        $model = $this->model;
        return App::make(strtolower($model));
    }

    /**
     * Create a new Object with given attributes.
     *
     * @param  array $attributes
     * @return $object
     */
    public function create(array $attributes = array())
    {
        $model = $this->model;

        if(empty($attributes))
        {
            return;
        }

        if(array_key_exists('id', $attributes))
        {
            return;
        }

        $object = App::make(strtolower($model));
        foreach($attributes as $key => $value)
        {
            $object->$key = $value;
        }
        $object->save();
        return $object;
    }

    /**
     * Retrieve this Object's attribute by key.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        $inAttributes = array_key_exists($key, $this->attributes);

        // If the key references an attribute, we can just go ahead and return the
        // plain attribute value from the model. This allows every attribute to
        // be dynamically accessed through the _get method without accessors.
        if ($inAttributes)
        {
            return $this->attributes[$key];
        }
    }

    /**
     * Determine if a given attribute is dirty.
     *
     * @param  string  $attribute
     * @return bool
     */
    public function isDirty($attribute)
    {
        return array_key_exists($attribute, $this->getDirty());
    }

    /**
     * Get the attributes that have been changed since last sync.
     *
     * @return array
     */
    public function getDirty()
    {
        $dirty = array();

        foreach ($this->attributes as $key => $value)
        {
            if ( ! array_key_exists($key, $this->original) or $value !== $this->original[$key])
            {
                $dirty[$key] = $value;
            }
        }

        return $dirty;
    }

    /**
     * Retrieve an Object by Id.
     *
     * @param   int     $object_id
     * @param   boolean $with_trashed Include soft deleted results.
     * @return mixed
     */
    public function find($object_id = null, $with_trashed = false)
    {
        $model = "SOE\DB\\".$this->model;
        if($with_trashed)
            $object = $model::withTrashed()->find($object_id);
        else
            $object = $model::find($object_id);
        if(!empty($object))
        {
            $repo_model = $this->model;
            $repos = $repo_model::blank();
            $repos = $repos->createFromModel($object);
            $this->primary_key = $object->id;
            $this->attributes = $object->getAttributes();
            $this->original = $object->getAttributes();
            return $repos;
            /*$this->primary_key = $object->id;
            $this->attributes = $object->getAttributes();
            $this->original = $object->getAttributes();
            return $this;*/
        }
        return;
    }

    /**
     * Retrieve an array of Objects by an array of filters.
     *
     * @param  array  $filters
     * @param  int    $limit
     * @param  int    $page
     * @return array
     */
    public function get(array $filters = array(), $limit = 0, $page = 0, $with_trashed = false)
    {
        $model = "SOE\DB\\".$this->model;
        $query = $model::query();
        if($with_trashed)
            $query = $query->withTrashed();
        $query = $this->parseFilters($query, $filters);
        $stats = $this->getStats(clone $query, $limit, $page);
        $query = $this->paginator($query, $limit, $page);
        $objects = $query->remember(Config::get('soe.cache', 60*60*24))->get();
        $stats['stats']['returned'] = count($objects);
        $return = array('objects' => array());
        foreach($objects as $o)
        {
            $object = App::make(strtolower($this->model));
            $object->attributes = $o->getAttributes();
            $object->original = $o->getAttributes();
            $object->primary_key = $o->id;
            $return['objects'][] = $object;
        }
        $return = array_merge($return, $stats);
        return $return;
    }

    /**
     * Update the given Object or create a new Object if no primary key is set.
     *
     * @return mixed
     */
    public function save()
    {
        $model = "SOE\DB\\".$this->model;
        $object = $model::withTrashed()->find($this->primary_key);
        if(empty($object))
        {
            $object = new $model;
        }

        foreach($this->attributes as $key => $value)
        {
            if($this->isDirty($key))
            {
                /*if($key == 'password')
                {
                    $value = Hash::make($value);
                }*/
                if(in_array($key, $this->columns))
                {
                    $object->$key = $value;
                }
            }
        }
        $object->save();
        $this->attributes = $object->getAttributes();
        $this->original = $this->attributes;
        $this->primary_key = $object->id;
        return $this;
    }

    /**
     * Update this Object's original attributes to match the current attributes.
     *
     * @return void
     */
    public function sync()
    {
        $this->original = $this->attributes;
    }

    /**
     * Populate a blank repository from an Eloquent Model.
     *
     * @return mixed A populated repository instance.
     */
    public function createFromModel($model)
    {
        if($this->primary_key)
            return;
        $class = get_class($model);
        $aPieces = explode('\\', $class);
        if(!isset($aPieces[2]))
            return;
        if($aPieces[2] != $this->model)
            return;
        $this->primary_key = $model->id;
        $this->attributes = $model->getAttributes();
        $this->original = $model->getAttributes();
        return $this;
    }

    /**
     * Delete this object.
     *
     * @return mixed
     */
    public function delete()
    {
        $model = "SOE\DB\\".$this->model;
        $object = $model::find($this->primary_key);
        if(!empty($object))
        {
            $object->delete();
            return $object;
        }
        return;
    }

    /**
     * Restore this object.
     *
     * @return mixed
     */
    public function restore()
    {
        $model = "SOE\DB\\".$this->model;
        $object = $model::withTrashed()->find($this->primary_key);
        if(!empty($object))
        {
            $object->restore();
            return $object;
        }
        return;
    }

    /***** API METHODS *****/

    /**
     * Create a new Object with given attributes.
     *
     * @api
     *
     * @return mixed $object
     */
    public function apiCreate()
    {
        $attributes = Input::all();

        if(empty($attributes) || !is_array($attributes))
        {
            return;
        }

        if(array_key_exists('id', $attributes))
        {
            return;
        }

        if(array_key_exists('format', $attributes))
        {
            $this->format = $attributes['format'];
            unset($attributes['format']);
        }

        $this->create($attributes);
        return $this->format($this);
    }

    /**
     * Retrieve an Object by Id.
     *
     * @api
     *
     * @return mixed
     */
    public function apiFind()
    {
        $object_id = Input::get('id', null);
        $with_trashed = Input::get('with_trashed', false);
        $object = $this->find($object_id, $with_trashed);
        $includes = Input::get('includes', '');
        $aIncludes = explode(',', $includes);
        $methods = get_class_methods($this);
        foreach($aIncludes as $include)
        {
            if(in_array($include, $methods))
            {
                $object->$include = $object->$include();
            }
        }

        return $this->format($object);
    }

    /**
     * Retrieve an array of Objects by the given filters of filters.
     *
     * @api
     *
     * @return array
     */
    public function apiGet()
    {
        $filters = $this->getFilters();
        $limit = Input::get('limit', 0);
        $page = Input::get('page', 0);

        $return = $this->get($filters, $limit, $page);

        return $this->format($return);
    }

    /**
     * Update an Object with the given attributes
     *
     * @api
     *
     * @return mixed $object
     */
    public function apiUpdate()
    {
        $this->find(Input::get('id'));
        if($this->primary_key)
        {
            $attributes = Input::all();
            foreach($attributes as $key => $value)
            {
                $this->attributes[$key] = $value;
            }
            $this->save();

            return $this->format($this);
        }
        return;
    }

    /**
     * List the available api methods for this class.
     *
     * @api
     *
     * @return mixed $object
     */
    public function apiList()
    {
        $ref = new ReflectionClass($this);
        $methods = array();
        foreach($ref->getMethods() as $method)
        {
            if(stristr($method->getDocComment(), '@api'))
            {
                $methods[] = $method->name;
            }
        }
        return $this->format($methods);
    }

    /***** END API METHODS *****/

    /**
     * Parse an array of filters to apply to the query.
     *
     * @param mixed  $query
     * @param array  $filters
     * @return mixed $query
     */
    protected function parseFilters($query, $filters)
    {
        foreach($filters as $filter)
        {
            $filter['type'] = array_key_exists('type', $filter) ? $filter['type'] : null;
            switch ($filter['type']) {
                case 'where':
                    $query = $query->where($filter['key'], $filter['operator'], $filter['value']);
                    break;
                case 'orWhere':
                    $query = $query->orWhere($filter['key'], $filter['operator'], $filter['value']);
                    break;
                case 'whereIn':
                    $query = $query->whereIn($filter['key'], $filter['value']);
                    break;
                case 'whereNotIn':
                    $query = $query->whereNotIn($filter['key'], $filter['value']);
                    break;
                case 'whereNull':
                    $query = $query->whereNull($filter['key']);
                    break;
                case 'whereNotNull':
                    $query = $query->whereNotNull($filter['key']);
                    break;
                case 'orderBy':
                    $query = $query->orderBy($filter['key'], isset($filter['value']) ? $filter['value'] : 'asc');
                    break;
                default:
                    $query = $query->where($filter['key'], $filter['operator'], $filter['value']);
                    break;
            }
        }

        return $query;
    }

    /**
     * Paginate a query.
     *
     * @param mixed  $query
     * @param int    $limit
     * @param int    $page
     * @return mixed $query
     */
    protected function paginator($query, $limit = 0, $page = 0)
    {
        if($limit)
            $query = $query->take($limit);
        if($page)
            $query = $query->skip($limit * $page);

        return $query;
    }

    /**
     * Get pagination stats for the given query.
     *
     * @param mixed $query
     * @param int   $limit
     * @param int   $page
     * @return array
     */
    protected function getStats($query, $limit, $page, $is_grouped = false)
    {
        $stats = array('stats' => array());
        if($is_grouped)
        {
            $objects = $query->get(array(DB::raw('1')));
            $stats['stats']['total'] = count($objects);
        }
        else
        {
            $stats['stats']['total'] = $query->count();
        }
        $stats['stats']['page'] = $page;
        $stats['stats']['take'] = $limit;
        return $stats;
    }

    /**
     * Format data for the response.
     *
     * @param mixed  $data
     * @return mixed $data
     */
    public function format($data)
    {
        $this->format = $this->format ? $this->format : Input::get('format', '');
        switch ($this->format)
        {
            case 'json':
                $return = new StdClass;
                if(is_array($data) && isset($data['objects']))
                {
                    $return->data = array();
                    foreach($data['objects'] as &$object)
                    {
                        $object = $this->checkForRepo($object);
                        $return->data[] = $object->attributes;
                    }
                    $return->stats = $data['stats'];
                }
                else if($data instanceof BaseEloquentRepository)
                {
                    $data = $this->checkForRepo($data);
                    $return = $data->attributes;
                }
                else
                {
                    $return->data = $data;
                }
                return json_encode($return);
                break;

            default:
                return $data;
                break;
        }
    }

    /**
     * Recursively search through an objects attributes to convert all Repositories to an array of attributes.
     *
     * @param mixed $object
     * @return mixed $object
     */
    protected function checkForRepo($object)
    {
        foreach($object->attributes as &$attr)
        {
            if($attr instanceof BaseEloquentRepository)
            {
                $attr = $this->checkForRepo($attr);
                $attr = $attr->attributes;
            }
        }
        return $object;
    }

    /**
     * Retrieve filters from URL parameters.
     *
     * @return array $filters
     */
    protected function getFilters()
    {
        $inputs = Input::all();
        $filters = array();
        foreach($inputs as $key => $value)
        {
            $pieces = explode('|', $key);
            switch (strtolower($pieces[0]))
            {
                case 'where':
                    if(count($pieces > 1))
                        $filters[] = array('key' => $pieces[1], 'operator' => '=', 'value' => $value);
                    break;
                case 'wherenot':
                    if(count($pieces > 1))
                        $filters[] = array('key' => $pieces[1], 'operator' => '!=', 'value' => $value);
                    break;
                case 'orwhere':
                    if(count($pieces > 1))
                        $filters[] = array('type' => 'orWhere', 'key' => $pieces[1], 'operator' => '=', 'value' => $value);
                    break;
                case 'orwherenot':
                    if(count($pieces > 1))
                        $filters[] = array('type' => 'orWhere', 'key' => $pieces[1], 'operator' => '!=', 'value' => $value);
                    break;
                case 'wherelike':
                    if(count($pieces > 1))
                        $filters[] = array('key' => $pieces[1], 'operator' => 'LIKE', 'value' => $value);
                    break;
                case 'wherenotlike':
                    if(count($pieces > 1))
                        $filters[] = array('key' => $pieces[1], 'operator' => 'NOT LIKE', 'value' => $value);
                    break;
                case 'orwherelike':
                    if(count($pieces > 1))
                        $filters[] = array('type' => 'orWhere', 'key' => $pieces[1], 'operator' => 'LIKE', 'value' => $value);
                    break;
                case 'orwherenotlike':
                    if(count($pieces > 1))
                        $filters[] = array('type' => 'orWhere', 'key' => $pieces[1], 'operator' => 'NOT LIKE', 'value' => $value);
                    break;
                case 'wherein':
                    if(count($pieces > 1))
                    {
                        $values = explode(',', $value);
                        $aValues = array();
                        foreach($values as $v)
                        {
                            $aValues[] = $v;
                        }
                        $filters[] = array('type' => 'whereIn', 'key' => $pieces[1], 'value' => $aValues);
                    }
                    break;
                case 'wherenotin':
                    if(count($pieces > 1))
                    {
                        $values = explode(',', $value);
                        $aValues = array();
                        foreach($values as $v)
                        {
                            $aValues[] = $v;
                        }
                        $filters[] = array('type' => 'whereNotIn', 'key' => $pieces[1], 'value' => $aValues);
                    }
                    break;
                case 'wherenull':
                    if(count($pieces > 1))
                        $filters[] = array('type' => 'whereNull', 'key' => $pieces[1]);
                    break;
                case 'wherenotnull':
                    if(count($pieces > 1))
                        $filters[] = array('type' => 'whereNotNull', 'key' => $pieces[1]);
                    break;
            }
        }

        return $filters;
    }

    /**
     * Truncates a pieces of text to a given length while preserving html tags.
     *
     * @param string    $text The text to be truncated.
     * @param int       $length The length of the trucated string, default 200.
     * @return string $text
     */
    protected function truncate($text, $length = 200)
    {
        $ending = '...';
        if(mb_strlen(preg_replace('/<.*?>/', '', $text)) <= $length)
        {
            return $text;
        }
        $totalLength = mb_strlen(strip_tags($ending));
        $openTags = array();
        $truncate = '';

        preg_match_all('/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $text, $tags, PREG_SET_ORDER);
        foreach($tags as $tag)
        {
            if(!preg_match('/img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param/s', $tag[2]))
            {
                if(preg_match('/<[\w]+[^>]*>/s', $tag[0]))
                {
                    array_unshift($openTags, $tag[2]);
                }
                else if (preg_match('/<\/([\w]+)[^>]*>/s', $tag[0], $closeTag))
                {
                    $pos = array_search($closeTag[1], $openTags);
                    if($pos !== false)
                    {
                        array_splice($openTags, $pos, 1);
                    }
                }
            }
            $truncate .= $tag[1];

            $contentLength = mb_strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $tag[3]));
            if($contentLength + $totalLength > $length)
            {
                $left = $length - $totalLength;
                $entitiesLength = 0;
                if(preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $tag[3], $entities, PREG_OFFSET_CAPTURE))
                {
                    foreach($entities[0] as $entity)
                    {
                        if($entity[1] + 1 - $entitiesLength <= $left)
                        {
                            $left--;
                            $entitiesLength += mb_strlen($entity[0]);
                        }
                        else
                        {
                            break;
                        }
                    }
                }

                $truncate .= mb_substr($tag[3], 0 , $left + $entitiesLength);
                break;
            }
            else
            {
                $truncate .= $tag[3];
                $totalLength += $contentLength;
            }
            if($totalLength >= $length)
            {
                break;
            }
        }

        $truncate .= $ending;

        foreach($openTags as $tag)
        {
            $truncate .= '</'.$tag.'>';
        }
        return $truncate;
    }

}
