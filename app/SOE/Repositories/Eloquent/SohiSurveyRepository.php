<?php namespace SOE\Repositories\Eloquent;

class SohiSurveyRepository extends BaseRepository implements \SohiSurveyRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'user_name',
        'user_id',
        'business_name',
        'expected_completion',
        'rating',
        'work_begun',
        'completion_expected',
        'feedback',
        'type',
        'completed_on_time',
        'would_recommend'
    );

    protected $model = 'SohiSurvey';
}

