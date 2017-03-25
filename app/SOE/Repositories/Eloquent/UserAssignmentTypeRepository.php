<?php namespace SOE\Repositories\Eloquent;

class UserAssignmentTypeRepository extends BaseRepository implements \UserAssignmentTypeRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'user_id',
        'assignment_type_id'
    );

    protected $model = 'UserAssignmentType';
}