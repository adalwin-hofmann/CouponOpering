<?php

class CouponsRss
{
    public function __construct()
    {
		// construct as needed
    }

    public function build($type, $page = 0, $limit = 0)
    {
		
		$merchants = $this->get_merchants($type);

		$xml = new \SimpleXMLElement('<rss></rss>');
		$xml->addAttribute('version', '2.0');
		$channel = $xml->addChild('channel');

		if (!empty($type)) {
			foreach ($merchants as $merchant) {
				if(!$merchant)
					return false;

				$display = htmlentities($merchant->display);
				$display = $this->stripInvalidXml($display);

				$offers = $this->get_offers($merchant->id);
				$today = strtotime(date('Y-m-d', time()). '00:00:00');
				
				foreach($offers as $offer)
				{
					
					$description = $offer->description.'<br><br>Expires: '.date('m/d/Y', strtotime($offer->expires_at));
					$description = htmlentities($description);
					$description = $this->stripInvalidXml($description);

					$item = $channel->addChild('item');
					$item->addChild('id', $offer->id);
					$item->addChild('dealer', $display);

					$title = htmlentities($offer->name);
					$title = $this->stripInvalidXml($title);
					$item->addChild('title', $title);

					$item->addChild('is_active', $offer->is_active);
					
					$item->addChild('timestamp', $today);
					$item->addChild('stopTimestamp', strtotime($offer->expires_at));
					
					$item->addChild('startDate', date('D, d M Y H:i:s -0500', strtotime($offer->starts_at)));
					$item->addChild('stoptDate', date('D, d M Y H:i:s -0500', strtotime($offer->expires_at)));
					$item->addChild('description', $description);
					$item->addChild('special_price', htmlentities($offer->special_price));
					$item->addChild('regular_price', htmlentities($offer->regular_price));
					$item->addChild('savings', htmlentities($offer->savings));
					
					$item->addChild('custom_category_id', $offer->custom_category_id);
					$item->addChild('custom_subcategory_id', $offer->custom_subcategory_id);
					
					$item->addChild('deleted_at', htmlentities($offer->deleted_at));
					$item->addChild('requires_member', htmlentities($offer->requires_member));
					
					$item->addChild('url', htmlentities($offer->path));
					$item->addChild('is_featured', htmlentities($offer->is_featured));
					$item->addChild('merchant_logo', htmlentities($offer->merchant_logo));
					$item->addChild('hide_expiration', htmlentities($offer->hide_expiration));
				}
			}
		}
		return $xml;
	}
	
	public function get_merchants($type) {
		// get all merchants that have a title and match the category type
		// car dealers - $type = 120
		$merchants = DB::table('merchants')
			->where('subcategory_id', '=', $type)
			->where('page_title', '!=', "")
			->get();
	
		return $merchants;	
	}
	
	public function get_offers($merchant_id) {
		// get timestamp for today
		//$today = strtotime(date('Y-m-d', time()). '00:00:00');
		//$today = date('Y-m-d 00:00:00', time());
		$offers = DB::table('offers')
			->where('merchant_id', '=', $merchant_id)
			->where('is_active', '=', 1)
			/*->where('expires_at', '>=', $today)*/
			->get();
	
		return $offers;	
	}

	/**
	 * Removes invalid XML
	 *
	 * @access public
	 * @param string $value
	 * @return string
	 */
	public function stripInvalidXml($value) {
		$ret = "";
		$current;
		if (empty($value)) 
		{
			return $ret;
		}
	
		$value = str_replace('&reg;', '&#174;', $value);
		$value = str_replace('&amp;', '&#38;', $value);
		$value = str_replace('&dagger;', '&#8224;', $value);
		$value = str_replace('&Dagger;', '&#8225;', $value);

		$length = strlen($value);
		for ($i=0; $i < $length; $i++)
		{
			$current = ord($value{$i});
			if (($current == 0x9) ||
				($current == 0xA) ||
				($current == 0xD) ||
				(($current >= 0x20) && ($current <= 0xD7FF)) ||
				(($current >= 0xE000) && ($current <= 0xFFFD)) ||
				(($current >= 0x10000) && ($current <= 0x10FFFF)))
			{
				$ret .= chr($current);
			}
			else
			{
				$ret .= " ";
			}
		}
		return $ret;
	}
}