<?php

interface ReviewRepository
{
    public function deleteReview();

    /***** API METHODS *****/

    public function apiDeleteReview();

    public function apiDeleteUserReview();

    public function apiDeleteNonmemberReview();
}