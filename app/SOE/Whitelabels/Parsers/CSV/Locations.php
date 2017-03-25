<?php namespace SOE\Whitelabels\Parsers\CSV;

use \SOE\Whitelabels\Parsers\ParserException;

class Locations
{
    const COLUMNS = 13;

    public function __construct(
        \EntityRepositoryInterface $entities,
        \FranchiseRepositoryInterface $franchises,
        \LocationRepositoryInterface $locations,
        \MerchantRepositoryInterface $merchants,
        \ZipcodeRepositoryInterface $zipcodes
    )
    {
        $this->entities = $entities;
        $this->franchises = $franchises;
        $this->locations = $locations;
        $this->merchants = $merchants;
        $this->zipcodes = $zipcodes;
    }

    /**
     * Parse the given company's location file.
     *
     * @param int $company_id
     * @param string $file_path
     * @return mixed
     */
    public function parse($company_id, $file_path)
    {
        set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '1024M');

        if (($handle = fopen($file_path, "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE)
            {
                //skip the label row
                $num = count($data);
                if($num != self::COLUMNS)
                    throw new ParserException('invalid row count');
            }

            $counter=0;
            $failed = array();
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {  
                if(count($data)!=$num)
                {
                    $failed[] = array('error' => 'invalid row count', 'data' => $data);
                }
                else
                {
                    $valid = $this->validate($data);
                    if(!$valid)
                    {
                        $failed[] = array('error' => 'invalid data format', 'data' => $data);
                        continue;
                    }
                    $merchant = $this->merchants->findWhitelabel($company_id, $data[1]);
                    if(!$merchant)
                    {
                        $failed[] = array('error' => 'merchant not found, upload merchants first', 'data' => $data);
                        continue;
                    }
                    $franchise = $this->franchises->findByCompanyMerchant($company_id, $merchant->id);
                    if(!$franchise)
                    {
                        $failed[] = array('error' => 'franchise not found, upload merchants first', 'data' => $data);
                        continue;
                    }
                    $location = $this->locations->findWhitelabel($company_id, $data[0]);
                    if($location)
                    {
                        $new = false;
                    }
                    else
                    {
                        $new = true;
                        $location = $this->locations->blank();
                        $location->old_id = $data[0];
                        $location->merchant_id = $merchant->id;
                        $location->franchise_id = $franchise->id;
                        $location->company_id = $company_id;
                    }
                    $location->name = $data[2];
                    $location->slug = \SoeHelper::getSlug($data[2]);
                    $location->is_active = strtolower($data[3]) == 'true' ? 1 : 0;
                    $location->address = $data[4];
                    $location->address2 = $data[5];
                    $location->city = $data[6];
                    $location->state = strtoupper($data[7]);
                    $location->zip = $data[8];
                    $latitude = $data[9];
                    $longitude = $data[10];
                    if(strlen($data[9]) == 0 || strlen($data[10]) == 0)
                    {
                        $zipcode = $this->zipcodes->findByZipcode($data[8]);
                        if(!$zipcode)
                        {
                            $failed[] = array('error' => 'invalid zipcode', 'data' => $data);
                            continue;
                        }
                        $latitude = $zipcode->latitude;
                        $longitude = $zipcode->longitude;
                    }
                    $location->latitude = $latitude;
                    $location->longitude = $longitude;
                    $cartesian = \SoeHelper::getCartesian($latitude, $longitude);
                    $location->latm = $cartesian['latm'];
                    $location->lngm = $cartesian['lngm'];
                    $location->phone = $data[11];
                    $location->website = $data[12];
                    $location->merchant_name = $merchant->display;
                    $location->merchant_slug = $merchant->slug;
                    $location->save();

                    $this->entities->updateLocation($location->id);
                    $counter++;
                }
            }

            fclose($handle);

            return \Response::json(array('imported' => $counter, 'error_count' => count($failed), 'errors' => $failed), 200);
        }

        throw new ParserException('could not open file');
    }

    protected function validate($data)
    {
        $valid = true;
        $valid = $valid && strlen($data[0]);
        //echo $valid ? '1'.PHP_EOL : '0'.PHP_EOL;
        $valid = $valid && strlen($data[1]);
        //echo $valid ? '1'.PHP_EOL : '0'.PHP_EOL;
        $valid = $valid && (strtolower($data[3]) == 'true' || strtolower($data[3]) == 'false');
        //echo $valid ? '1'.PHP_EOL : '0'.PHP_EOL;
        $valid = $valid && strlen($data[4]);
        //echo $valid ? '1'.PHP_EOL : '0'.PHP_EOL;
        $valid = $valid && strlen($data[6]);
        //echo $valid ? '1'.PHP_EOL : '0'.PHP_EOL;
        $valid = $valid && (strlen($data[7]) == 2);
        //echo $valid ? '1'.PHP_EOL : '0'.PHP_EOL;
        $valid = $valid && strlen($data[8]);
        //echo $valid ? '1'.PHP_EOL : '0'.PHP_EOL;
        $valid = $valid && strlen($data[11]);
        //echo $valid ? '1'.PHP_EOL : '0'.PHP_EOL;

        return $valid;
    }

    protected function removePunctuation($string)
    {
        return preg_replace("/[^A-Za-z0-9 ]/", '', $string);
    }
}