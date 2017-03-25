<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class InventoryImportCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'inventory_import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import used car inventory from a vendor.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->categories = \App::make('CategoryRepositoryInterface');
        $this->dealerRelations = \App::make('DealerRelationRepositoryInterface');
        $this->franchises = \App::make('FranchiseRepositoryInterface');
        $this->leadEmailRepository = App::make('LeadEmailRepositoryInterface');
        $this->locations = \App::make('LocationRepositoryInterface');
        $this->merchants = \App::make('MerchantRepositoryInterface');
        $this->zipcodes = \App::make('ZipcodeRepositoryInterface');
        $this->vehicleEntities = \App::make('VehicleEntityRepositoryInterface');
        $this->vehicleModels = \App::make('VehicleModelRepositoryInterface');
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        set_time_limit(60*120); // 2 hours
        ini_set('memory_limit', '4096M');

        $type = $this->option('type');
        switch ($type)
        {
            case 'get_dt_file':
                $this->getDTFile();
                break;
            case 'dt_dealers':
                $this->detroitTradingDealers();
                break;
            case 'dt_inventory':
                $this->detroitTradingInventory();
                break;
            case 'count':
                $this->lineCount();
                break;
            case 'indexes':
                $this->indexes();
                break;
            case 'dealer_specialties':
                $this->dealerSpecialties();
                break;
            case 'vauto':
                $this->vAuto();
                break;
            case 'soct_dealers':
                $this->importSoctDealers();
                break;
            case 'soct_inventory':
                $this->importSoctInventory();
                break;
            case 'merge_vehicles':
                $this->mergeVehicles();
                break;
            default:
                $this->getDTFile();
                break;
        }
    }

    protected function getDTFile()
    {
        $this->info('Connecting to FTP server');
        $local_file = public_path().'/dtcinventory.zip';
        $server_file = 'dtcinventory.zip';
        $conn_id = ftp_connect(Config::get('integrations.detroit_trading.ftp'));

        $login_result = ftp_login($conn_id, Config::get('integrations.detroit_trading.user'), Config::get('integrations.detroit_trading.password'));
        $this->info('Logged in successfully');
        ftp_pasv($conn_id, true);
        if(ftp_get($conn_id, $local_file, $server_file, FTP_BINARY))
        {
             $this->info("Successfully written to $local_file");
        }
        else
        {
            $this->info("There was a problem");
            return;
        }
        ftp_close($conn_id);
        
        $zip = new ZipArchive;
        $res = $zip->open($local_file);
        if($res === TRUE)
        {
            $zip->extractTo(public_path());
            $zip->close();
            $this->info('Zip File Extracted');
            unlink(public_path().'/dtcinventory.zip');
        }
        else
        {
            try
            {
                unlink(public_path().'/dtcinventory.zip');
                $this->info('Error extracting zip file');
            }
            catch(\Exception $e)
            {
                $this->info('Error extracting zip file');
                $log = new \SOE\DB\SysLog();
                $log->type = 'Inventory Import';
                $log->message = 'Error - Error downloading DT Inventory file. '.$e->getMessage();
                $log->save();
            }
            return;
        }

        /*$this->info('Writing files to S3');
        $fileStore = App::make('FileStoreInterface');
        $inventory = fopen(sys_get_temp_dir().'/dtcinventory.txt', "r");
        $stored = $fileStore->storeFromTemp($inventory, 'dtcinventory.txt', 'inventory/detroit_trading/');
        fclose($inventory);
        $this->info('dtcinventory.txt written to S3');
        $dealers = fopen(sys_get_temp_dir().'/dtcdealers.txt', "r");
        $stored = $fileStore->store($dealers, 'inventory/detroit_trading/');
        fclose($dealers);
        $this->info('dtcdealers.txt written to S3');
        $this->info('Files Uploaded to S3');*/
    }

    protected function detroitTradingDealers()
    {
        $this->info("Importing Detroit Trading Dealers...\n");
        $file = public_path().'/dtcdealers.txt';
        $dealerCounter=0;
        $category = $this->categories->findBySlug('auto-transportation');
        $subcategory = $this->categories->findBySlug('auto-dealers');
        if (($handle = fopen(/*"http://s3.amazonaws.com/saveoneverything_uploads/inventory/detroit_trading/dtcdealers.txt"*/$file, "r")) !== FALSE)
        {
            $aMerchants = array();
            $merchants = $this->merchants->getByVendor('detroit_trading');
            foreach($merchants as $merchant)
            {
                $aMerchants[$merchant->old_id] = $merchant;
            }

            // Skip label row
            $data = fgetcsv($handle, null, "\t");
            // Load zipcodes
            $aZips = array();
            while (($data = fgetcsv($handle, null, "\t")) !== FALSE)
            {
                $aZips[] = $data[5];
            }
            fclose($handle);
            $zipcodes = $this->zipcodes->findByZipcode($aZips);
            $aZipcodes = array();
            foreach($zipcodes as $zip)
            {
                $aZipcodes[$zip->zipcode] = $zip;
            }

            $handle = fopen(/*"http://s3.amazonaws.com/saveoneverything_uploads/inventory/detroit_trading/dtcdealers.txt"*/$file, "r");
            // Skip label row
            $data = fgetcsv($handle, null, "\t");
            while (($data = fgetcsv($handle, null, "\t")) !== FALSE)
            {   
                //$merchant = $this->merchants->findByOldIdVendor($data[0], 'detroit_trading');
                $merchant = isset($aMerchants[$data[0]]) ? $aMerchants[$data[0]] : null;
                //$zipcode = $this->zipcodes->findByZipcode($data[5]);
                if(!isset($aZipcodes[$data[5]]))
                    continue;
                $zipcode = $aZipcodes[$data[5]];
                if(!$merchant)
                {
                    $merchant = $this->merchants->blank();
                    $merchant->display = $data[1];
                    $merchant->name = SoeHelper::removePunctuation($data[1]);
                    $merchant->slug = SoeHelper::getSlug($data[1]);
                    $merchant->type = 'PPL';
                    $merchant->old_id = $data[0];
                    $merchant->vendor = 'detroit_trading';
                    $merchant->new_disclaimer = $data[6];
                    $merchant->used_disclaimer = $data[7];
                    $merchant->category_id = $category->id;
                    $merchant->subcategory_id = $subcategory->id;
                    $merchant->save();

                    $franchise = $this->franchises->blank();
                    $franchise->name = $data[1];
                    $franchise->merchant_id = $merchant->id;
                    $franchise->is_dealer = 1;
                    $franchise->is_used_car_leads = 1;
                    $franchise->allow_directed_leads = 1;
                    $franchise->zipcode = $data[5];
                    $franchise->radius = 80000;
                    $franchise->service_plan = 'basic';
                    $franchise->save();

                    $location = $this->locations->blank();
                    $location->merchant_id = $merchant->id;
                    $location->franchise_id = $franchise->id;
                    $location->name = $data[3];
                    $location->slug = SoeHelper::getSlug($data[3]);
                    $location->address = $data[2];
                    $location->city = $data[3];
                    $location->state = strtoupper($data[4]);
                    $location->zip = $data[5];
                    $location->latitude = $zipcode->latitude;
                    $location->longitude = $zipcode->longitude;
                    $location->latm = $zipcode->latm;
                    $location->lngm = $zipcode->lngm;
                    $location->merchant_name = $data[1];
                    $location->merchant_slug = SoeHelper::getSlug($data[1]);
                    $location->save();
                }
                else
                {
                    /*$merchant = $this->merchants->findByOldIdVendor($data[0], 'detroit_trading');
                    $merchant->name = SoeHelper::removePunctuation($data[1]);
                    $merchant->slug = SoeHelper::getSlug($data[1]);
                    $merchant->new_disclaimer = $data[6];
                    $merchant->used_disclaimer = $data[7];
                    $merchant->save();*/

                    /*$franchise = $this->franchises->findByCompanyMerchant(1, $merchant->id);
                    $franchise->name = $data[1];
                    $franchise->zipcode = $data[5];
                    $franchise->save();*/

                    /*$locations = $this->locations->getByFranchise($franchise);
                    foreach($locations as $location)
                    {
                        $location->name = $data[3];
                        $location->slug = SoeHelper::getSlug($data[3]);
                        $location->address = $data[2];
                        $location->city = $data[3];
                        $location->state = strtoupper($data[4]);
                        $location->zip = $data[5];
                        $zipcode = $this->zipcodes->findByZipcode($data[5]);
                        $location->latitude = $zipcode->latitude;
                        $location->longitude = $zipcode->longitude;
                        $location->latm = $zipcode->latm;
                        $location->lngm = $zipcode->lngm;
                        $location->merchant_name = $data[1];
                        $location->merchant_slug = SoeHelper::getSlug($data[1]);
                        $location->save();
                    }*/
                }
                $dealerCounter++;
            }
            fclose($handle);
        }
        else
        {
            $log = new \SOE\DB\SysLog();
            $log->type = 'Inventory Import';
            $log->message = 'Error - Cannot find '.public_path().'/dtcdealers.txt.';
            $log->save();
            exit;
        }
        $this->info("Loaded Info for $dealerCounter Dealers.\n");
    }

    protected function lineCount()
    {
        //$file="largefile.txt";
        $file = public_path().'/dtcinventory.txt';
        $linecount = 0;
        $handle = fopen($file, "r");
        while(!feof($handle)){
          $line = fgets($handle, 4096);
          $linecount = $linecount + substr_count($line, PHP_EOL);
        }

        fclose($handle);

        echo $linecount;
    }

    protected function detroitTradingInventory()
    {
        set_time_limit(60*240); // 4 hours
        ini_set('memory_limit', '4096M');
        $file = public_path().'/dtcinventory.txt';

        if(($handle = fopen($file, "r")) === FALSE)
        {
            $log = new \SOE\DB\SysLog();
            $log->type = 'Inventory Import';
            $log->message = 'Error - Cannot find '.public_path().'/dtcinventory.txt.';
            $log->save();
            exit;
        }

        $this->info('Cleaning the inventory file');
        $fh = fopen(public_path().'/clean-dtcinventory.txt', 'w+');
        while (($buffer = fgets($handle, 4096)) !== false)
        {
            $buffer = str_replace('"', '', $buffer);
            fputs($fh, $buffer);
        }
        fclose($fh);
        fclose($handle);

        $fileClean = public_path().'/clean-dtcinventory.txt';
        $vehicleCounter=0;
        $rowCounter = 0;
        
        if (($handle = fopen($fileClean, "r")) !== FALSE)
        {
            /*try
            {
                $this->info('Creating temp table');
                DB::connection('mysql-used')->statement('CREATE TABLE vehicle_entities_temp LIKE vehicle_entities;');
            }
            catch(\Exception $e)
            {
                $this->info('Temp already exists, removing.');
                Schema::connection('mysql-used')->drop('vehicle_entities_temp');
                DB::connection('mysql-used')->statement('CREATE TABLE vehicle_entities_temp LIKE vehicle_entities;');
            }

            try
            {
                Schema::connection('mysql-used')->table('vehicle_entities_temp', function($table)
                {
                    $table->dropIndex('vehicle_entities_state_internet_price_latm_lngm_index');
                });
            }
            catch(\Exception $e)
            {
                $this->info('Index is already dropped.');
            }

            try
            {
                Schema::connection('mysql-used')->table('vehicle_entities_temp', function($table)
                {
                    $table->dropIndex('vehicle_entities_merchant_id_make_model_index');
                });
            }
            catch(\Exception $e)
            {
                $this->info('Index is already dropped.');
            }

            try
            {
                Schema::connection('mysql-used')->table('vehicle_entities_temp', function($table)
                {
                    $table->dropIndex('vehicle_entities_vin_vendor_index');
                });
            }
            catch(\Exception $e)
            {
                $this->info('Index is already dropped.');
            }*/

            // Skip label row
            $data = fgetcsv($handle, null, "\t");
            $updated_at = date('Y-m-d H:i:s');
            $rows = array();

            $aMerchants = array();
            $merchants = $this->merchants->getByVendor('detroit_trading');
            foreach($merchants as $merchant)
            {
                $aMerchants[$merchant->old_id] = $merchant;
            }

            $aLocations = array();
            $locations = $this->locations->getByVendor('detroit_trading');
            foreach($locations as $location)
            {
                $aLocations[$location->merchant_id] = $location;
            }

            $aMakes = $this->getMakesArray();

            $aModels = array();
            $models = $this->vehicleModels->getNames();
            foreach($models as $model)
            {
                $aModels[$model->name] = $model->id;
            }

            $this->info('Writing formatted data to csv...');
            $fileVehicles = fopen(public_path()."/vehicles.csv","w");
            while (($data = fgetcsv($handle, null, "\t")) !== FALSE)
            {   
                if(count($data) < 39)
                    continue;
                $merchant = isset($aMerchants[$data[29]]) ? $aMerchants[$data[29]] : null;
                if(!$merchant)
                    continue;
                $location = isset($aLocations[$merchant->id]) ? $aLocations[$merchant->id] : null;
                if(!$location)
                    continue;
                $aImages = explode('|', $data[33]);
                $aRealImages = array();
                foreach($aImages as $image)
                {
                    if(trim($image) == '')
                        continue;
                    $image = 'http://img.leaddelivery.net/images/'.$data[2].'/Supersized/'.$image.'.jpg?s='.\Config::get('integrations.detroit_trading.user');
                    $aRealImages[] = $image;
                }
                $images = implode('|', $aRealImages);

                try
                {
                    $make = isset($aMakes['names'][$data[5]]) ? $aMakes['names'][$data[5]] : (isset($aMakes['slugs'][$data[5]]) ? $aMakes['slugs'][$data[5]] : null);
                    if(!$make)
                        continue;
                    $make_id = $make->id;
                    $model_id = isset($aModels[$data[6]]) ? $aModels[$data[6]] : 0;
                    $model_year_id = 0;
                }
                catch(\Exception $e)
                {
                    $make_id = 0;
                    $model_id = 0;
                    $model_year_id = 0;
                }
                
                $aTypeCodes = array(
                    'C' => 'car',
                    'S' => 'suv',
                    'T' => 'truck',
                    'V' => 'van'
                );

                $aTrans = array(
                    'A' => 'Automatic',
                    'AT' => 'Automatic',
                    'M' => 'Manual',
                    'MT' => 'Manual',
                    'C' => 'Continuously Variable',
                    'CVT' => 'Continuously Variable'
                );

                $row = array(
                    date('Y-m-d H:i:s'),
                    date('Y-m-d H:i:s'),
                    null,
                    'detroit_trading',
                    $data[29],
                    '',
                    $data[0],
                    strtolower($data[1]),
                    $data[3],
                    $data[2],
                    $data[4],
                    $model_year_id,
                    $make_id,
                    $data[5],
                    $model_id,
                    $data[6],
                    $data[31] == 'True' ? 1 : 0,
                    0,
                    $data[24],
                    $data[8],
                    isset($aTypeCodes[trim(strtoupper($data[19]))]) ? $aTypeCodes[trim(strtoupper($data[19]))] : '',
                    $data[18],
                    $data[15],
                    $data[13].' '.$data[12],
                    '',
                    isset($aTrans[trim(strtoupper($data[16]))]) ? $aTrans[trim(strtoupper($data[16]))] : '',
                    $data[11],
                    $data[7],
                    $data[25],
                    $data[26],
                    $data[21],
                    $data[22],
                    $data[27],
                    $data[28],
                    0,
                    $data[23],
                    count($aRealImages),
                    implode('|', $aRealImages),
                    count($aRealImages) ? $aRealImages[0] : '',
                    $merchant->display,
                    $merchant->slug,
                    $merchant->id,
                    $location->id,
                    $location->address,
                    $location->city,
                    $location->state,
                    $location->zip,
                    '',
                    $location->latitude,
                    $location->longitude,
                    $location->latm,
                    $location->lngm,
                    $data[38] == 'True' ? 1 : 0,
                    null,
                    'detroit_trading',
                    $data[30],
                    $data[35],
                    $data[36],
                    $data[37],
                    $data[38],
                    $data[39]
                );

                $vehicleCounter++;
                $rowCounter++;
                fputcsv($fileVehicles,$row);
            }

            fclose($fileVehicles);
            // Remove old inventory entries
            \DB::connection('mysql-used')->table('dealer_entities')->where('vendor', 'detroit_trading')
                ->where('source', 'detroit_trading')
                ->delete();
            try
            {
                Schema::connection('mysql-used')->table('dealer_entities', function($table)
                {
                    $table->dropIndex('dealer_entities_source_vin_index');
                });
            }
            catch(\Exception $e)
            {
                $this->info('Index is already dropped.');
            }
            $this->info('Beginning Inserts...');
            //$query = sprintf("LOAD DATA LOCAL INFILE '".public_path()."/vehicles.csv' INTO TABLE vehicle_entities_temp FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' ESCAPED BY '\"' LINES TERMINATED BY '\\n' IGNORE 0 LINES");
            $query = sprintf("LOAD DATA LOCAL INFILE '".public_path()."/vehicles.csv' INTO TABLE dealer_entities FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' ESCAPED BY '\"' LINES TERMINATED BY '\\n' IGNORE 0 LINES");
            $this->info("Loaded Info for $rowCounter DT Vehicles.\n");
            DB::connection('mysql-used')->getpdo()->exec($query);
            unlink(public_path()."/vehicles.csv");
            try
            {
                Schema::connection('mysql-used')->table('dealer_entities', function($table)
                {
                    $table->index(array('source', 'vin'));
                });
            }
            catch(\Exception $e)
            {
                // Index already exists
            }
            $this->recordSync('detroit_trading');
            //DB::connection('mysql-used')->getpdo()->exec($query);
            
            /*try
            {
                $this->importSoctDealers();
            }
            catch(\Exception $e)
            {
                $log = new \SOE\DB\SysLog();
                $log->type = 'Inventory Import';
                $log->message = 'Error - Error importing SOCT dealers. '.$e->getMessage();
                $log->save();
            }

            try
            {
                $this->importSoctInventory();
            }
            catch(\Exception $e)
            {
                $log = new \SOE\DB\SysLog();
                $log->type = 'Inventory Import';
                $log->message = 'Error - Error importing SOCT inventory. '.$e->getMessage();
                $log->save();
            }

            try
            {
                DB::connection('mysql-used')->statement('RENAME TABLE `vehicle_entities` TO `vehicle_entities_old`;');
            }
            catch(\Exception $e)
            {
                $this->info('Old table exists, dropping and recreating.');
                Schema::connection('mysql-used')->drop('vehicle_entities_old');
                DB::connection('mysql-used')->statement('RENAME TABLE `vehicle_entities` TO `vehicle_entities_old`;');
            }
            DB::connection('mysql-used')->statement('RENAME TABLE `vehicle_entities_temp` TO `vehicle_entities`;');
            Schema::connection('mysql-used')->drop('vehicle_entities_old');

            $this->info('Adding indexes');
            Schema::connection('mysql-used')->table('vehicle_entities', function($table)
            {
                $table->index(array('state', 'internet_price', 'latm', 'lngm'));
            });
            Schema::connection('mysql-used')->table('vehicle_entities', function($table)
            {
                $table->index(array('merchant_id', 'make', 'model'));
            });
            Schema::connection('mysql-used')->table('vehicle_entities', function($table)
            {
                $table->index(array('vin', 'vendor'));
            });*/

            unlink($fileClean);
        }
        
    }

    protected function indexes()
    {
        //Drop Indexes
        try
        {
            Schema::connection('mysql-used')->table('vehicle_entities', function($table)
            {
                $table->dropIndex('vehicle_entities_state_internet_price_latm_lngm_index');
            });
        }
        catch(\Exception $e)
        {
            $this->info('Index is already dropped.');
        }

        try
        {
            Schema::connection('mysql-used')->table('vehicle_entities', function($table)
            {
                $table->dropIndex('vehicle_entities_merchant_id_make_model_index');
            });
        }
        catch(\Exception $e)
        {
            $this->info('Index is already dropped.');
        }

        try
        {
            Schema::connection('mysql-used')->table('vehicle_entities', function($table)
            {
                $table->dropIndex('vehicle_entities_vin_vendor_index');
            });
        }
        catch(\Exception $e)
        {
            $this->info('Index is already dropped.');
        }

        //Add Indexes
        Schema::connection('mysql-used')->table('vehicle_entities', function($table)
        {
            $table->index(array('state', 'internet_price', 'latm', 'lngm'));
        });
        $this->info('Index added.');
        Schema::connection('mysql-used')->table('vehicle_entities', function($table)
        {
            $table->index(array('merchant_id', 'make', 'model'));
        });
        $this->info('Index added.');
        Schema::connection('mysql-used')->table('vehicle_entities', function($table)
        {
            $table->index(array('vin', 'vendor'));
        });
        $this->info('Index added.');

    }

    protected function importSoctDealers()
    {
        set_time_limit(60*60); // 60 Mins
        ini_set('memory_limit', '2048M');
        $this->info('Importing SOCT Dealers...');
        $dealers = DB::connection('soct_old')->table('dealerships')->get();
        foreach($dealers as $dealer)
        {
            $existing = \SOE\DB\Merchant::on('mysql-write')
                                        ->join('franchises', 'merchants.id', '=', 'franchises.merchant_id')
                                        ->where('merchants.name', '=', $this->removePunctuation($this->removeHtml($dealer->name)))
                                        ->where('merchants.vendor', '=', 'soct')
                                        ->first(array('merchants.*', DB::raw('franchises.id as franchise_id')));
            if($existing)
                $this->importSoctExistingDealer($existing, $dealer);
            else
                $this->importSoctNewDealer($dealer);
        }
        $this->info('Done Importing SOCT Dealers.');
    }

    protected function importSoctNewDealer($dealer)
    {
        $merchant = new \SOE\DB\Merchant;
        $merchant->name = $this->removePunctuation($this->removeHtml($dealer->name));
        $merchant->display = $this->removeHtml($dealer->name);
        $merchant->slug = SoeHelper::getSlug($this->removeHtml($dealer->name));
        $merchant->type = 'PPL';
        $merchant->about = $this->removeHtml($dealer->description);
        $merchant->catchphrase = $this->removeHtml($dealer->catchPhrase);
        $merchant->website = $dealer->website;
        $merchant->hours = $dealer->hours;
        $merchant->phone = $dealer->phone;
        $merchant->category_id = 10;
        $merchant->subcategory_id = 120;
        $merchant->is_demo = 0;
        $merchant->is_active = $dealer->isActive;
        $merchant->max_prints = 1;
        $merchant->mobile_redemption = 1;
        $merchant->rating = 3.5;
        $merchant->is_displayed = 1;
        $merchant->tnl_id = $dealer->tnlID;
        $merchant->old_id = $dealer->id;
        $merchant->vendor = 'soct';
        $merchant->save();

        if($dealer->imagePath != '')
        {
            $logo = new \SOE\DB\Asset;
            $logo->assetable_id = $merchant->id;
            $logo->assetable_type = 'Merchant';
            $logo->path = 'http://saveoncarsandtrucks.com'.$dealer->imagePath;
            $logo->name = 'logo1';
            $logo->type = 'image';
            $logo->save();
        }

        $franchise = new \SOE\DB\Franchise;
        $franchise->name = $merchant->name;
        $franchise->company_id = 1;
        $franchise->merchant_id = $merchant->id;
        $franchise->is_active = $dealer->isActive;
        $franchise->max_prints = 1;
        $franchise->mobile_redemption = 1;
        $franchise->is_demo = 0;
        $franchise->service_plan = 'basic';
        $franchise->is_used_car_leads = 1;
        $franchise->is_new_car_leads = 1;
        
        $franchise->save();

        if($dealer->dealerEmail != '')
        {
            $this->leadEmailRepository->addEmails($franchise, array(array('email' => $dealer->dealerEmail, 'format' => 'pretty')));
            $franchise->is_dealer = 1;
        }
        else if(!empty($franchise->primary_contact))
        {
            $this->leadEmailRepository->addEmails($franchise, array(array('email' => $franchise->primary_contact, 'format' => 'pretty')));
            $franchise->is_dealer = 1;
        }
        $franchise->zipcode = $dealer->zip;
        $franchise->radius = 50*1600;
        $franchise->monthly_budget = 100;
        $franchise->contact_phone = $dealer->phone;
        $franchise->save();

        $location = new \SOE\DB\Location;
        $location->name = $this->removeHtml($dealer->name);
        $location->slug = SoeHelper::getSlug($this->removeHtml($dealer->name));
        $location->is_demo = 0;
        $location->is_active = $dealer->isActive;
        $location->address = $dealer->address1;
        $location->address2 = $dealer->address2;
        $location->city = $dealer->city;
        $location->state = $dealer->state;
        $location->zip = $dealer->zip;
        $location->latitude = $dealer->lat;
        $location->longitude = $dealer->lng;
        $location->hours = $dealer->hours;
        $location->phone = $dealer->phone;
        $location->website = $dealer->website;
        $location->rating = 3.5;
        $location->merchant_id = $merchant->id;
        $location->franchise_id = $franchise->id;
        $location->company_id = 1;
        $cartesian = SoeHelper::getCartesian((double) $dealer->lat, (double) $dealer->lng);
        $location->latm = $cartesian['latm'];
        $location->lngm = $cartesian['lngm'];
        $location->merchant_name = $merchant->display;
        $location->merchant_slug = $merchant->slug;
        $location->save();
    }

    protected function importSoctExistingDealer($merchant, $dealer)
    {
        $merchant->type = 'PPL';
        $merchant->tnl_id = $dealer->tnlID;
        $merchant->old_id = $dealer->id;
        $merchant->vendor = 'soct';
        $merchant->save();

        if($dealer->imagePath != '')
        {
            $logo = \SOE\DB\Asset::where('assetable_id', '=', $merchant->id)
                                ->where('assetable_type', '=', 'Merchant')
                                ->where('name', '=', 'logo1')->first();
            if(!$logo)
            {
                $logo = new \SOE\DB\Asset;
                $logo->assetable_id = $merchant->id;
                $logo->assetable_type = 'Merchant';
                $logo->name = 'logo1';
                $logo->type = 'image';
                $logo->path = 'http://saveoncarsandtrucks.com'.$dealer->imagePath;
                $logo->save();
            }
        }

        $franchise = \SOE\DB\Franchise::where('merchant_id', '=', $merchant->id)->first();
        $franchise->name = $merchant->name;
        $franchise->zipcode = $dealer->zip;
        $franchise->contact_phone = $dealer->phone;
        $franchise->save();

        if($dealer->dealerEmail != '')
        {
            $this->leadEmailRepository->addEmails($franchise, array(array('email' => $dealer->dealerEmail, 'format' => 'pretty')));
            $franchise->is_dealer = 1;
        }
        else if(!empty($franchise->primary_contact))
        {
            $this->leadEmailRepository->addEmails($franchise, array(array('email' => $franchise->primary_contact, 'format' => 'pretty')));
            $franchise->is_dealer = 1;
        }
        $franchise->save();
        $franchise->touch();
    }

    protected function importSoctInventory()
    {
        set_time_limit(60*60); // 60 Mins
        ini_set('memory_limit', '2048M');

        $this->info("Importing SOCT Inventory...\n");
        $cars = DB::connection('soct_old')->table('used_cars_live')->get();
        $updated_at = date('Y-m-d H:i:s');
        Eloquent::unguard();

        $aLinked = array();
        $linkedRelations = $this->dealerRelations->getLinkedByType('dealer_specialties');
        foreach($linkedRelations as $linked)
        {
            if($linked->tnl_id && $linked->merchant_vendor == 'soct')
                $aLinked[] = $linked->tnl_id;
        }

        // Remove old inventory entries
        \DB::connection('mysql-used')->table('dealer_entities')
            ->where('vendor', 'soct')
            ->where('source', 'soct')
            ->delete();
        try
        {
            Schema::connection('mysql-used')->table('dealer_entities', function($table)
            {
                $table->dropIndex('dealer_entities_source_vin_index');
            });
        }
        catch(\Exception $e)
        {
            $this->info('Index is already dropped.');
        }
        foreach($cars as $car)
        {
            // If this dealer is being imported from dealer specialties, skip
            if(in_array($car->tnlID, $aLinked))
                continue;
            $merchant = \SOE\DB\Merchant::on('mysql-write')->where('tnl_id', '=', $car->tnlID)->first();
            if(empty($merchant))
                continue;
            $location = \SOE\DB\Location::on('mysql-write')->where('merchant_id', '=', $merchant->id)->first();
            if(empty($location))
                continue;
            $make = $this->getMake($car->make);
            if(!$make)
                continue;
            $model = \SOE\DB\VehicleModel::on('mysql')->where('make_id', '=', $make->id)->where('name', '=', $car->model)->first();
            $model_id = empty($model) ? 0 : $model->id;
            $model_year = \SOE\DB\VehicleYear::on('mysql')->where('model_id', '=', $model_id)->where('year', '=', $car->year)->first();
            $model_year_id = empty($model_year) ? 0 : $model_year->id;
            $aImages = explode('|', $car->imageURIs);

            $id = DB::connection('mysql-used')->table('dealer_entities')->insertGetId(array(
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'vendor_dealer_key' => $car->tnlID,
                'dealer_name' => $car->franchise,
                'dealer_slug' => SoeHelper::getSlug($car->franchise),
                'condition' => 'pre-owned',
                'address' => $car->address,
                'city' => $car->city,
                'state' => $car->state,
                'zipcode' => $car->zip,
                'phone' => $car->phone,
                'latitude' => $car->latitude,
                'longitude' => $car->longitude,
                'stock_number' => $car->stockNumber,
                'vin' => $car->vin,
                'year' => $car->year,
                'model_year_id' => $model_year_id,
                'make' => $make->name,
                'make_id' => $make->id,
                'model' => $car->model,
                'model_id' => $model_id,
                'mileage' => $car->mileage,
                'is_certified' => $car->certified,
                'class' => $car->class,
                'body_type' => $car->bodyStyle,
                'fuel' => $car->fuel,
                'engine' => $car->engine,
                'cylinders' => $car->cylinders,
                'transmission' => $car->transmission,
                'drive_type' => $car->driveType,
                'trim_level' => $car->trimLevel,
                'exterior_color' => $car->exteriorColor,
                'interior_color' => $car->interiorColor,
                'options' => $car->dealerSpecifiedFeatures,
                'dealer_comments' => $car->dealerComments,
                'msrp' => $car->msrp,
                'internet_price' => $car->internetPrice,
                'image_count' => count($aImages),
                'image_urls' => $car->imageURIs,
                'display_image' => count($aImages) ? $aImages[0] : '',
                'merchant_id' => $merchant->id,
                'location_id' => $location->id,
                'latm' => $location->latm,
                'lngm' => $location->lngm,
                'vendor_dealer_id' => $merchant->old_id,
                'vendor' => 'soct',
                'vendor_inventory_id' => $car->id,
                'source' => 'soct'
            ));
        }
        try
        {
            Schema::connection('mysql-used')->table('dealer_entities', function($table)
            {
                $table->index(array('source', 'vin'));
            });
        }
        catch(\Exception $e)
        {
            // Index already exists
        }
        $this->recordSync('soct');
        $this->info('Done Importing SOCT Inventory.');
    }

    protected function dealerSpecialties()
    {
        $this->info('Connecting to FTP server');
        $local_file = public_path().'/dealerspecialties.zip';
        $server_file = date('ymd').'.zip';
        $conn_id = ftp_connect(Config::get('integrations.dealer_specialties.ftp'));

        $login_result = ftp_login($conn_id, Config::get('integrations.dealer_specialties.user'), Config::get('integrations.dealer_specialties.password'));
        $this->info('Logged in successfully');
        ftp_pasv($conn_id, true);
        if(ftp_get($conn_id, $local_file, $server_file, FTP_BINARY))
        {
             $this->info("Successfully written to $local_file");
        }
        else
        {
            $this->info("There was a problem");
            return;
        }
        ftp_close($conn_id);
        
        $zip = new ZipArchive;
        $res = $zip->open($local_file);
        if($res === TRUE)
        {
            $zip->extractTo(public_path());
            $zip->close();
            $this->info('Zip File Extracted');
            unlink(public_path().'/dealerspecialties.zip');
        }
        else
        {
            try
            {
                unlink(public_path().'/dealerspecialties.zip');
                $this->info('Error extracting zip file');
            }
            catch(\Exception $e)
            {
                $this->info('Error extracting zip file');
                $log = new \SOE\DB\SysLog();
                $log->type = 'Inventory Import';
                $log->message = 'Error - Error downloading Dealer Specialties file. '.$e->getMessage();
                $log->save();
            }
            return;
        }

        /** Get Dealer Info **/
        $this->info("Importing Dealer Specialties Dealers...\n");
        $file = public_path().'/LOTDATA.TXT';
        if (($handle = fopen($file, "r")) !== FALSE)
        {
            $aOld = array();
            $oldRelations = $this->dealerRelations->getByType('dealer_specialties');
            foreach($oldRelations as $old)
            {
                $aOld[$old->dealer_id] = $old->franchise_id;
            }
            // Skip label row
            $data = fgetcsv($handle, null, ",");
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {
                if(!isset($aOld[$data[0]]))
                {
                    // Enter new dealer into relations table
                    $this->dealerRelations->create(array(
                        'dealer_id' => $data[0],
                        'dealer_id_type' => 'dealer_specialties',
                        'dealer_name' => $data[2],
                        'dealer_address' => $data[3],
                        'dealer_city' => $data[5],
                        'dealer_state' => $data[6],
                        'dealer_zipcode' => $data[7],
                        'dealer_phone' => $data[8]
                    ));
                }
            }
            fclose($handle);
        }
        unlink(public_path().'/LOTDATA.TXT');

        /** Load vehicle info **/
        $this->info("Importing Dealer Specialties Vehicles...\n");
        $file = public_path().'/VEHICLES.TXT';
        if (($handle = fopen($file, "r")) !== FALSE)
        {
            $aLinked = array();
            $aDealerIds = array(0);
            $linkedRelations = $this->dealerRelations->getLinkedByType('dealer_specialties');
            foreach($linkedRelations as $linked)
            {
                $aLinked[$linked->dealer_id] = $linked;
                $aDealerIds[] = $linked->dealer_id;
            }
            // Remove old inventory entries for linked dealers
            \DB::connection('mysql-used')->table('dealer_entities')->whereIn('vendor_dealer_id', $aDealerIds)
                ->where('vendor', 'soct')
                ->where('source', 'dealer_specialties')
                ->delete();
            try
            {
                Schema::connection('mysql-used')->table('dealer_entities', function($table)
                {
                    $table->dropIndex('dealer_entities_source_vin_index');
                });
            }
            catch(\Exception $e)
            {
                $this->info('Index is already dropped.');
            }
            // Skip label row
            $data = fgetcsv($handle, null, ",");
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {
                if(!isset($aLinked[$data[0]]))
                    continue;

                $make = $this->getMake($data[6]);
                if(!$make)
                    continue;
                $model = \SOE\DB\VehicleModel::on('mysql')->where('make_id', '=', $make->id)->where('name', '=', $data[7])->first();
                $model_id = empty($model) ? 0 : $model->id;
                $model_year = \SOE\DB\VehicleYear::on('mysql')->where('model_id', '=', $model_id)->where('year', '=', $data[5])->first();
                $model_year_id = empty($model_year) ? 0 : $model_year->id;

                //print_r($aLinked[$data[0]]->latm);exit;
                $id = DB::connection('mysql-used')->table('dealer_entities')->insertGetId(array(
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'vendor_dealer_key' => $data[0].'-'.$data[2],
                    'dealer_name' => $aLinked[$data[0]]->merchant_name,
                    'dealer_slug' => $aLinked[$data[0]]->merchant_slug,
                    'condition' => $data[4] == 0 ? 'new' : 'pre-owned',
                    'address' => $aLinked[$data[0]]->dealer_address,
                    'city' => $aLinked[$data[0]]->dealer_city,
                    'state' => $aLinked[$data[0]]->dealer_state,
                    'zipcode' => $aLinked[$data[0]]->dealer_zipcode,
                    'phone' => $aLinked[$data[0]]->dealer_phone,
                    'latitude' => $aLinked[$data[0]]->latitude,
                    'longitude' => $aLinked[$data[0]]->longitude,
                    'stock_number' => $data[2],
                    'vin' => $data[1],
                    'year' => $data[5],
                    'model_year_id' => $model_year_id,
                    'make' => $data[6],
                    'make_id' => $make->id,
                    'model' => $data[7],
                    'model_id' => $model_id,
                    'mileage' => $data[12],
                    'body_type' => $data[9],
                    'fuel' => $data[18],
                    'engine' => $data[14],
                    'cylinders' => preg_replace('/[^\d]/i', '', $data[15]),
                    'transmission' => $data[13],
                    'drive_type' => $data[17],
                    'trim_level' => $data[8],
                    'exterior_color' => $data[25],
                    'interior_color' => $data[26],
                    'options' => $data[53],
                    'dealer_comments' => $data[52],
                    'msrp' => $data[32],
                    'internet_price' => $data[29],
                    'image_count' => 0,
                    'image_urls' => '',
                    'display_image' => '',
                    'merchant_id' => $aLinked[$data[0]]->merchant_id,
                    'location_id' => $aLinked[$data[0]]->location_id,
                    'latm' => $aLinked[$data[0]]->latm,
                    'lngm' => $aLinked[$data[0]]->lngm,
                    'vendor_dealer_id' => $data[0],
                    'vendor' => 'soct',
                    'vendor_inventory_id' => $data[2],
                    'source' => 'dealer_specialties'
                ));
            }
            fclose($handle);
        }

        // Get vehicle images
        $this->info("Importing Dealer Specialties Images...\n");
        $file = public_path().'/LINKS.TXT';
        if (($handle = fopen($file, "r")) !== FALSE)
        {
            try
            {
                Schema::connection('mysql-used')->table('dealer_entities', function($table)
                {
                    $table->index(array('source', 'vin'));
                });
            }
            catch(\Exception $e)
            {
                // Index already exists
            }
            $aImages = array();
            // Skip label row
            $data = fgetcsv($handle, null, ",");
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {
                if($data[4] == '')
                    continue;
                $count = explode('|', $data[4]);
                DB::connection('mysql-used')->table('dealer_entities')->where('vendor_dealer_id', $data[0])
                    ->where('vin', $data[1])
                    ->where('source', 'dealer_specialties')
                    ->update(array(
                        'image_count' => count($count),
                        'image_urls' => $data[4],
                        'display_image' => $count[0]
                    ));
            }
        }

        unlink(public_path().'/LINKS.TXT');
        unlink(public_path().'/VEHICLES.TXT');
        $this->recordSync('dealer_specialties');
    }

    protected function vAuto()
    {
        $this->info('Connecting to FTP server');
        $aLocals = array(
            public_path().'/INVENTORY-YYYY'.date('m').'DD.CSV',
        );
        $aServers = array(
            'INVENTORY-YYYY'.date('m').'DD.CSV'
        );
        $conn_id = ftp_connect(Config::get('integrations.vauto.ftp'));

        $login_result = ftp_login($conn_id, Config::get('integrations.vauto.user'), Config::get('integrations.vauto.password'));
        $this->info('Logged in successfully');
        ftp_pasv($conn_id, true);
        for($i=0; $i<count($aServers); $i++)
        {
            if(ftp_get($conn_id, $aLocals[$i], $aServers[$i], FTP_BINARY))
            {
                 $this->info("Successfully written to ".$aLocals[$i]);
            }
            else
            {
                $this->info("There was a problem");
                return;
            }
        }
        ftp_close($conn_id);

        /** Get Dealer Info **/
        $this->info("Importing VAUTO Dealers...\n");
        $file = public_path().'/INVENTORY-YYYY'.date('m').'DD.CSV';
        if (($handle = fopen($file, "r")) !== FALSE)
        {
            $aOld = array();
            $oldRelations = $this->dealerRelations->getByType('vauto');
            foreach($oldRelations as $old)
            {
                $aOld[$old->dealer_id] = $old->franchise_id;
            }
            // Skip label row
            $data = fgetcsv($handle, null, ",");
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {
                if(!isset($aOld[$data[0]]))
                {
                    // Enter new dealer into relations table
                    $this->dealerRelations->create(array(
                        'dealer_id' => $data[0],
                        'dealer_id_type' => 'vauto',
                        'dealer_name' => $data[25],
                        'dealer_address' => $data[35],
                        'dealer_city' => $data[36],
                        'dealer_state' => $data[38],
                        'dealer_zipcode' => $data[37],
                        'dealer_phone' => $data[40]
                    ));
                    $aOld[$data[0]] = 0;
                }
            }
            fclose($handle);
        }

        /** Load vehicle info **/
        $this->info("Importing VAUTO Vehicles...\n");
        $file = public_path().'/INVENTORY-YYYY'.date('m').'DD.CSV';
        if (($handle = fopen($file, "r")) !== FALSE)
        {
            $aLinked = array();
            $aDealerIds = array(0);
            $linkedRelations = $this->dealerRelations->getLinkedByType('vauto');
            foreach($linkedRelations as $linked)
            {
                $aLinked[$linked->dealer_id] = $linked;
                $aDealerIds[] = $linked->dealer_id;
            }
            // Remove old inventory entries for linked dealers
            \DB::connection('mysql-used')->table('dealer_entities')->whereIn('vendor_dealer_id', $aDealerIds)
                ->where('vendor', 'soct')
                ->where('source', 'vauto')
                ->delete();
            try
            {
                Schema::connection('mysql-used')->table('dealer_entities', function($table)
                {
                    $table->dropIndex('dealer_entities_source_vin_index');
                });
            }
            catch(\Exception $e)
            {
                $this->info('Index is already dropped.');
            }
            // Skip label row
            $data = fgetcsv($handle, null, ",");
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {
                if(!isset($aLinked[$data[0]]))
                    continue;

                $make = $this->getMake($data[3]);
                if(!$make)
                    continue;
                $model = \SOE\DB\VehicleModel::on('mysql')->where('make_id', '=', $make->id)->where('name', '=', $data[4])->first();
                $model_id = empty($model) ? 0 : $model->id;
                $model_year = \SOE\DB\VehicleYear::on('mysql')->where('model_id', '=', $model_id)->where('year', '=', $data[2])->first();
                $model_year_id = empty($model_year) ? 0 : $model_year->id;
                $aImages = explode('|', $data[23]);

                $id = DB::connection('mysql-used')->table('dealer_entities')->insertGetId(array(
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'vendor_dealer_key' => $data[0].'-'.$data[10],
                    'dealer_name' => $aLinked[$data[0]]->merchant_name,
                    'dealer_slug' => $aLinked[$data[0]]->merchant_slug,
                    'condition' => $data[1] == 'N' ? 'new' : 'pre-owned',
                    'address' => $aLinked[$data[0]]->dealer_address,
                    'city' => $aLinked[$data[0]]->dealer_city,
                    'state' => $aLinked[$data[0]]->dealer_state,
                    'zipcode' => $aLinked[$data[0]]->dealer_zipcode,
                    'phone' => $aLinked[$data[0]]->dealer_phone,
                    'latitude' => $aLinked[$data[0]]->latitude,
                    'longitude' => $aLinked[$data[0]]->longitude,
                    'stock_number' => $data[10],
                    'vin' => $data[11],
                    'year' => $data[2],
                    'model_year_id' => $model_year_id,
                    'make' => $data[3],
                    'make_id' => $make->id,
                    'model' => $data[4],
                    'model_id' => $model_id,
                    'mileage' => $data[12],
                    'body_type' => $data[9],
                    'fuel' => '',
                    'engine' => $data[15],
                    'cylinders' => '',
                    'transmission' => $data[16],
                    'drive_type' => '',
                    'trim_level' => $data[5],
                    'exterior_color' => $data[17],
                    'interior_color' => $data[18],
                    'options' => $data[20],
                    'dealer_comments' => $data[19],
                    'msrp' => $data[14],
                    'internet_price' => $data[13],
                    'image_count' => count($aImages),
                    'image_urls' => $data[23],
                    'display_image' => count($aImages) ? $aImages[0] : '',
                    'merchant_id' => $aLinked[$data[0]]->merchant_id,
                    'location_id' => $aLinked[$data[0]]->location_id,
                    'latm' => $aLinked[$data[0]]->latm,
                    'lngm' => $aLinked[$data[0]]->lngm,
                    'vendor_dealer_id' => $data[0],
                    'vendor' => 'soct',
                    'vendor_inventory_id' => $data[10],
                    'source' => 'vauto'
                ));
            }
            fclose($handle);

            try
            {
                Schema::connection('mysql-used')->table('dealer_entities', function($table)
                {
                    $table->index(array('source', 'vin'));
                });
            }
            catch(\Exception $e)
            {
                // Index already exists
            }
        }

        unlink(public_path().'/INVENTORY-YYYY'.date('m').'DD.CSV');
        $this->recordSync('vauto');
    }

    protected function recordSync($method)
    {
        $currentBatch = DB::table('dealer_sync')
            ->whereNull('merged_at')
            ->max('batch');

        $batch = $currentBatch ? $currentBatch : 0;

        $unmerged = DB::table('dealer_sync')
            ->where('method', $method)
            ->where('batch', $batch)
            ->whereNull('merged_at')
            ->first();

        if($unmerged)
            return;

        $batch = $currentBatch ? $currentBatch : $batch + 1;
        DB::table('dealer_sync')->insert(array(
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'method' => $method,
            'batch' => $batch
        ));
    }

    protected function mergeVehicles()
    {
        $methods = array('detroit_trading'/*, 'dealer_specialties', 'soct'*/);
        $allReady = true;
        $currentBatch = DB::table('dealer_sync')
            ->whereNull('merged_at')
            ->max('batch');
        $batch = $currentBatch ? $currentBatch : 0;
        foreach($methods as $method)
        {
            $ready = DB::table('dealer_sync')
                ->whereNull('merged_at')
                ->where('method', $method)
                ->where('batch', $batch)
                ->first();
            $allReady = $ready ? $allReady : false;
        }

        if(!$allReady)
            return;

        try
        {
            $this->info('Creating temp table');
            DB::connection('mysql-used')->statement('CREATE TABLE vehicle_entities_temp LIKE vehicle_entities;');
        }
        catch(\Exception $e)
        {
            $this->info('Temp already exists, removing.');
            Schema::connection('mysql-used')->drop('vehicle_entities_temp');
            DB::connection('mysql-used')->statement('CREATE TABLE vehicle_entities_temp LIKE vehicle_entities;');
        }

        try
        {
            Schema::connection('mysql-used')->table('vehicle_entities_temp', function($table)
            {
                $table->dropIndex('vehicle_entities_state_internet_price_latm_lngm_index');
            });
        }
        catch(\Exception $e)
        {
            $this->info('Index is already dropped.');
        }

        try
        {
            Schema::connection('mysql-used')->table('vehicle_entities_temp', function($table)
            {
                $table->dropIndex('vehicle_entities_merchant_id_make_model_index');
            });
        }
        catch(\Exception $e)
        {
            $this->info('Index is already dropped.');
        }

        try
        {
            Schema::connection('mysql-used')->table('vehicle_entities_temp', function($table)
            {
                $table->dropIndex('vehicle_entities_vin_vendor_index');
            });
        }
        catch(\Exception $e)
        {
            $this->info('Index is already dropped.');
        }

        try
        {
            Schema::connection('mysql-used')->table('vehicle_entities', function($table)
            {
                $table->dropIndex('vehicle_entities_dealer_radius_index');
            });
        }
        catch(\Exception $e)
        {
            $this->info('Index is already dropped.');
        }

        try
        {
            $this->info('Writing to outfile...');
            /*$columnsOne = DB::connection('mysql-used')->getPdo()->query("DESCRIBE dealer_entities;")->fetchAll();
            $columnsTwo = DB::connection('mysql-used')->getPdo()->query("DESCRIBE vehicle_entities_temp;")->fetchAll();
            $aColOne = array();
            $aColTwo = array();
            foreach($columnsOne as $col)
            {
                $aColOne[] = $col['Field'];
            }
            foreach($columnsTwo as $col)
            {
                $aColTwo[] = $col['Field'];
            }
            print_r(array_diff($aColTwo, $aColOne));
            exit;*/

            /*$row = DB::connection('mysql-used')->getPdo()->query("SELECT 
                          `created_at`,
                          `updated_at`,
                          NULL as id,
                          `vendor`,
                          `vendor_dealer_id`,
                          `vendor_dealer_key`,
                          `vendor_inventory_id`,
                          `condition`,
                          `stock_number`,
                          `vin`,
                          `year`,
                          `model_year_id`,
                          `make_id`,
                          `make`,
                          `model_id`,
                          `model`,
                          `is_certified`,
                          `price`,
                          `mileage`,
                          `body_type`,
                          `vehicle_type`,
                          `class`,
                          `fuel`,
                          `engine`,
                          `cylinders`,
                          `transmission`,
                          `drive_type`,
                          `trim_level`,
                          `exterior_color`,
                          `interior_color`,
                          `city_mpg`,
                          `highway_mpg`,
                          `options`,
                          `dealer_comments`,
                          `msrp`,
                          `internet_price`,
                          `image_count`,
                          `image_urls`,
                          `display_image`,
                          `dealer_name`,
                          `dealer_slug`,
                          `merchant_id`,
                          `location_id`,
                          `address`,
                          `city`,
                          `state`,
                          `zipcode`,
                          `phone`,
                          `latitude`,
                          `longitude`,
                          `latm`,
                          `lngm`,
                          `is_prequalified`,
                          NULL as deleted_at,
                          `source`,
                          `dealer_radius`,
                          `web_payout_price`,
                          `phone_extension`,
                          `phone_payout_price`,
                          `ppc_url`,
                          `ppc_payout`
                           FROM dealer_entities LIMIT 1;")->fetchAll();
            $aCols = array();
            foreach($row[0] as $key => $value)
            {
                $key = str_replace('[', '', $key);
                $key = str_replace(']', '', $key);
                //print_r($key);
                if(!is_numeric($key))
                    $aCols[] = $key;
            }
            print_r($aCols);
            exit;*/

            DB::connection('mysql-used')->statement("INSERT INTO vehicle_entities_temp SELECT 
              `created_at`,
              `updated_at`,
              NULL as id,
              `vendor`,
              `vendor_dealer_id`,
              `vendor_dealer_key`,
              `vendor_inventory_id`,
              `condition`,
              `stock_number`,
              `vin`,
              `year`,
              `model_year_id`,
              `make_id`,
              `make`,
              `model_id`,
              `model`,
              `is_certified`,
              `price`,
              `mileage`,
              `body_type`,
              `vehicle_type`,
              `class`,
              `fuel`,
              `engine`,
              `cylinders`,
              `transmission`,
              `drive_type`,
              `trim_level`,
              `exterior_color`,
              `interior_color`,
              `city_mpg`,
              `highway_mpg`,
              `options`,
              `dealer_comments`,
              `msrp`,
              `internet_price`,
              `image_count`,
              `image_urls`,
              `display_image`,
              `dealer_name`,
              `dealer_slug`,
              `merchant_id`,
              `location_id`,
              `address`,
              `city`,
              `state`,
              `zipcode`,
              `phone`,
              `latitude`,
              `longitude`,
              `latm`,
              `lngm`,
              `is_prequalified`,
              NULL as deleted_at,
              `source`,
              `dealer_radius`,
              `web_payout_price`,
              `phone_extension`,
              `phone_payout_price`,
              `ppc_url`,
              `ppc_payout`
               FROM dealer_entities;");
            //$query = sprintf("LOAD DATA LOCAL INFILE '".public_path()."/dealer_entities.txt' INTO TABLE vehicle_entities_temp;");
            $this->info('Loading into temp table...');
            //DB::connection('mysql-used')->getpdo()->exec($query);
        }
        catch(\Exception $e)
        {
            $this->info('Merge Failed - '.$e->getMessage());
            return;
        }
        //unlink(public_path().'/dealer_entities.txt');
        DB::connection('mysql-used')->table('dealer_entities')->truncate();
        DB::table('dealer_sync')
            ->where('batch', $batch)
            ->update(array(
                'merged_at' => date('Y-m-d H:i:s')
            ));

        try
        {
            DB::connection('mysql-used')->statement('RENAME TABLE `vehicle_entities` TO `vehicle_entities_old`;');
        }
        catch(\Exception $e)
        {
            $this->info('Old table exists, dropping and recreating.');
            Schema::connection('mysql-used')->drop('vehicle_entities_old');
            DB::connection('mysql-used')->statement('RENAME TABLE `vehicle_entities` TO `vehicle_entities_old`;');
        }
        DB::connection('mysql-used')->statement('RENAME TABLE `vehicle_entities_temp` TO `vehicle_entities`;');
        Schema::connection('mysql-used')->drop('vehicle_entities_old');

        $this->info('Adding indexes');
        Schema::connection('mysql-used')->table('vehicle_entities', function($table)
        {
            $table->index(array('state', 'internet_price', 'latm', 'lngm'));
        });
        Schema::connection('mysql-used')->table('vehicle_entities', function($table)
        {
            $table->index(array('merchant_id', 'make', 'model'));
        });
        Schema::connection('mysql-used')->table('vehicle_entities', function($table)
        {
            $table->index(array('vin', 'vendor'));
        });
        Schema::connection('mysql-used')->table('vehicle_entities', function($table)
        {
            $table->index(array('state', 'internet_price', 'latm', 'lngm', 'dealer_radius'), 'vehicle_entities_dealer_radius_index');
        });

    }

    protected function getMake($make)
    {
        $aID = array(
            "1" => 'gmc',
            "2" => 'dodge',
            "3" => 'chevrolet',
            "4" => 'buick',
            "5" => 'saab',
            "6" => 'jaguar',
            "7" => 'chrysler',
            "8" => 'jeep',
            "9" => 'ford',
            "10" => 'cadillac',
            "11" => 'land-rover',
            "12" => 'bmw',
            "13" => 'mercedes-benz',
            "14" => 'hyundai',
            '15' => 'audi',
            "16" => 'volkswagen',
            "17" => 'kia',
            "18" => 'mazda',
            "19" => 'acura',
            "20" => 'honda',
            "21" => 'nissan',
            "22" => 'lincoln',
            "23" => 'volvo',
            "31" => 'ram',
            "32" => 'toyota',
            "33" => 'scion',
            "34" => 'subaru',
            "35" => 'mitsubishi',
            "24" => "harley-davison",
            "25" => "other",
        );

        $aName = array(
            "GMC" => 'gmc',
            "Dodge" => 'dodge',
            "Chevrolet" => 'chevrolet',
            "Buick" => 'buick',
            "SAAB" => 'saab',
            'Saab' => 'saab',
            "Jaguar" => 'jaguar',
            "Chrysler" => 'chrysler',
            "Jeep" => 'jeep',
            "Ford" => 'ford',
            "Cadillac" => 'cadillac',
            "Landrover" => 'land-rover',
            "Land Rover" => 'land-rover',
            "BMW" => 'bmw',
            "Mercedes" => 'mercedes-benz',
            "Mercedes-Benz" => 'mercedes-benz',
            "Hyundai" => 'hyundai',
            'Audi' => 'audi',
            "VW" => 'volkswagen',
            'Volkswagen' => 'volkswagen',
            "Kia" => 'kia',
            "Mazda" => 'mazda',
            "Acura" => 'acura',
            "Honda" => 'honda',
            "Nissan" => 'nissan',
            "Lincoln" => 'lincoln',
            "Volvo" => 'volvo',
            "Ram" => 'ram',
            "Toyota" => 'toyota',
            "Scion" => 'scion',
            "Subaru" => 'subaru',
            "Mitsubishi" => 'mitsubishi',
            "Harley Davidson" => "harley-davidson",
            "Harley-Davidson" => "harley-davidson",
            "Other" => "other",
            "FIAT" => 'fiat',
            'HUMMER' => 'hummer',
            'Hummer' => 'hummer',
            'Infiniti' => 'infiniti',
            'Lexus' => 'lexus',
            'Mercury' => 'mercury',
            'MINI' => 'mini',
            'Mini' => 'mini',
            'Oldsmobile' => 'oldsmobile',
            'Plymouth' => 'plymouth',
            'Pontiac' => 'pontiac',
            'Porsche' => 'porsche',
            'RAM' => 'ram',
            'Saturn' => 'saturn',
            'Suzuki' => 'suzuki',
        );

        $slug = '';
        if(is_numeric($make))
        {
            $slug = $aID[$make];
        }
        else
        {
            $slug = isset($aName[$make]) ? $aName[$make] : $make;
        }

        return \SOE\DB\VehicleMake::where('slug', '=', $slug)->first();
    }

    protected function getMakesArray()
    {
        $aName = array(
            "GMC" => 'gmc',
            "Dodge" => 'dodge',
            "Chevrolet" => 'chevrolet',
            "Buick" => 'buick',
            "SAAB" => 'saab',
            'Saab' => 'saab',
            "Jaguar" => 'jaguar',
            "Chrysler" => 'chrysler',
            "Jeep" => 'jeep',
            "Ford" => 'ford',
            "Cadillac" => 'cadillac',
            "Landrover" => 'land-rover',
            "Land Rover" => 'land-rover',
            "BMW" => 'bmw',
            "Mercedes" => 'mercedes-benz',
            "Mercedes-Benz" => 'mercedes-benz',
            "Hyundai" => 'hyundai',
            'Audi' => 'audi',
            "VW" => 'volkswagen',
            'Volkswagen' => 'volkswagen',
            "Kia" => 'kia',
            "Mazda" => 'mazda',
            "Acura" => 'acura',
            "Honda" => 'honda',
            "Nissan" => 'nissan',
            "Lincoln" => 'lincoln',
            "Volvo" => 'volvo',
            "Ram" => 'ram',
            "Toyota" => 'toyota',
            "Scion" => 'scion',
            "Subaru" => 'subaru',
            "Mitsubishi" => 'mitsubishi',
            "Harley Davidson" => "harley-davidson",
            "Harley-Davidson" => "harley-davidson",
            "Other" => "other",
            "FIAT" => 'fiat',
            'HUMMER' => 'hummer',
            'Hummer' => 'hummer',
            'Infiniti' => 'infiniti',
            'Lexus' => 'lexus',
            'Mercury' => 'mercury',
            'MINI' => 'mini',
            'Mini' => 'mini',
            'Oldsmobile' => 'oldsmobile',
            'Plymouth' => 'plymouth',
            'Pontiac' => 'pontiac',
            'Porsche' => 'porsche',
            'RAM' => 'ram',
            'Saturn' => 'saturn',
            'Suzuki' => 'suzuki',
        );

        $aMakes = array();
        foreach($aName as $name => $slug)
        {
            $make = \SOE\DB\VehicleMake::where('slug', '=', $slug)->first();            
            if($make)
            {
                $aMakes[$name] = $make;
                $aMakesSlug[$slug] = $make;
            }
        }

        return array('names' => $aMakes, 'slugs' => $aMakesSlug);
    }

    protected function removePunctuation($string)
    {
        return preg_replace("/[^A-Za-z0-9 ]/", '', $string);
    }

    protected function removeHtml($string)
    {
        return str_replace("&#39;", "'", $string);
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