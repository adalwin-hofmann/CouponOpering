<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class RankCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'rank';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Ranks Users and Entities.';

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
            case 'user':
                $this->rankuser();
                break;
            case 'entity':
                $this->rankentity();
                break;
    
            default:
                $this->info('Possible --type= values: user, entity');
                break;
        }
	}

	public function rankuser()
    {
        $counter=0;
        
        //Process Users
        $tbl = array(array("obj"=>"Nonmember","type"=>"nonmembers","idtype"=>"nonmember_id"),array("obj"=>"User","type"=>"users","idtype"=>"user_id"));
        DB::connection()->disableQueryLog();
        foreach($tbl as $rec)
        {
               
            $users = DB::table($rec["type"])->take(50)->get();
            foreach ($users as $user)
            {

                 $userviews = DB::table('user_views')
                    ->select(DB::raw("count(*) as c"),"categories.slug")
                    ->join('merchants', 'merchants.id', '=', 'user_views.merchant_id')
                    ->join('categories', 'merchants.category_id', '=', 'categories.id')
                    ->groupBy('category_id')
                    ->where($rec["idtype"], '=', $user->id)
                    ->orderBy('c','desc')
                    ->get();

                // Grab User Record to update rank
                $u = $rec["obj"]::find($user->id);
                $i=3;
                foreach ($userviews as $rank)
                {   
                    $rankvalue = "rank_".$rank->slug;
                    $u->$rankvalue = $rank->c*$i;
                    $u->save();
                    $i=1;
                }             
            }
        }
        
        //$queries = print_r(DB::getQueryLog(),false);
        //$this->info($queries);
		$this->info("Ranking of $counter Users Complete\n");
    }

    public function rankentity()
	{
        $counter=0;
        //Process Entities
        $views = DB::table('user_views')->where('created_at', '>', DB::raw("DATE_SUB(NOW(), INTERVAL 30 DAY)"))
                                        ->groupBy('merchant_id')
                                        ->get(array(DB::raw('COUNT(*) as view_count'), 'merchant_id'));
        $aViews = array();
        foreach($views as $view)
        {
            $aViews[$view->merchant_id] = $view->view_count;
        }

        $reviews = DB::table('reviews')->join('offers', 'reviews.reviewable_id', '=', 'offers.id')
                                        ->where('reviews.reviewable_type', '=', 'Offer')
                                        ->where('reviews.is_deleted', '=', '0')
                                        ->groupBy('reviews.reviewable_id')
                                        ->get(array(DB::raw('SUM(reviews.upvotes) as score'), 'reviews.reviewable_id', 'offers.merchant_id'));
        $aReviews = array();
        foreach($reviews as $review)
        {
            $aReviews[$review->reviewable_id] = array('score' => $review->score, 'merchant_id' => $review->merchant_id);
        }

        DB::table('entities')->update(array('popularity' => 0));
        foreach($aReviews as $reviewable_id => $data)
        {
            $popularity = ($data['score'] * 10);
            DB::table('entities')->where('entitiable_type', '=', 'Offer')
                                ->where('entitiable_id', '=', $reviewable_id)
                                ->update(array(
                                    'popularity' => $popularity
                                ));
        }
        foreach($aViews as $merchant_id => $views)
        {
            DB::table('entities')->where('merchant_id', '=', $merchant_id)
                                ->update(array(

                                    'popularity' => DB::raw('`popularity` + '.$views)
                                ));
        }

        $this->info("Ranking of Entities Complete\n");
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
			array('type', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
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