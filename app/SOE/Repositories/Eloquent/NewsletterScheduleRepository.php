<?php namespace SOE\Repositories\Eloquent;

class NewsletterScheduleRepository extends BaseRepository implements \NewsletterScheduleRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'type',
        'batch_id',
        'prep_at',
        'prepped_at',
        'send_at',
        'sent_at',
        'send_interval',
        'send_hour',
        'first_category',
        'second_category',
        'third_category',
        'zipcode',
        'radius',
        'schedule_name',
        'subject_line',
        'intro_paragraph',
        'featured_merchant_id',
    );

    protected $model = 'NewsletterSchedule';

    public function __construct(
        \FeatureRepositoryInterface $features,
        \UserRepositoryInterface $users
    )
    {
        $this->features = $features;
        $this->users = $users;
        parent::__construct();
    }

    public function query()
    {
        return \SOE\DB\NewsletterSchedule::on('mysql-write');
    }

    public function getLatest($type, $id = null, $batch_id = null)
    {
        $query = $this->query()->where('type', $type);
        if($id)
            $query->where('id', $id);
        if($batch_id)
            $query->where('batch_id', $batch_id);

        return $query->orderBy('id', 'desc')
            ->first();
    }

    public function getAll()
    {
        //return $this->query()->groupBy('type')->orderBy('type')->get();

        return $this->query()
            ->whereNull('sent_at')
            ->orderBy('schedule_name')
            ->get();
    }

    public function updateLatest($id, $type, $batch_id, $params)
    {
        $latest = $this->find($id);
        if(!$latest)
        {
            $previous = $this->query()
                ->where('type', $type)
                ->where('batch_id', $batch_id)
                ->orderBy('id', 'desc')
                ->first();
            $latest = $this->blank();
            $params['batch_id'] = $previous ? $previous->batch_id : 0;
        }
        foreach($params as $key => $value)
        {
            if(in_array($key, $this->columns))
            {
                $latest->$key = $value;
            }
        }
        $latest->send_at = date('Y-m-d '.$latest->send_hour.':i:s', strtotime($latest->send_at));
        $latest->prep_at = date('Y-m-d 00:00:00', strtotime('-2 day', strtotime($latest->send_at)));
        $latest->save();
        if($latest->batch_id == 0)
        {
            $latest->batch_id = $latest->id;
            $latest->save();
        }
        return $latest;
    }

    public function setSchedule($id)
    {
        $past = $this->find($id);
        $past->sent_at = date('Y-m-d H:i:s');
        $past->save();

        $prep_date = date('Y-m-d 00:00:00', strtotime('+'.($past->send_interval-2).' days'));
        $send_date = date('Y-m-d '.$past->send_hour.':00:00', strtotime('+'.$past->send_interval.' days'));

        $this->create(array(
            'type' => $past->type,
            'batch_id' => $past->batch_id,
            'prep_at' => $prep_date,
            'send_at' => $send_date,
            'send_interval' => $past->send_interval,
            'send_hour' => $past->send_hour,
            'zipcode' => $past->zipcode,
            'radius' => $past->radius,
            'schedule_name' => $past->schedule_name,
            'subject_line' => $past->subject_line,
            'intro_paragraph' => $past->intro_paragraph,
            'featured_merchant_id' => $past->featured_merchant_id
        ));
    }

    public function preppingDone($id)
    {
        $schedule = $this->query()
            ->where('id', $id)
            ->whereNull('prepped_at')
            ->update(array('prepped_at' => date('Y-m-d H:i:s')));
    }

    public function getPreppable()
    {
        return $this->query()
            ->where('prep_at', '<=', date('Y-m-d 23:59:59'))
            ->whereNull('prepped_at')
            ->get();
    }

    public function getSendable()
    {
        return $this->query()
            ->whereNotNull('prepped_at')
            ->where('send_at', '<=', date('Y-m-d H:i:s'))
            ->whereNull('sent_at')
            ->get();
    }
    
}