<?php namespace SOE\Repositories\Eloquent;

class DistrictRepository extends BaseRepository implements \DistrictRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'name',
        'slug',
        'is_active',
    );

    protected $model = 'District';

    public function findBySlug($slug)
    {
        return $this->query()->where('slug', $slug)->where('is_active', '1')->first();
    }

}
