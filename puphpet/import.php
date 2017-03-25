<?php

$values_string = "zipcodes, cities, categories, affiliate_tracking, assets, category_assets, asset_categories, asset_tags, tags, merchants, locations, entities, contest_applications, users, user_prints, user_views, user_clipped, user_locations, user_redeems, user_searches, shares, share_emails, reviews, banners, assignment_types, customerio_users, roles, companies";

$exclude = array(
    "cities",
    "contests",
    "entities",
    "locations",
    "merchants",
    "offers"
);

$values_array = explode(", ", $values_string);

foreach($values_array as $value) {
    if (in_array($value, $exclude)) {
        continue;
    }

    $cmd = "php /vagrant/Platform-SOE4/artisan import --type=$value";
    echo "Running command: $cmd" . PHP_EOL;
    $output = exec($cmd);
    echo $output . PHP_EOL;
}
