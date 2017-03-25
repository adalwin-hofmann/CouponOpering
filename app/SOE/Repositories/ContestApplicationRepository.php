<?php

/**
*
* @api
*/

interface ContestApplicationRepository
{
    public function fillOut(ContestRepository $contest, $applicant);

    /***** API METHODS *****/
}