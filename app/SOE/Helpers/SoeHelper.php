<?php

class SoeHelper
{

    /**
     * Return a slug for a given string.
     * @static
     *
     * @param string $string The string to be converted to a slug.
     * @return string The slug version of the string.
     */
    static function getSlug($string)
    {
        return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
    }

    /**
     * Replace dashes in a slug with spaces.
     * @param string $string
     * @return string
     */
    static function unSlug($string)
    {
        return str_replace('-', ' ', $string);
    }

    static function unSlugCategory($string)
    {
        $category = \SOE\DB\Category::where('slug', $string)
            ->remember(\Config::get('soe.cache', 60*24))
            ->first();

        /*$aCats = array(
            "home-improvement-old" => "Home Improvement - Old",
            "around-the-house" => "Around the House",
            "food-dining" => "Food & Dining",
            "travel-entertainment" => "Travel & Fun",
            "accounting-tax-preparation" => "Accounting & Tax Preparation",
            "banks-credit-unions" => "Banks & Credit Unions",
            "retail-fashion" => "Retail & Fashion",
            "special-services" => "Special Services",
            "auto-transportation" => "Auto & Transportation",
            "health-beauty" => "Health & Beauty",
            "american" => "American",
            "asian" => "Asian",
            "bagels-donuts-snacks" => "Bagels, Donuts & Snacks",
            "bakery" => "Bakery",
            "bar-grill" => "Bar & Grill",
            "barbecue" => "Barbecue",
            "bistros-cafes" => "Bistros & Cafes",
            "breakfast" => "Breakfast",
            "buffet-smorgasbord" => "Buffet & Smorgasbord",
            "casual-dining" => "Casual Dining",
            "catering-banquet-halls" => "Catering & Banquet Halls",
            "chicken-ribs" => "Chicken & Ribs",
            "chinese" => "Chinese",
            "coffee-shops" => "Coffee Shops",
            "coneys-hot-dogs" => "Coneys & Hot Dogs",
            "deli-sandwich-shop" => "Deli & Sandwich Shop",
            "family-dining" => "Family Dining",
            "fast-food" => "Fast Food",
            "fine-dining" => "Fine Dining",
            "greek-mediterranean" => "Greek & Mediterranean",
            "healthy-eating" => "Healthy Eating",
            "ice-cream-yogurt-candy" => "Ice Cream, Yogurt & Candy",
            "indian" => "Indian",
            "italian" => "Italian",
            "japanese" => "Japanese",
            "lebanese-arabic" => "Lebanese & Arabic",
            "meal-preparation-delivery" => "Meal Preparation & Delivery",
            "mexican" => "Mexican",
            "pizza" => "Pizza",
            "seafood" => "Seafood",
            "southern" => "Southern",
            "sports-bar" => "Sports Bar",
            "steakhouse" => "Steakhouse",
            "tex-mex" => "Tex-Mex",
            "thai" => "Thai",
            "air-conditioning-heating" => "Air Conditioning & Heating",
            "awnings" => "Awnings",
            "basement-remodeling" => "Basement Remodeling",
            "brick-pavers-patios" => "Brick Pavers & Patios",
            "closet-remodeling" => "Closet Remodeling",
            "concrete-paving-asphalt" => "Concrete, Paving & Asphalt",
            "decks-gazebos-fences" => "Decks, Gazebos & Fences",
            "door-replacement" => "Door Replacement",
            "flooring-carpet" => "Flooring & Carpet",
            "foundation-repair" => "Foundation Repair",
            "garages-garage-doors" => "Garages & Garage Doors",
            "gutter-guards" => "Gutter Guards",
            "home-alarm-systems" => "Home Alarm Systems",
            "home-builders-contractors" => "Home Builders & Contractors",
            "home-remodeling" => "Home Remodeling",
            "kitchen-bath-remodeling" => "Kitchen & Bath Remodeling",
            "marble-granite" => "Marble & Granite",
            "patio-enclosures" => "Patio Enclosures",
            "roofing-insulation" => "Roofing & Insulation",
            "siding-gutters" => "Siding & Gutters",
            "storage-sheds" => "Storage & Sheds",
            "swimming-pools-spas" => "Swimming Pools & Spas",
            "waterproofing" => "Waterproofing",
            "window-replacement" => "Window Replacement",
            "air-duct-cleaning" => "Air Duct Cleaning",
            "appliance-service-sales" => "Appliance Service & Sales",
            "blinds-drapes" => "Blinds & Drapes",
            "carpet-cleaning" => "Carpet Cleaning",
            "electricians" => "Electricians",
            "fireplace-cleaning-repair" => "Fireplace Cleaning & Repair",
            "garage-floors-coatings" => "Garage Floors & Coatings",
            "glass-mirror" => "Glass & Mirror",
            "glass-block" => "Glass Block",
            "handyman-service" => "Handyman Service",
            "interior-design" => "Interior Design",
            "lawn-landscaping" => "Lawn & Landscaping",
            "maid-services-cleaning" => "Maid Services & Cleaning",
            "outdoor-furniture" => "Outdoor Furniture",
            "painting-wallpaper" => "Painting & Wallpaper",
            "pest-control" => "Pest Control",
            "plumbing-contractors" => "Plumbing Contractors",
            "pressure-washing" => "Pressure Washing",
            "satellite-dishes-cable-tv" => "Satellite Dishes & Cable TV",
            "snow-removal" => "Snow Removal",
            "sprinklers" => "Sprinklers",
            "tile-grout-services" => "Tile & Grout Services",
            "trash-removal" => "Trash Removal",
            "tree-trimming-service" => "Tree Trimming & Service",
            "upholstery" => "Upholstery",
            "vacuum-sales-service" => "Vacuum Sales & Service",
            "water-softening-supplies" => "Water Softening Supplies",
            "window-cleaning" => "Window Cleaning",
            "window-tinting" => "Window Tinting",
            "barber-shops" => "Barber Shops",
            "chiropractors" => "Chiropractors",
            "cosmetic-surgery" => "Cosmetic Surgery",
            "dentists" => "Dentists",
            "dermatology" => "Dermatology",
            "geriatric-services" => "Geriatric Services",
            "hair-replacement" => "Hair Replacement",
            "hair-salons" => "Hair Salons",
            "health-clubs-fitness-gyms" => "Health Clubs, Fitness & Gyms",
            "hearing-aids" => "Hearing Aids",
            "hospital-medical-clinics" => "Hospital & Medical Clinics",
            "medical-alerts" => "Medical Alerts",
            "nail-salons" => "Nail Salons",
            "optometrist-lasik" => "Optometrist & Lasik",
            "pharmacy" => "Pharmacy",
            "podiatrists" => "Podiatrists",
            "spas-massage" => "Spas & Massage",
            "tanning-salons" => "Tanning Salons",
            "weight-loss-centers" => "Weight Loss Centers",
            "auto-accessories" => "Auto Accessories",
            "auto-dealers" => "Auto Dealers",
            "auto-glass" => "Auto Glass",
            "auto-repair" => "Auto Repair",
            "car-rental" => "Car Rental",
            "car-washing-detailing" => "Car Washing & Detailing",
            "limousines-taxis" => "Limousines & Taxis",
            "motorcycles-jet-skis-atvs" => "Motorcycles, Jet Skis & ATVs",
            "mufflers-brakes" => "Mufflers & Brakes",
            "oil-change-lube" => "Oil Change & Lube",
            "parking-garages-service" => "Parking Garages & Service",
            "rv-boat" => "RV & Boat",
            "tires" => "Tires",
            "transmissions" => "Transmissions",
            "amusement-parks" => "Amusement Parks",
            "bowling" => "Bowling",
            "casinos" => "Casinos",
            "dance-studios" => "Dance Studios",
            "events-festivals" => "Events & Festivals",
            "go-karts-fun-centers" => "Family Fun Centers",
            "golf-courses" => "Golf Courses",
            "gymnastics" => "Gymnastics",
            "hotels-motels-resorts" => "Hotels, Motels & Resorts",
            "karate-martial-arts" => "Karate & Martial Arts",
            "museums" => "Museums",
            "orchards-farms" => "Orchards & Farms",
            "recreation" => "Recreation",
            "ski-sledding" => "Ski & Sledding",
            "sport-teams" => "Sport Teams",
            "sporting-events" => "Sporting Events",
            "theaters" => "Theaters",
            "ticket-brokers" => "Ticket Brokers",
            "travel-agents" => "Travel Agents",
            "water-parks" => "Water Parks",
            "zoos-animal-facilities" => "Zoos & Animal Facilities",
            "antiques-resale-shops" => "Antiques & Resale Shops",
            "beds-mattresses" => "Beds & Mattresses",
            "bicycle-fitness" => "Bicycle & Fitness",
            "book-stores-dealers" => "Book Stores & Dealers",
            "boutiques-specialty-shops" => "Boutiques & Specialty Shops",
            "bridal-salons" => "Bridal Salons",
            "camera-video-sales" => "Camera & Video Sales",
            "card-shops-gifts-crafts" => "Card Shops, Gifts & Crafts",
            "cell-phones-pagers" => "Cell Phones & Pagers",
            "clothing-shoes" => "Clothing & Shoes",
            "consignment-ebay" => "Consignment (ebay)",
            "cosmetics" => "Cosmetics",
            "discount-convenient-stores" => "Discount & Convenient Stores",
            "florists-nursery-s" => "Florists & Nursery's",
            "frame-shop-art-galleries" => "Frame Shop & Art Galleries",
            "furniture" => "Furniture",
            "grocery-beverages" => "Grocery & Beverages",
            "hardware-tools-lumber" => "Hardware, Tools & Lumber",
            "health-food" => "Health Food",
            "home-demonstrations" => "Home Demonstrations",
            "home-theater-audio-video" => "Home Theater, Audio & Video",
            "jewelers-watches-clocks" => "Jewelers, Watches & Clocks",
            "lighting" => "Lighting",
            "malls-shopping-ctr" => "Malls & Shopping Ctr",
            "movie-game-rental-sales" => "Movie/Game Rental & Sales",
            "office-equipment-supplies" => "Office Equipment & Supplies",
            "pawnshops-flea-markets" => "Pawnshops & Flea Markets",
            "sporting-goods" => "Sporting Goods",
            "tobacco-cigars" => "Tobacco & Cigars",
            "toys-hobbies" => "Toys & Hobbies",
            "tuxedo-rentals" => "Tuxedo Rentals",
            "checks-cashing" => "Checks Cashing",
            "credit-card-companies" => "Credit Card Companies",
            "debt-consolidation" => "Debt Consolidation",
            "money-wire" => "Money Wire",
            "mortgages" => "Mortgages",
            "real-estate" => "Real Estate",
            "attorneys-legal-services" => "Attorneys & Legal Services",
            "charitable-organizations" => "Charitable Organizations",
            "child-care-camps" => "Child Care & Camps",
            "churches" => "Churches",
            "computer-repair" => "Computer Repair",
            "counseling-therapy" => "Counseling & Therapy",
            "cleaners-tailors-laundry" => "Cleaners, Tailors & Laundry",
            "education-schools" => "Education & Schools",
            "equipment-tool-rental" => "Equipment & Tool Rental",
            "funeral-cemetery" => "Funeral & Cemetery",
            "gift-baskets" => "Gift Baskets",
            "hypnotists-psychics" => "Hypnotists & Psychics",
            "ink-toner-cartridge-service" => "Ink, Toner & Cartridge Service",
            "insurance" => "Insurance",
            "locksmiths" => "Locksmiths",
            "marinas-boating" => "Marinas & Boating",
            "movers-storage" => "Movers & Storage",
            "party-rental-supplies" => "Party Rental Supplies",
            "pet-grooming" => "Pet Grooming",
            "pet-services" => "Pet Services",
            "pet-supplies" => "Pet Supplies",
            "pet-veterinarian" => "Pet Veterinarian",
            "photo-finishing" => "Photo Finishing",
            "photography" => "Photography",
            "postal-shipping-services" => "Postal & Shipping Services",
            "printers-copy-service" => "Printers & Copy Service",
            "retirement-home" => "Retirement Home",
            "shoe-repair" => "Shoe Repair",
            "tv-audio-video-repair" => "TV, Audio & Video Repair",
            "utilities" => "Utilities",
            "video-transferring-duplicating" => "Video Transferring & Duplicating",
            "dating-relationships" => "Dating & Relationships",
            "irish-pubs" => "Irish Pubs",
            "energy-audits" => "Energy Audits",
            "rug-cleaning" => "Rug Cleaning",
            "national-brands" => "National Brands",
            "real-estate-finance" => "Real Estate & Finance",
            "home-improvement" => "Home Improvement",
            "financing" => "Financing"
        );*/

        return $category ? $category->name : $string;
    }

    static function hasLocations($user)
    {
        $locations = \App::make('UserLocationRepositoryInterface');
        return $locations->query()
            ->where('user_id', $user->id)
            ->where('is_deleted', 0)
            ->count();
    }

    /**
     * Replace city / state placeholders in a given string.
     * @static
     *
     * @param string
     * @param object $geoip Geoip object to use in the replacements
     * @return string
     */
    static function cityStateReplace($string, $geoip)
    {
        $string = str_replace('{city}', ucwords(strtolower($geoip->city_name)), $string);
        $string = str_replace('{state}', strtoupper($geoip->region_name), $string);
        $string = str_replace('{city_slug}', SoeHelper::getSlug($geoip->city_name), $string);
        $string = str_replace('{city_lower}', strtolower($geoip->city_name), $string);
        $string = str_replace('{state_lower}', strtolower($geoip->region_name), $string);

        return $string;
    }

    static function merchantReplacements($string, $location, $merchant, $category = null, $subcategory = null)
    {
        $aProperties = array('address' => '\[address\]', 'city' => '\[city\]', 'state' => '\[state\]', 'phone' => '\[phone\]', 'website' => '\[website\]');
        if(preg_match_all('/\{[^\{]+\}/', $string, $matches))
        {
            foreach($matches[0] as $match)
            {
                $terms = preg_replace('/[{}]/', '', $match);
                $aTerms = explode('|', $terms);
                $string = str_replace($match, $aTerms[rand(0,count($aTerms)-1)], $string);
            }
        }

        foreach($aProperties as $property => $regex) 
        {
            $string = preg_replace('/'.$regex.'/', $location->$property, $string);
        }
        $cat = $category ? $category->name : '';
        $subcat = $subcategory ? $subcategory->name : '';
        $catSlug = $category ? $category->slug : '';
        $subcatSlug = $subcategory ? $subcategory->slug : '';
        $string = preg_replace('/\[category\]/', $cat, $string);
        $string = preg_replace('/\[subcategory\]/', $subcat, $string);
        $string = preg_replace('/\[category_slug\]/', $catSlug, $string);
        $string = preg_replace('/\[subcategory_slug\]/', $subcatSlug, $string);
        $string = str_replace('[merchant]', $merchant->display, $string);
        $string = str_replace('[city_slug]', SoeHelper::getSlug($location->city), $string);
        $string = str_replace('[city_lower]', strtolower($location->city), $string);
        $string = str_replace('[state_lower]', strtolower($location->state), $string);
        return $string;
    }

    /**
     * Decompress a .gz file.
     *
     * @param string $gz Path to .gz file
     * @return mixed Path of decompressed file or false on failure.
     */
    static function gunzip($gz)
    {
        try
        {
            $decompressed = sys_get_temp_dir().'/'.basename($gz, '.gz');

            $handle = fopen($decompressed, "w") ;
            
            $zp = gzopen($gz, "r");

            if ($zp)
            {
                while (!gzeof($zp))
                {
                    $buff = gzgets($zp, 4096);
                    fputs($handle, $buff);
                }               
            }           
            gzclose($zp);
            fclose($handle);

            return $decompressed;
        }
        catch(\Exception $e)
        {
            return false;
        }
    }

    /**
     * Replaces underscores in a slug with hyphens.
     * @static
     *
     * @param string $string The slug to be fixed
     * @return string The fixed slug.
     */
    static function fixSlug($string)
    {
        return str_replace('_', '-', $string);
    }

    /**
     * Replaces hyphens in a string with underscores.
     * @static
     *
     * @param string $string
     * @return string
     */
    static function hyphenToUnderscore($string)
    {
        return str_replace('-', '_', $string);
    }

    /**
     * Return a slug for a given string.
     * @static
     *
     * @param string $string The string to be converted to a slug.
     * @return string The slug version of the string.
     */
    static function getCartesian($lat, $lng)
    {
        $latm = ($lat*111133);
        $lngm = (111133*cos(deg2rad($lat))*$lng);
        return array('latm' => $latm, 'lngm' => $lngm);
    }

    /**
     * Return an array of state abbreviations and names by country.
     *
     * @return array
     */
    static function states()
    {
        return array(
            'USA' => array('name' => 'United States',
                        'states' => array(
                                        'AL'=>'ALABAMA',
                                        'AK'=>'ALASKA',
                                        'AZ'=>'ARIZONA',
                                        'AR'=>'ARKANSAS',
                                        'CA'=>'CALIFORNIA',
                                        'CO'=>'COLORADO',
                                        'CT'=>'CONNECTICUT',
                                        'DE'=>'DELAWARE',
                                        'DC'=>'DISTRICT OF COLUMBIA',
                                        'FL'=>'FLORIDA',
                                        'GA'=>'GEORGIA',
                                        'HI'=>'HAWAII',
                                        'ID'=>'IDAHO',
                                        'IL'=>'ILLINOIS',
                                        'IN'=>'INDIANA',
                                        'IA'=>'IOWA',
                                        'KS'=>'KANSAS',
                                        'KY'=>'KENTUCKY',
                                        'LA'=>'LOUISIANA',
                                        'ME'=>'MAINE',
                                        'MD'=>'MARYLAND',
                                        'MA'=>'MASSACHUSETTS',
                                        'MI'=>'MICHIGAN',
                                        'MN'=>'MINNESOTA',
                                        'MS'=>'MISSISSIPPI',
                                        'MO'=>'MISSOURI',
                                        'MT'=>'MONTANA',
                                        'NE'=>'NEBRASKA',
                                        'NV'=>'NEVADA',
                                        'NH'=>'NEW HAMPSHIRE',
                                        'NJ'=>'NEW JERSEY',
                                        'NM'=>'NEW MEXICO',
                                        'NY'=>'NEW YORK',
                                        'NC'=>'NORTH CAROLINA',
                                        'ND'=>'NORTH DAKOTA',
                                        'OH'=>'OHIO',
                                        'OK'=>'OKLAHOMA',
                                        'OR'=>'OREGON',
                                        'PA'=>'PENNSYLVANIA',
                                        'PR'=>'PUERTO RICO',
                                        'RI'=>'RHODE ISLAND',
                                        'SC'=>'SOUTH CAROLINA',
                                        'SD'=>'SOUTH DAKOTA',
                                        'TN'=>'TENNESSEE',
                                        'TX'=>'TEXAS',
                                        'UT'=>'UTAH',
                                        'VT'=>'VERMONT',
                                        'VA'=>'VIRGINIA',
                                        'WA'=>'WASHINGTON',
                                        'WV'=>'WEST VIRGINIA',
                                        'WI'=>'WISCONSIN',
                                        'WY'=>'WYOMING'
                                    )
                        ),
            'CAN' => array('name' => 'Canada',
                        'states' => array(
                                        "BC"=>"British Columbia", 
                                        "ON"=>"Ontario", 
                                        "NL"=>"Newfoundland and Labrador", 
                                        "NS"=>"Nova Scotia", 
                                        "PE"=>"Prince Edward Island", 
                                        "NB"=>"New Brunswick", 
                                        "QC"=>"Quebec", 
                                        "MB"=>"Manitoba", 
                                        "SK"=>"Saskatchewan", 
                                        "AB"=>"Alberta", 
                                        "NT"=>"Northwest Territories", 
                                        "NU"=>"Nunavut",
                                        "YT"=>"Yukon Territory"
                                    )
                        )
        );
    }

    /**
     * Return a static array of categories.
     *
     * @return array
     */
    static function categories()
    {
        $category = \SOE\DB\Category::where('parent_id', '0')
            ->orderBy('category_order')
            ->get();
        $return = array();
        foreach($categories as $cat)
        {
            $return[$cat->id] = $cat->slug;
        }
        return $return;
    }

    /**
     * Remove non-alphanumeric characters from a string.
     *
     * @param string $string
     * @return string
     */
    static function removePunctuation($string)
    {
        return preg_replace("/[^A-Za-z0-9 ]/", '', $string);
    }

    /**
     * Determine if the current user is a bot.
     *
     * @return boolean
     */
    static function isBot()
    {
        if(empty($_SERVER['HTTP_USER_AGENT']) || (!stristr($_SERVER['HTTP_USER_AGENT'], 'bot') && !stristr($_SERVER['HTTP_USER_AGENT'], 'spider')))
            return false;
        else
            return true;
    }

    /**
     * Determine if the current user is an employee.
     *
     * @return boolean
     */
    static function isEmployee()
    {
        if(!Auth::check())
            return false;
        $user = Auth::User();
        if(stristr($user->type, 'Employee'))
            return true;
        return false;
    }

    /**
     * Determine if the current user is trackable.
     *
     * @return boolean
     */
    static function isTrackable()
    {
        if(!Auth::check())
            return true;
        $user = Auth::User();
        if(stristr($user->type, 'Employee') || stristr($user->type, 'Demo'))
            return false;
        return true;
    }

    /**
     * Truncates a pieces of text to a given length while preserving html tags.
     *
     * @param string    $text The text to be truncated.
     * @param int       $length The length of the trucated string, default 200.
     * @return string $text
     */
    static function truncate($text, $length = 200)
    {
        $ending = '...';
        if(mb_strlen(preg_replace('/<.*?>/', '', $text)) <= $length)
        {
            return $text;
        }
        $totalLength = mb_strlen(strip_tags($ending));
        $openTags = array();
        $truncate = '';

        preg_match_all('/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $text, $tags, PREG_SET_ORDER);
        foreach($tags as $tag)
        {
            if(!preg_match('/img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param/s', $tag[2]))
            {
                if(preg_match('/<[\w]+[^>]*>/s', $tag[0]))
                {
                    array_unshift($openTags, $tag[2]);
                }
                else if (preg_match('/<\/([\w]+)[^>]*>/s', $tag[0], $closeTag))
                {
                    $pos = array_search($closeTag[1], $openTags);
                    if($pos !== false)
                    {
                        array_splice($openTags, $pos, 1);
                    }
                }
            }
            $truncate .= $tag[1];

            $contentLength = mb_strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $tag[3]));
            if($contentLength + $totalLength > $length)
            {
                $left = $length - $totalLength;
                $entitiesLength = 0;
                if(preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $tag[3], $entities, PREG_OFFSET_CAPTURE))
                {
                    foreach($entities[0] as $entity)
                    {
                        if($entity[1] + 1 - $entitiesLength <= $left)
                        {
                            $left--;
                            $entitiesLength += mb_strlen($entity[0]);
                        }
                        else
                        {
                            break;
                        }
                    }
                }

                $truncate .= mb_substr($tag[3], 0 , $left + $entitiesLength);
                break;
            }
            else
            {
                $truncate .= $tag[3];
                $totalLength += $contentLength;
            }
            if($totalLength >= $length)
            {
                break;
            }
        }

        $truncate .= $ending;

        foreach($openTags as $tag)
        {
            $truncate .= '</'.$tag.'>';
        }
        return $truncate;
    }

    /**
     * Get the current user's IP
     *
     * @return string
     */
    static function getIp()
    {
        $ip = $_SERVER['REMOTE_ADDR'];

        if (!empty($_SERVER['HTTP_CLIENT_IP']))
        {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        return $ip;
    }

    static function csvToText($csvFile, $filename)
    {
        $txtFile = public_path().'/textfile';
        $maxPage = 0;
        $maxNameLength = 0;
        $rows = array();
        $files = array();
        $coverItemCount = 0;
        if(($handle = fopen($csvFile, "r")) !== false)
        {
            //Skip label row of CSV
            $data = fgetcsv($handle, null, ",");
            // Count page Items
            while(($data = fgetcsv($handle, null, ",")) !== FALSE)
            {
                if(!isset(${$data[0].'-'.$data[1].'-Count'}))
                {
                    ${$data[0].'-'.$data[1].'-Count'} = 1;

                } else {
                    ${$data[0].'-'.$data[1].'-Count'}++;
                }
            }
        }
        fclose($handle);
        if(($handle = fopen($csvFile, "r")) !== false)
        {
            //Skip label row of CSV
            $data = fgetcsv($handle, null, ",");
            // Loop through the rest of the rows of the CSV
            while(($data = fgetcsv($handle, null, ",")) !== FALSE)
            {
                if($data[0] == '' || $data[1] == '')
                    continue;
                // Get the Zone or Territory
                $zone = explode('-', $data[0]);
                $zone = explode(' ', $zone[1]);

                //Contains a T ? It's a territory, else Zone - Outdated
                //$zone = strstr($zone[0], 'T') ? trim($zone[0]) : trim($zone[1]);

                //Turn it back to a string
                $zone = $zone[0];

                $altPieces = explode('-', $data[3]);
                // Get the 4 digit ad code (unformated)
                $fourDigRaw = count($altPieces) > 1 ? $altPieces[1] : 0;
                // Parse the filename (MMYYST_XXXXXX.CSV)
                $filePieces = explode('_', $filename);
                $month = substr($filePieces[0], 0, 2);
                $year = substr($filePieces[0], 2, 2);
                $state = substr($filePieces[0], -2);

                // Each zone or territory should be its own file, so we create an array of files with the zone/territory as the key
                if(isset($files[$zone]))
                {
                    // We have already started building the file array for this zone/territory, so push to that array
                    // Check if this row's page number is greater than the max page number, if it is then make it the max page number
                    $files[$zone]['maxPage'] = $data[1] > $files[$zone]['maxPage'] ? $data[1] : $files[$zone]['maxPage'];
                    // Check if this row's Company Name is longer than the max name length, if it is then make it the max name length
                    $files[$zone]['maxNameLength'] = strlen($data[4]) > $files[$zone]['maxNameLength'] ? strlen($data[4]) : $files[$zone]['maxNameLength'];
                    // Format the page number to be 2 digits
                    $page = str_pad($data[1], 2, '0', STR_PAD_LEFT);
                    // Format the 4 digit ad code to actually be 4 digits
                    $fourDig = str_pad($fourDigRaw, 4, '0', STR_PAD_LEFT);
                    // Format the ad size
                    $size = $data[5] == 2 ? '2.00' : ($data[5] == 1 ? '1.00' : ($data[5] == 0 ? '0.00' : str_pad($data[5], 4, '0')));
                    // Parse and format the 2 digit ad code
                    $twoDig = explode('-', $data[6]);
                    $twoDig = count($twoDig) ? trim($twoDig[0]) : 'XX';

                    /*// Determine the position ('', A, B, C, D)
                    if($size == '2.00' || $size == '1.00')
                    {
                        // Full page or 2 page ads always have blank position
                        $posi = '';
                    }
                    else
                    {
                        // Certain 2 digit ad codes always convertion to a specific position
                        switch ($twoDig) {
                            case '55':
                            case '055':
                            case '41':
                            case '041':
                                $posi = 'B';
                                break;
                            case '13':
                            case '013':
                            case '42':
                            case '042':
                                $posi = 'C';
                                break;
                            case '14':
                            case '014':
                            case '44':
                            case '044':
                                $posi = 'A';
                                break;
                            case '53':
                            case '053':
                                $posi = '';
                                break;
                            case '43':
                            case '043':
                                $posi = 'D';
                                break;
                            default:
                                // If we can't use the ad code to determine position, then use the X and Y coordinates.
                                if($data[7] < 10 && $data[8] < 10)
                                    $posi = 'A';
                                else if($data[7] > 10 && $data[8] < 10)
                                    $posi = 'B';
                                else if($data[7] < 10 && $data[8] > 10)
                                    $posi = 'C';
                                else if ($data[7] > 10 && $data[8] > 10)
                                    $posi = 'D';
                                else
                                    $posi = '';
                                break;
                        }
                    }*/

                    // If 1st page, position top to bottom, otherwise use XY coordinates
                    if(($data[1] == 1) && ($twoDig == '017' || $twoDig == '040' || $twoDig == '041' 
                        || $twoDig == '042' || $twoDig == '043' || $twoDig == '044' 
                        || $twoDig == '014' || $twoDig == '055' || $twoDig == '057' 
                        || $twoDig == '058' || $twoDig == '020' || $twoDig == '099'
                        || $twoDig == '095' || $twoDig == '098' || $twoDig == '019'))
                    {
                        // SOCT Cover Header
                        if ($twoDig == '099') {
                            $posi = 'A';
                        // SOCT Front Cover 
                        } elseif ($twoDig == '095') {
                            $posi = 'A';
                        // SOCT 1/2 Page Cover
                        } elseif ($twoDig == '098') {
                            if($data[7] == 0 && $data[8] == 0) {
                                $posi = 'B';
                            } else {
                                $posi = 'C';
                            }
                        // Check if cover is Detroit market
                        } elseif (strpos($data[0],'DT SOE') !== false) {
                            if($data[7] > 10) // If X axis is more than 10
                                $posi = 'A';
                            elseif($data[8] < 10) // If Y axis is less than 10
                                $posi = 'B';
                            elseif(($data[8] > 10) && ($data[8] < 400))
                                $posi = 'C';
                            elseif($data[8] > 400)
                                $posi = 'D';
                        } else {
                            if($data[8] < 10)
                                $posi = 'A';
                            elseif(($data[8] > 10) && ($data[8] < 300))
                                $posi = 'B';
                            elseif(($data[8] > 300) && ($data[8] < 610))
                                $posi = 'C';
                            elseif($data[8] > 610) // Assign footer
                            {
                                // Check the amount of ads on the first page
                                if(${$data[0].'-'.$data[1].'-Count'} == 3)
                                    $posi = 'C';
                                elseif(${$data[0].'-'.$data[1].'-Count'} == 4)
                                    $posi = 'D';
                            }
                            //echo ${$data[0].'-'.$data[1].'-Count'}.' '.$data[7].' '.$data[8].' '.$posi."\n";
                        }
                    } else {
                        // 1/2 Page Horizontal Ads
                        if ($twoDig == '054') {
                            if ($data[7] < 10 && $data[8] < 10) {
                                $posi = 'A';
                            } elseif ($data[7] < 10 && $data[8] > 10) {
                                $posi = 'C';
                            }
                        // 1/2 Page Vertical Ads
                        } elseif ($twoDig == '050') {
                            if ($data[7] < 10) {
                                $posi = 'A';
                            } else {
                                $posi = 'B';
                            }
                        // 1/4 Page Ads
                        } elseif ($twoDig == '052') {
                            if ($data[7] < 10 && $data[8] < 10) {
                                $posi = 'A';
                            } elseif ($data[7] > 10 && $data[8] < 10) {
                                $posi = 'B';
                            } elseif ($data[7] < 10 && $data[8] > 10) {
                                $posi = 'C';
                            } elseif ($data[7] > 10 && $data[8] > 10) {
                                $posi = 'D';
                            }
                        // 1/6 Page Ads
                        } elseif ($twoDig == '013' || $twoDig == '015') {
                            if ($data[7] < 10 && $data[8] < 10) {
                                $posi = 'A';
                            } elseif ($data[7] > 10 && $data[8] < 10) {
                                $posi = 'B';
                            } elseif ($data[7] < 10 && $data[8] > 10 && $data[8] < 275) {
                                $posi = 'C';
                            } elseif ($data[7] > 10 && $data[8] > 10 && $data[8] < 275) {
                                $posi = 'D';
                            } elseif ($data[7] < 10 && $data[8] > 275) {
                                $posi = 'E';
                            } elseif ($data[7] > 10 && $data[8] > 275) {
                                $posi = 'F';
                            }
                        // 2/3 Page Ads
                        } elseif ($twoDig == '112') {
                            $posi = 'A';
                        // 1/3 Page Ads
                        } elseif ($twoDig == '111') {
                            $posi = 'B';
                        // 2/9 Page Ads
                        } elseif ($twoDig == '119') {
                            if ($data[7] < 10) {
                                $posi = 'B';
                            } else {
                                $posi = 'C';
                            }
                        // 1/9 Page Ads
                        } elseif ($twoDig == '120') {
                            if ($data[7] < 10) {
                                $posi = 'B';
                            } elseif ($data[7] > 10 && $data[7] < 250) {
                                $posi = 'C';
                            } elseif ($data[7] > 250) {
                                $posi = 'D';
                            }
                        // 1 Page Ads
                        } elseif ($size == '1.00') {
                            $posi = 'A';
                        // Catch All
                        } else {
                            if(($data[7] < 10) && ($data[8] < 10))
                                $posi = 'A';
                            elseif(($data[7] > 10) && ($data[8] < 10))
                                $posi = 'B';
                            elseif(($data[7] < 10) && ($data[8] > 10) && ($data[8] < 425))
                                $posi = 'C';
                            elseif(($data[7] > 10) && ($data[8] > 10) && ($data[8] < 425))
                                $posi = 'D';
                            elseif($data[7] < 10 && $data[8] > 425)
                                $posi = 'E';
                            else
                                $posi = 'F';
                        }
                    }

                    // Group the ads by page number
                    if(!isset($files[$zone]['rows'][$page]))
                    {
                        // No ads for this page yet, insert the first one into the array
                        $files[$zone]['rows'][$page] = array($posi => array(
                            '4dig' => $fourDig,
                            'state' => $state,
                            'issue' => $month.'/'.$year,
                            'name' => preg_replace('!\s+!', ' ', preg_replace("/[^A-Za-z0-9 ]/", " ", $data[4])),
                            'size' => $size == '2.00' ? '1.00' : $size,
                            '2dig' => $twoDig
                        ));
                    }
                    else
                    {
                        // Already have some ads on this page, push this next one on to the array
                        $files[$zone]['rows'][$page][$posi] = array(
                            '4dig' => $fourDig,
                            'state' => $state,
                            'issue' => $month.'/'.$year,
                            'name' => preg_replace('!\s+!', ' ', preg_replace("/[^A-Za-z0-9 ]/", " ", $data[4])),
                            'size' => $size == '2.00' ? '1.00' : $size,
                            '2dig' => $twoDig
                        );
                    }
                    // If this is a 2 page ad, we need to split it into 2 pages in the array
                    if($size == '2.00')
                    {
                        // Increment page number
                        $data[1]++;
                        // Increase max page number if we need to
                        $files[$zone]['maxPage'] = $data[1] > $files[$zone]['maxPage'] ? $data[1] : $files[$zone]['maxPage'];
                        // Reformat the page number
                        $page = str_pad($data[1], 2, '0', STR_PAD_LEFT);
                        // Insert it into the array
                        if(!isset($files[$zone]['rows'][$page]))
                        {
                            $files[$zone]['rows'][$page] = array($posi => array(
                                '4dig' => $fourDig,
                                'state' => $state,
                                'issue' => $month.'/'.$year,
                                'name' => preg_replace('!\s+!', ' ', preg_replace("/[^A-Za-z0-9 ]/", " ", $data[4])),
                                'size' => $size == '2.00' ? '1.00' : $size, // Since we are splitting this ad over 2 pages, the size is now 1.00 on each page
                                '2dig' => $twoDig
                            ));
                        }
                        else
                        {
                            $files[$zone]['rows'][$page][$posi] = array(
                                '4dig' => $fourDig,
                                'state' => $state,
                                'issue' => $month.'/'.$year,
                                'name' => preg_replace('!\s+!', ' ', preg_replace("/[^A-Za-z0-9 ]/", " ", $data[4])),
                                'size' => $size == '2.00' ? '1.00' : $size, // Since we are splitting this ad over 2 pages, the size is now 1.00 on each page
                                '2dig' => $twoDig
                            );
                        }
                    }
                }
                else
                {
                    // We don't have any entries in the files array for this zone/territory yet, insert into array.
                    // Same logic as above, see above comments
                    $files[$zone] = array('maxPage' => 0, 'maxNameLength' => 0, 'rows' => array());
                    $files[$zone]['maxPage'] = $data[1] > $files[$zone]['maxPage'] ? $data[1] : $files[$zone]['maxPage'];
                    $files[$zone]['maxNameLength'] = strlen($data[4]) > $files[$zone]['maxNameLength'] ? strlen($data[4]) : $files[$zone]['maxNameLength'];
                    $page = str_pad($data[1], 2, '0', STR_PAD_LEFT);
                    $fourDig = str_pad($fourDigRaw, 4, '0', STR_PAD_LEFT);
                    $size = $data[5] == 2 ? '2.00' : ($data[5] == 1 ? '1.00' : ($data[5] == 0 ? '0.00' : str_pad($data[5], 4, '0')));
                    $twoDig = explode('-', $data[6]);
                    $twoDig = count($twoDig) ? trim($twoDig[0]) : 'XX';
                    /*if($size == '2.00' || $size == '1.00')
                        $posi = '';
                    else
                    {
                        switch ($twoDig) {
                            case '55':
                            case '055':
                            case '41':
                            case '041':
                                $posi = 'B';
                                break;
                            case '13':
                            case '013':
                            case '42':
                            case '042':
                                $posi = 'C';
                                break;
                            case '14':
                            case '014':
                            case '44':
                            case '044':
                                $posi = 'A';
                                break;
                            case '53':
                            case '053':
                                $posi = '';
                                break;
                            case '43':
                            case '043':
                                $posi = 'D';
                                break;
                            default:
                                if($data[7] == 0 && $data[8] == 0)
                                    $posi = 'A';
                                else if($data[7] > 0 && $data[8] == 0)
                                    $posi = 'B';
                                else if($data[7] == 0 && $data[8] > 0)
                                    $posi = 'C';
                                else if ($data[7] > 0 && $data[8] > 0)
                                    $posi = 'D';
                                else
                                    $posi = '';
                                break;
                        }
                    }*/

                    // If 1st page, position top to bottom, otherwise use XY coordinates
                    if(($data[1] == 1) && ($twoDig == '017' || $twoDig == '040' || $twoDig == '041' 
                        || $twoDig == '042' || $twoDig == '043' || $twoDig == '044' 
                        || $twoDig == '014' || $twoDig == '055' || $twoDig == '057' 
                        || $twoDig == '058' || $twoDig == '020' || $twoDig == '099'
                        || $twoDig == '095' || $twoDig == '098' || $twoDig == '019'))
                    {
                        // SOCT Cover Header
                        if ($twoDig == '099') {
                            $posi = 'A';
                        // SOCT Front Cover 
                        } elseif ($twoDig == '095') {
                            $posi = 'A';
                        // SOCT 1/2 Page Cover
                        } elseif ($twoDig == '098') {
                            if($data[7] == 0 && $data[8] == 0) {
                                $posi = 'B';
                            } else {
                                $posi = 'C';
                            }
                        // Check if cover is Detroit market
                        } elseif (strpos($data[0],'DT SOE') !== false) {
                            if($data[7] > 10) // If X axis is more than 10
                                $posi = 'A';
                            elseif($data[8] < 10) // If Y axis is less than 10
                                $posi = 'B';
                            elseif(($data[8] > 10) && ($data[8] < 400))
                                $posi = 'C';
                            elseif($data[8] > 400)
                                $posi = 'D';
                        } else {
                            if($data[8] < 10)
                                $posi = 'A';
                            elseif(($data[8] > 10) && ($data[8] < 300))
                                $posi = 'B';
                            elseif(($data[8] > 300) && ($data[8] < 650))
                                $posi = 'C';
                            elseif($data[8] > 650) // Assign footer
                            {
                                // Check the amount of ads on the first page
                                if(${$data[0].'-'.$data[1].'-Count'} == 3)
                                    $posi = 'C';
                                elseif(${$data[0].'-'.$data[1].'-Count'} == 4)
                                    $posi = 'D';
                            }
                            //echo ${$data[0].'-'.$data[1].'-Count'}.' '.$data[7].' '.$data[8].' '.$posi."\n";
                        }
                    } else {
                        // 1/2 Page Horizontal Ads
                        if ($twoDig == '054') {
                            if ($data[7] < 10 && $data[8] < 10) {
                                $posi = 'A';
                            } elseif ($data[7] < 10 && $data[8] > 10) {
                                $posi = 'C';
                            }
                        // 1/2 Page Vertical Ads
                        } elseif ($twoDig == '050') {
                            if ($data[7] < 10) {
                                $posi = 'A';
                            } else {
                                $posi = 'B';
                            }
                        // 1/4 Page Ads
                        } elseif ($twoDig == '052') {
                            if ($data[7] < 10 && $data[8] < 10) {
                                $posi = 'A';
                            } elseif ($data[7] > 10 && $data[8] < 10) {
                                $posi = 'B';
                            } elseif ($data[7] < 10 && $data[8] > 10) {
                                $posi = 'C';
                            } elseif ($data[7] > 10 && $data[8] > 10) {
                                $posi = 'D';
                            }
                        // 1/6 Page Ads
                        } elseif ($twoDig == '013' || $twoDig == '015') {
                            if ($data[7] < 10 && $data[8] < 10) {
                                $posi = 'A';
                            } elseif ($data[7] > 10 && $data[8] < 10) {
                                $posi = 'B';
                            } elseif ($data[7] < 10 && $data[8] > 10 && $data[8] < 275) {
                                $posi = 'C';
                            } elseif ($data[7] > 10 && $data[8] > 10 && $data[8] < 275) {
                                $posi = 'D';
                            } elseif ($data[7] < 10 && $data[8] > 275) {
                                $posi = 'E';
                            } elseif ($data[7] > 10 && $data[8] > 275) {
                                $posi = 'F';
                            }
                        // 2/3 Page Ads
                        } elseif ($twoDig == '112') {
                            $posi = 'A';
                        // 1/3 Page Ads
                        } elseif ($twoDig == '111') {
                            $posi = 'B';
                        // 2/9 Page Ads
                        } elseif ($twoDig == '119') {
                            if ($data[7] < 10) {
                                $posi = 'B';
                            } else {
                                $posi = 'C';
                            }
                        // 1/9 Page Ads
                        } elseif ($twoDig == '120') {
                            if ($data[7] < 10) {
                                $posi = 'B';
                            } elseif ($data[7] > 10 && $data[7] < 250) {
                                $posi = 'C';
                            } elseif ($data[7] > 250) {
                                $posi = 'D';
                            }
                        // 1 Page Ads
                        } elseif ($size == '1.00') {
                            $posi = 'A';
                        // Catch All
                        } else {
                            if(($data[7] < 10) && ($data[8] < 10))
                                $posi = 'A';
                            elseif(($data[7] > 10) && ($data[8] < 10))
                                $posi = 'B';
                            elseif(($data[7] < 10) && ($data[8] > 10) && ($data[8] < 425))
                                $posi = 'C';
                            elseif(($data[7] > 10) && ($data[8] > 10) && ($data[8] < 425))
                                $posi = 'D';
                            elseif($data[7] < 10 && $data[8] > 425)
                                $posi = 'E';
                            else
                                $posi = 'F';
                        }
                    }

                    if(!isset($files[$zone]['rows'][$page]))
                    {
                        $files[$zone]['rows'][$page] = array($posi => array(
                            '4dig' => $fourDig,
                            'state' => $state,
                            'issue' => $month.'/'.$year,
                            'name' => preg_replace('!\s+!', ' ', preg_replace("/[^A-Za-z0-9 ]/", " ", $data[4])),
                            'size' => $size == '2.00' ? '1.00' : $size,
                            '2dig' => $twoDig
                        ));
                    }
                    else
                    {
                        $files[$zone]['rows'][$page][$posi] = array(
                            '4dig' => $fourDig,
                            'state' => $state,
                            'issue' => $month.'/'.$year,
                            'name' => preg_replace('!\s+!', ' ', preg_replace("/[^A-Za-z0-9 ]/", " ", $data[4])),
                            'size' => $size == '2.00' ? '1.00' : $size,
                            '2dig' => $twoDig
                        );
                    }

                    if($size == '2.00')
                    {
                        $data[1]++;
                        $files[$zone]['maxPage'] = $data[1] > $files[$zone]['maxPage'] ? $data[1] : $files[$zone]['maxPage'];
                        $page = str_pad($data[1], 2, '0', STR_PAD_LEFT);
                        if(!isset($files[$zone]['rows'][$page]))
                        {
                            $files[$zone]['rows'][$page] = array($posi => array(
                                '4dig' => $fourDig,
                                'state' => $state,
                                'issue' => $month.'/'.$year,
                                'name' => preg_replace('!\s+!', ' ', preg_replace("/[^A-Za-z0-9 ]/", " ", $data[4])),
                                'size' => $size == '2.00' ? '1.00' : $size,
                                '2dig' => $twoDig
                            ));
                        }
                        else
                        {
                            $files[$zone]['rows'][$page][$posi] = array(
                                '4dig' => $fourDig,
                                'state' => $state,
                                'issue' => $month.'/'.$year,
                                'name' => preg_replace('!\s+!', ' ', preg_replace("/[^A-Za-z0-9 ]/", " ", $data[4])),
                                'size' => $size == '2.00' ? '1.00' : $size,
                                '2dig' => $twoDig
                            );
                        }
                    }
                }
            }
            fclose($handle);

            // Begin building output files
            $aFiles = array();
            // Create an output file for each zone/territory
            foreach($files as $zone => $zoneData)
            {
                // Name the output file according to zone/territory
                $aFiles[] = 'textfile'.$zone.'.txt';
                $file = fopen($txtFile.$zone.'.txt',"w");
                // Write out the initial lines of data
                fwrite($file, "Number of Pages =".$zoneData['maxPage']."\n");
                fwrite($file, "POSITION =01 GROUP =00 ZONE =".$zone." \n");
                fwrite($file, $zone."/F1/".$zone."/".$state);
                // Keep track of what line we are on
                $line=0;
                // Sort the data by page number
                ksort($zoneData['rows']);
                // Max name should be 30 at most
                $zoneData['maxNameLength'] = $zoneData['maxNameLength'] > 30 ? 30 : $zoneData['maxNameLength'];
                foreach($zoneData['rows'] as $page => $data)
                {
                    // Sort the page groups by position
                    ksort($data);
                    foreach($data as $pos => $vals)
                    {
                        // If this is the first line, don't pad with spaces
                        $pre = $line == 0 ? "" : "           ";
                        // Shorten name to 30 chars
                        $vals['name'] = strlen($vals['name']) > 30 ? substr($vals['name'], 0, 30) : $vals['name'];
                        // Determine the difference in length between this line and the max name length so we can pad with the necessary number of spaces
                        $diff = $zoneData['maxNameLength'] - strlen($vals['name']);
                        // Build the padding
                        $padding = "";
                        for($i=0;$i<$diff;$i++)
                            $padding .= " ";
                        // Output the line to the file
                        fwrite($file, $pre.$page.($pos == '' ? ' ' : $pos).$page.$vals['4dig'].$vals['state']."-".$vals['issue'].$vals['name'].$padding.$vals['size'].$vals['2dig']."\n");
                        $line++;
                        $issue = $vals['issue'];
                    }
                }
                // For soct files, we need to add "Blank" pages for any files that don't have a page count that is a multile of 4 (4,8,12...)
                if(stristr($txtFile, 'soct') && $zoneData['maxPage'] % 4 != 0)
                {
                    $diff = $zoneData['maxNameLength'] - strlen("Blank");
                    $padding = "";
                    for($i=0;$i<$diff;$i++)
                        $padding .= " ";
                    for($i=0; $i<(4 - $zoneData['maxPage'] % 4); $i++)
                    {
                        $page = ($zoneData['maxPage']+$i+1);
                        fwrite($file, "           ".$page." ".$page."0000".$state."-".$issue."Blank".$padding."1.00"."93"."\n");
                    }
                }
                fclose($file);
            }

            // Create zip archive to put all of our files in
            $zip = new ZipArchive();
            $zip->open(public_path().'/'.$filename.'.zip', ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE);
            foreach($aFiles as $f)
            {
                $zip->addFile($f, $f);
            }
            $zip->close();

            // Return the name of the zipfile
            return array($filename.'.zip');
        }
        else
        {
            return array();
        }
    }
}