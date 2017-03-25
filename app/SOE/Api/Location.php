<?php
namespace SOE\Api;

class Location extends Api implements ApiInterface, LocationApi
{
    public function __construct(
        \FranchiseRepositoryInterface $franchises,
        \LocationRepositoryInterface $repository,
        \UserRepositoryInterface $userRepository
    )
    {
        $this->repository = $repository;
        $this->franchises = $franchises;
        $this->userRepository = $userRepository;
    }

    public function find()
    {
        return $this->format($this->repository->find(\Input::get('id')));
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

    /**
     * Get an array of entities belonging to a location, based on location_id.
     *
     * @api
     *
     * @return mixed
     */
    public function getEntities()
    {
        $page = \Input::get('page');
        $limit = \Input::get('limit');
        $location_id = \Input::get('location_id');
        $user_id = \Input::get('user_id');
        $expired = \Input::get('expired', false);
        $location = $this->repository->find($location_id);
        $user = $this->userRepository->find($user_id);
        $show_demo = empty($user) ? false : $this->userRepository->showDemo($user);
        $entities = $this->repository->getEntities($location, $show_demo, $page, $limit, $expired);

        $aEntIDs = array(0);
        foreach($entities['objects'] as $entity)
        {
            if($entity->entitiable_type == 'Offer')
            {
                $aEntIDs[] = $entity->entitiable_id;
            }
        }
        $aClipIDs = array(0);
        $clips = array();
        if(\Auth::check())
        {
            $clips = \SOE\DB\UserClipped::on('mysql-write')
                                        ->whereIn('offer_id', $aEntIDs)
                                        ->where('user_id', '=', empty($user) ? 0 : $user->id)
                                        ->where('is_deleted', '=', '0')
                                        ->get(array('offer_id'));
        }
        foreach($clips as $clip)
        {
            $aClipIDs[] = $clip->offer_id;
        }
        foreach($entities['objects'] as &$entity)
        {
            $entity->is_clipped = ($entity->entitiable_type == 'Offer' && in_array($entity->entitiable_id, $aClipIDs)) ? 1 : 0;
        }

        return $this->format($entities);
    }

    /**
     * Get locations belonging to a merchant.
     *
     * @api
     * @return mixed
     */
    public function getMerchantLocationsByDistance()
    {
        return $this->format(
            $this->repository->getMerchantLocationsByDistance(\Input::get('merchant_id'), \Input::get('lat'), \Input::get('lng'), \Input::get('page'), \Input::get('limit'))
        );
    }

    /**
     * Get locations belonging to a franchise.
     *
     * @api
     * @return mixed
     */
    public function getFranchiseLocationsByDistance()
    {
        return $this->format(
            $this->repository->getFranchiseLocationsByDistance(\Input::get('franchise_id'), \Input::get('lat'), \Input::get('lng'), \Input::get('page'), \Input::get('limit'))
        );
    }

    /**
     * Get locations belonging to a franchise.
     *
     * @api
     * @return mixed
     */
    public function getByFranchise()
    {
        $franchise = $this->franchises->find(\Input::get('franchise_id'));
        return $this->format(
            $this->repository->getByFranchise($franchise, \Input::get('page'), \Input::get('limit'))
        );
    }

    /**
     * Get the most recently updated locations for a merchant.
     *
     * @api
     * @return mixed
     */
    public function getRecentlyUpdatedByMerchant()
    {
        return $this->format(
            $this->repository->getRecentlyUpdatedByMerchant(\Input::get('merchant_id'), \Input::get('page'), \Input::get('limit'))
        );
    }
}
