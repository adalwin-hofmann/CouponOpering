<?php 

class Ranking {

    protected $userRepository;
    protected $nonmemberRepository;
    protected $userViewRepository;
    protected $entityRepository;
    protected $reviewRepository;

    const RANK_MULTIPLIER = 3;
    const REVIEW_MULTIPLIER = 10;

    function __construct(
        \UserRepositoryInterface $userRepository,
        \NonmemberRepositoryInterface $nonmemberRepository,
        \UserViewRepositoryInterface $userViewRepository,
        \EntityRepositoryInterface $entityRepository,
        \ReviewRepositoryInterface $reviewRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->nonmemberRepository = $nonmemberRepository;
        $this->userViewRepository = $userViewRepository;
        $this->entityRepository = $entityRepository;
        $this->reviewRepository = $reviewRepository;
    }

    /**
     * Add ranking jobs of the given type to the queue.
     *
     * @param string    $type The type of job to enqueue, user or entity
     */
    public function enqueue($type)
    {
        switch ($type) {
            case 'user':
                $rankables = $this->userRepository->getRankable();
                $nonmember_rankables = $this->nonmemberRepository->getRankable();
                $this->enqueueUser($rankables, 'User');
                $this->enqueueUser($nonmember_rankables, 'Nonmember');
                break;
            
            case 'entity':
                $this->enqueueEntities();
                break;
        }
    }

    /**
     * Rank a User's preferences.
     *
     * @param object    $job Laravel queue job.
     * @param array     $data Array of job data.
     * @return void
     */
    public function rankUser($job, $data)
    {
        if(isset($data['user_id']))
        {
            $user = $this->userRepository->find($data['user_id']);
            $userviews = $this->userViewRepository->getUserViewCountsByCategory($user);
            $this->generateUserScore($user, $userviews);
            $user->ranked_at = date('Y-m-d H:i:s');
            $user->save();
        }
        $job->delete();
    }

    /**
     * Rank a Nonmember's preferences.
     *
     * @param object    $job Laravel queue job.
     * @param array     $data Array of job data.
     * @return void
     */
    public function rankNonmember($job, $data)
    {
        if(isset($data['user_id']))
        {
            $nonmember = $this->nonmemberRepository->find($data['user_id']);
            $userviews = $this->userViewRepository->getNonmemberViewCountsByCategory($nonmember);
            $this->generateUserScore($nonmember, $userviews);
            $nonmember->ranked_at = date('Y-m-d H:i:s');
            $nonmember->save();
        }
        $job->delete();
    }

    /**
     * Update entity offer popularity.
     *
     * @param object    $job Laravel queue job.
     * @param array     $data Array of job data.
     * @return void
     */
    public function updateOfferPopularity($job, $data)
    {
        if(isset($data['popularity']) && isset($data['offer_id']))
            $this->entityRepository->addOfferPopularity($data['popularity'], $data['offer_id']);
        $job->delete();
    }

    /**
     * Update entity merchant popularity.
     *
     * @param object    $job Laravel queue job.
     * @param array     $data Array of job data.
     * @return void
     */
    public function updateMerchantPopularity($job, $data)
    {
        if(isset($data['popularity']) && isset($data['merchant_id']))
            $this->entityRepository->addMerchantPopularity($data['popularity'], $data['merchant_id']);
        $job->delete();
    }

    /**
     * Generate user preference scores based on view data.
     *
     * @param mixed     $person User or Nonmember model.
     * @param array     $view_data Array of user's view data.
     * @return void
     */
    protected function generateUserScore($person, $view_data)
    {
        $i = self::RANK_MULTIPLIER;
        foreach ($view_data as $rank)
        {   
            $rankvalue = "rank_".SoeHelper::hyphenToUnderscore($rank->slug);
            $rankvalue = $rankvalue == 'rank_home_improvement' ? 'rank_home_services' : $rankvalue;
            $person->$rankvalue = $rank->views*$i;
            $person->save();
            $i=1;
        }
    }

    /**
     * Push a user ranking job to the queue.
     *
     * @param array     $rankables An array of Users or Nonmembers to push to the queue.
     * @param string    $type The type of user.
     * @return void
     */
    protected function enqueueUser($rankables, $type)
    {
        foreach($rankables as $rankable)
        {
            Queue::push('Ranking@rank'.$type, array('user_id' => $rankable->id), 'SOE_Tasks');
        }
    }

    /**
     * Push entity popularity jobs to the queue.
     *
     * @return void;
     */
    protected function enqueueEntities()
    {
        $views = $this->userViewRepository->getMerchantViewCounts();
        $aViews = array();
        foreach($views as $view)
        {
            $aViews[$view->merchant_id] = $view->view_count;
        }

        $reviews = $this->reviewRepository->getReviewScores();
        $aReviews = array();
        foreach($reviews as $review)
        {
            $aReviews[$review->reviewable_id] = array('score' => $review->score, 'merchant_id' => $review->merchant_id);
        }

        $this->entityRepository->resetPopularity();
        foreach($aReviews as $reviewable_id => $data)
        {
            $popularity = ($data['score'] * self::REVIEW_MULTIPLIER);
            Queue::push('Ranking@updateOfferPopularity', array('popularity' => $popularity, 'offer_id' => $reviewable_id), 'SOE_Tasks');   
        }
        foreach($aViews as $merchant_id => $views)
        {
            Queue::push('Ranking@updateMerchantPopularity', array('popularity' => $views, 'merchant_id' => $merchant_id), 'SOE_Tasks');
        }
    }
}