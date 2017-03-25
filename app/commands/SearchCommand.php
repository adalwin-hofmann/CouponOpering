<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SearchCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'search';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Search Index Query and Sync Tool.';

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
        $type = $this->option('type');
        $this->info($type);
        if($type=="merchant"){$this->processMerchant();}
        if($type=="entity"){$this->processEntity();}
        if($type=="has_coupons"){$this->processCoupons();}
        if($type=="coupons_index"){$this->hasCouponsIndex();}
        if($type=="no_coupons_index"){$this->noCouponsIndex();}
    }


    /**
     * Process Merchant Search
     *
     * @return array
     */
    protected function processMerchant()
    {
        //Need to grab when this was last run.

        // Use custom date
        $created = $this->option('created');
        $page = $this->option('page');
        $id = $this->option('id');
        $pagesize = Config::get('integrations.cloud_search.locations.pagesize');

        $sql = 'select merchants.about as about, merchants.catchphrase as catchphrase, category.name as category, locations.city as city, locations.id as id, locations.offer_count as is_active, abs(locations.latm) as latm, abs(locations.lngm) as longm, merchants.id as mid, merchants.display as name, locations.state as state, subcategory.name as subcategory, CONCAT_WS(" ", category.tags, subcategory.tags, merchants.keywords, merchants.offer_keywords, locations.display_name) as tags from merchants inner join locations on merchants.id = locations.merchant_id inner join categories category on merchants.category_id = category.id inner join categories subcategory on merchants.subcategory_id = subcategory.id where locations.id = ?';
        $results = DB::select($sql,array($id));
        //$this->info(print_r($results,false));
        $arrDate = getdate();

        if($id!=0)
        {
            for($i=0;$i<count($results);$i++)
            {
                // TODO: Batch through x of these records
                $data[] = array(
                        "type" => "add",
                        "id" => $results[$i]->id,
                        "lang"=>"en",
                        "version"=>$arrDate["0"],
                        "fields"=>array(
                            "name"=>strip_tags(str_replace("'","",$results[$i]->name)),
                            "about"=>preg_replace('/[\x00-\x1F\x80-\xFF]/', '', strip_tags($results[$i]->about)),
                            "catchphrase"=>strip_tags($results[$i]->catchphrase),
                            "category"=>$results[$i]->category,
                            "city"=>$results[$i]->city,
                            "id"=>$results[$i]->id,
                            "is_active"=>($results[$i]->is_active > 0),
                            "latm"=>$results[$i]->latm,
                            "longm"=>$results[$i]->longm,
                            "mid"=>$results[$i]->mid,
                            "state"=>$results[$i]->state,
                            "subcategory"=>$results[$i]->subcategory,
                            "tags"=>$results[$i]->tags,
                ));
            }
            if(count($results)>0)
            {
                $json = json_encode($data);
                $ch = curl_init(Config::get('integrations.cloud_search.locations.update_url'));
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($json))
                );

                $result = curl_exec($ch);
                $this->info($result);
            }
            return 1;
        }

        if($page=="auto")
        {
            for($j=0;$j<600;$j++)
            {
                $page = $j;
                $data = array();
                // curl post document
                $arrDate = getdate();
                $this->info($j);
                $sql = 'select merchants.about as about, merchants.catchphrase as catchphrase, category.name as category, locations.city as city, locations.id as id, locations.offer_count as is_active, abs(locations.latm) as latm, abs(locations.lngm) as longm, merchants.id as mid, merchants.display as name, locations.state as state, subcategory.name as subcategory, CONCAT_WS(" ", category.tags, subcategory.tags, merchants.keywords, merchants.offer_keywords, locations.display_name) as tags from merchants inner join locations on merchants.id = locations.merchant_id inner join categories category on merchants.category_id = category.id inner join categories subcategory on merchants.subcategory_id = subcategory.id where merchants.updated_at > ? limit ?,?';
                //Loop through all merchant/locations that have been updated since the sync last ran.
                $results = DB::select($sql,array($created,$page*$pagesize,$pagesize));
                //$this->info(print_r($results,false));exit;
                for($i=0;$i<count($results);$i++)
                {
                    // TODO: Batch through x of these records
                    $data[] = array(
                            "type" => "add",
                            "id" => $results[$i]->id,
                            "lang"=>"en",
                            "version"=>$arrDate["0"],
                            "fields"=>array(
                                "name"=>strip_tags(str_replace("'","",$results[$i]->name)),
                                "about"=>preg_replace('/[\x00-\x1F\x80-\xFF]/', '', strip_tags($results[$i]->about)),
                                "catchphrase"=>strip_tags($results[$i]->catchphrase),
                                "category"=>$results[$i]->category,
                                "city"=>$results[$i]->city,
                                "id"=>$results[$i]->id,
                                "is_active"=>($results[$i]->is_active > 0),
                                "latm"=>$results[$i]->latm,
                                "longm"=>$results[$i]->longm,
                                "mid"=>$results[$i]->mid,
                                "state"=>$results[$i]->state,
                                "subcategory"=>$results[$i]->subcategory,
                                "tags"=>$results[$i]->tags,
                    ));
                }

                if(count($results)>1)
                {
                    $json = json_encode($data);
                    $ch = curl_init(Config::get('integrations.cloud_search.locations.update_url'));
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json',
                        'Content-Length: ' . strlen($json))
                    );

                    $result = curl_exec($ch);
                    $this->info($result);
                }
            }
        }
        else
        {
            // curl post document
            $arrDate = getdate();
            $this->info($arrDate["0"]);
            $sql = 'select merchants.about as about, merchants.catchphrase as catchphrase, category.name as category, locations.city as city, locations.id as id, locations.offer_count as is_active, abs(locations.latm) as latm, abs(locations.lngm) as longm, merchants.id as mid, merchants.display as name, locations.state as state, subcategory.name as subcategory, CONCAT_WS(" ", category.tags, subcategory.tags, merchants.keywords, merchants.offer_keywords, locations.display_name) as tags from merchants inner join locations on merchants.id = locations.merchant_id inner join categories category on merchants.category_id = category.id inner join categories subcategory on merchants.subcategory_id = subcategory.id where merchants.updated_at > ? order by merchants.id desc limit ?,1000';
            //Loop through all merchant/locations that have been updated since the sync last ran.
            $results = DB::select($sql,array($created,$page*1000));
            //$this->info(print_r($results,false));exit;
            for($i=0;$i<count($results);$i++)
            {
                // TODO: Batch through x of these records
                $data[] = array(
                        "type" => "add",
                        "id" => $results[$i]->id,
                        "lang"=>"en",
                        "version"=>$arrDate["0"],
                        "fields"=>array(
                            "name"=>strip_tags(str_replace("'","",$results[$i]->name)),
                            "about"=>preg_replace('/[\x00-\x1F\x80-\xFF]/', '', strip_tags($results[$i]->about)),
                            "catchphrase"=>strip_tags($results[$i]->catchphrase),
                            "category"=>$results[$i]->category,
                            "city"=>$results[$i]->city,
                            "id"=>$results[$i]->id,
                            "is_active"=>($results[$i]->is_active > 0),
                            "latm"=>$results[$i]->latm,
                            "longm"=>$results[$i]->longm,
                            "mid"=>$results[$i]->mid,
                            "state"=>$results[$i]->state,
                            "subcategory"=>$results[$i]->subcategory,
                            "tags"=>$results[$i]->tags,
                ));
            }

            if(count($results)>1)
            {
                $json = json_encode($data);
                $ch = curl_init(Config::get('integrations.cloud_search.locations.update_url'));
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($json))
                );

                $result = curl_exec($ch);
                $this->info($result);
            }

        }
    }


    /**
     * Process Entity Search
     *
     * @return array
     */
    protected function processEntity()
    {
        //Need to grab when this was last run.

        // Use custom date
        $created = $this->option('created');
        $page = $this->option('page');
        $pagesize = Config::get('integrations.cloud_search.entities.pagesize');
        if($page=="auto")
        {
            for($j=0;$j<600;$j++)
            {
                $page = $j;
                $data = array();
                // curl post document
                $arrDate = getdate();
                $this->info($j);
                $sql = 'select udf_cleanString(fnStripTags(merchants.about)) as about,udf_cleanString(fnStripTags(merchants.catchphrase)) as catchphrase, udf_cleanString(fnStripTags(category.name)) as category, locations.city, DAY(entities.expires_at) as day,entities.id as eid, entities.entitiable_type as etype,entities.id as id,abs(entities.latm) as latm,abs(entities.lngm) as longm,MONTH(entities.expires_at) as month,udf_cleanString(fnStripTags(merchants.display)) as name,locations.state, udf_cleanString(fnStripTags(subcategory.name)) as subcategory, CONCAT_WS(" ",category.tags, subcategory.tags) as tags,YEAR(entities.expires_at) as year from entities inner join categories category on entities.category_id = category.id inner join categories subcategory on entities.subcategory_id = subcategory.id inner join merchants on entities.merchant_id = merchants.id inner join locations on entities.location_id = locations.id where entities.updated_at > ? order by entities.id desclimit ?,?;';
                //Loop through all merchant/locations that have been updated since the sync last ran.
                $results = DB::select($sql,array($created,$page*$pagesize,$pagesize));
                //$this->info(print_r($results,false));exit;
                for($i=0;$i<count($results);$i++)
                {
                    // TODO: Batch through x of these records
                    $data[] = array(
                            "type" => "add",
                            "id" => $results[$i]->id,
                            "lang"=>"en",
                            "version"=>$arrDate["0"],
                            "fields"=>array(
                                "about"=>$results[$i]->about,
                                "catchphrase"=>$results[$i]->catchphrase,
                                "category"=>$results[$i]->category,
                                "city"=>$results[$i]->city,
                                "day"=>$results[$i]->day,
                                "eid"=>$results[$i]->eid,
                                "etype"=>$results[$i]->etype,
                                "id"=>$results[$i]->id,
                                "latm"=>$results[$i]->latm,
                                "longm"=>$results[$i]->longm,
                                "month"=>$results[$i]->month,
                                "name"=>str_replace("'","",$results[$i]->name),
                                "state"=>$results[$i]->state,
                                "subcategory"=>$results[$i]->subcategory,
                                "tags"=>$results[$i]->tags,
                                "year"=>$results[$i]->year,
                    ));
                }

                if(count($results)>1)
                {
                    $json = json_encode($data);
                    $ch = curl_init(Config::get('integrations.cloud_search.entities.update_url'));
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/json',
                        'Content-Length: ' . strlen($json))
                    );

                    $result = curl_exec($ch);
                    $this->info($result);
                }
            }
        }
        else
        {
            // curl post document
            $arrDate = getdate();
            $this->info($arrDate["0"]);
            $sql = 'select udf_cleanString(fnStripTags(merchants.about)) as about,udf_cleanString(fnStripTags(merchants.catchphrase)) as catchphrase, udf_cleanString(fnStripTags(category.name)) as category, locations.city, DAY(entities.expires_at) as day,entities.id as eid, entities.entitiable_type as etype,entities.id as id,entities.latm as latm,entities.lngm as longm,MONTH(entities.expires_at) as month,udf_cleanString(fnStripTags(merchants.display)) as name,locations.state, udf_cleanString(fnStripTags(subcategory.name)) as subcategory, CONCAT_WS(" ",category.tags, subcategory.tags) as tags,YEAR(entities.expires_at) as year from entities inner join categories category on entities.category_id = category.id inner join categories subcategory on entities.subcategory_id = subcategory.id inner join merchants on entities.merchant_id = merchants.id inner join locations on entities.location_id = locations.id where entities.updated_at > ? limit ?,1000;';
            //Loop through all merchant/locations that have been updated since the sync last ran.
            $results = DB::select($sql,array($created,$page*1000));
            //$this->info(print_r($results,false));exit;
            for($i=0;$i<count($results);$i++)
            {
                // TODO: Batch through x of these records
                $data[] = array(
                        "type" => "add",
                        "id" => $results[$i]->id,
                        "lang"=>"en",
                        "version"=>$arrDate["0"],
                        "fields"=>array(
                            "about"=>$results[$i]->about,
                            "catchphrase"=>$results[$i]->catchphrase,
                            "category"=>$results[$i]->category,
                            "city"=>$results[$i]->city,
                            "day"=>$results[$i]->day,
                            "eid"=>$results[$i]->eid,
                            "etype"=>$results[$i]->etype,
                            "id"=>$results[$i]->id,
                            "latm"=>$results[$i]->latm,
                            "longm"=>$results[$i]->longm,
                            "month"=>$results[$i]->month,
                            "name"=>str_replace("'","",$results[$i]->name),
                            "state"=>$results[$i]->state,
                            "subcategory"=>$results[$i]->subcategory,
                            "tags"=>$results[$i]->tags,
                            "year"=>$results[$i]->year,
                    ));
            }

            if(count($results)>1)
            {
                $json = json_encode($data);
                $ch = curl_init(Config::get('integrations.cloud_search.entities.update_url'));
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($json))
                );

                $result = curl_exec($ch);
                $this->info($result);
            }

        }
    }

    /**
     * Update the offer counts for locations.
     */
    protected function processCoupons()
    {
        DB::table('locations')->update(array('offer_count' => 0));

        DB::statement("UPDATE locations
                        SET offer_count = (
                            SELECT COUNT(entities.location_id) FROM entities
                                WHERE entities.is_active = 1
                                    AND entities.is_demo = 0
                                    AND entities.franchise_active = 1
                                    AND entities.franchise_demo = 0
                                    AND (entities.starts_year = '".date('Y')."'
                                        AND entities.starts_day <= '".(date('z')+1)."'
                                        OR entities.starts_year < '".date('Y')."')
                                    AND (entities.expires_year = '".date('Y')."'
                                        AND entities.expires_day >= '".(date('z')+1)."'
                                        OR entities.expires_year >= '".(date('Y')+1)."')
                                    AND entities.location_id = locations.id
                            )");
    }

    protected function hasCouponsIndex()
    {
        set_time_limit(60*120); // 120 Mins
        ini_set('memory_limit', '4096M');
        $arrDate = getdate();
        $this->info("Gathering Location With Coupons...");
        $total_with = DB::table('locations')->where('offer_count', '>', 0)
                                            ->count();

        $pages_with = ceil($total_with / 500);
        $start_page = 0;
        $start = $this->option('start');
        if($start != 0)
        {
            $start_page = floor($start / 500);
            if($start_page > $pages_with)
                return;
        }
        for($page=$start_page; $page<$pages_with; $page++)
        {

            $locations = DB::table('locations')->join('merchants', 'locations.merchant_id', '=', 'merchants.id')
                                            ->join(DB::raw('categories category'), 'merchants.category_id', '=', 'category.id')
                                            ->join(DB::raw('categories subcategory'), 'merchants.subcategory_id', '=', 'subcategory.id')
                                            ->where('locations.offer_count', '>', 0)
                                            ->take(500)
                                            ->skip($page*500)
                                            ->get(array(
                                                DB::raw('merchants.about as about'),
                                                DB::raw('merchants.catchphrase as catchphrase'),
                                                DB::raw('category.name as category'),
                                                DB::raw('locations.city as city'),
                                                DB::raw('locations.id as id'),
                                                DB::raw('merchants.is_active as is_active'),
                                                DB::raw('abs(locations.latm) as latm'),
                                                DB::raw('abs(locations.lngm) as longm'),
                                                DB::raw('merchants.id as mid'),
                                                DB::raw('merchants.display as name'),
                                                DB::raw('locations.state as state'),
                                                DB::raw('subcategory.name as subcategory'),
                                                DB::raw("CONCAT_WS(' ', category.tags, subcategory.tags, merchants.keywords, IF(merchants.entity_search_parse = 1, merchants.offer_keywords, '')) as tags")
                                            ));

            //Update locations with coupons, batch through 500 at a time.
            $k=0;
            $location_count = count($locations);
            $this->info("Processing Locations With Coupons ".($page*500)." - ".($page*500 + $location_count)."...");
            $data = array();
            while($k<$location_count)
            {
                $data[] = array(
                        "type" => "add",
                        "id" => $locations[$k]->id,
                        "lang"=>"en",
                        "version"=>$arrDate["0"],
                        "fields"=>array(
                            "name"=>strip_tags(str_replace("'","",$locations[$k]->name)),
                            "about"=>preg_replace('/[\x00-\x1F\x80-\xFF]/', '', strip_tags($locations[$k]->about)),
                            "catchphrase"=>strip_tags($locations[$k]->catchphrase),
                            "category"=>$locations[$k]->category,
                            "city"=>$locations[$k]->city,
                            "id"=>$locations[$k]->id,
                            "is_active"=>1,
                            "latm"=>$locations[$k]->latm,
                            "longm"=>$locations[$k]->longm,
                            "mid"=>$locations[$k]->mid,
                            "state"=>$locations[$k]->state,
                            "subcategory"=>$locations[$k]->subcategory,
                            "tags"=>$locations[$k]->tags,
                ));
                $k++;
            }
            if(count($data)>1 && App::environment() == 'prod')
            {
                $json = json_encode($data);
                $ch = curl_init(Config::get('integrations.cloud_search.locations.update_url'));
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($json))
                );

                $result = curl_exec($ch);
                $this->info($result);
                $rec = DB::table('sys_logs')->where('type', '=', 'search_index_with_coupons')->update(array(
                    'updated_at' => date('Y-m-d H:i:s'),
                    'message' => 'With Coupon Records Completed: '.($page*500 + $location_count)
                ));
                if($rec == 0)
                {
                    DB::table('sys_logs')->insert(array(
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'type' => 'search_index_with_coupons',
                        'message' => 'With Coupon Records Completed: '.($page*500 + $location_count)
                    ));
                }
            }
        }
    }

    protected function noCouponsIndex()
    {
        set_time_limit(60*120); // 120 Mins
        ini_set('memory_limit', '4096M');
        $this->info("Gathering Location Without Coupons...");
        $total_without = DB::table('locations')->where('offer_count', '=', 0)
                                                ->count();

        $pages_without = ceil($total_without / 500);
        $start_page = 0;
        $start = $this->option('start');
        if($start != 0)
        {
            $start_page = floor($start / 500);
            if($start_page > $pages_without)
                return;
        }
        for($page=$start_page; $page<$pages_without; $page++)
        {
            $arrDate = getdate();
            $no_offer_locations = DB::table('locations')->join('merchants', 'locations.merchant_id', '=', 'merchants.id')
                                            ->join(DB::raw('categories category'), 'merchants.category_id', '=', 'category.id')
                                            ->join(DB::raw('categories subcategory'), 'merchants.subcategory_id', '=', 'subcategory.id')
                                            ->where('offer_count', '=', 0)
                                            ->take(500)
                                            ->skip($page*500)
                                            ->get(array(
                                                DB::raw('merchants.about as about'),
                                                DB::raw('merchants.catchphrase as catchphrase'),
                                                DB::raw('category.name as category'),
                                                DB::raw('locations.city as city'),
                                                DB::raw('locations.id as id'),
                                                DB::raw('merchants.is_active as is_active'),
                                                DB::raw('abs(locations.latm) as latm'),
                                                DB::raw('abs(locations.lngm) as longm'),
                                                DB::raw('merchants.id as mid'),
                                                DB::raw('merchants.display as name'),
                                                DB::raw('locations.state as state'),
                                                DB::raw('subcategory.name as subcategory'),
                                                DB::raw("CONCAT_WS(' ', category.tags, subcategory.tags, merchants.keywords, IF(merchants.entity_search_parse = 1, merchants.offer_keywords, '')) as tags")
                                            ));

            //Update locations without coupons, batch through 500 at a time.
            $k=0;
            $location_count = count($no_offer_locations);
            $this->info("Processing Locations Without Coupons ".($page*500)." - ".($page*500 + $location_count)."...");
            $data = array();
            while($k<$location_count)
            {
                $data[] = array(
                        "type" => "add",
                        "id" => $no_offer_locations[$k]->id,
                        "lang"=>"en",
                        "version"=>$arrDate["0"],
                        "fields"=>array(
                            "name"=>strip_tags(str_replace("'","",$no_offer_locations[$k]->name)),
                            "about"=>preg_replace('/[\x00-\x1F\x80-\xFF]/', '', strip_tags($no_offer_locations[$k]->about)),
                            "catchphrase"=>strip_tags($no_offer_locations[$k]->catchphrase),
                            "category"=>$no_offer_locations[$k]->category,
                            "city"=>$no_offer_locations[$k]->city,
                            "id"=>$no_offer_locations[$k]->id,
                            "is_active"=>0,
                            "latm"=>$no_offer_locations[$k]->latm,
                            "longm"=>$no_offer_locations[$k]->longm,
                            "mid"=>$no_offer_locations[$k]->mid,
                            "state"=>$no_offer_locations[$k]->state,
                            "subcategory"=>$no_offer_locations[$k]->subcategory,
                            "tags"=>$no_offer_locations[$k]->tags,
                ));
                $k++;
            }
            if(count($data)>1 && App::environment() == 'prod')
            {
                $json = json_encode($data);
                $ch = curl_init(Config::get('integrations.cloud_search.locations.update_url'));
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($json))
                );

                $result = curl_exec($ch);
                $this->info($result);
                $rec = DB::table('sys_logs')->where('type', '=', 'search_index_without_coupons')->update(array(
                    'updated_at' => date('Y-m-d H:i:s'),
                    'message' => 'Without Coupon Records Completed: '.($page*500 + $location_count)
                ));
                if($rec == 0)
                {
                    DB::table('sys_logs')->insert(array(
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'type' => 'search_index_without_coupons',
                        'message' => 'Without Coupon Records Completed: '.($page*500 + $location_count)
                    ));
                }
            }
        }
    }

	public function convert_ascii($string)
	{
	  // Replace Single Curly Quotes
	  $search[]  = chr(226).chr(128).chr(152);
	  $replace[] = "'";
	  $search[]  = chr(226).chr(128).chr(153);
	  $replace[] = "'";

	  // Replace Smart Double Curly Quotes
	  $search[]  = chr(226).chr(128).chr(156);
	  $replace[] = '"';
	  $search[]  = chr(226).chr(128).chr(157);
	  $replace[] = '"';

	  // Replace En Dash
	  $search[]  = chr(226).chr(128).chr(147);
	  $replace[] = '--';

	  // Replace Em Dash
	  $search[]  = chr(226).chr(128).chr(148);
	  $replace[] = '---';

	  // Replace Bullet
	  $search[]  = chr(226).chr(128).chr(162);
	  $replace[] = '*';

	  // Replace Middle Dot
	  $search[]  = chr(194).chr(183);
	  $replace[] = '*';

	  // Replace Ellipsis with three consecutive dots
	  $search[]  = chr(226).chr(128).chr(166);
	  $replace[] = '...';

	  // Apply Replacements
	  $string = str_replace($search, $replace, $string);

	  // Remove any non-ASCII Characters
	  $string = preg_replace("/[^\x01-\x7F]/","", $string);

	  return $string;
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
			array('created', null, InputOption::VALUE_OPTIONAL, 'Date in the past to begin syncronizing. ex. 2014-01-20 00:00:00', "2014-01-20 00:00:00"),
            array('page', null, InputOption::VALUE_OPTIONAL, 'Which Page of Batches of 500 to Grab. Default:0', 0),
            array('type', null, InputOption::VALUE_OPTIONAL, 'Which type of Search Update to perform', 'merchant'),
            array('id', null, InputOption::VALUE_OPTIONAL, 'Update A specific entity or location using id', '0'),
            array('start', null, InputOption::VALUE_OPTIONAL, 'What record to start at?', 0),
		);
	}

    /**
     * fix data from csv files if needed.
     * @param type $data
     */
    public function fix($data)
    {
        $data = str_replace("\\","",$data);
        return($data);
    }

}
