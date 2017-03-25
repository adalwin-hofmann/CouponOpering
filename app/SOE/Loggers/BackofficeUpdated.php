<?php namespace SOE\Loggers;

class BackofficeUpdated {

    public function __construct()
    {
        
    }

    /**
     * Handle a backoffice update.  
     *
     * @param string $object_type
     * @param integer $object_id
     * @param integer $user_id
     * @param mixed $data
     * @param string $description
     * @return void
     */
    public function handle($object_type, $object_id, $user_id = 0, $data = array(), $description = '')
    {
        $log = new \SOE\DB\ActionLog;
        $log->object_type = $object_type;
        $log->object_id = $object_id;
        $log->user_id = $user_id ? $user_id : (Auth::check() ? Auth::user()->id : 0);
        $log->data_snapshot = method_exists($data, 'toArray') ? json_encode($data->toArray()) : json_encode($data);
        $log->action_description = $description;
        $log->save();
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param Illuminate\Events\Dispatcher $events
     * @return array
     */
    public function subscribe($events)
    {
        $events->listen('backoffice.updated', 'SOE\Loggers\BackofficeUpdated');
    }

}
