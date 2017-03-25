<?php
 
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
 
class ContestCommand extends Command {
 
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'contests';
 
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Commands for Contests";

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
        switch ($type)
        {
            case 'contest-winners':
                $this->contestWinnners();
                break;
            default:
                $this->info('Possible --type= contest-winners');
                //$this->makeUpdate();
                break;
        }
    }

    public function contestWinnners()
    {
        set_time_limit(30*60); // 30 Mins
        ini_set('memory_limit', '1024M');
        $counter = 0;

        $this->info("Begin Updating Contest Winners\n\n");

        $contests = SOE\DB\Contest::get();
        foreach ($contests as $contest)
        {
            $winners = SOE\DB\ContestWinner::where("contest_id","=",$contest->id)->get();
            foreach ($winners as $winner)
            {
                $winner_full_name = $winner->first_name.' '.$winner->last_name;
                $disclaimer = SOE\DB\ContestDisclaimer::where("contest_id","=",$contest->id)->where('name','=',$winner_full_name)->first();
                if(!empty($disclaimer)) //Update Disclaimer
                {
                    $disclaimer->contest_winner_id = $winner->id;
                    $disclaimer->save();

                    $winner->email = $disclaimer->email;
                    $winner->address = $disclaimer->address;
                    $winner->save();

                    $address_array = preg_split("/[\s,\/]+/", trim($disclaimer->city_state_zip));
                    if(!preg_match('/^[a-z]+$/i',end($address_array)))
                    {
                        $winner->zip = end($address_array);
                        $winner->save();
                    }
                    $counter++;
                }
            }

        }
        $this->info("Updated info for $counter Contest Winners.\n");
    }

    protected function getOptions()
    {
        return array(
            array('type', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
        );
    }
 
}