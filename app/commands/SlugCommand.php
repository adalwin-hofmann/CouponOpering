<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SlugCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'slug';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup slugs.';

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
        switch ($type)
        {
            case 'categories':
                $this->categories();
                break;
            case 'contests':
                $this->contests();
                break;
            case 'entities':
                $this->entities();
                break;
            case 'locations':
                $this->locations();
                break;
            case 'merchants':
                $this->merchants();
                break;
            case 'offers':
                $this->offers();
                break;
    
            default:
                $this->allSlugs();
                break;
        }
    }

    protected function allSlugs()
    {
        $this->info('Fixing all slugs...');
        $this->categories();
        $this->contests();
        $this->entities();
        $this->locations();
        $this->merchants();
        $this->offers();
    }

    protected function categories()
    {
        $this->info('Fixing category slugs...');
        DB::table('categories')->update(array('slug' => DB::raw("REPLACE(slug, '_', '-')")));
    }

    protected function contests()
    {
        $this->info('Fixing contest slugs...');
        DB::table('contests')->update(array('slug' => DB::raw("REPLACE(slug, '_', '-')")));
    }

    protected function entities()
    {
        $this->info('Fixing entity slugs...');
        DB::table('entities')->update(array('slug' => DB::raw("REPLACE(slug, '_', '-')"),
                                            'merchant_slug' => DB::raw("REPLACE(merchant_slug, '_', '-')"),
                                            'category_slug' => DB::raw("REPLACE(category_slug, '_', '-')"),
                                            'subcategory_slug' => DB::raw("REPLACE(subcategory_slug, '_', '-')")));
        Schema::table('entities', function($table)
        {
            $table->index('category_id', 'category_index');
            $table->index('subcategory_id', 'subcategory_index');
        });
        $categories = SOE\DB\Category::where('parent_id', '=', '0')->get(array('id', 'slug'));
        $subcategories = SOE\DB\Category::where('parent_id', '>', '0')->get(array('id', 'slug'));
        foreach($categories as $cat)
        {
            DB::table('entities')->where('category_id', '=', $cat->id)->where('category_slug', '=', '')->update(array('category_slug' => $cat->slug));
        }

        foreach($subcategories as $subcat)
        {
            DB::table('entities')->where('subcategory_id', '=', $subcat->id)->where('subcategory_slug', '=', '')->update(array('subcategory_slug' => $subcat->slug));
        }
        Schema::table('entities', function($table)
        {
            $table->dropIndex('category_index');
            $table->dropIndex('subcategory_index');
        });
    }

    protected function locations()
    {
        $this->info('Fixing location slugs...');
        DB::table('locations')->update(array('slug' => DB::raw("REPLACE(slug, '_', '-')"),
                                            'merchant_slug' => DB::raw("REPLACE(merchant_slug, '_', '-')")));
    }

    protected function merchants()
    {
        $this->info('Fixing merchant slugs...');
        DB::table('merchants')->update(array('slug' => DB::raw("REPLACE(slug, '_', '-')")));
    }

    protected function offers()
    {
        $this->info('Fixing offer slugs...');
        DB::table('offers')->update(array('slug' => DB::raw("REPLACE(slug, '_', '-')")));
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