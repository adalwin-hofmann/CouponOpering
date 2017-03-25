<?php namespace SOE\Repositories\Eloquent;

class NonmemberRepository extends BaseRepository implements \NonmemberRepositoryInterface, \RankableInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'rank_food_dining',
        'rank_home_services',
        'rank_health_beauty',
        'rank_auto_transportation',
        'rank_travel_entertainment',
        'rank_retail_fashion',
        'rank_special_services',
        'rank_community',
    );

    protected $model = 'Nonmember';
    protected $entityRepository;

    public function __construct(
        \EntityRepositoryInterface $entityRepository
    )
    {
        $this->entityRepository = $entityRepository;
        parent::__construct();
    }

    /**
     * Retrieve an Nonmember by Id, overrides BaseRepository find().
     *
     * @param   int     $nonmember_id
     * @param   boolean $with_trashed Include soft deleted results.
     * @return mixed
     */
    public function find($nonmember_id = null, $with_trashed = false, $write_db = false)
    {
        if($with_trashed)
            $user = $this->query()->withTrashed()->find($nonmember_id);
        else
            $user = $this->query()->find($nonmember_id);
        if(!empty($user))
        {
            return $user;
        }
        return;
    }

    /**
     * Begin querying the model.
     *
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query()
    {
        return \SOE\DB\Nonmember::on('mysql-write');
    }

    /**
     * Retrieve a list of nonmembers who have not been ranked recently.
     *
     * @param int   $limit Default 50;
     * @return Nonmembers
     */
    public function getRankable($limit = 50)
    {
        return $this->query()->take($limit)->orderBy('ranked_at', 'asc')->get();
    }

    /**
     * Get an array of recommended Entities.
     * See getRecommendations in EntityRepository
     * @return mixed Entities
     */
    public function getRecommendations($nonmember, $limit = 0, $geoip = array(), $ordering, $type = 'soe')
    {
        if($type == 'soe')
        {
            $rankings = $this->getRankings($nonmember);
            $preferences = $this->getPreferences($nonmember);
            $recommendations = $this->entityRepository->getRecommendations($rankings, $preferences, false, $geoip, $limit, $ordering);
        }
        else if($type == 'sohi')
        {
            $recommendations = $this->entityRepository->getSohiRecommendations(false, $geoip, $limit, $ordering);
        }
        else if($type == 'soct')
        {
            $recommendations = $this->entityRepository->getSoctRecommendations(false, $geoip, $limit, $ordering);
        }

        return $recommendations;
    }

    /**
     * Return the category rankings for this Person.
     *
     * @return array
     */
    public function getRankings(\SOE\DB\Nonmember $nonmember)
    {
        $cat_slugs = array('food_dining', 'home_services', 'health_beauty', 'auto_transportation', 'travel_entertainment', 'retail_fashion', 'special_services');
        $aRankings = array();
        foreach($cat_slugs as $slug)
        {
            $aRankings[$slug] = $nonmember->{'rank_'.$slug};
        }
        return $aRankings;
    }

    /**
     * Return the category preferences for this Person.
     *
     * @return array
     */
    public function getPreferences(\SOE\DB\Nonmember $nonmember)
    {
        $cat_slugs = array('food_dining', 'home_services', 'health_beauty', 'auto_transportation', 'travel_entertainment', 'retail_fashion', 'special_services');
        $aPreferences = array();
        foreach($cat_slugs as $slug)
        {
            $aPreferences[$slug] = 1;
        }
        return $aPreferences;
    }

    public function view(\PersonInterface $viewer, \ViewableInterface $viewable)
    {
        return $viewable->view($viewer);
    }
}
