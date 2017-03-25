<?php namespace SOE\Repositories\Eloquent;

class DealerOrderRepository extends BaseRepository implements \DealerOrderRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'franchise_id',
        'make_id',
        'zipcode',
        'radius',
        'budget',
        'starts_at',
        'ends_at',
        'netlms_order_id',
    );

    protected $model = 'DealerOrder';

    public function __construct(\FranchiseRepositoryInterface $franchises, \VehicleMakeRepositoryInterface $makes, \ZipcodeRepositoryInterface $zipcodes)
    {
        $this->franchises = $franchises;
        $this->makes = $makes;
        $this->zipcodes = $zipcodes;
    }

    public function getByFranchise($franchise_id, $page = 0, $limit = 0)
    {
        $query = $this->query()->where('franchise_id', $franchise_id);
        $stats = $this->getStats(clone $query, $limit, $page);
        $query = $this->paginator($query, $limit, $page);
        $orders = $query->get();
        $return = array('objects' => $orders);
        $stats['stats']['returned'] = count($orders);
        return array_merge($return, $stats);
    }

    public function netlmsCreate(\SOE\DB\DealerOrder $order)
    {
        $api = \App::make('NetLMSAPIInterface');
        $make = $this->makes->find($order->make_id);
        $franchise = $this->franchises->find($order->franchise_id);
        $response = $api->curl('GET', 'customer', $franchise->netlms_id);
        $oResponse = $response['response'];
        $account_id = $oResponse->accounts[0]->id;
        $data = array(
            'account' => $account_id,
            'category' => $make->slug,
            'service_zips' => array(),
            'starts_at' => $order->starts_at,
            'ends_at' => $order->ends_at,
            'budget' => $order->budget
        );
        $zipcode = $this->zipcodes->findByZipcode($order->zipcode);
        $zipcodes = empty($zipcode) ? array() : $this->zipcodes->getByRadius($zipcode->latitude, $zipcode->longitude, $order->radius);
        foreach($zipcodes as $zip)
        {
            $data['service_zips'][] = $zip->zipcode;
        }
        $response = $api->curl('POST', 'order', null, $data);
        $oResponse = $response['response'];
        $order->netlms_order_id = $oResponse->id;
        $order->save();

        \Event::fire('dealer-order.created', array($order));
    }

    public function netlmsUpdate(\SOE\DB\DealerOrder $order)
    {
        $api = \App::make('NetLMSAPIInterface');
        $make = $this->makes->find($order->make_id);
        $data = array(
            'category' => $make->slug,
            'service_zips' => array(),
            'starts_at' => $order->starts_at,
            'ends_at' => $order->ends_at,
            'budget' => $order->budget
        );
        $zipcode = $this->zipcodes->findByZipcode($order->zipcode);
        $zipcodes = empty($zipcode) ? array() : $this->zipcodes->getByRadius($zipcode->latitude, $zipcode->longitude, $order->radius);
        foreach($zipcodes as $zip)
        {
            $data['service_zips'][] = $zip->zipcode;
        }
        $api->curl('PUT', 'order', $order->netlms_order_id, $data);
    }

    public function netlmsDelete(\SOE\DB\DealerOrder $order)
    {
        $api = \App::make('NetLMSAPIInterface');
        $api->curl('DELETE', 'order', $order->netlms_order_id);
    }
}

/**
 * Handle the Dealer Order created event.
 *
 * @param SOE\DB\Dealer Order $dealerOrder
 * @return void
 */
\SOE\DB\DealerOrder::created(function($dealerOrder)
{
    $repo = \App::make('DealerOrderRepositoryInterface');
    $repo->netlmsCreate($dealerOrder);
});

/**
 * Handle the Dealer Order updating event.
 *
 * @param SOE\DB\Dealer Order $dealerOrder
 * @return void
 */
\SOE\DB\DealerOrder::updating(function($dealerOrder)
{
    $dirty = $dealerOrder->getDirty();

    if(isset($dirty['make_id']) || isset($dirty['zipcode']) || isset($dirty['radius']) || isset($dirty['budget']) || isset($dirty['starts_at']) || isset($dirty['ends_at']))
    {
        $repo = \App::make('DealerOrderRepositoryInterface');
        $repo->netlmsUpdate($dealerOrder);
    }
});

/**
 * Handle the Dealer Order deleting event.
 *
 * @param SOE\DB\Dealer Order $dealerOrder
 * @return void
 */
\SOE\DB\DealerOrder::deleting(function($dealerOrder)
{
    $repo = \App::make('DealerOrderRepositoryInterface');
    $repo->netlmsDelete($dealerOrder);
});