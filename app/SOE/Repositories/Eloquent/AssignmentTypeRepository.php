<?php namespace SOE\Repositories\Eloquent;

class AssignmentTypeRepository extends BaseRepository implements \AssignmentTypeRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'name',
    );

    protected $model = 'AssignmentType';

    /**
     * Retrieve an Assignment Type by name.
     *
     * @param string $name
     * @return SOE\DB\AssignmentType
     */
    public function findByName($name)
    {
        return \SOE\DB\AssignmentType::where('name', '=', $name)->first();
    }
}