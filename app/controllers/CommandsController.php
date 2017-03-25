<?php

class CommandsController extends BaseController {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->beforeFilter(function()
        {
            if(!Auth::check())
                return Redirect::to('/');

            $user = Auth::User();
            if($user->email != 'admin@save.com')
                return Redirect::to('/');
        });
    }

    public function getIndex($command = null, $type = null)
    {
        
        if($command)
            return $this->callCommand($command, $type);

        $vw = View::make('commands.index');
        return $vw;
        
    }

    protected function callCommand($command = null, $type = null)
    {
        
        $params = array();
        if($type)
            $params['--type'] = $type;
        Artisan::call($command, $params);
        
    }
}