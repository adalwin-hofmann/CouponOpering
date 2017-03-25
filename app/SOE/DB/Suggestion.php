<?php namespace SOE\DB;

use SOE\Extensions\Eloquent;

class Suggestion extends Eloquent 
{
    protected $table = 'suggestions';

    public static function boot()
    {
        parent::boot();

        Suggestion::saving(function($model)
        {
            if(isset($_SERVER['PARAM2']) && $_SERVER['PARAM2']=="readonly"){return false;}
        });
    }  
}

\SOE\DB\Suggestion::created(function($suggestion)
{
    $emails = \SOE\DB\Feature::where('name', 'suggestion_emails')->first();
    if($emails)
        $aEmails = explode(',', $emails->value);
    else
        $aEmails = array('wfobbs@saveon.com', 'cmelie@saveon.com');
    \Mail::send('emails.suggestion', array('suggestion' => $suggestion), function($message) use ($aEmails)
    {
        foreach($aEmails as $email)
        {
            $message->to($email);
        }
        $message->subject('User Suggestion');
        $message->sender('webmaster@saveoneverything.com', 'Save On Everything');
    });
});