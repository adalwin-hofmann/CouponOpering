<?php namespace SOE\Repositories\Eloquent;

class CompanyEventRepository extends BaseRepository implements \CompanyEventRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'id',
        'name',
        'slug',
        'date',
        'description',
        'path',
        'end_datetime'
    );

    protected $model = 'CompanyEvent';

    public function findActiveEvents()
    {
        return $this->query()
        	->where('date', '>', date("Y-m-d H:i:s"))
            ->get();
    }

    public function getOrderDate()
    {
        return $this->query()
            ->orderBy('date','desc')
            ->get();
    }

    public function searchByName($query)
    {
        return $this->query()
            ->where('name', 'LIKE', $query.'%')
            ->get();
    }

    public function getBySlug($slug)
    {
        return $this->query()
            ->where('slug', '=', $slug)
            ->first();
    }
}