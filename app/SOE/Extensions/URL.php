<?php namespace SOE\Extensions;

class URL extends \Illuminate\Support\Facades\URL
{
    public static function abs($path)
    {
        if(\App::environment() == 'prod')
        {
            $abs = parent::to($path);
            $host = parse_url($abs, PHP_URL_HOST);
            if(stristr($host, 'sales.saveon') || stristr($host, 'saveon-admin') || stristr($host, 'stage'))
                return $abs;
            else
                return 'http://www.saveon.com'.($path == '/' ? '' : '/').ltrim($path,'/');
        }
        else
            return parent::to($path);
    }
}