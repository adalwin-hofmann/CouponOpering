<?php

interface EntityRepositoryInterface
{
    public function resetPopularity();
    public function addOfferPopularity($popularity, $offer_id);
    public function addMerchantPopularity($popularity, $offer_id);
    /***** API METHODS *****/
}