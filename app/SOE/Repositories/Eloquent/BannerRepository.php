<?php namespace SOE\Repositories\Eloquent;

class BannerRepository extends BaseRepository implements \BannerRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'name',
        'merchant_id',
        'franchise_id',
        'path',
        'type',
        'is_active',
        'is_demo',
        'custom_url',
        'target',
        'service_radius',
        'keywords',
        'is_location_specific',
    );

    protected $model = 'Banner';

    public function __construct(

    )
    {

        parent::__construct();
    }

    /**
     * Get banners belonging to the given franchise along with the locations associated.
     *
     * @param int $franchise_id
     * @param boolean $show_inactive
     * @return array
     */
    public function getByFranchise($franchise_id, $show_inactive = false, $is_location_specific = ture)
    {
        $query = $this->query()
            ->where('banners.franchise_id', $franchise_id)
            ->join('banner_entities', 'banners.id', '=', 'banner_entities.banner_id');
        if(!$show_inactive)
            $query->where('is_active', '1');
        if(!$is_location_specific)
            $query->where('is_location_specific', '0');
        $banners = $query->get(array('banners.*', 'banner_entities.location_id'));
        $aBanners = array();
        foreach($banners as $banner)
        {
            if(isset($aBanners[$banner->id]))
                $aBanners[$banner->id]['locations'][] = $banner->location_id;
            else
                $aBanners[$banner->id] = array('banner' => $banner->toArray(), 'locations' => array($banner->location_id));
        }

        return array('objects' => $aBanners, 'stats' => array());
    }

}