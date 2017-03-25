<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SeoContentCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'seo_content';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import SEO Contents.';

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
        $this->info("Load SEO Content\n");

        DB::table("seo_contents")->truncate();

        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/seo_contents.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                //var_dump($data);
                $num = count($data);
                $this->info("seo_contents.csv ".$num." columns\n");
            }
            
            $counter=0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {   
                
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    DB::table('seo_contents')->insert(array(
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'page_url' => $data[0],
                        'content_type' => $data[1],
                        'content' => $data[2]
                    ));
                    $counter++;
                }
            }
            $this->info("Loaded ".$counter." columns\n");
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
            //array('type', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
        );
    }

}