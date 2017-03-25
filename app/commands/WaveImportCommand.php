<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class WaveImportCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'wave';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import wave of savings merchants.';

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

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/wave_merchants2.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE)
            {
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("wave_merchants.csv ".$num." columns\n");
            }

            $counter=0;
            $company = \SOE\DB\Company::where('name', 'The Wave of Savings')->first();
            $awsAccessKey = 'AKIAI6NWHQYFADWZVYLQ';
            $awsSecretKey = '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';
            $bucketName = 'saveoneverything_assets';
            $s3path = "http://s3.amazonaws.com/saveoneverything_assets/";
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {  
                $counter++;
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    $existing = \SOE\DB\Merchant::where('display', $this->clean($data[1]))->first();
                    if($existing)
                        continue;

                    $aPieces = explode(',', $data[4]);
                    if(!count($aPieces))
                        continue;

                    if(trim($data[6]) != '' && trim($data[7] != ''))
                        $cartesian = \SoeHelper::getCartesian(trim($data[6]), trim($data[7]));

                    $category = \SOE\DB\Category::where('name', $data[9])->first();
                    $subcategory = \SOE\DB\Category::where('name', $data[10])->first();

                    if(!$category || !$subcategory)
                    {
                        echo "No Category";
                        print_r($data);
                        continue;
                    }

                    $clean = $this->clean($data[1]);
                    $merchant = new \SOE\DB\Merchant;
                    $merchant->name = $clean;
                    $merchant->display = $clean;
                    $merchant->slug = \SoeHelper::getSlug($clean);
                    $merchant->type = 'RETAIL';
                    $merchant->website = $this->fixWebsite($data[8]);
                    $merchant->phone = trim($data[5]);
                    $merchant->max_prints = 1;
                    $merchant->mobile_redemption = 1;
                    $merchant->category_id = $category->id;
                    $merchant->subcategory_id = $subcategory->id;
                    $merchant->save();

                    $file = fopen ($data[3], "rb");
                    if($file) {
                        $newf = fopen (storage_path().'/'.basename($data[3]), "wb");
                        if($newf)
                            while(!feof($file)) {
                                fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );
                            }
                    }
                    if($file) {
                        fclose($file);
                    }
                    if($newf) {
                        $path = "wave/images/".time()."-GRlogo"."-".basename($data[3]);
                        $s3 = new S3($awsAccessKey, $awsSecretKey);
                        if($s3->putObjectFile(storage_path().'/'.basename($data[3]), $bucketName, $path, S3::ACL_PUBLIC_READ))
                        {
                            $merch_asset = new \SOE\DB\Asset;
                            $merch_asset->type = 'image';
                            $merch_asset->path = $this->httpImage($s3path.$path);
                            $merch_asset->name = 'logo1';
                            $merch_asset->assetable_id = $merchant->id;
                            $merch_asset->assetable_type = 'Merchant';
                            $merch_asset->save();
                        }
                        else
                        {
                            echo "Could not upload logo";
                            print_r($data);
                        }
                        fclose($newf);
                    }

                    $franchise = new \SOE\DB\Franchise;
                    $franchise->name = $clean;
                    $franchise->company_id = $company->id;
                    $franchise->merchant_id = $merchant->id;
                    $franchise->save();

                    $zipcode = \SOE\DB\Zipcode::where('city', trim($aPieces[1]))
                        ->where('state', 'MI')
                        ->where('zipcodetype', '=', 'STANDARD')
                        ->where(function($query)
                        {
                            $query->where('locationtype', '=', 'PRIMARY');
                            $query->orWhere('locationtype', '=', 'ACCEPTABLE');
                        })
                        ->first();
                    if(!$zipcode && (trim($data[6]) == '' || trim($data[7]) == ''))
                    {
                        if(trim($aPieces[4]) != '')
                        {
                            $zipcode = \SOE\DB\Zipcode::where('zipcode', trim($aPieces[4]))->first();
                            if(!$zipcode)
                            {
                                echo "Skipped";
                                print_r($data);
                                continue;
                            }
                        }
                        else
                        {
                            echo "Skipped";
                            print_r($data);
                            continue;
                        }
                    }
                    $location = new \SOE\DB\Location;
                    $location->name = trim($aPieces[1]).' Location';
                    $location->slug = \SoeHelper::getSlug(trim($aPieces[1]).' Location');
                    $location->address = trim($aPieces[0]);
                    $location->city = trim($aPieces[1]);
                    $location->state = 'MI';
                    $location->zip = trim($data[3]) == '' ? ($zipcode ? $zipcode->zipcode : '') : trim($data[3]);
                    $location->latitude = trim($data[6]) == '' ? $zipcode->latitude : trim($data[6]);
                    $location->longitude = trim($data[7]) == '' ? $zipcode->longitude : trim($data[7]);
                    $location->phone = trim($data[5]);
                    $location->website = $this->fixWebsite($data[8]);
                    $location->rating = 3.5;
                    $location->merchant_id = $merchant->id;
                    $location->franchise_id = $franchise->id;
                    $location->company_id = $company->id;
                    $location->latm = trim($data[6]) == '' ? $zipcode->latm : $cartesian['latm'];
                    $location->lngm = trim($data[7]) == '' ? $zipcode->lngm : $cartesian['lngm'];
                    $location->merchant_name = $merchant->display;
                    $location->merchant_slug = $merchant->slug;
                    $location->subheader = '{Online|Free} [merchant] coupons{, deals & sweepstakes} from SaveOn help you save money on [category] and more. Click here to {print|search for} <a href="/coupons/[state_lower]/[city_slug]/[category_slug]/[subcategory_slug]">[subcategory] coupons</a> {near you|in [city], [state]|close by|in your local area}.';
                    $location->save();
                }
            }
        }
    }

    protected function logos()
    {
        set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '2048M');
        

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/wave_merchants.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE)
            {
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("wave_merchants.csv ".$num." columns\n");
            }

            $counter=0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {  
                $counter++;
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    
                }
            }
        }
    }

    protected function clean($string)
    {
        $string = trim($string);
        return trim(str_replace('&amp;', '&', str_replace('&#039;', "'", $string)));
    }

    protected function fixWebsite($string)
    {
        $string = trim($string);
        return stristr($string, 'http://') ? $string : 'http://'.$string;
    }

    protected function httpImage($path)
    {
        return str_replace('https', 'http', $path);
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