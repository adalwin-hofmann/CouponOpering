<?php 

class Yipit {

    protected $userRepository;

    function __construct (
        \ZipcodeRepositoryInterface $zipcodeRepository, 
        \YipitDivisionRepositoryInterface $yipitDivisionRepository
    )
    {
        $this->zipcodeRepository = $zipcodeRepository;
        $this->yipitDivisionRepository = $yipitDivisionRepository;
    }

    /**
     * Add yipit city jobs to the queue.
     *
     * @return void
     */
    public function enqueue()
    {
        $divisions = $this->yipitDivisionRepository->getActive();
        foreach($divisions as $division)
        {
            Queue::push('Yipit@getCity', array('city' => $division->slug), 'SOE_Tasks');
        }
    }

    /**
     * Call the yipit command for a specific city.
     *
     * @param object    $job Laravel queue job.
     * @param array     $data Array of job data.
     * @return void
     */
    public function getCity($job, $data)
    {
        if(isset($data['city']))
        {
            try
            {
                Artisan::call('yipit', array('--city' => $data['city']));
            }
            catch(Exception $e)
            {
                $job->delete();
                return;
            }
        }
        $job->delete();
    }

}