<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SitemapCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build sitemaps.';

    protected $featureRepository;
    protected $franchiseRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->categories = \App::make('CategoryRepositoryInterface');
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->createIndex();
    }

    protected function createIndex()
    {
        $cats = $this->categories->getByParentId(0);
        $aCats = array();
        foreach($cats['objects'] as $cat)
        {
            $aCats[$cat->slug] = $cat->id;
        }

        $this->info('Building Index...');

        $sitemap = new Sitemap('index');
        $sitemap->open();
         
        $sitemap->createItem('http://www.saveon.com/sitemaps/states.xml');
        $sitemap->createItem('http://www.saveon.com/sitemaps/top-mi-cities.xml');
        $sitemap->createItem('http://www.saveon.com/sitemaps/top-il-cities.xml');
        $sitemap->createItem('http://www.saveon.com/sitemaps/top-mn-cities.xml');

        $locations = \SOE\DB\Location::where('city', '!=', '')
                                    ->where('state', 'MI')
                                    ->where('is_active', '=', 1)
                                    ->where('is_demo', '0')
                                    ->groupBy('city')
                                    ->having('total', '>', 100)
                                    ->get(array('city', 'state', \DB::raw('COUNT(*) as total')));
        $this->createTopCitiesSitemap('MI', $locations);
        foreach($locations as $location)
        {
            $this->info('Building '.$location->city.', '.$location->state.' categories...');
            $sitemap->createItem('http://www.saveon.com/sitemaps/mi-'.SoeHelper::getSlug($location->city).'-categories.xml');
            $this->createCitySitemap($location->city, $location->state);
        }

        $locations = \SOE\DB\Location::where('city', '!=', '')
                                    ->where('state', 'IL')
                                    ->where('is_active', '=', 1)
                                    ->where('is_demo', '0')
                                    ->groupBy('city')
                                    ->having('total', '>', 100)
                                    ->get(array('city', 'state', \DB::raw('COUNT(*) as total')));
        $this->createTopCitiesSitemap('IL', $locations);
        foreach($locations as $location)
        {
            $this->info('Building '.$location->city.', '.$location->state.' categories...');
            $sitemap->createItem('http://www.saveon.com/sitemaps/il-'.SoeHelper::getSlug($location->city).'-categories.xml');
            $this->createCitySitemap($location->city, $location->state);
        }

        $locations = \SOE\DB\Location::where('city', '!=', '')
                                    ->where('state', 'MN')
                                    ->where('is_active', '=', 1)
                                    ->where('is_demo', '0')
                                    ->groupBy('city')
                                    ->having('total', '>', 100)
                                    ->get(array('city', 'state', \DB::raw('COUNT(*) as total')));
        $this->createTopCitiesSitemap('MN', $locations);
        foreach($locations as $location)
        {
            $this->info('Building '.$location->city.', '.$location->state.' categories...');
            $sitemap->createItem('http://www.saveon.com/sitemaps/mn-'.SoeHelper::getSlug($location->city).'-categories.xml');
            $this->createCitySitemap($location->city, $location->state);
        }

        foreach($aCats as $slug => $id)
        {
            // Detroit Market
            $cartesian = SoeHelper::getCartesian(42.38, -83.10);
            $distance = \DB::raw('(sqrt(pow(locations.latm - '.$cartesian['latm'].', 2) + pow(locations.lngm - '.$cartesian['lngm'].', 2)))');
            $subcats = \SOE\DB\Category::where('categories.parent_id', $id)
                                    ->join('merchants', 'categories.id', '=', 'merchants.subcategory_id')
                                    ->join('locations', 'merchants.id', '=', 'locations.merchant_id')
                                    ->where('merchants.is_active', '1')
                                    ->where('locations.is_active', '1')
                                    ->where('locations.state', 'MI')
                                    ->where('locations.is_demo', '0')
                                    ->where('merchants.is_demo', '0')
                                    ->where($distance, '<', 75000)
                                    ->groupBy('categories.id')
                                    ->having('total', '>', 10)
                                    ->get(array(\DB::raw('COUNT(*) as total'), 'categories.id', 'categories.slug', 'categories.parent_id'));
            foreach($subcats as $subcat)
            {
                $this->info('Building '.$slug.' - '.$subcat->slug.' DT markets...');
                $sitemap->createItem('http://www.saveon.com/sitemaps/market-detroit-'.$slug.'-'.$subcat->slug.'-site-map.xml');
                $this->createMarketSitemap('detroit', $distance, $id, $subcat->id, $slug, $subcat->slug);
            }

            // MN Market
            $cartesian = SoeHelper::getCartesian(44.96, -93.16);
            $distance = \DB::raw('(sqrt(pow(locations.latm - '.$cartesian['latm'].', 2) + pow(locations.lngm - '.$cartesian['lngm'].', 2)))');
            $subcats = \SOE\DB\Category::where('categories.parent_id', $id)
                                    ->join('merchants', 'categories.id', '=', 'merchants.subcategory_id')
                                    ->join('locations', 'merchants.id', '=', 'locations.merchant_id')
                                    ->where('merchants.is_active', '1')
                                    ->where('locations.is_active', '1')
                                    ->where('locations.state', 'MN')
                                    ->where('locations.is_demo', '0')
                                    ->where('merchants.is_demo', '0')
                                    ->where($distance, '<', 75000)
                                    ->groupBy('categories.id')
                                    ->having('total', '>', 10)
                                    ->get(array(\DB::raw('COUNT(*) as total'), 'categories.id', 'categories.slug', 'categories.parent_id'));
            foreach($subcats as $subcat)
            {
                $this->info('Building '.$slug.' - '.$subcat->slug.' TWIN markets...');
                $sitemap->createItem('http://www.saveon.com/sitemaps/market-twin-'.$slug.'-'.$subcat->slug.'-site-map.xml');
                $this->createMarketSitemap('twin', $distance, $id, $subcat->id, $slug, $subcat->slug);
            }

            // IL Market
            $cartesian = SoeHelper::getCartesian(41.83, -87.68);
            $distance = \DB::raw('(sqrt(pow(locations.latm - '.$cartesian['latm'].', 2) + pow(locations.lngm - '.$cartesian['lngm'].', 2)))');
            $subcats = \SOE\DB\Category::where('categories.parent_id', $id)
                                    ->join('merchants', 'categories.id', '=', 'merchants.subcategory_id')
                                    ->join('locations', 'merchants.id', '=', 'locations.merchant_id')
                                    ->where('merchants.is_active', '1')
                                    ->where('locations.is_active', '1')
                                    ->where('locations.state', 'IL')
                                    ->where('locations.is_demo', '0')
                                    ->where('merchants.is_demo', '0')
                                    ->where($distance, '<', 75000)
                                    ->groupBy('categories.id')
                                    ->having('total', '>', 10)
                                    ->get(array(\DB::raw('COUNT(*) as total'), 'categories.id', 'categories.slug', 'categories.parent_id'));
            foreach($subcats as $subcat)
            {
                $this->info('Building '.$slug.' - '.$subcat->slug.' CHI markets...');
                $sitemap->createItem('http://www.saveon.com/sitemaps/market-chicago-'.$slug.'-'.$subcat->slug.'-site-map.xml');
                $this->createMarketSitemap('chicago', $distance, $id, $subcat->id, $slug, $subcat->slug);
            }
        }

        $xml = $sitemap->getXML();
        $xml->asXML(public_path().'/sitemap.xml');
    }

    protected function createTopCitiesSitemap($state, $locations)
    {
        $sitemap = new Sitemap('sitemap');
        $sitemap->open();
        
        foreach($locations as $location)
        {
            $sitemap->createItem(
                'http://www.saveon.com/coupons/'.strtolower($state).'/'.SoeHelper::getSlug($location->city).'/all',
                'daily'
            );
        }

        $xml = $sitemap->getXML();
        $xml->asXML(public_path().'/sitemaps/top-'.strtolower($state).'-cities.xml');
    }

    protected function createCitySitemap($city, $state)
    {
        $sitemap = new Sitemap('sitemap');
        $sitemap->open();

        $categories = $this->categories->getByParentId(0);
        foreach($categories['objects'] as $category)
        {
            $sitemap->createItem('http://www.saveon.com/coupons/'.strtolower($state).'/'.SoeHelper::getSlug($city).'/'.$category->slug);
        }
        $sitemap->createItem('http://www.saveon.com/contests/'.strtolower($state).'/'.SoeHelper::getSlug($city).'/all');
        $sitemap->createItem('http://www.saveon.com/dailydeals/'.strtolower($state).'/'.SoeHelper::getSlug($city).'/all');
        $xml = $sitemap->getXML();
        $xml->asXML(public_path().'/sitemaps/'.strtolower($state).'-'.SoeHelper::getSlug($city).'-categories.xml');
    }

    protected function createMarketSitemap($market, $distance, $cat_id, $subcat_id, $cat_slug, $subcat_slug)
    {
        $aMarkets = array(
            'detroit' => 'MI',
            'chicago' => 'IL',
            'twin' => 'MN'
        );

        $merchants = \SOE\DB\Location::join('merchants', 'locations.merchant_id', '=', 'merchants.id')
                                    ->join('categories', 'merchants.subcategory_id', '=', 'categories.id')
                                    ->where($distance, '<', 75000)
                                    ->where('locations.state', $aMarkets[$market])
                                    ->where('merchants.is_active', '1')
                                    ->where('locations.is_active', '1')
                                    ->where('merchants.subcategory_id', $subcat_id)
                                    ->where('locations.is_demo', '0')
                                    ->where('merchants.is_demo', '0')
                                    ->groupBy('merchants.id')
                                    ->get(array('locations.city', 'locations.state', 'merchants.slug'));

        $sitemap = new Sitemap('sitemap');
        $sitemap->open();

        foreach($merchants as $merch)
        {
            $sitemap->createItem(
                'http://www.saveon.com/coupons/'.strtolower($merch->state).'/'.SoeHelper::getSlug($merch->city).'/'.$cat_slug.'/'.$subcat_slug.'/'.$merch->slug,
                'weekly'
            );
        }

        $xml = $sitemap->getXML();
        $xml->asXML(public_path().'/sitemaps/market-'.$market.'-'.$cat_slug.'-'.$subcat_slug.'-site-map.xml');
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