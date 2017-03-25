<?php

interface NonmemberRepository
{
    public function getPrints(array $filters = array(), $limit = 0, $page = 0);

    public function getViews(array $filters = array(), $limit = 0, $page = 0);

    public function getRedeems(array $filters = array(), $limit = 0, $page = 0);

    public function printEntity(EntityRepository $entity, $offer_rand = null);

    public function redeemEntity(EntityRepository $entity, $offer_rand = null);

    public function viewEntity(EntityRepository $entity);

    public function writeReview(ReviewableInterface $reviewable);

    public function getRecommendations($limit = 0, $geoip = array());

    public function clickBanner(BannerRepository $banner);

    public function applyForContest(ContestRepository $contest);

    public function setLocation($latitude = '', $longitude = '', $city = '', $state = '', $use_current = false);

    public function getRankings();

    public function getPreferences();

    public function voteOnReview(ReviewRepository $review, $vote);

    public function makeSuggestion(array $params = array());

    /***** API METHODS *****/
    
    public function apiGetPrints();

    public function apiGetViews();

    public function apiGetRedeems();

    public function apiPrintEntity();

    public function apiRedeemEntity();

    public function apiViewEntity();

    public function apiWriteReview();

    public function apiClickBanner();

    public function apiApplyForContest();

    public function apiSetLocation();

    public function apiGetRecommendations();

    public function apiVoteOnReview();

    public function apiMakeSuggestion();
}
