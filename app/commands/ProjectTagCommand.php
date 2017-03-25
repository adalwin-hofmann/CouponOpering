<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ProjectTagCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'project_tag';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Handle project tags.';

    protected $franchiseRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->franchiseRepository = App::make('FranchiseRepositoryInterface');
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
        switch ($type)
        {
            case 'parents':
                $this->parents();
                break;
            case 'import':
            default:
                $this->import();
                break;
        }
    }

    protected function import()
    {
        set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '2048M');
        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/subcategory_mapping.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                $num = count($data);
                $this->info("subcategory_mapping.csv ".$num." columns\n");
            }
            $counter=0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {
                $counter++;
                $franchises = DB::table('franchises')->join('merchants', 'franchises.merchant_id', '=', 'merchants.id')
                                                    ->where('merchants.subcategory_id', '=', $data[0])
                                                    ->get(array('franchises.id'));
                foreach($franchises as $franchise)
                {
                    try
                    {
                        DB::table('franchise_project_tag')->insert(array('franchise_id' => $franchise->id, 'project_tag_id' => $data[1]));
                    }
                    catch(\Exception $e)
                    {
                        continue;
                    }
                }
                DB::table('entities')->where(DB::raw("find_in_set('".$data[1]."',project_tags)"), '=', 0)
                                    ->where('category_id', '=', 231)
                                    ->where('subcategory_id', '=', $data[0])
                                    ->update(array('project_tags' => DB::raw("CONCAT(project_tags, IF(project_tags = '', '".$data[1]."', ',".$data[1]."'))")));
            }
            fclose($handle);
            $this->info($counter.' Subcategory Mappings Imported');
        }
    }

    protected function parents()
    {
        set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '2048M');
        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/parent_tags.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                $num = count($data);
                $this->info("parent_tags.csv ".$num." columns\n");
            }
            $counter=0;
            $current = array('key' => 0, 'value' => '');
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {
                $counter++;
                if($current['value'] != $data[0])
                {
                    $current['value'] = $data[0];
                    $existing = DB::table('project_tags')->where('slug', '=', SoeHelper::getSlug($data[0]))->first();
                    if(empty($existing))
                    {
                        $current['key'] = DB::table('project_tags')->insertGetId(array(
                                                                                    'name' => $data[0],
                                                                                    'slug' => SoeHelper::getSlug($data[0])
                                                                                ));

                    }
                    else
                    {
                        $current['key'] = $existing->id;
                    }
                }
                
                try
                {
                    DB::table('project_tag_relation')->insert(array('parent_id' => $current['key'], 'child_id' => $data[1]));
                }
                catch(\Exception $e)
                {
                    continue;
                }
            }
            fclose($handle);
            $this->info($counter.' Parent Tags Imported');
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