<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CoordUpdateCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'coord_update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update geo coordinates.';

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
        set_time_limit(60*60); // 30 Mins
        ini_set('memory_limit', '4096M');
        $locations = SOE\DB\Location::where('latm', '=', '0')->limit(500)->get();
        Schema::table('entities', function($table)
        {
            $table->index(array('location_id'), 'entities_location_id_index');
        });
        foreach($locations as $location)
        {
            $this->info('Updating location id: '.$location->id.'...');
            $zip = $location->zip;
            if(empty($zip))
                continue;
            $zip = explode('-', $zip);
            $zip = is_array($zip) ? $zip[0] : $zip;
            $zipcode = SOE\DB\Zipcode::where('zipcode', '=', $zip)
                                    ->where('zipcodetype', '=', 'STANDARD')
                                    ->where(function($query)
                                    {
                                        $query->where('locationtype', '=', 'PRIMARY');
                                        $query->orWhere('locationtype', '=', 'ACCEPTABLE');
                                    })
                                    ->orderBy('estimatedpopulation', 'desc')
                                    ->first();
            if(empty($zipcode))
                continue;
            $location->latitude = $zipcode->latitude;
            $location->longitude = $zipcode->longitude;
            $cartesian = SoeHelper::getCartesian($zipcode->latitude, $zipcode->longitude);
            $location->latm = $cartesian['latm'];
            $location->lngm = $cartesian['lngm'];
            $location->save();
            SOE\DB\Entity::where('location_id', '=', $location->id)->update(array('latitude' => $zipcode->latitude, 'longitude' => $zipcode->longitude, 'latm' => $location->latm, 'lngm' => $location->lngm));
        }
        Schema::table('entities', function($table)
        {
            $table->dropIndex('entities_location_id_index');
        });
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