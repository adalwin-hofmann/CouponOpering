<?php namespace SOE\Favoritables;

class LocationFavoritable extends Favoritable implements FavoritableInterface
{
    public function __construct($object_id)
    {
        parent::__construct();
        $this->model = $this->repository->find($object_id);
        $this->favorites = \App::make('UserFavoriteRepositoryInterface');
    }

    public function favorite(\SOE\Persons\PersonInterface $person)
    {
        if($person->getForeignKey() != 'user_id')
            return false;

        $favorite = $this->favorites->create(array(
            'favoritable_type' => 'Location',
            'favoritable_id' => $this->model->id,
            'user_id' => $person->getId()
        ));
        return $favorite;
    }

    public function isFavorite(\SOE\Persons\PersonInterface $person)
    {
        if($person->getForeignKey() != 'user_id')
            return false;
        
        return $this->favorites->findByUserTypeId($person->getId(), 'Location', $this->model->id);
    }

    public function unfavorite(\SOE\Persons\PersonInterface $person)
    {
        if($person->getForeignKey() != 'user_id')
            return false;
        
        $favorite = $this->favorites->findByUserTypeId($person->getId(), 'Location', $this->model->id);
        if(!$favorite)
            return false;
        return $this->favorites->update($favorite->id, array('is_deleted' => 1));
    }
}