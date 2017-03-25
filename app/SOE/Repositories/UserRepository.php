<?php

/**
*
* @api
*/

interface UserRepository
{
    public function findByEmail($email);

    public function checkType($type_string);

    public function getPrints(array $filters = array(), $limit = 0, $page = 0);

    public function getViews(array $filters = array(), $limit = 0, $page = 0);

    public function getClips(/*array $filters = array(), */$is_dailydeal, $limit = 0, $page = 0);

    public function getRedeems(array $filters = array(), $limit = 0, $page = 0);

    public function printEntity(EntityRepository $entity, $offer_rand = null);

    public function redeemEntity(EntityRepository $entity, $offer_rand = null);

    public function viewEntity(EntityRepository $entity);

    public function getRecommendations($limit = 0, $geoip = array());

    public function clipOffer(OfferRepository $offer);

    public function clickBanner(BannerRepository $banner);

    public function applyForContest(ContestRepository $contest);

    public function writeReview(ReviewableInterface $reviewable);

    public function share(ShareableInterface $shareable, $type, $params = array());

    public function attachRole(RoleRepository $role);

    public function removeRole(RoleRepository $role);

    public function getRoles(array $filters = array(), $limit = 0, $page = 0);

    public function getRules();

    public function setLocation($latitude = '', $longitude = '', $city = '', $state = '', $use_current = false);

    public function getRankings();

    public function getPreferences();

    public function saveLocation();

    public function saveSearchLocation($latitude, $longitude, $city, $state, $page = 0, $limit = 5);

    public function getSavedLocations($page = 0, $limit = 5);

    public function deleteSavedLocation($location_id);

    public function getEnteredContests();

    public function getNearbyContests($page = 0, $limit = 3);

    public function voteOnReview(ReviewRepository $review, $vote);

    public function makeSuggestion(array $params = array());

    /***** API METHODS *****/
    
    public function apiGetPrints();

    public function apiGetViews();

    public function apiGetClips();

    public function apiGetRedeems();

    public function apiPrintEntity();
    
    public function apiRedeemEntity();

    public function apiViewEntity();

    public function apiClipOffer();

    public function apiClickBanner();

    public function apiApplyForContest();

    public function apiSetLocation();

    public function apiGetRecommendations();

    public function apiSaveLocation();

    public function apiGetSavedLocations();

    public function apiDeleteSavedLocation();

    public function apiGetEnteredContests();

    public function apiGetNearbyContests();

    public function apiVoteOnReview();

    public function apiMakeSuggestion();

    public function apiSaveSearchLocation();
}
