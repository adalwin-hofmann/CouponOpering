<?php

interface QuoteRepositoryInterface
{
    public function postQuote(\SOE\DB\Quote $quote);
}