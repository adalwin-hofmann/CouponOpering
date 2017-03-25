<?php namespace SOE\Repositories\Eloquent;

class ReportRepository extends BaseRepository implements \ReportRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'name',
        'link',
        'parent_id',
        'parent_link',
    );

    protected $model = 'Report';

    /**
     * Retrieve all child reports of the given parent.
     *
     * @param SOE\DB\Report
     * @return array
     */
    public function getByParent(\SOE\DB\Report $report = null)
    {
        return \SOE\DB\Report::where('parent_id', '=', ($report ? $report->id : 0))->get();
    }

}