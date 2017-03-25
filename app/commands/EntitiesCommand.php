<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class EntitiesCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'entities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup entities.';

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
        set_time_limit(60*60); // 60 Mins
        ini_set('memory_limit', '2048M');
        $type = $this->option('type');
        $franchises = DB::table('franchises')->get();
        foreach($franchises as $franchise)
        {
            DB::table('offers')->where('merchant_id', '=', $franchise->merchant_id)->update(array('franchise_id' => $franchise->id));
        }

        Schema::table('entities', function($table)
        {
            $table->index('category_id', 'category_index');
            $table->index('subcategory_id', 'subcategory_index');
        });
        $categories = SOE\DB\Category::where('parent_id', '=', '0')->get(array('id', 'slug'));
        $subcategories = SOE\DB\Category::where('parent_id', '>', '0')->get(array('id', 'slug'));
        foreach($categories as $cat)
        {
            DB::table('entities')->where('category_id', '=', $cat->id)->update(array('category_slug' => $cat->slug));
        }

        foreach($subcategories as $subcat)
        {
            DB::table('entities')->where('subcategory_id', '=', $subcat->id)->update(array('subcategory_slug' => $subcat->slug));
        }
        Schema::table('entities', function($table)
        {
            $table->dropIndex('category_index');
            $table->dropIndex('subcategory_index');
        });
        $merchants = SOE\DB\Merchant::where('id', '>', '0')->get();
        foreach($merchants as $merchant)
        {
            DB::table('entities')->where('merchant_id', '=', $merchant->id)->update(array('franchise_active' => $merchant->is_active, 'franchise_demo' => $merchant->is_demo));
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