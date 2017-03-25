<?php namespace SOE\Repositories\Eloquent;

class NewsletterRepository extends BaseRepository implements \NewsletterRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'batch_id',
        'email',
        'type',
        'data_objects',
        'is_locked',
        'sent_at',
    );

    protected $model = 'Newsletter';

    public function __construct(
        \FeatureRepositoryInterface $features,
        \NewsletterScheduleRepositoryInterface $newsletterSchedules,
        \UserRepositoryInterface $users,
        \ZipcodeRepositoryInterface $zipcodes
    )
    {
        $this->features = $features;
        $this->newsletterSchedules = $newsletterSchedules;
        $this->users = $users;
        $this->zipcodes = $zipcodes;
        parent::__construct();
    }

    public function query()
    {
        return \SOE\DB\Newsletter::on('mysql-write');
    }

    public function lastFromBatch($batch_id, $type)
    {
        return $this->query()
                    ->where('batch_id', $batch_id)
                    ->where('type', $type)
                    ->orderBy('id', 'desc')
                    ->first();
    }

    public function getMemberNewsletterUsers($schedule_id)
    {

        $last = $this->lastFromBatch($schedule_id, 'member_newsletter');
        $begin_id = 0;
        if($last)
        {
            $begin = $this->users->findByEmail($last->email);
            $begin_id = $begin ? $begin->id : 0;
        }

        $batch = $this->newsletterSchedules->find($schedule_id);
        if($batch->zipcode)
        {
            $zipcode = $this->zipcodes->findByZipcode($batch->zipcode);

            return $this->users->query()
                ->leftJoin('user_locations', function($join)
                {
                    $join->on('users.id', '=', 'user_locations.user_id')
                        ->on('user_locations.is_deleted', '=', \DB::raw('0'));
                })
                ->canEmail()
                ->hasLocation()
                ->type('Member')
                ->where('users.id', '>', $begin_id)
                ->where('users.email', '!=', 'mgauthier@saveoneverything.com')
                ->where('users.email', '!=', 'mike@saveon.com')
                ->where('users.email', '!=', 'mikeg@saveoneverything.com')
                ->groupBy('users.id')
                ->orderBy('users.id')
                ->take(500)
                ->having(\DB::raw('(sqrt(pow(user_latm - '.$zipcode->latm.', 2) + pow(user_lngm - '.$zipcode->lngm.', 2)))'), '<', $batch->radius)
                ->get(array(
                    'users.*',
                    \DB::raw("COALESCE(user_locations.latm, users.latm) as user_latm"),
                    \DB::raw("COALESCE(user_locations.lngm, users.lngm) as user_lngm")
                ));
        }
        else
        {
            // Get recent location-specific batches
            $recent = $this->newsletterSchedules->query()
                ->where('send_at', '>', date('Y-m-d H:i:s', strtotime('-3 weeks')))
                ->whereNotNull('zipcode')
                ->lists('id');
            $recent[] = 0;

            // Get users who have not had a local newsletter generated for them within the last 3 weeks.
            return $this->users->query()
                ->leftJoin(\DB::raw("(SELECT users.id FROM users
                            JOIN newsletters ON users.email = newsletters.email
                            WHERE newsletters.batch_id IN (".implode(',', $recent).")
                            GROUP BY users.id) local_users"), 'users.id', '=', 'local_users.id')
                ->whereNull('local_users.id')
                ->canEmail()
                ->type('Member')
                ->where('users.id', '>', $begin_id)
                ->orderBy('users.id')
                ->take(500)
                ->get();
        }
    }

    public function getBatchToSend($schedule_id, $limit = 500)
    {
        $currents = $this->query()
                        ->where('batch_id', $schedule_id)
                        ->where('is_locked', '0')
                        ->whereNull('sent_at')
                        ->take($limit)
                        ->get();
        $aIds = array(0);
        foreach($currents as $current)
        {
            $aIds[] = $current->id;
        }
        \DB::connection('mysql-write')->table('newsletters')->whereIn('id', $aIds)->update(array('is_locked' => 1));
        return $currents;
    }

    public function unlockNewsletter($id)
    {
        \DB::connection('mysql-write')->table('newsletters')->where('id', $id)->update(array('is_locked' => 0));
    }

    public function markSent($newsletter_id)
    {
        \SOE\DB\Newsletter::on('mysql-write')->where('id', $newsletter_id)->update(array('is_locked' => 0, 'sent_at' => date('Y-m-d H:i:s')));
    }

    public function getMemberHistory($batch_id, $member_email, $iterations = 1)
    {
        $previous = $this->newsletterSchedules->query()
            ->where('batch_id', $batch_id)
            ->whereNotNull('sent_at')
            ->orderBy('id', 'desc')
            ->take($iterations)
            ->lists('id');
        $previous[] = 0;
        $newsletters = $this->query()
            ->whereIn('batch_id', $previous)
            ->where('email', $member_email)
            ->whereNotNull('sent_at')
            ->where('type', 'member_newsletter')
            ->get(array('data_objects'));
        $aHistory = array('coupons' => array(), 'deals' => array(), 'contests' => array());
        foreach($newsletters as $newsletter)
        {
            $content = json_decode($newsletter->data_objects);
            foreach($content->coupons as $coupon)
            {
                $aHistory['coupons'][] = $coupon;
            }
            foreach($content->deals as $deal)
            {
                $aHistory['deals'][] = $deal;
            }
            foreach($content->contests as $contest)
            {
                $aHistory['contests'][] = $contest;
            }
        }

        return $aHistory;
    }
}