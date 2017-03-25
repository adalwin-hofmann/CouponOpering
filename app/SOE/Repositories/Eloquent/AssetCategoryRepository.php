<?php namespace SOE\Repositories\Eloquent;

class AssetCategoryRepository extends BaseRepository implements \AssetCategoryRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'name',
        'parent_id',
    );

    protected $model = 'AssetCategory';

    /**
     * Retrieve asset categories by parent id
     *
     * @param int   $parent_id
     * @return array
     */
    public function getByParentId($parent_id = 0)
    {
        return $this->query()
                    ->byParentId($parent_id)
                    ->orderBy('name')
                    ->get();
    }
}