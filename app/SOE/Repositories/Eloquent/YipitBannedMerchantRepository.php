<?php namespace SOE\Repositories\Eloquent;

class YipitBannedMerchantRepository extends BaseRepository implements \YipitBannedMerchantRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'id',
        'merchant_id',
        'yipitbusiness_id',
        'merchant_name'
    );

    protected $model = 'YipitBannedMerchant';

    public function getByMerchantId($merchant_id)
    {
        return $this->query()
            ->where('merchant_id', '=', $merchant_id)
            ->first();
    }

    /*public function getActive()
    {
        return \SOE\DB\YipitBannedMerchants::where('is_active', '=', '1')->get();
    }*/

}