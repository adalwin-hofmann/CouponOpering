<?php

class EloquentReviewRepository extends BaseEloquentRepository implements ReviewRepository, RepositoryInterface
{
    protected $columns = array(
        'reviewable_id',
        'reviewable_type',
        'user_id',
        'content',
        'is_deleted',
        'rating',
        'upvotes',
        'nonmember_id',
    );

    protected $model = 'Review';

    /**
     * Delete this review.
     *
     * @return Review
     */
    public function deleteReview()
    {
        if($this->primary_key)
        {
            $this->is_deleted = 1;
            $this->save();

            if($this->reviewable_type == 'Location')
            {
                $review_id = $this->primary_key;
                Queue::push(function($job) use ($review_id)
                {
                    $review = SOE\DB\Review::find($review_id);
                    if(empty($review))
                    {
                        $job->delete();
                        return;
                    }
                    $rating = SOE\DB\Review::where('reviewable_type', '=', 'Location')
                                            ->where('reviewable_id', '=', $review->reviewable_id)
                                            ->where('is_deleted', '=', 0)
                                            ->avg('rating');
                    $location = SOE\DB\Location::find($review->reviewable_id);
                    if(!empty($location))
                    {
                        $location->rating = !empty($rating) ? $rating : 3.5;
                        $location->rating_count = $location->rating_count - 1;
                        $location->save();
                    }
                    $job->delete();
                });
            }

            return $this;
        }
    }

    /**
     * Vote on this review.
     *
     * @param  PersonInterface  $person The person voting on this review
     * @param  int              $vote The vote, 1 or -1
     * @return ReviewVote
     */
    public function vote(PersonInterface $person, $vote)
    {
        if($this->primary_key)
        {
            $query = SOE\DB\ReviewVote::where('is_deleted', '=', '0')->where('review_id', '=', $this->primary_key);
            if($person->getType() == 'User')
            {
                $query = $query->where('user_id', '=', $person->id);
            }
            else
            {
                $query = $query->where('nonmember_id', '=', $person->id);
            }
            $voted = $query->first();
            $review_id = $this->primary_key;

            if(!empty($voted))
            {
                $voted->is_deleted = 1;
                $voted->save();

                Queue::push(function($job) use ($review_id, $voted)
                {
                    $review = SOE\DB\Review::find($review_id);
                    if(empty($review))
                    {
                        $job->delete();
                        return;
                    }
                    $review->upvotes  = $review->upvotes - $voted->vote;
                    $review->save();
                    $job->delete();
                });
            }
            $review_vote = ReviewVote::blank();
            $review_vote->review_id = $this->primary_key;
            $review_vote->vote = $vote;
            if($person->getType() == 'User')
            {
                $review_vote->user_id = $person->id;
            }
            else
            {
                $review_vote->nonmember_id = $person->id;
            }
            $review_vote->save();

            Queue::push(function($job) use ($review_id, $vote)
            {
                $review = SOE\DB\Review::find($review_id);
                if(empty($review))
                {
                    $job->delete();
                    return;
                }
                $review->upvotes  = $review->upvotes + $vote;
                $review->save();
                $job->delete();
            });

            $upvotes = SOE\DB\ReviewVote::where('review_id', '=', $this->primary_key)->where('is_deleted', '=', 0)->where('vote', '=', '1')->count();
            $downvotes = SOE\DB\ReviewVote::where('review_id', '=', $this->primary_key)->where('is_deleted', '=', 0)->where('vote', '=', '-1')->count();
            $review_vote->upvotes = $upvotes;
            $review_vote->votes = $upvotes + $downvotes;
            return $review_vote;
        }
    }

    /***** API METHODS *****/

    /**
     * Delete this review given a review_id.
     *
     * @api
     *
     * @return mixed Formatted Review
     */
    public function apiDeleteReview()
    {
        $review_id = Input::get('review_id');
        $this->find($review_id);
        if($this->primary_key)
        {
            return $this->format($this->deleteReview());
        }
    }

    /**
     * Delete a review based on reviewable_id, reviewable_type, user_id.
     *
     * @api
     *
     * @return mixed Formatted Review
     */
    public function apiDeleteUserReview()
    {
        $reviewable_id = Input::get('reviewable_id');
        $reviewable_type = Input::get('reviewable_type');
        $user_id = Input::get('user_id');
        $review = SOE\DB\Review::where('reviewable_id', '=', $reviewable_id)
                                ->where('reviewable_type', '=', $reviewable_type)
                                ->where('user_id', '=', $user_id)
                                ->where('is_deleted', '=', '0')
                                ->first();
        if(!empty($review))
        {
            $this->find($review->id);
            return $this->format($this->deleteReview());
        }
    }

    /**
     * Delete a review based on reviewable_id, reviewable_type, nonmember_id.
     *
     * @api
     *
     * @return mixed Formatted Review
     */
    public function apiDeleteNonmemberReview()
    {
        $reviewable_id = Input::get('reviewable_id');
        $reviewable_type = Input::get('reviewable_type');
        $nonmember_id = Input::get('nonmember_id');
        $review = SOE\DB\Review::where('reviewable_id', '=', $reviewable_id)
                                ->where('reviewable_type', '=', $reviewable_type)
                                ->where('nonmember_id', '=', $nonmember_id)
                                ->where('is_deleted', '=', '0')
                                ->first();
        if(!empty($review))
        {
            $this->find($review->id);
            return $this->format($this->deleteReview());
        }
    }

}