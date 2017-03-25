<?php namespace SOE\Repositories\Eloquent;

class ReviewRepository extends BaseRepository implements \ReviewRepositoryInterface, \BaseRepositoryInterface
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
     * Get the total review scores for every offer.
     *
     * @return array
     */
    public function getReviewScores()
    {
        return \DB::table('reviews')->join('offers', 'reviews.reviewable_id', '=', 'offers.id')
                                    ->where('reviews.reviewable_type', '=', 'Offer')
                                    ->where('reviews.is_deleted', '=', '0')
                                    ->groupBy('reviews.reviewable_id')
                                    ->get(array(\DB::raw('SUM(reviews.upvotes) as score'), 'reviews.reviewable_id', 'offers.merchant_id'));
    }

    public function getOfferReviewVotes($offer_id)
    {
        return \SOE\DB\Review::on('mysql-write')
                                ->where('reviewable_id', '=', $offer_id)
                                ->where('reviewable_type', '=', 'Offer')
                                ->where('is_deleted', '=', '0')
                                ->get(array('user_id', 'upvotes'));
    }
}

