<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class EmailsCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for valid emails.';

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
        switch ($type) {
            case 'validate':
                $this->validate();
                break;
            case 'bulk_send':
                $this->bulk_send();
                break;
            case 'tag_quotes':
                $this->tagQuotes();
                break;
            default:
                $this->validate();
                break;
        }
    }

    protected function bulk_send()
    {
        set_time_limit(60*60); // 60 Mins
        ini_set('memory_limit', '2048M');
        if (($handle = fopen("http://s3.amazonaws.com/saveoneverything_uploads/soe3_users.csv", "r")) !== FALSE)
        {
            if(($data = fgetcsv($handle, null, ",")) !== FALSE){
                //skip the label row
                $num = count($data);
                $this->info("soe3_users.csv ".$num." columns\n");
            }
            $counter=0;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE)
            {
                $counter++;
                if(count($data)!=$num)
                {
                    $this->info($counter.": column # mismatch ".count($data)."\n");
                }
                else
                {
                    Mail::queueOn('SOE_Tasks', 'emails.welcometour', array(), function($message) use ($data)
                    {
                        $message->to($data[3])->subject('Welcome To The New SaveOn!');
                    });
                }   
            }
            fclose($handle);
            $this->info($counter.' Emails Sent');
        }
    }

    protected function validate()
    {
        set_time_limit(60*60); // 60 Mins
        ini_set('memory_limit', '2048M');
        $start = $this->option('start');
        $end = $this->option('end');
        $base_url = 'https://api.sendgrid.com/api/';
        $tail_url = '.get.json?api_user='.Config::get('mail.username').'&api_key='.Config::get('mail.password').'&start_date='.$start;
        if($end)
            $tail_url .= '&end_date='.$end;

        $aInvalid = array('');

        $ch = curl_init($base_url.'bounces'.$tail_url);                                                                      
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");                                                                
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $results = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $this->info($results);
        if($status == 200)
        {
            $results = json_decode($results);
            foreach($results as $bounce)
            {
                $aInvalid[] = $bounce->email;
            }
        }

        $ch = curl_init($base_url.'blocks'.$tail_url);                                                                      
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");                                                                
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $results = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $this->info($results);
        if($status == 200)
        {
            $results = json_decode($results);
            foreach($results as $block)
            {
                $aInvalid[] = $block->email;
            }
        }

        $ch = curl_init($base_url.'invalidemails'.$tail_url);                                                                      
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");                                                                
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $results = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $this->info($results);
        if($status == 200)
        {
            $results = json_decode($results);
            foreach($results as $invalid)
            {
                $aInvalid[] = $invalid->email;
            }
        }

        SOE\DB\User::whereIn('email', $aInvalid)->update(array('is_email_valid' => '0'));
    }

    protected function tagQuotes()
    {
        $appEmail = \App::make('AppEmailInterface');
        $old_quotes = \SOE\DB\Quote::join('users', 'quotes.user_id', '=', 'users.id')
                                    ->whereNotNull('quotes.posted_at')
                                    ->where('quotes.posted_at', '<', \DB::raw('DATE_SUB(NOW(), INTERVAL 14 DAY)'))
                                    ->groupBy('quotes.user_id')
                                    ->get(array('users.email'));
        foreach($old_quotes as $quote)
        {
            $appEmail->tagEmail($quote->email, 'TEMP Began SOHI Sequence');
        }

        $new_quotes = \SOE\DB\Quote::join('users', 'quotes.user_id', '=', 'users.id')
                                    ->whereNotNull('quotes.posted_at')
                                    ->where('quotes.posted_at', '>=', \DB::raw('DATE_SUB(NOW(), INTERVAL 14 DAY)'))
                                    ->groupBy('quotes.user_id')
                                    ->get(array('users.email'));
        foreach($new_quotes as $quote)
        {
            $appEmail->tagEmail($quote->email, 'Requested Project Quote');
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
            array('start', null, InputOption::VALUE_OPTIONAL, 'What date to query from', date('Y-m-d')),
            array('end', null, InputOption::VALUE_OPTIONAL, 'What date to end the query at', null),
            array('type', null, InputOption::VALUE_OPTIONAL, 'What command function to run', 'validate'),
        );
    }

}