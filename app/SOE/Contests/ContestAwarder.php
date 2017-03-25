<?php namespace SOE\Contests;

class ContestAwarder
{
    public function  __construct()
    {
        $this->contests = \App::make('ContestRepositoryInterface');
        $this->contestApplications = \App::make('ContestApplicationRepositoryInterface');
        $this->contestWinners = \App::make('ContestWinnerRepositoryInterface');
        $this->contestAwardDates = \App::make('ContestAwardDateRepositoryInterface');
        $this->features = \App::make('FeatureRepositoryInterface');
        $this->users = \App::make('UserRepositoryInterface');
        $this->zipcodes = \App::make('ZipcodeRepositoryInterface');
    }

    public function handleReady($dates)
    {
        foreach($dates as $date)
        {
            // Not time to award.
            if(time() - strtotime($date->award_at) < 0)
                continue;

            $verified = $this->contestWinners->query()
                ->where('award_date_id', $date->id)
                ->whereNotNull('verified_at')
                ->count();
            // Already enough winners
            if($verified >= $date->winners)
            {
                $contest = $this->contests->find($date->contest_id);
                if($contest->follow_up_id != 0 && $contest->total_inventory != 0 && $contest->current_inventory == 0)
                {
                    $today = strtotime("now");
                    $content_ended = strtotime($contest->current_inventory);
                    $dateDiff = $today - $content_ended;
                    $fullDays = floor($dateDiff/(60*60*24));
                    if($fullDays == 5)
                    {
                        $this->contests->sendEndingEmail($date->contest_id);
                    }
                }
                continue;
            }

            if($date->verify_attempts == 0)
                $this->firstAttempt($date);
            else if($date->verify_attempts <= 6)
                $this->secondaryAttempt($date);
            else if($date->verify_attempts >= 7)
                $this->finalAttempt($date);
            else
            {
                $contest = $this->contests->find($date->contest_id);
                $this->closeContest($date,$contest);
            }
        }
    }

    protected function firstAttempt($date)
    {
        $contest = $this->contests->find($date->contest_id);
        $date->verify_attempts = $date->verify_attempts + 1;
        $date->save();

        /*if(!$contest->is_automated)
        {
            $admins = $this->features->findByName('contest_admins', false);
            $admins = $admins ? $admins->value : 'abedor@saveon.com';
            $admins = explode(',', $admins);
            \Mail::send('emails.contest-ready', ['contest' => $contest->toArray()], function($message) use ($admins)
            {
                $message->subject('SaveOn - Contest Ready For Awarding');
                foreach($admins as $admin)
                {
                    $message->to($admin, '');
                }
            });
            return;
        }*/

        $pool_size = $this->features->findByName('contest_winner_pool', false);
        $pool_size = $pool_size ? $pool_size->value : 5;
        // Select semi-finalists
        $semiFinalists = $this->contestApplications->selectSemifinalists($contest->id, $pool_size);
        if(count($semiFinalists) == 0)
        {
            $this->closeContest($date,$contest);
            return;
        }
        $this->notifySemifinalists($semiFinalists, $date, $contest);
    }

    protected function secondaryAttempt($date)
    {
        $contest = $this->contests->find($date->contest_id);
        $date->verify_attempts = $date->verify_attempts + 1;
        $date->save();

        /*if(!$contest->is_automated || (time() - strtotime($date->verify_by) < 0))
            return;*/
        if(time() - strtotime($date->verify_by) < 0)
            return;

        $selected = $this->contestAwardDates->selectedWinners($date->id);
        if($selected >= $date->winners)
            return;

        $pool_size = $this->features->findByName('contest_winner_pool', false);
        $pool_size = $pool_size ? $pool_size->value : 5;
        // Select semi-finalists
        $semiFinalists = $this->contestApplications->selectSemifinalists($contest->id, $pool_size);
        if(count($semiFinalists) == 0)
        {
            $this->closeContest($date,$contest);
            return;
        }
        $this->notifySemifinalists($semiFinalists, $date, $contest);
    }

    protected function finalAttempt($date)
    {
        $contest = $this->contests->find($date->contest_id);
        /*if(!$contest->is_automated)
            return;*/

        // Select semi-finalists
        $semiFinalists = $this->contestApplications->makeAllSemifinalists($contest->id);
        if(count($semiFinalists) == 0)
        {
            if($contest->total_inventory != 0 && $contest->current_inventory != 0)
            {
                $semiFinalists = $this->contestApplications->getAllSemifinalists($contest->id);
                $this->notifyAllSemifinalists($semiFinalists, $date, $contest);
                return;
            }
            $this->closeContest($date,$contest);
            return;
        }
        $this->notifySemifinalists($semiFinalists, $date, $contest);
    }

    protected function closeContest($date,$contest)
    {
        $date->all_awarded_at = date('Y-m-d H:i:s');
        $date->save();

        $admins = $this->features->findByName('contest_admins', false);
        $admins = $admins ? $admins->value : 'abedor@saveon.com';
        $admins = explode(',', $admins);
        $selected = $this->contestAwardDates->selectedWinners($date->id);

        \Mail::send('emails.contest-closed', ['contest' => $contest->toArray(), 'date' => $date->toArray(), 'selected' => $selected], function($message) use ($admins)
        {
            $message->subject('SaveOn - Not Enough Contest Winners');
            foreach($admins as $admin)
            {
                $message->to($admin, '');
            }
        });
    }

    protected function notifySemifinalists($semiFinalists, $date, $contest)
    {
        $date->verify_by = date('Y-m-d H:i:s', strtotime('+48 hours'));
        $date->save();
        $data = ['contest' => $contest->toArray(), 'date' => $date->toArray()];
        foreach($semiFinalists as $semi)
        {
            $zipcode = $this->zipcodes->findByZipcode($semi->zip);
            $user = $this->users->find($semi->user_id);
            $names = explode(' ', $user->name);
            // Create contest winner record
            $winner = $this->contestWinners->create(array(
                'contest_id' => $contest->id,
                'user_id' => $semi->user_id,
                'first_name' => $names[0],
                'last_name' => isset($names[1]) ? $names[1] : '',
                'city' => $zipcode ? $zipcode->city : '',
                'state' => $zipcode ? $zipcode->state : '',
                'selected_at' => date('Y-m-d H:i:s'),
                'verify_by' => $date->verify_by,
                'award_date_id' => $date->id,
                'verify_key' => bin2hex(openssl_random_pseudo_bytes(8))
            ));

            $data['winner'] = $winner->toArray();
            \Mail::send('emails.contest-semifinalist', $data, function($message) use ($semi)
            {
                $message->to($semi->email, ucwords($semi->first_name.' '.$semi->last_name))->subject('SaveOn - Contest Semi-Finalist!');
            });
        }

        $admins = $this->features->findByName('contest_admins', false);
        $admins = $admins ? $admins->value : 'abedor@saveon.com';
        $admins = explode(',', $admins);

        \Mail::send('emails.contest-semifinalist-notification', ['contest' => $contest->toArray(), 'date' => $date->toArray(), 'selected' => $semiFinalists], function($message) use ($admins)
        {
            $message->subject('SaveOn - Semifinalists Selected');
            foreach($admins as $admin)
            {
                $message->to($admin, '');
            }
        });
    }

    protected function notifyAllSemifinalists($semiFinalists, $date, $contest)
    {
        $date->verify_by = date('Y-m-d H:i:s', strtotime('+48 hours'));
        $date->save();
        $data = ['contest' => $contest->toArray(), 'date' => $date->toArray()];
        foreach($semiFinalists as $semi)
        {
            $zipcode = $this->zipcodes->findByZipcode($semi->zip);
            $user = $this->users->find($semi->user_id);
            $names = explode(' ', $user->name);
            $winner = $this->getByContestAndUser($semi->user_id, $contest->id);
            // Create contest winner record
            /*$winner = $this->contestWinners->create(array(
                'contest_id' => $contest->id,
                'user_id' => $semi->user_id,
                'first_name' => $names[0],
                'last_name' => isset($names[1]) ? $names[1] : '',
                'city' => $zipcode ? $zipcode->city : '',
                'state' => $zipcode ? $zipcode->state : '',
                'selected_at' => date('Y-m-d H:i:s'),
                'verify_by' => $date->verify_by,
                'award_date_id' => $date->id,
                'verify_key' => bin2hex(openssl_random_pseudo_bytes(8))
            ));*/

            $data['winner'] = $winner->toArray();
            \Mail::send('emails.contest-semifinalist', $data, function($message) use ($semi)
            {
                $message->to($semi->email, ucwords($semi->first_name.' '.$semi->last_name))->subject('SaveOn - Contest Semi-Finalist!');
            });
        }

        $admins = $this->features->findByName('contest_admins', false);
        $admins = $admins ? $admins->value : 'abedor@saveon.com';
        $admins = explode(',', $admins);

        \Mail::send('emails.contest-semifinalist-notification', ['contest' => $contest->toArray(), 'date' => $date->toArray(), 'selected' => $semiFinalists], function($message) use ($admins)
        {
            $message->subject('SaveOn - Semifinalists Selected');
            foreach($admins as $admin)
            {
                $message->to($admin, '');
            }
        });
    }
}