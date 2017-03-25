<?php namespace SOE\Events;

class DealerOrderEmailer {

    /**
    * Create a new instance of the DealerOrderEmailer Handler
    *
    * @return void
    */
    public function __construct()
    {

    }

    /**
    * Notify dealer and sales rep of order creation.  
    *
    * @param \SOE\DB\DealerOrder $order
    * @return void
    */
    public function handle(\SOE\DB\DealerOrder $order)
    {
        $franchises = \App::make('FranchiseRepositoryInterface');
        $merchants = \App::make('MerchantRepositoryInterface');
        $vehicleMakes = \App::make('VehicleMakeRepositoryInterface');
        $franchise = $franchises->find($order->franchise_id);
        if(!$franchise)
            return false;

        $merchant = $merchants->find($franchise->merchant_id);
        $make = $vehicleMakes->find($order->make_id);
        $data = array(
            'order' => $order->toArray(),
            'make' => $make->toArray(),
            'merchant' => $merchant->toArray()
        );
        $salesRep = $franchise->assignments()->where('franchise_assignments.assignment_type_id', '=', '1')->first();

        /*if($franchise->primary_contact != '')
        {
            \Mail::send('emails.dealer-order-created-dealer', $data, function($message) use ($franchise)
            {
                $message->to($franchise->primary_contact)->subject('SaveOn - Lead Order Created');
            });
        }
        if($salesRep)
        {
            \Mail::send('emails.dealer-order-created-rep', $data, function($message) use ($salesRep)
            {
                $message->to($salesRep->email)->subject('SaveOn - Lead Order Created');
            });
        }*/
    }

    /**
    * Register the listeners for the subscriber.
    *
    * @param Illuminate\Events\Dispatcher $events
    * @return array
    */
    public function subscribe($events)
    {
        $events->listen('dealer-order.created', 'SOE\Events\DealerOrderEmailer');
    }

}
