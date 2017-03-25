<?php namespace SOE\Repositories\Eloquent;

class DealerRelationRepository extends BaseRepository implements \DealerRelationRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'dealer_id',
        'dealer_id_type',
        'dealer_name',
        'dealer_address',
        'dealer_city',
        'dealer_state',
        'dealer_zipcode',
        'dealer_phone',
        'franchise_id',
    );

    protected $model = 'DealerRelation';

    /**
     * Get all dealer relations that have been linked to a franchise of the given type.
     *
     * @param string $type
     * @return mixed
     */
    public function getLinkedByType($type)
    {
        return $this->query()
            ->where('dealer_id_type', $type)
            ->join('franchises', 'dealer_relations.franchise_id', '=', 'franchises.id')
            ->join('merchants', 'franchises.merchant_id', '=', 'merchants.id')
            ->join('locations', 'franchises.id', '=', 'locations.franchise_id')
            ->groupBy('franchises.id')
            ->get(array(
                'dealer_relations.*',
                'franchises.merchant_id',
                \DB::raw('merchants.display as merchant_name'),
                \DB::raw('merchants.slug as merchant_slug'),
                \DB::raw('merchants.tnl_id as tnl_id'),
                \DB::raw('merchants.vendor as merchant_vendor'),
                \DB::raw('locations.id as location_id'),
                \DB::raw('locations.latitude as latitude'),
                \DB::raw('locations.longitude as longitude'),
                \DB::raw('locations.latm as latm'),
                \DB::raw('locations.lngm as lngm'),
            ));
    }

    /**
     * Get all dealer relations of the given type.
     *
     * @param string $type
     * @return mixed
     */
    public function getByType($type)
    {
        return $this->query()
            ->where('dealer_id_type', $type)
            ->get();
    }

    /**
     * Get all the unlinked dealers.
     *
     * @return mixed
     */
    public function getUnlinked()
    {
        return $this->query()
            ->where('franchise_id', 0)
            ->orderBy('dealer_name')
            ->get();
    }

    /**
     * Link a dealer with the given franchise.
     * @return void
     */
    public function linkDealer($id, $franchise_id)
    {
        $this->query()
            ->where('id', $id)
            ->update(array('franchise_id' => $franchise_id));
    }
}