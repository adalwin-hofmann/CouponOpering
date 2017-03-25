<?php namespace SOE\Repositories\Eloquent;

class DealerBrandRepository extends BaseRepository implements \DealerBrandRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'merchant_id',
        'make_id',
        'old_dealer_id',
        'old_make_id',
    );

    protected $model = 'DealerBrand';

    /**
     * Get the brands associated with a merchant.
     *
     * @param int $merchant_id
     * @param int $page
     * @param int $limit
     * @return mixed
     */
    public function getByMerchant($merchant_id, $page = 0, $limit = 0)
    {
        $query = $this->query()
            ->join('vehicle_makes', 'dealer_brands.make_id', '=', 'vehicle_makes.id')
            ->where('merchant_id', $merchant_id)
            ->orderBy('vehicle_makes.name');

        $stats = $this->getStats(clone $query, $page, $limit);
        if($limit != 0)
        {
            $query = $query->take($limit)->skip($limit*$page);
        }
        $brands = $query->get(array('dealer_brands.make_id', 'vehicle_makes.name'));;
        $results = array('objects' => array());
        foreach($brands as $brand)
        {
            $results['objects'][] = $brand;
        }
        $stats['stats']['returned'] = count($brands);
        $results = array_merge($results, $stats);
        return $results;
    }

    /**
     * Set the brands for a merchant.
     *
     * @param int $merchant_id
     * @param mixed $brands Comma separated list or array.
     * @return void
     */
    public function setBrands($merchant_id, $brands = array())
    {
        if(!is_array($brands))
        {
            $brands = trim($brands, ',');
            $brands = explode(',', $brands);
        }

        $oldBrands = $this->query()->where('merchant_id', $merchant_id)->get();
        $aOld = array();
        foreach($oldBrands as $old)
        {
            $aOld[] = $old->make_id;
        }

        $aNew = array();
        $aExisting = array();
        $aRemove = array();

        foreach($brands as $brand)
        {
            if(in_array(trim($brand), $aOld))
                $aExisting[] = trim($brand);
            else
                $aNew[] = trim($brand);
        }

        $aRemove = array_diff($aOld, $aExisting, $aNew);
        if(count($aRemove))
        {
            $this->query()->where('merchant_id', $merchant_id)
                ->whereIn('make_id', $aRemove)
                ->delete();
        }
        foreach($aNew as $new)
        {
            $this->create(array(
                'merchant_id' => $merchant_id,
                'make_id' => $new
            ));
        }
    }

}