<?php namespace SOE\Whitelabels\Parsers\CSV;

use \SOE\Whitelabels\Parsers\ParserException;

class Merchants
{
    const COLUMNS = 12;

    public function __construct(
        \AssetRepositoryInterface $assets,
        \CategoryRepositoryInterface $categories,
        \EntityRepositoryInterface $entities,
        \FranchiseRepositoryInterface $franchises,
        \LocationRepositoryInterface $locations,
        \MerchantRepositoryInterface $merchants
    )
    {
        $this->assets = $assets;
        $this->categories = $categories;
        $this->entities = $entities;
        $this->franchises = $franchises;
        $this->locations = $locations;
        $this->merchants = $merchants;
    }

    /**
     * Parse the given company's merchant file.
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
                    $merchant = $this->merchants->findWhitelabel($company_id, $data[0]);
                    if($merchant)
                    {
                        $new = false;
                    }
                    else
                    {
                        $new = true;
                        $merchant = $this->merchants->blank();
                        $merchant->type = 'RETAIL';
                        $merchant->old_id = $data[0];
                    }
                    $merchant->display = $data[1];
                    $merchant->name = $this->removePunctuation($data[1]);
                    $merchant->slug = \SoeHelper::getSlug($data[1]);
                    $merchant->is_active = strtolower($data[2]) == 'true' ? 1 : 0;
                    $merchant->about = $data[4];
                    $merchant->catchphrase = $data[5];
                    $merchant->facebook = $data[6];
                    $merchant->twitter = $data[7];
                    $merchant->website = $data[8];
                    $category = $this->categories->findBySlug($data[9]);
                    $subcategory = $this->categories->findBySlug($data[10]);
                    $merchant->category_id = $category->id;
                    $merchant->subcategory_id = $subcategory->id;
                    $merchant->keywords = $data[11];

                    if($new)
                    {
                        $merchant->save();
                        $franchise = $this->franchises->create(array(
                            'name' => $merchant->name,
                            'company_id' => $company_id,
                            'merchant_id' => $merchant->id,
                            'is_active' => $merchant->is_active
                        ));

                        if(strlen($data[3]))
                            $this->getRemoteLogo($merchant, $data[3]);
                    }
                    else
                    {
                        $franchise = $this->franchises->findByCompanyMerchant($company_id, $merchant->id);
                        if(!$franchise)
                        {
                            $failed[] = array('error' => 'error importing merchant', $data);
                            continue;
                        }
                        $franchise->is_active = $data[2] == 'true' ? 1 : 0;
                        $franchise->save();
                        $merchant->save();

                        $logo = $this->assets->getLogo($merchant, false);
                        if(!$logo || ($logo && strlen($data[3]) && $logo->original_path != $data[3]))
                        {
                            $logo = $this->getRemoteLogo($merchant, $data[3]);        
                        }

                        $this->entities->updateMerchant($franchise, $merchant, $logo);
                        $this->locations->updateMerchant($franchise, $merchant);
                    }
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
        $valid = $valid && (strtolower($data[2]) == 'true' || strtolower($data[2]) == 'false');
        //echo $valid ? '1'.PHP_EOL : '0'.PHP_EOL;
        $valid = $valid && strlen($data[3]);
        //echo $valid ? '1'.PHP_EOL : '0'.PHP_EOL;
        //$valid = $valid && is_string($data[4]);
        //echo $valid ? 'pass</br>' : 'failed<br/>';
        //$valid = $valid && is_string($data[5]);
        //echo $valid ? 'pass</br>' : 'failed<br/>';
        //$valid = $valid && is_string($data[6]);
        //echo $valid ? 'pass</br>' : 'failed<br/>';
        //$valid = $valid && is_string($data[7]);
        //echo $valid ? 'pass</br>' : 'failed<br/>';
        //$valid = $valid && is_string($data[8]);
        //echo $valid ? 'pass</br>' : 'failed<br/>';
        $category = $this->categories->findBySlug($data[9]);
        $subcategory = $this->categories->findBySlug($data[10]);
        $valid = $valid && ($category && $subcategory && $subcategory->parent_id == $category->id);
        //echo $valid ? '1'.PHP_EOL : '0'.PHP_EOL;

        return $valid;
    }

    protected function removePunctuation($string)
    {
        return preg_replace("/[^A-Za-z0-9 ]/", '', $string);
    }

    protected function getRemoteLogo($merchant, $url)
    {
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
        $stored = $fileStore->storeFromTemp($temp, basename($url), 'assets/images/logos/');

        $logo = $this->assets->getLogo($merchant, false);

        if($stored)
        {
            if(!$logo)
            {
                $logo = $this->assets->create(array(
                    'assetable_id' => $merchant->id,
                    'assetable_type' => 'Merchant',
                    'path' => $stored,
                    'name' => 'logo1',
                    'type' => 'image',
                    'original_path' => $url
                )); 
            }
            else
            {
                $logo->original_path = $url;
                $logo->path = $stored;
                $logo->save();
            }
        }

        return $logo;
    }
}