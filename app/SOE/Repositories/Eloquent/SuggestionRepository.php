<?php namespace SOE\Repositories\Eloquent;

class SuggestionRepository extends BaseRepository implements \SuggestionRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'type',
        'category_id',
        'business',
        'city',
        'state',
        'address1',
        'address2',
        'zipcode',
        'user_id',
        'nonmember_id',
    );

    protected $model = 'Suggestion';
}

