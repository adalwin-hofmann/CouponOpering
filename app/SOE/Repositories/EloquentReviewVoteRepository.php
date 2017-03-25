<?php

class EloquentReviewVoteRepository extends BaseEloquentRepository implements ReviewVoteRepository, RepositoryInterface
{
    protected $columns = array(
        'user_id',
        'review_id',
        'vote',
        'nonmember_id',
        'is_deleted',
    );

    protected $model = 'ReviewVote';

}