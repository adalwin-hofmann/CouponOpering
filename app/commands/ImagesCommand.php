<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ImagesCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup images.';

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
            case 'assets':
                $this->assets();
                break;
            case 'city_images':
                $this->city_images();
                break;
            case 'contests':
                $this->contests();
                break;
            case 'entities':
                $this->entities();
                break;
            case 'offers':
                $this->offers();
                break;
            case 'win5k':
                $this->win5k();
                break;
            case 'check_logos_broken':
                $this->checkLogosBroken();
                break;
            case 'check_vehicle_assets_broken':
                $this->checkVehicleAssetsBroken();
                break;
            case 'check_used_broken':
                $this->checkUsedImagesBroken();
                break;
            default:
                $this->assets();
                $this->city_images();
                $this->contests();
                $this->entities();
                $this->offers();
                break;
        }
        
    }

    protected function assets()
    {
        $this->info("https -> http for assets...");
        DB::table('assets')->where('path', 'LIKE', 'http://s3.amazonaws.com%')->update(array('path' => DB::raw("REPLACE(`path`, 'https', 'http')")));
    }

    protected function city_images()
    {
        $this->info("https -> http for city_images...");
        DB::table('city_images')->where('path', 'LIKE', 'http://s3.amazonaws.com%')->update(array('path' => DB::raw("REPLACE(`path`, 'https', 'http')")));
        DB::table('city_images')->where('img_415x258', 'LIKE', 'http://s3.amazonaws.com%')->update(array('img_415x258' => DB::raw("REPLACE(`img_415x258`, 'https', 'http')")));
        DB::table('city_images')->where('img_266x266', 'LIKE', 'http://s3.amazonaws.com%')->update(array('img_266x266' => DB::raw("REPLACE(`img_266x266`, 'https', 'http')")));
    }

    protected function contests()
    {
        $this->info("https -> http for contests...");
        DB::table('contests')->where('path', 'LIKE', 'http://s3.amazonaws.com%')->update(array('path' => DB::raw("REPLACE(`path`, 'https', 'http')")));
        DB::table('contests')->where('banner', 'LIKE', 'http://s3.amazonaws.com%')->update(array('banner' => DB::raw("REPLACE(`banner`, 'https', 'http')")));
        DB::table('contests')->where('logo', 'LIKE', 'http://s3.amazonaws.com%')->update(array('logo' => DB::raw("REPLACE(`logo`, 'https', 'http')")));
        DB::table('contests')->where('landing', 'LIKE', 'http://s3.amazonaws.com%')->update(array('landing' => DB::raw("REPLACE(`landing`, 'https', 'http')")));
        DB::table('contests')->where('contest_logo', 'LIKE', 'http://s3.amazonaws.com%')->update(array('contest_logo' => DB::raw("REPLACE(`contest_logo`, 'https', 'http')")));
    }

    protected function entities()
    {
        $this->info("https -> http for entities...");
        DB::table('entities')->where('path', 'LIKE', 'http://s3.amazonaws.com%')->update(array('path' => DB::raw("REPLACE(`path`, 'https', 'http')")));
    }

    protected function offers()
    {
        $this->info("https -> http for offers...");
        DB::table('offers')->where('path', 'LIKE', 'http://s3.amazonaws.com%')->update(array('path' => DB::raw("REPLACE(`path`, 'https', 'http')")));
    }

    protected function win5k()
    {
        SOE\DB\Contest::where('name', '=', 'win5k')->update(array('banner' => 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/5k_contest_banner.png'));
    }

    protected function checkLogosBroken()
    {
        set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '2048M');
        $logos = \SOE\DB\Asset::join('merchants', 'assets.assetable_id', '=', 'merchants.id')
                                ->where('assets.assetable_type', 'Merchant')
                                ->where('assets.name', 'logo1')
                                ->get(array('assets.path', 'merchants.id', 'merchants.name'));
        // Set CSV Headers
        $csv_data = array(array('Merchant Id', 'Merchant Name', 'Logo Path'));

        foreach($logos as $logo)
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $logo->path);
            curl_setopt($ch, CURLOPT_NOBODY, true); // this is what sets it as HEAD request
            curl_exec($ch);

            if (curl_getinfo($ch, CURLINFO_HTTP_CODE) != '200')
            {
                $csv_data[] = array(
                    $logo->id,
                    $logo->name,
                    $logo->path
                );
            }

            curl_close($ch);
        }

        header('Content-Type: application/csv');
        header('Content-Disposition: attachement; filename="brokenlogos.csv";');

        $file = fopen('php://output', 'w');
        foreach($csv_data as $row) {
            fputcsv($file, $row, ',');
        }
    }

    protected function checkVehicleAssetsBroken()
    {
        set_time_limit(60*60); // 30 Mins
        ini_set('memory_limit', '2048M');
        $assets = \SOE\DB\VehicleAsset::all(array('id', 'path'));
        $csv_data = array(array('AssetId','Path'));
        foreach($assets as $asset)
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $asset->path);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_exec($ch);

            if (curl_getinfo($ch, CURLINFO_HTTP_CODE) != '200')
            {
                $csv_data[] = array(
                    $asset->id,
                    $asset->path
                );
            }

            curl_close($ch);
        }
        header('Content-Type: application/csv');
        header('Content-Disposition: attachement; filename="brokenassets.csv";');

        $file = fopen('php://output', 'w');
        foreach($csv_data as $row) {
            fputcsv($file, $row, ',');
        }
    }

    protected function checkUsedImagesBroken()
    {
        set_time_limit(60*60*5); // 5 Hours
        ini_set('memory_limit', '2048M');
        $cars = \SOE\DB\UsedVehicle::where('is_active', '1')->get(array('id', 'image_urls'));
        foreach($cars as $car)
        {
            if($car->image_urls == '')
                continue;
            $images = explode('|', $car->image_urls);
            $aValid = array();
            foreach($images as $image)
            {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $image);
                curl_setopt($ch, CURLOPT_NOBODY, true);
                curl_exec($ch);

                if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == '200')
                {
                    $aValid[] = $image;
                }

                curl_close($ch);
            }
            $this->info('Vehicle ID '.$car->id.' has '.count($aValid).' images.');
            $car->image_urls = implode('|', $aValid);
            $car->save();
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