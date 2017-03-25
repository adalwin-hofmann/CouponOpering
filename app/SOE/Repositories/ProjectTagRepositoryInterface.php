<?php

interface ProjectTagRepositoryInterface
{
    public function getFranchiseTags(\SOE\DB\Franchise $franchise = null);
}