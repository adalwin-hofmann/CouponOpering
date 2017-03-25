<?php namespace SOE\Whitelabels\Parsers\CSV;

use \SOE\Whitelabels\Parsers\ParserException;

class Offers
{
    const COLUMNS = 12;

    public function __construct(
        \EntityRepositoryInterface $entities,
        \FranchiseRepositoryInterface $franchises,
        \LocationRepositoryInterface $locations,
        \MerchantRepositoryInterface $merchants,
        \OfferRepositoryInterface $offers
    )
    {
        $this->entities = $entities;
        $this->franchises = $franchises;
        $this->locations = $locations;
        $this->merchants = $merchants;
        $this->offers = $offers;
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
                    $offer = $this->offers->findWhitelabel($company_id, $data[0]);
                    if($offer)
                    {
                        $new = false;
                    }
                    else
                    {
                        $new = true;
                        $offer = $this->offers->blank();
                        $offer->old_id = $data[0];
                        $offer->merchant_id = $merchant->id;
                        $offer->franchise_id = $franchise->id;
                    }
                    $offer->name = $data[2];
                    $offer->slug = \SoeHelper::getSlug($data[2]);
                    $offer->is_active = strtolower($data[3]) == 'true' ? 1 : 0;
                    $offer->regular_price = $data[5];
                    $offer->special_price = $data[6];
                    $offer->code = $data[7];
                    $offer->description = $data[8];
                    $offer->starts_at = $data[9];
                    $offer->expires_at = $data[10];
                    $offer->savings = ($data[5] != '' && $data[6] != '') ? ($data[5] - $data[6]) : 0;
                    $offer->save();

                    if(strlen($data[4]) && $offer->original_path != $data[4])
                    {
                        $offer = $this->getRemoteImage($offer, $data[4]);        
                    }

                    $location_ids = array();
                    if($data[11] != '')
                    {
                        $aIDs = explode(',', $data[11]);
                        foreach($aIDs as $id)
                        {
                            $location = $this->locations->findWhitelabel($company_id, $id);
                            if($location)
                                $location_ids[] = $location->id;
                            else
                            {
                                $failed[] = array('error' => 'location not found, upload locations first', 'data' => $data);
                            }
                        }
                    }

                    if(!empty($location_ids))
                        $offer->is_location_specific = 1;
                    else
                        $offer->is_location_specific = 0;
                    $offer->save();

                    $this->entities->updateOffer($offer->id, $location_ids);
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
        $valid = $valid && strlen($data[2]);
        //echo $valid ? '1'.PHP_EOL : '0'.PHP_EOL;
        $valid = $valid && (strtolower($data[3]) == 'true' || strtolower($data[3]) == 'false');
        //echo $valid ? '1'.PHP_EOL : '0'.PHP_EOL;
        $valid = $valid && strlen($data[8]);
        //echo $valid ? '1'.PHP_EOL : '0'.PHP_EOL;
        $valid = $valid && strlen($data[9]) && preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/', $data[9]) && strtotime($data[9]);
        //echo $valid ? '1'.PHP_EOL : '0'.PHP_EOL;
        $valid = $valid && strlen($data[10]) && preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/', $data[10]) && strtotime($data[10]);
        //echo $valid ? '1'.PHP_EOL : '0'.PHP_EOL;

        return $valid;
    }

    protected function removePunctuation($string)
    {
        return preg_replace("/[^A-Za-z0-9 ]/", '', $string);
    }

    protected function getRemoteImage($offer, $url)
    {
        if($offer->original_path == $url)
            return $offer;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
        $rawdata=curl_exec ($ch);
        curl_close ($ch);

        $temp = tmpfile();
        fwrite($temp, $rawdata);
        fseek($temp, 0);

        $fileStore = \App::make('FileStoreInterface');
        $fileStore->setBucket('saveoneverything_assets');
        $stored = $fileStore->storeFromTemp($temp, basename($url), 'assets/images/coupons/');

        if($stored)
        {
            $offer->original_path = $url;
            $offer->path = $stored;
            $offer->save();
        }

        return $offer;
    }
}