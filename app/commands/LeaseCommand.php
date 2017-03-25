<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class LeaseCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lease';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse lease title.';

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
        $leases = \DB::table('offers')->where('secondary_type', 'lease')->where('expires_at', '>', \DB::raw('NOW()'))->get();
        foreach($leases as $lease)
        {
            $data = explode(' ', $lease->name);
            if(count($data) > 2 && is_numeric($data[0]) && strlen($data[0]) == 4)
            {
                $make = \SOE\DB\VehicleMake::where('name', $data[1])->first();
                if($make)
                {
                    $model = \SOE\DB\VehicleModel::where('make_id', $make->id)->where('name', $data[2])->first();
                    if($model)
                    {
                        \DB::table('offers')->where('id', $lease->id)->update(array(
                            'year' => $data[0],
                            'make' => $make->name,
                            'make_id' => $make->id,
                            'model' => $model->name,
                            'model_id' => $model->id
                        ));
                    }
                }
            }
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