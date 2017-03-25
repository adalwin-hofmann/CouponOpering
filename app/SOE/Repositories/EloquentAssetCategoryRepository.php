<?php

class EloquentAssetCategoryRepository extends BaseEloquentRepository implements AssetCategoryRepository, RepositoryInterface
{
    protected $columns = array(
        'name',
        'parent_id'
    );

    protected $model = 'AssetCategory';

}