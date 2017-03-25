<?php

class CarDealersRss
{
    public function __construct()
    {
		// construct as needed
    }

    public function build($page = 0, $limit = 0)
    {
		
		$merchants = $this->get_dealers();

		$xml = new \SimpleXMLElement('<rss></rss>');
		$xml->addAttribute('version', '2.0');
		$channel = $xml->addChild('channel');

		foreach ($merchants as $merchant) {
			if(!$merchant)
				return false;

			$item = $channel->addChild('item');
			$item->addChild('id', $merchant->id);

			$display = htmlentities($merchant->display);
			$display = $this->stripInvalidXml($display);

			$item->addChild('dealer', $display);

			$franchise_id = $this->get_franchise_id($merchant->id);
			$dealer_id = $this->get_dealer_id_from_franchise_id($franchise_id);
			$item->addChild('dealer_id', $dealer_id);

			$about = $merchant->about;
			$about = htmlentities($about);
			$about = $this->stripInvalidXml($about);
			$item->addChild('about', $about);

			$catchphrase = $merchant->catchphrase;
			$item->addChild('catchphrase', $this->stripInvalidXml($catchphrase));
			
			$item->addChild('facebook', $merchant->facebook);
			$item->addChild('twitter', $merchant->twitter);
			
			//$item->addChild('website', $merchant->website);
			$website = $this->get_location_website($merchant->id);
			$item->addChild('website', $website);

			$phone = '';
			$address = '';
			$city = '';
			$state = '';
			$zip = '';

			$locations = $this->get_location($merchant->id);
			foreach ($locations as $location) {
				$phone = $location->phone;
				$address = $location->address;
				$city = $location->city;
				$state = $location->state;
				$zip = $location->zip;
			}

			$item->addChild('address', $this->stripInvalidXml($address));
			$item->addChild('city', $this->stripInvalidXml($city));
			$item->addChild('state', $this->stripInvalidXml($state));
			$item->addChild('zip', $this->stripInvalidXml($zip));

			$hours = $merchant->hours;
			$item->addChild('hours', $this->stripInvalidXml($hours));
			$item->addChild('phone', $phone);
			$item->addChild('email', $merchant->primary_contact);
			$item->addChild('category_id', $merchant->category_id);
			$item->addChild('is_active', $merchant->is_active);
			
			$new_disclaimer = $merchant->new_disclaimer;
			$item->addChild('new_disclaimer', $this->stripInvalidXml($new_disclaimer));
			
			$used_disclaimer = $merchant->used_disclaimer;
			$item->addChild('used_disclaimer', $this->stripInvalidXml($used_disclaimer));

			$assets = $item->addChild('assets');

			$dealer_assets = $this->get_dealer_assets($merchant->id);
			foreach ($dealer_assets as $asset_item) {
				$dealer_item = $assets->addChild('dealer_item');
			
				$dealer_item->addChild('id', $asset_item->id);
				$dealer_item->addChild('name', $asset_item->name);
				$dealer_item->addChild('path', $asset_item->path);

				$long_description = $asset_item->long_description;
				$dealer_item->addChild('long_description', $this->stripInvalidXml($long_description));

				$short_description = $asset_item->short_description;
				$dealer_item->addChild('short_description', $this->stripInvalidXml($short_description));

				$dealer_item->addChild('original_path', $asset_item->original_path);
				$dealer_item->addChild('type', $asset_item->type);
				$dealer_item->addChild('assetable_type', $asset_item->assetable_type);
			}
			
			// need correct type for car dealers to enable type assets
			$type = 120;
			$type_assets = $this->get_type_assets($type);
			
			$assets = $item->addChild('type_assets');
			foreach ($type_assets as $asset_item) {
				$type_item = $assets->addChild('type_item');
			
				$type_item->addChild('id', $asset_item->id);
				$type_item->addChild('name', $asset_item->name);
				$type_item->addChild('path', $asset_item->path);

				$long_description = $asset_item->long_description;
				$type_item->addChild('long_description', $this->stripInvalidXml($long_description));

				$short_description = $asset_item->short_description;
				$type_item->addChild('short_description', $this->stripInvalidXml($short_description));

				$type_item->addChild('original_path', $asset_item->original_path);
				$type_item->addChild('type', $asset_item->type);
				$type_item->addChild('assetable_type', $asset_item->assetable_type);
			}

		}
		return $xml;
	}

	public function get_dealers() {
		// get all merchants that have a title and match the category type
		// car dealers - $type = 120
		$type = 120;
		$merchants = DB::table('merchants')
			->where('subcategory_id', '=', $type)
			->where('page_title', '!=', "")
			->get();
	
		return $merchants;
	}
	
	public function get_location($merchant_id) {
		$locations = DB::table('locations')
			->where('merchant_id', '=', $merchant_id)
			->get();
	
		return $locations;
	}
	
	public function get_location_website($merchant_id) {
		$locations = DB::table('locations')
			->where('merchant_id', '=', $merchant_id)
			->get();
	
		$website = '';

		foreach ($locations as $location) {
			$website = $location->website;
		}

	
		return $website;
	}
	
	public function get_dealer_assets($dealer_id) {
		$assets = DB::table('assets')
			->where('assetable_id', '=', $dealer_id)
			->get();
	
		return $assets;	
	}
	
	public function get_type_assets($type) {
		$assets = DB::table('assets')
			->where('category_id', '=', $type)
			->get();
	
		return $assets;	
	}
	
	public function get_franchise_id($merchant_id) {
		$dealers = DB::table('franchises')
			->where('merchant_id', '=', $merchant_id)
			->get();
		
		$dealer_id = '';

		foreach ($dealers as $dealer) {
			$dealer_id = $dealer->id;
		}

		return $dealer_id;	
	}

	public function get_dealer_id_from_franchise_id($franchise_id) {
		$dealers = DB::table('dealer_relations')
			->where('franchise_id', '=', $franchise_id)
			->get();

		$dealer_id = '';

		foreach ($dealers as $dealer) {
			$dealer_id = $dealer->dealer_id;
		}

		return $dealer_id;	
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

		$value = htmlentities($value);
		$value = str_replace('&reg;', '&#174;', $value);
		$value = str_replace('&amp;', '&#38;', $value);
		$value = str_replace('&dagger;', '&#8224;', $value);
		$value = str_replace('&Dagger;', '&#8225;', $value);
		$value = str_replace('&trade;', '&#8482;', $value);
			
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