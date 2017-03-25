<?php namespace SOE\Extensions;

/**
 * Extending the Laravel Mail facade to allow for filtering of known invalid emails.
 */
class Mail extends \Illuminate\Support\Facades\Mail
{
    public static function send($view, array $data, $callback, $validate = false)
    {
        if($validate)
        {
            $new_callback = self::validate($callback);
            if($new_callback)
                parent::send($view, $data, $new_callback);
        }
        else
            parent::send($view, $data, $callback);
    }

    public static function queue($view, array $data, $callback, $queue = null, $validate = false)
    {
        if($validate)
        {
            $new_callback = self::validate($callback);
            if($new_callback)
                parent::queue($view, $data, $new_callback, $queue);
        }
        else
            parent::queue($view, $data, $callback, $queue);
    }

    public static function queueOn($queue, $view, array $data, $callback, $validate = false)
    {
        if($validate)
        {
            $new_callback = self::validate($callback);
            if($new_callback)
                parent::queueOn($queue, $view, $data, $new_callback);
        }
        else
            parent::queueOn($queue, $view, $data, $callback);
    }

    /**
     * Determine if the To email addresses belong to a user with a valid email address.
     *
     * @param Closure   $callback The closure passed to the Mail function.
     * @return boolean
     */
    protected static function validate($callback)
    {
        $valid = true;
        $message = new \Illuminate\Mail\Message(new \Swift_Message);
        call_user_func($callback, $message);
        $aValidTo = array();
        foreach($message->getTo() as $address => $name)
        {
            $repo = \App::make('UserRepositoryInterface');
            $user = $repo->findByEmail($address);
            if(empty($user) || (!empty($user) && $user->is_email_valid == 1))
            {
                $aValidTo[$address] = $name;
            }
        }
        if(!empty($aValidTo))
        {
            $new_callback = function($message) use ($callback, $aValidTo)
            {
                call_user_func($callback, $message);
                $message->setTo($aValidTo);
            };
            return $new_callback;
        }

        return false;
    }
}