<?php namespace SOE\Repositories\Eloquent;

use Illuminate\Support\MessageBag;

class BaseRepository
{
    protected $format = 'json';
    protected $defaultConn = 'mysql';
    protected $validators = array();

    /**
     * Construct
     *
     */
    public function __construct()
    {
        $this->errors = new MessageBag;
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
     * @return mixed $object
     */
    public function blank($attributes = array())
    {
        $model = "SOE\DB\\".$this->model;
        $blank = new $model;
        $blank->setConnection($this->defaultConn);
        foreach($attributes as $key => $value)
        {
            if(in_array($key, $this->columns) && $key != 'id')
                $blank->$key = $value;
        }
        return $blank;
    }

    /**
     * Create a new Object with given attributes.
     *
     * @param  array $attributes
     * @return mixed $object
     */
    public function create(array $attributes = array())
    {
        if(empty($attributes))
        {
            return;
        }

        if(array_key_exists('id', $attributes))
        {
            unset($attributes['id']);
        }
        
        $object = $this->blank();
        foreach($attributes as $key => $value)
        {
            if(in_array($key, $this->columns))
                $object->$key = $value;
        }
        $object->save();
        return $object;
    }

    /**
     * Create many new Objects from the given array of attribute sets.
     *
     * @param array $rows
     * @return mixed
     */
    public function createMany(array $rows = array())
    {
        if(empty($rows))
            return;

        $objects = array();
        foreach($rows as $row)
        {
            $objects[] = $this->create($row);
        }

        return $objects;
    }

    /**
     * Update an object based on the given id and array of attributes.
     *
     * @param integer   $id
     * @param array     $attributes
     * @param mixed     The modified object.
     */
    public function update($id, array $attributes = array())
    {
        $object = $this->find($id);
        if(empty($object))
            return;
        if(empty($attributes))
            return $object;

        if(array_key_exists('id', $attributes))
        {
            unset($attributes['id']);
        }
        
        foreach($attributes as $key => $value)
        {
            if(in_array($key, $this->columns))
                $object->$key = $value;
        }
        $object->save();
        return $object;
    }

    /**
     * Retrieve an Object by Id.
     *
     * @param   int     $object_id
     * @param   boolean $with_trashed Include soft deleted results.
     * @return mixed
     */
    public function find($object_id = null, $with_trashed = false, $write_db = false)
    {
        $db = $write_db ? 'mysql-write' : $this->defaultConn;
        $model = "SOE\DB\\".$this->model;
        if($with_trashed)
            $object = $model::on($db)->withTrashed()->find($object_id);
        else
            $object = $model::on($db)->find($object_id);
        if(!empty($object))
        {
            return $object;
        }
        return;
    }

    /**
     * Register Validators
     *
     * @param string $name
     * @param Validible $validator
     */
    public function registerValidator($name, $validator)
    {
        $this->validators[$name] = $validator;
    }

    /**
     * Check to see if the input data is valid
     *
     * @param array $data
     * @return boolean
     */
    public function isValid($name, array $data)
    {
        if( $this->validators[$name]->with($data)->passes() )
        {
            return true;
        }

        $this->errors = $this->validators[$name]->errors();
        return false;
    }

    /**
     * Return the errors
     *
     * @return Illuminate\Support\MessageBag
     */
    public function errors()
    {
        return $this->errors;
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
        $query = $model::on($this->defaultConn);
        if($with_trashed)
            $query = $query->withTrashed();
        $query = $this->parseFilters($query, $filters);
        $stats = $this->getStats(clone $query, $limit, $page);
        $query = $this->paginator($query, $limit, $page);
        $objects = $query->remember(Config::get('soe.cache', 60*60*24))->get();
        $stats['stats']['returned'] = count($objects);
        $return = array_merge($objects, $stats);
        return $return;
    }

    /**
     * Begin querying the model.
     *
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query() {
        return $this->callModelStatic(__FUNCTION__, func_get_args());
    }

    /**
     * Get all of the models from the database.
     *
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all($columns = array('*')) {
        return $this->callModelStatic(__FUNCTION__, func_get_args());
    }

    /**
     * Being querying a model with eager loading.
     *
     * @param  array|string  $relations
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function with($relations) {
        return $this->callModelStatic(__FUNCTION__, func_get_args());
    }

    /**
     * Destroy the models for the given IDs.
     *
     * @param  array|int  $ids
     * @return int
     */
    public function destroy($ids) {
        return $this->callModelStatic(__FUNCTION__, func_get_args());
    }

    /**
     * Call a model method statically with the specified parameters
     *
     * @param string $methodName
     * @param array $parameters
     * @return mixed
     */
    protected function callModelStatic($methodName, array $parameters) {
        return call_user_func_array("\\SOE\\DB\\".$this->model."::{$methodName}", $parameters);
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
        $limit = \Input::get('limit', 0);
        $page = \Input::get('page', 0);

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
        $object = $this->find(\Input::get('id'));
        if(!empty($object))
        {
            $attributes = \Input::all();
            foreach($attributes as $key => $value)
            {
                $object->$key = $value;
            }
            $object->save();

            return $this->format($object);
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
        $ref = new \ReflectionClass($this);
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
            $objects = $query->get(array(\DB::raw('1')));
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
    protected function format($data)
    {
        $this->format = $this->format ? $this->format : \Input::get('format', '');
        switch ($this->format)
        {
            case 'json':
                return $data->toJson();
                break;
            case 'array':
                return $data->toArray();
            default:
                return $data;
                break;
        }
    }

    /**
     * Retrieve filters from URL parameters.
     *
     * @return array $filters
     */
    protected function getFilters()
    {
        $inputs = \Input::all();
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