<?php namespace SOE\Repositories\Eloquent;

class UserFavoriteRepository extends BaseRepository implements \UserFavoriteRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'favoritable_id',
        'favoritable_type',
        'user_id',
        'is_deleted',
    );

    protected $model = 'UserFavorite';

    public function findByUserTypeId($user_id, $type, $id)
    {
        return \SOE\DB\UserFavorite::on('mysql-write')
            ->where('user_id', $user_id)
            ->where('favoritable_type', $type)
            ->where('favoritable_id', $id)
            ->where('is_deleted', '0')
            ->first();
    }

    public function getFavoriteStats($type = null, $start = null, $end = null, $market = null)
    {
        $query = $this->query()->where('user_favorites.is_deleted', '0');
        if($type)
            $query->where('user_favorites.favoritable_type', $type);
        if($start)
            $query->where('user_favorites.created_at', '>=', $start);
        if($end)
            $query->where('user_favorites.created_at', '<=', $end);
        if($market)
        {
            $states = \SoeHelper::states();
            $abbr = array_search(strtoupper($market), $states['USA']['states']);
            if($abbr)
            {
                $query->join('users', 'user_favorites.user_id', '=', 'users.id')
                    ->where('users.state', $abbr);
            }
        }
        return $query->remember(60*12)->count();
    }

    public function getFranchiseFavorites($franchise_id, $start = null, $end = null)
    {
        $locations = \SOE\DB\Location::where('franchise_id', $franchise_id)->lists('id');
        $locations[] = 0;
        $query = $this->query()->where('user_favorites.is_deleted', '0')
            ->whereIn('favoritable_id', $locations)
            ->where('is_deleted', 0)
            ->where('user_favorites.favoritable_type', 'Location');
        if($start)
            $query->where('user_favorites.created_at', '>=', $start);
        if($end)
            $query->where('user_favorites.created_at', '<=', $end);

        return $query->remember(60*12)->count();
    }
}