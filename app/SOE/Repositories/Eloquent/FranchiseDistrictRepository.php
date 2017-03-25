<?php namespace SOE\Repositories\Eloquent;

class FranchiseDistrictRepository extends BaseRepository implements \FranchiseDistrictRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'franchise_id',
        'district_id',
    );

    protected $model = 'FranchiseDistrict';

}
