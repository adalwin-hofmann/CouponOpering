<?php namespace SOE\Services\AppEmail;

interface AppEmailInterface
{
    public function addUser(\SOE\DB\User $user);
    public function updateUser(\SOE\DB\User $user);
    public function changeEmail($original, $new);
}