<?php namespace SOE\Extensions;

use Session;

class Auth extends \Illuminate\Support\Facades\Auth
{

    public static function nonmember()
    {
        if(!parent::check())
        {
            $nonmember_id = Session::get('nonmember_id', 0);
            $nonmember = \SOE\DB\Nonmember::on('mysql-write')->find($nonmember_id);
            if(!empty($nonmember_id) && !$nonmember)
            {
                $nonmember = new \SOE\DB\Nonmember;
                $nonmember->id = $nonmember_id;
            }
            if(empty($nonmember))
            {
                $nonmember = new \SOE\DB\Nonmember;
                if(!\SoeHelper::isBot())
                {
                    $nonmember->save();
                    Session::put('nonmember_id', $nonmember->id);
                }   
            }
            return $nonmember;
        }
    }

    public static function person()
    {
        if(!parent::check())
        {
            $nonmember_id = Session::get('nonmember_id', 0);
            $nonmember = \SOE\DB\Nonmember::on('mysql-write')->find($nonmember_id);
            if($nonmember_id != 0 && !$nonmember)
            {
                $nonmember = new \SOE\DB\Nonmember;
                $nonmember->id = $nonmember_id;
            }
            if(empty($nonmember))
            {
                $nonmember = new \SOE\DB\Nonmember;
                if(!\SoeHelper::isBot())
                {
                    $nonmember->save();
                    Session::put('nonmember_id', $nonmember->id);
                }   
            }
            return $nonmember;
        }
        else
        {
            return parent::User();
        }
    }

    public static function attempt($credentials)
    {
        if(!isset($credentials['email']) || !isset($credentials['password']))
            return false;
        $user = \SOE\DB\User::on('mysql-write')
            ->where('email', '=', $credentials['email'])
            ->where('is_deleted', '0')
            ->first();
        if(!$user)
            return false;
        if(\Hash::check($credentials['password'], $user->password))
        {
            parent::login($user);
            return true;
        }
        return false;
    }

    public static function validate($credentials = array())
    {
        $valid = parent::validate($credentials);
        $u = \SOE\DB\User::on('mysql-write')->where('email', '=', $credentials['email'])->first();
        if(!empty($u) && $u->is_deleted == 0 && $valid)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}