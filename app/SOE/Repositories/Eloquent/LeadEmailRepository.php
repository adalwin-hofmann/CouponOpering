<?php namespace SOE\Repositories\Eloquent;

class LeadEmailRepository extends BaseRepository implements \LeadEmailRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'franchise_id',
        'email_address',
        'format'
    );

    protected $model = 'LeadEmail';

    /**
     * Get all lead emails belonging to the given franchise.
     *
     * @param SOE\DB\Franchise
     * @return array
     */
    public function getByFranchise(\SOE\DB\Franchise $franchise)
    {
        return \SOE\DB\LeadEmail::where('franchise_id', '=', $franchise->id)->get();
    }

    /**
     * Remove a set of lead emails for the given franchise.
     *
     * @param SOE\DB\Franchise
     * @param array     $aEmails The array of emails to remove.
     * @return void
     */
    public function removeEmails(\SOE\DB\Franchise $franchise, $aEmails)
    {
        if(empty($aEmails))
            return;
        foreach($aEmails as $email)
        {
            \SOE\DB\LeadEmail::where('franchise_id', '=', $franchise->id)
                            ->where('email_address', '=', $email['email'])
                            ->where('format', '=', $email['format'])
                            ->delete();
        }
    }

    /**
     * Add a set of lead emails for the given franchise.
     *
     * @param SOE\DB\Franchise
     * @param array     $aEmails The array of emails to add.
     * @return void
     */
    public function addEmails(\SOE\DB\Franchise $franchise, $aEmails)
    {
        foreach($aEmails as $email)
        {
            $exists = \SOE\DB\LeadEmail::where('franchise_id', '=', $franchise->id)
                                        ->where('email_address', '=', $email['email'])
                                        ->where('format', '=', $email['format'])
                                        ->first();

            if(empty($exists))
            {
                $this->create(array(
                    'franchise_id' => $franchise->id,
                    'email_address' => $email['email'],
                    'format' => $email['format']
                ));
            }
        }
    }
}