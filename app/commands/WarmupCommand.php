<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class WarmupCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'warmup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prep the cache.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '2048M');
        $type = $this->option('type');
        switch($type)
        {
            case 'recommendations':
                $this->recommendations();
                break;
            case 'categories':
                $this->categories();
                break;
            default:
                $this->recommendations();
                $this->categories();
                break;
        }
    }

    protected function recommendations()
    {
        $this->info("Generating recommendations cache for states...");
        $aStates = array('MI', 'IL', 'MN');
        foreach($aStates as $state)
        {
            $this->info("Generating recommendations cache for ".$state."...");
            $zipcodes = Zipcode::getTopByState($state, 0, 0, 15000, false);
            foreach($zipcodes['objects'] as $city)
            {
                $nonmember = Nonmember::blank();
                $geoip = new StdClass();
                $geoip->latitude = $city->latitude;
                $geoip->longitude = $city->longitude;
                $geoip->region_name = $city->state;
                $geoip->city_name = $city->city;
                Entity::getRecommendations($nonmember, $geoip);
            }
        }
        $countries = SoeHelper::states();
        foreach($countries['USA']['states'] as $abbr => $name)
        {
            if(!in_array($abbr, $aStates))
            {
                $this->info("Generating recommendations ".$abbr." for CA...");
                $zipcodes = Zipcode::getTopByState($abbr, 0, 0, 50000, false);
                foreach($zipcodes['objects'] as $city)
                {
                    $nonmember = Nonmember::blank();
                    $geoip = new StdClass();
                    $geoip->latitude = $city->latitude;
                    $geoip->longitude = $city->longitude;
                    $geoip->region_name = $city->state;
                    $geoip->city_name = $city->city;
                    Entity::getRecommendations($nonmember, $geoip);
                }
            }   
        }
        $this->info("Done!");
    }

    protected function categories()
    {
        $aTypes = array('coupon', 'dailydeal', 'contest');
        $categories = SOE\DB\Category::where('parent_id', '=', 0)->get();
        $nonmember = Nonmember::blank();
        foreach($categories as $cat)
        {
            $this->info("Generating categories cache for ".$cat->slug."...");
            $aStates = array('MI', 'IL', 'MN');
            foreach($aStates as $state)
            {
                $this->info("Generating ".$cat->slug." cache for ".$state."...");
                $zipcodes = Zipcode::getTopByState($state, 0, 0, 15000, false);
                foreach($zipcodes['objects'] as $city)
                {
                    $geoip = new StdClass();
                    foreach($aTypes as $type)
                    {
                        $this->info("Generating ".$type." for ".$cat->slug." cache for ".$city->city.", ".$state."...");
                        Entity::getByCategory($nonmember, $city->city, $city->state, $city->latitude, $city->longitude, $type, $cat->id, 'nearest', 0, 12);
                    }
                }
            }
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            //array('example', InputArgument::REQUIRED, 'An example argument.'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('type', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
        );
    }

}