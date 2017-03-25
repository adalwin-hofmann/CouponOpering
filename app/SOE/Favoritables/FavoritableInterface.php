<?php namespace SOE\Favoritables;

interface FavoritableInterface
{
    public function __construct($object_id);
    public function favorite(\SOE\Persons\PersonInterface $person);
    public function isFavorite(\SOE\Persons\PersonInterface $person);
    public function unfavorite(\SOE\Persons\PersonInterface $person);
}