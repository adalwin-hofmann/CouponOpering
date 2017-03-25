<?php namespace SOE\Events;

class QuoteRequestEmailer {

    /**
    * Create a new instance of the QuoteRequestEmailer Handler
    *
    * @param Illuminate\Mail\Mailer $mailer
    * @return void
    */
    public function __construct()
    {

    }

    /**
    * Notify user of quote request acceptance.  
    *
    * @param \SOE\DB\AutoQuote $quote
    * @return void
    */
    public function handle(\SOE\DB\AutoQuote $quote)
    {
        \Mail::send('emails.auto-quote-received', $quote->toArray(), function($message) use ($quote)
        {
            $message->to($quote->email, $quote->first_name.' '.$quote->last_name)->subject('SaveOn - Quote Request Received!');
        });
    }

    /**
    * Register the listeners for the subscriber.
    *
    * @param Illuminate\Events\Dispatcher $events
    * @return array
    */
    public function subscribe($events)
    {
        $events->listen('quote.auto.requested', 'SOE\Events\QuoteRequestEmailer');
    }

}
