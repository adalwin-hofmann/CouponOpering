<?php namespace SOE\Events;

use Illuminate\Support\ServiceProvider;

class EventsServiceProvider extends ServiceProvider {

    /**
    * Boot the Lead Acceptance Events
    *
    * @return void
    */
    public function boot()
    {
        $this->app->events->subscribe(
            new QuoteRequestEmailer()
        );

        $this->app->events->subscribe(
            new DealerOrderEmailer()
        );

        $this->app->events->subscribe(
            new TestLeadsConfirmedEmailer()
        );

        $this->app->events->subscribe(
            new DealerReactivatedEmailer()
        );
    }

    public function register() {
        
    }

}

