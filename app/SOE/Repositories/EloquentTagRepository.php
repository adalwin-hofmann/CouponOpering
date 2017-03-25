<?php

class EloquentTagRepository extends BaseEloquentRepository implements TagRepository, RepositoryInterface
{
    protected $columns = array(
        'name',
    );

    protected $model = 'Tag';

}