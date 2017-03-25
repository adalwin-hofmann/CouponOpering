<?php namespace SOE\Repositories\Eloquent;

class ContestLocationRepository extends BaseRepository implements \ContestLocationRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'contest_id',
        'zipcode',
        'state',
        'latitude',
        'longitude',
        'latm',
        'lngm',
        'service_radius',
    );

    protected $model = 'ContestLocation';

    public function __construct(\ZipcodeRepositoryInterface $zipcodes)
    {
        $this->zipcodes = $zipcodes;
    }

    /**
     * Retrieve all Contest Locations for the given contest.
     *
     * @param \SOE\DB\Contest $contest
     * @return array
     */
    public function getForContest(\SOE\DB\Contest $contest)
    {
        return $this->query()
            ->where('contest_id', $contest->id)
            ->get();
    }

    /**
     * Delete all Contest Locations for the given contest.
     *
     * @param \SOE\DB\Contest $contest]
     */
    public function removeForContest(\SOE\DB\Contest $contest)
    {
        $this->query()
            ->where('contest_id', $contest->id)
            ->delete();
    }

    /**
     * Add contest locations by zipcode and radius.
     *
     * @param \SOE\DB\Contest $contest
     * @param mixed $zipcode Single or array of zipcodes
     * @param integer $radius Service radius around zipcode
     */
    public function addForContestByZipcode(\SOE\DB\Contest $contest, $zipcode, $radius)
    {
        $zipcodes = is_array($zipcode) ? $zipcode : array($zipcode);
        foreach($zipcodes as $zip)
        {
            $existing = $this->query()->where('contest_id', $contest->id)
                ->where('zipcode', $zip)
                ->first();

            $zipcode = $this->zipcodes->findByZipcode($zip);
            if(!$zipcode || $existing)
                continue;

            $this->create(array(
                'contest_id' => $contest->id,
                'zipcode' => $zip,
                'state' => $zipcode->state,
                'latitude' => $zipcode->latitude,
                'longitude' => $zipcode->longitude,
                'latm' => $zipcode->latm,
                'lngm' => $zipcode->lngm,
                'service_radius' => $radius
            ));
        }
    }

    /**
     * Remove contest locations by zipcode.
     *
     * @param \SOE\DB\Contest $contest
     * @param mixed $zipcode Single or array of zipcodes
     */
    public function removeForContestByZipcode(\SOE\DB\Contest $contest, $zipcode)
    {
        $zipcodes = is_array($zipcode) ? $zipcode : array($zipcode);
        foreach($zipcodes as $zip)
        {
            $this->query()->where('contest_id', $contest->id)
                ->where('zipcode', $zip)
                ->delete();
        }
    }

}