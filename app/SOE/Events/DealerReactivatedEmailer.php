<?php namespace SOE\Events;

class DealerReactivatedEmailer {

    public function __construct() {
        
    }

    /**
    * Notify sales rep of dealer reactivation.  
    *
    * @param \SOE\DB\Franchise $franchise
    * @return void
    */
    public function handle(\SOE\DB\Franchise $franchise)
    {
        $merchants = \App::make('MerchantRepositoryInterface');
        $merchant = $merchants->find($franchise->merchant_id);
        $data = array(
            'merchant' => $merchant->toArray()
        );
        $salesRep = $franchise->assignments()->where('franchise_assignments.assignment_type_id', '=', '1')->first();
        /*if($salesRep)
        {
            \Mail::send('emails.dealer-reactivated', $data, function($message) use ($salesRep)
            {
                $message->to($salesRep->email)->subject('SaveOn - Dealer Reactivated');
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
        $events->listen('dealer.reactivated', 'SOE\Events\DealerReactivatedEmailer');
    }
}