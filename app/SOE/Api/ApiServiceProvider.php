<?php namespace SOE\Api;

use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider
{
    public function register()
    {
        \App::bind('AdvertisementApi', 'SOE\Api\Advertisement');
        \App::bind('AssetApi', 'SOE\Api\Asset');
        \App::bind('AssetCategoryApi', 'SOE\Api\AssetCategory');
        \App::bind('BannerApi', 'SOE\Api\Banner');
        \App::bind('BannerEntityApi', 'SOE\Api\BannerEntity');
        \App::bind('CategoryApi', 'SOE\Api\Category');
        \App::bind('CompanyEventApi', 'SOE\Api\CompanyEvent');
        \App::bind('CompanyEventAttendeeApi', 'SOE\Api\CompanyEventAttendee');
        \App::bind('ContestApi', 'SOE\Api\Contest');
        \App::bind('ContestAwardDateApi', 'SOE\Api\ContestAwardDate');
        \App::bind('ContestLocationApi', 'SOE\Api\ContestLocation');
        \App::bind('ContestWinnerApi', 'SOE\Api\ContestWinner');
        \App::bind('DealerBrandApi', 'SOE\Api\DealerBrand');
        \App::bind('DealerOrderApi', 'SOE\Api\DealerOrder');
        \App::bind('EntityApi', 'SOE\Api\Entity');
        \App::bind('EventApi', 'SOE\Api\Event');
        \App::bind('FeatureApi', 'SOE\Api\Feature');
        \App::bind('FranchiseApi', 'SOE\Api\Franchise');
        \App::bind('LocationApi', 'SOE\Api\Location');
        \App::bind('MerchantApi', 'SOE\Api\Merchant');
        \App::bind('NetlmsApi', 'SOE\Api\Netlms');
        \App::bind('NonmemberApi', 'SOE\Api\Nonmember');
        \App::bind('NoteApi', 'SOE\Api\Note');
        \App::bind('OfferApi', 'SOE\Api\Offer');
        \App::bind('ProjectTagApi', 'SOE\Api\ProjectTag');
        \App::bind('NewsletterScheduleApi', 'SOE\Api\NewsletterSchedule');
        \App::bind('TrainingPageApi', 'SOE\Api\TrainingPage');
        \App::bind('TrainingSectionApi', 'SOE\Api\TrainingSection');
        \App::bind('UsedVehicleApi', 'SOE\Api\UsedVehicle');
        \App::bind('UserAssignmentTypeApi', 'SOE\Api\UserAssignmentType');
        \App::bind('UserApi', 'SOE\Api\User');
        \App::bind('UserLocationApi', 'SOE\Api\UserLocation');
        \App::bind('VehicleEntityApi', 'SOE\Api\VehicleEntity');
        \App::bind('VehicleIncentiveApi', 'SOE\Api\VehicleIncentive');
        \App::bind('VehicleModelApi', 'SOE\Api\VehicleModel');
        \App::bind('VehicleStyleApi', 'SOE\Api\VehicleStyle');
        \App::bind('VehicleYearApi', 'SOE\Api\VehicleYear');
    }
}