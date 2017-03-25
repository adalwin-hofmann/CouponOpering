<?php namespace SOE\Events;

class TestLeadsConfirmedEmailer {

    public function __construct() {
        
    }

    /**
    * Notify sales rep of test leads confirmation.  
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
        if($salesRep)
        {
            \Mail::send('emails.test-leads-confirmed', $data, function($message) use ($salesRep)
            {
                $message->to($salesRep->email)->subject('SaveOn - Test Leads Confirmed');
            });
        }
    }

    /**
    * Register the listeners for the subscriber.
    *
    * @param Illuminate\Events\Dispatcher $events
    * @return array
    */
    public function subscribe($events)
    {
        $events->listen('test-lead.confirmed', 'SOE\Events\TestLeadsConfirmedEmailer');
    }
}