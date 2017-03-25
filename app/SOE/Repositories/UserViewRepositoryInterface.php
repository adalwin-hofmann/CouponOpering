<?php

interface UserViewRepositoryInterface
{
    public function getUserViewCountsByCategory(SOE\DB\User $user);
    public function getNonmemberViewCountsByCategory(SOE\DB\Nonmember $user);
    public function getMerchantViewCounts($days = 30);
    /***** API METHODS *****/
}