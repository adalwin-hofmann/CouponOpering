<?php

class EloquentUserFavoriteRepository extends BaseEloquentRepository implements UserFavoriteRepository, RepositoryInterface
{
    protected $columns = array(
        'favoritable_id',
        'favoritable_type',
        'user_id',
        'is_deleted',
    );

    protected $model = 'UserFavorite';

    public function deleteFavorite(FavoritableInterface $favoritable, PersonInterface $person)
    {
        $fav = SOE\DB\UserFavorite::where('favoritable_type', '=', 'SOE\\DB\\'.$favoritable->getModel())
                            ->where('favoritable_id', '=', $favoritable->id)
                            ->where('user_id', '=', $person->id)
                            ->where('is_deleted', '=', '0')
                            ->first();
        if(!empty($fav))
        {
            $fav->is_deleted = 1;
            $fav->save();
            $favorite = UserFavorite::blank();
            $favorite = $favorite->createFromModel($fav);
            return $favorite;
        }
    }

    public function apiDeleteFavorite()
    {
        $user_id = Input::get('user_id');
        $user = User::find($user_id);
        $type = Input::get('favoritable_type', 'location');
        $type = studly_case($type);
        $id = Input::get('favoritable_id', 0);
        $model = $type;
        $favoritable = $model::find($id);
        if(empty($favoritable))
            return;
        return $this->format($this->deleteFavorite($favoritable, $user));
    }
}