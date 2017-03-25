<?php

interface ShareableInterface
{
    public function share(UserRepository $sharer, $type, $params = array());
}