<?php namespace SOE\Repositories\Eloquent;

class DealerApplicationRepository extends BaseRepository implements \DealerApplicationRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'business_name',
        'primary_contact',
        'contact_email',
        'contact_phone',
        'lead_email',
        'new_inventory_number',
        'used_inventory_number',
        'address1',
        'address2',
        'city',
        'state',
        'zipcode',
        'country',
        'account_password',
        'hours',
        'website',
        'description',
        'additional_info',
        'approved_at',
        'lead_amount',
        'market',
    );

    protected $model = 'DealerApplication';

    public function approve(\SOE\DB\ContractorApplication $application)
    {
        $data = array('application' => $application->toArray());
        \Mail::queueOn('SOE_Tasks', 'emails.sohi.contractor-approved', $data, function($message)
        {
            $message->to('digital.detroit@saveon.com')->subject('Contractor Approved');
        });
        $app_email = $application->contact_email;
        \Mail::queueOn('SOE_Tasks', 'emails.sohi.contractor-approved-notify', $data, function($message) use ($app_email)
        {
            $message->to($app_email)->subject('Your Application Is Approved!');
        });
        $application->approved_at = date('Y-m-d H:i:s');
        $application->save();
    }

}