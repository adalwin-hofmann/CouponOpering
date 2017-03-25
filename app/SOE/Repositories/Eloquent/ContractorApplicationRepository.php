<?php namespace SOE\Repositories\Eloquent;

class ContractorApplicationRepository extends BaseRepository implements \ContractorApplicationRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'business_name',
        'primary_contact',
        'contact_email',
        'contact_phone',
        'lead_email',
        'address1',
        'address2',
        'city',
        'state',
        'zipcode',
        'country',
        'account_password',
        'license_number',
        'bond_number',
        'insurance_company',
        'policy_number',
        'agent',
        'has_outside_labor',
        'is_outside_insured',
        'is_bbb_accredited',
        'does_background_checks',
        'background_explaination',
        'additional_info',
        'approved_at',
        'agent_phone',
        'lead_phone',
    );

    protected $model = 'ContractorApplication';

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

    public function setTags($id, $tags = array())
    {
        if(empty($tags))
            return false;
        $validTags = \SOE\DB\ProjectTag::whereIn('slug', $tags)->lists('id');
        if(empty($validTags))
            return false;

        \DB::table('contractor_application_tags')
            ->where('contractor_application_id', $id)
            ->delete();

        foreach($validTags as $tag)
        {
            \Eloquent::unguard();
            \DB::table('contractor_application_tags')->insert(array(
                'contractor_application_id' => $id,
                'project_tag_id' => $tag
            ));
        }

        return true;
    }

    public function getTags($id)
    {
        return \SOE\DB\ContractorApplicationTag::where('contractor_application_id', $id)
            ->join('project_tags', 'contractor_application_tags.project_tag_id', '=', 'project_tags.id')
            ->get(array('project_tags.*'));
    }

}