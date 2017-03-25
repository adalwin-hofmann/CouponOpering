<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class TrialCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'trial';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import SOHI trial merchant details.';

    protected $featureRepository;
    protected $franchiseRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->featureRepository = App::make('FeatureRepositoryInterface');
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
            case 'update':
                $this->update();
                break;
            case 'trial_check':
                $this->trialCheck();
                break;
            default:
                $this->import();
                break;
        }
    }

    protected function import()
    {
        set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '2048M');
        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/sohi_trial_merchants.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                $num = count($data);
                $this->info("sohi_trial_merchants.csv ".$num." columns\n");
            }
            $counter=0;
            $trial_start = $this->featureRepository->findByName('sohi_free_trial_start', false);
            $trial_start = $trial_start ? $trial_start->value : '2014-04-23 00:00:00';
            $trial_end = $this->featureRepository->findByName('sohi_free_trial_end', false);
            $trial_end = $trial_end ? $trial_end->value : '2014-06-30 00:00:00';
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {
                $counter++;
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    $update = array(
                        'maghub_id' => $data[1],
                        'primary_contact' => $data[3]
                    );
                    
                    if($data[3] != '')
                    {
                        $update['service_plan'] = 'trial';
                        $update['is_sohi_trial'] = 1;
                        $update['sohi_trial_starts_at'] = $trial_start;
                        $update['sohi_trial_ends_at'] = $trial_end;
                        $locations = DB::table('locations')->where('franchise_id', '=', $data[0])->get(array('id'));
                        $aLocIDs = array(0);
                        foreach($locations as $location)
                        {
                            $aLocIDs[] = $location->id;
                        }
                        DB::table('entities')->whereIn('location_id', $aLocIDs)
                                            ->update(array(
                                                'is_sohi_trial' => 1,
                                                'sohi_trial_starts_at' => $trial_start,
                                                'sohi_trial_ends_at' => $trial_end
                                            ));
                        DB::table('franchises')->where('id', '=', $data[0])->update($update);
                        $franchise = $this->franchiseRepository->find($data[0]);
                        if(!empty($franchise))
                        {
                            DB::table('lead_emails')->insert(array('created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()'), 'franchise_id' => $data[0], 'email_address' => $data[3]));
                            $this->franchiseRepository->netlmsCreate($franchise, true);
                        }
                    }
                    else
                    {
                        DB::table('franchises')->where('id', '=', $data[0])->update($update);
                    }
                }   
            }
            fclose($handle);
            $this->info($counter.' Trial Merchants Imported');
        }
    }

    protected function update()
    {
        $franchises = \SOE\DB\Franchise::where('is_sohi_trial', '=', '1')->get();
        foreach($franchises as $franchise)
        {
            if($franchise->netlms_id)
                $this->franchiseRepository->netlmsSync($franchise);
            else
                $this->franchiseRepository->netlmsCreate($franchise);
        }
        $this->info(count($franchises).' Trial Merchants Updated');
    }

    protected function trialCheck()
    {
        $locations = \SOE\DB\Franchise::join('locations', 'franchises.id', '=', 'locations.franchise_id')
                                        ->where('is_sohi_trial', '=', '1')
                                        ->where('sohi_trial_ends_at', '<=', DB::raw('NOW()'))
                                        ->get(array('locations.id'));
        DB::table('franchises')->where('is_sohi_trial', '=', '1')->where('sohi_trial_ends_at', '<=', DB::raw('NOW()'))->update(array('is_sohi_trial' => 0));
        $aLocIDs = array(0);
        foreach($locations as $location)
        {
            $aLocIDs[] = $location->id;
        }
        DB::table('entities')->whereIn('location_id', $aLocIDs)->update(array('is_sohi_trial' => 0));
        $this->info('Trials Checked');
        echo "Trials Checked";
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