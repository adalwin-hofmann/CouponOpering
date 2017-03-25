<?php namespace SOE\Repositories\Eloquent;

class UserViewRepository extends BaseRepository implements \UserViewRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'merchant_id',
        'user_id',
        'user_agent',
        'nonmember_id',
        'location_id',
        'franchise_id',
        'tracking_id',
        'url',
        'refer_id',
    );

    protected $model = 'UserView';

    /**
     * Get user view counts grouped by category.
     *
     * @param UserRepository    $user
     * @return array
     */
    public function getUserViewCountsByCategory(\SOE\DB\User $user)
    {
        $userviews = $this->getViewCountsByCategory('user', $user->id);

        return $userviews;
    }

    /**
     * Get nonmember view counts grouped by category.
     *
     * @param NonmemberRepository    $nonmember
     * @return array
     */
    public function getNonmemberViewCountsByCategory(\SOE\DB\Nonmember $nonmember)
    {
        $userviews = $this->getViewCountsByCategory('nonmember', $nonmember->id);

        return $userviews;
    }

    /**
     * Get the count of user views within the past 30 days for every merchant.
     *
     * @param int   $days The number of days to go back when getting the count.
     * @return array
     */
    public function getMerchantViewCounts($days = 30)
    {
        return \DB::table('user_views')->where('created_at', '>', \DB::raw("DATE_SUB(NOW(), INTERVAL ".$days." DAY)"))
                                    ->groupBy('merchant_id')
                                    ->get(array(\DB::raw('COUNT(*) as view_count'), 'merchant_id'));
    }

    /**
     * Get view counts grouped by category.
     *
     * @param string    $user_type
     * @param int       $user_id
     * @return array
     */
    protected function getViewCountsByCategory($user_type, $user_id)
    {
        $id_type = strtolower($user_type).'_id';
        $userviews = \DB::table('user_views')
                    ->select(\DB::raw("count(*) as views"),"categories.slug")
                    ->join('merchants', 'merchants.id', '=', 'user_views.merchant_id')
                    ->join('categories', 'merchants.category_id', '=', 'categories.id')
                    ->groupBy('category_id')
                    ->where($id_type, '=', $user_id)
                    ->orderBy('views','desc')
                    ->get();

        return $userviews;
    }

}