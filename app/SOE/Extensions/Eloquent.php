<?php namespace SOE\Extensions;

class Eloquent extends \Illuminate\Database\Eloquent\Model
{
    /**
     * Scope the current query to the given pagination constraints.
     *
     * @param Illuminate\Database\Query\Builder $query
     * @param int $page
     * @param int $limit
     * @return Illuminate\Database\Query\Builder $query
     */
    public function scopeSetPagination($query, $page, $limit)
    {
        if($limit)
        {
            $query->skip($page*$limit)->take($limit);
        }
        return $query;
    }
}