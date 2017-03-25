<?php

interface ReviewableInterface
{
    public function writeReview(PersonInterface $reviewer);
}