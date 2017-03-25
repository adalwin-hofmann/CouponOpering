<?php namespace SOE\Repositories\Eloquent;

class BannerEntityRepository extends BaseRepository implements \BannerEntityRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'banner_id',
        'location_id'
    );

    protected $model = 'BannerEntity';

    public function __construct(
        \UserRepositoryInterface $users
    )
    {
        $this->users = $users;
        parent::__construct();
    }

    public function getByBanner($banner_id)
    {
        return $this->query()->where('banner_id', $banner_id)->get();
    }

    /**
     * Retrieve active banners of given type within service radius of given location.
     *
     * @param string $type
     * @param float $latitude
     * @param float $longitude
     * @param int $limit
     * @return array
     */
    public function getByTypeAndLocation($type, $latitude, $longitude, $subcategory_id = null, $keywords = null,  $limit = 1)
    {
        $cartesian = \SoeHelper::getCartesian($latitude, $longitude);
        $distance = \DB::raw('(sqrt(pow(locations.latm - '.$cartesian['latm'].', 2) + pow(locations.lngm - '.$cartesian['lngm'].', 2)))');
        $query = $this->query()
            ->join('banners', 'banner_entities.banner_id', '=', 'banners.id')
            ->join('locations', 'banner_entities.location_id', '=', 'locations.id')
            ->join('merchants', 'banners.merchant_id', '=', 'merchants.id')
            ->join(\DB::raw('categories as cat'), 'merchants.category_id', '=', 'cat.id')
            ->join(\DB::raw('categories as subcat'), 'merchants.subcategory_id', '=', 'subcat.id')
            ->where('banners.type', $type)
            ->where('banners.service_radius', '>=', $distance)
            ->where('banners.is_active', '1')
            ->where('locations.is_active', '1')
            ->where('merchants.is_active', '1');
        if(!\Auth::check() || !$this->users->showDemo(\Auth::User()))
            $query->where('banners.is_demo', '0');

        $get = array(
            'banners.*',
            \DB::raw('banner_entities.id as banner_entity_id'),
            'banner_entities.location_id',
            \DB::raw('cat.slug as category_slug'),
            \DB::raw('subcat.slug as subcategory_slug'),
            \DB::raw('merchants.slug as merchant_slug'),
            \DB::raw('merchants.name as merchant_name'),
            'locations.city',
            'locations.state',
        );

        if($subcategory_id)
        {
            $query->where('merchants.subcategory_id', $subcategory_id);
        }

        if($keywords)
        {
            $keywords = str_replace("'s", '', $keywords);
            $keywords = str_replace("&", '', $keywords);
            $keywords = preg_replace('/\s+/', ' ',$keywords);
            $aKeywords = explode(' ', $keywords);
            /*$aExact = array();
            $aStart = array();
            $aContain = array();
            foreach($aKeywords as $keyword)
            {
                $aExact[] = " keywords LIKE '".$keyword."'";
            }
            foreach($aKeywords as $keyword)
            {
                $aStart[] = " keywords LIKE '".$keyword."%'";
            }
            foreach($aKeywords as $keyword)
            {
                $aContatin[] = " keywords LIKE '%".$keyword."%'";
            }

            $qString = "(case when ".implode(' or ', $aExact)." then 60 else 0 end) +";
            $qString .= " (case when ".implode(' or ', $aStart)." then 50 else 0 end) +";
            $qString .= " (case when ".implode(' or ', $aContain)." then 4 else 0 end) as relevance";*/
            //$get[] = \DB::raw($qString);
            $aCases = array();
            foreach($aKeywords as $keyword)
            {
                $aCases[] = "(case when banners.keywords LIKE '%".$keyword."%' then ".strlen($keyword)." else 0 end)";
            }
            $get[] = \DB::raw(implode(' + ', $aCases)." as relevance");
            $query->orderBy('relevance', 'desc');
        }
        else
        {
            $query->orderBy(\DB::raw('RAND()'));
        }

        $banners = $query
            ->take($limit)
            ->get($get);

        return array('objects' => $banners);
    }

}