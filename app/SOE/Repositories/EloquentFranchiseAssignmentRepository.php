<?php

class EloquentFranchiseAssignmentRepository extends BaseEloquentRepository implements FranchiseAssignmentRepository, RepositoryInterface
{
    protected $columns = array(
        'franchise_id',
        'user_id',
        'assignment_type_id',
    );

    protected $model = 'FranchiseAssignment';

}