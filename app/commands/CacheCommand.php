<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CacheCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Handle cache actions.';

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
        $type = $this->option('type');
        switch($type)
        {
            case 'clear':
                $this->clear();
                break;
            case 'clean':
                $this->clean();
                break;
            default:
                $this->clean();
                break;
        }
    }

    protected function clear()
    {
        DB::table('cache')->truncate();
    }

    protected function clean()
    {
        $time = time();
        DB::table('cache')->where('expiration', '<', $time)->delete();
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