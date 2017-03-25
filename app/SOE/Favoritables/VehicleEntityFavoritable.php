<?php namespace SOE\Favoritables;

class VehicleEntityFavoritable extends Favoritable implements FavoritableInterface
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
            'favoritable_type' => 'VehicleEntity-'.$this->model->vendor,
            'favoritable_id' => $this->model->vendor_inventory_id,
            'user_id' => $person->getId()
        ));
        return $favorite;
    }

    public function isFavorite(\SOE\Persons\PersonInterface $person)
    {
        if($person->getForeignKey() != 'user_id' || !$person->exists())
            return false;
        
        return $this->favorites->findByUserTypeId($person->getId(), 'VehicleEntity', $this->model->id);
    }

    public function unfavorite(\SOE\Persons\PersonInterface $person)
    {
        if($person->getForeignKey() != 'user_id')
            return false;
        
        $favorite = $this->favorites->findByUserTypeId($person->getId(), 'VehicleEntity', $this->model->id);
        if(!$favorite)
            return false;
        return $this->favorites->update($favorite->id, array('is_deleted' => 1));
    }
}