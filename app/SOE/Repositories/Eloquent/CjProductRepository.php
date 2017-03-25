<?php namespace SOE\Repositories\Eloquent;

class CjProductRepository extends BaseRepository implements \CjProductRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'programname',
        'programurl',
        'lastupdated',
        'name',
        'keywords',
        'description',
        'sku',
        'manufacturer',
        'manufacturerid',
        'upc',
        'isbn',
        'currency',
        'saleprice',
        'price',
        'retailprice',
        'fromprice',
        'buyurl',
        'impressionurl',
        'imageurl',
        'advertisercategory',
        'thirdpartyid',
        'thirdpartycategory',
        'author',
        'artist',
        'title',
        'publisher',
        'label',
        'format',
        'special',
        'gift',
        'promotionaltext',
        'startdate',
        'enddate',
        'offline',
        'online',
        'instock',
        'condition',
        'warranty',
        'standardshippingcost'
    );

    protected $model = 'CjProduct';

    public function createFromPost($post)
    {
        $decompressed = \SoeHelper::gunzip($post->file_location);

        if(!$decompressed)
            return;

        if (($handle = fopen($decompressed, "r")) !== FALSE)
        {
            $keys = array();
            if(($data = fgetcsv($handle, null, "\t")) !== FALSE){
                //skip the label row
                $keys = $data;
                $keys = array_map('strtolower', $keys);
                $num = count($data);
            }
            
            $incentiveStyleCounter=0;
            while (($data = fgetcsv($handle, null, "\t")) !== FALSE)
            {   
                
                if(count($data)!=$num)
                {
                    
                }
                else
                {
                    $existing = $this->query()->where('programname', $data[0])->where('sku', $data[7])->first();
                    if($existing)
                    {
                        $row = array();
                        for($i=0; $i<count($data); $i++)
                        {
                            $row[$keys[$i]] = $data[$i];
                        }
                        $this->update($existing->id, $row);
                    }
                    else
                    {
                        $row = array();
                        for($i=0; $i<count($data); $i++)
                        {
                            $row[$keys[$i]] = $data[$i];
                        }
                        $this->create($row);
                    }
                }
            }

            $my_post = \SOE\DB\CjPost::find($post->id);
            $my_post->is_locked = 0;
            $my_post->status = 'parsed';
            $my_post->parsed_at = date('Y-m-d H:i:s');
            $my_post->save();
        }
    }
}