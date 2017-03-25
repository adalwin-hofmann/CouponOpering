<?php
namespace SOE\Api;

class Entity extends Api implements ApiInterface, EntityApi
{
    public function __construct(
        \EntityRepositoryInterface $repository,
        \UserRepositoryInterface $userRepository
    )
    {
        $this->repository = $repository;
        $this->userRepository = $userRepository;
    }

    public function find()
    {
    }

    public function create()
    {
    }

    public function get()
    {
    }

    public function update()
    {
    }

    public function getByCategory()
    {
        $category_id = \Input::get('category_id');
        $category_type = \Input::get('category_type', 'soe');
        $city = \Input::get('city');
        $state = \Input::get('state');
        $latitude = \Input::get('latitude');
        $longitude = \Input::get('longitude');
        $page = \Input::get('page', 0);
        $limit = \Input::get('limit', 12);
        $type = \Input::get('type');
        $filterEntered = \Input::get('filterEntered', false);
        $user_id = \Input::get('user_id', 0);
        $radius = \Input::get('radius', 0);
        $user = $this->userRepository->find($user_id);
        $show_demo = empty($user) ? false : $this->userRepository->showDemo($user);
        if($category_type == 'soe')
        {
            $results = $this->repository->getByCategory($city, $state, $latitude, $longitude, $show_demo, $type, $filterEntered, $category_id, null, $page, $limit, $radius);
        }
        else
        {
            $results = $this->repository->getBySohiCategory($city, $state, $latitude, $longitude, $show_demo, $category_id, null, $page, $limit, $radius);
        }

        if(!empty($user))
            $results['objects'] = $this->userRepository->markClipped($user, $results['objects']);

        return $this->format($results);
    }

    public function getSponsor()
    {
        $district = \Input::get('district_slug');
        $page = \Input::get('page', 0);
        $limit = \Input::get('limit', 12);
        $sort = \Input::get('sort', 'nearest');

        $results = $this->repository->getSponsors($district, $sort, $page, $limit);

        $user = \Auth::check() ? \Auth::User() : false;
        if(!empty($user))
            $results['objects'] = $this->userRepository->markClipped($user, $results['objects']);

        return $this->format($results);
    }

    public function getFeatured()
    {
        $type = \Input::get('type', 'coupon');
        $latitude = \Input::get('latitude');
        $longitude = \Input::get('longitude');
        $user_id = \Input::get('user_id', 0);
        $radius = \Input::get('radius');
        $category_slug = \Input::get('category');
        $subcategory_slug = \Input::get('subcategory');
        $user = $this->userRepository->find($user_id);
        $show_demo = empty($user) ? false : $this->userRepository->showDemo($user);

        $return = $this->repository->getFeatured($type, $latitude, $longitude, $show_demo, $radius, $category_slug, $subcategory_slug);
        if(!empty($user))
        {
            $aEntIDs = array(0);
            foreach($return['objects'] as $entity)
            {
                if($entity->entitiable_type == 'Offer')
                    $aEntIDs[] = $entity->entitiable_id;
            }
            $clips = \SOE\DB\UserClipped::on('mysql-write')
                                        ->whereIn('offer_id', $aEntIDs)
                                        ->where('is_deleted', '=', '0')
                                        ->where('user_id', '=', $user->id)
                                        ->get();
            $aClipIDs = array();
            foreach($clips as $clip)
            {
                $aClipIDs[] = $clip->offer_id;
            }
            foreach($return['objects'] as &$entity)
            {
                $entity->is_clipped = ($entity->entitiable_type == 'Offer' && in_array($entity->entitiable_id, $aClipIDs)) ? 1 : 0;
            }
        }
        return $this->format($return);
    }

    public function getByEntitiable()
    {
        return $this->format($this->repository->getByEntitiable(\Input::get('entitiable_id'), \Input::get('entitiable_type')));
    }
}
