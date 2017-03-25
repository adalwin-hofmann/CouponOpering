<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Offer and Merchant Query Distance
	|--------------------------------------------------------------------------
	|
	| Distance system will query for offers. Defaults to 20 miles within app. 
	|
	*/

	'distance' => 1700*60, // approx. 50 miles
	'cache'		=> (isset($_SERVER['PARAM3']))?$_SERVER['PARAM3']:60*60*24 //Cache results for a day

);
