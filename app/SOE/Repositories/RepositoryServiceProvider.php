<?php namespace SOE\Repositories;

use Illuminate\Support\ServiceProvider;
use App;
use Event;
use Illuminate\Foundation\AliasLoader;
use EloquentUserRepository;
use EloquentUserPrintRepository;
use EloquentUserViewRepository;
use EloquentUserClippedRepository;
use EloquentUserRedeemRepository;
use EloquentEntityRepository;
use EloquentOfferRepository;
use EloquentLocationRepository;
use EloquentMerchantRepository;
use EloquentFranchiseRepository;
use EloquentNonmemberRepository;
use EloquentRoleRepository;
use EloquentRuleRepository;
use EloquentContestRepository;
use EloquentContestApplicationRepository;
use EloquentFeatureRepository;
use EloquentCustomerioUserRepository;
use EloquentBannerRepository;
use EloquentUserLocationRepository;
use EloquentReviewRepository;
use EloquentShareRepository;
use EloquentZipcodeRepository;
use EloquentAssetRepository;
use EloquentCategoryRepository;
use EloquentUserImpressionRepository;
use EloquentUserFavoriteRepository;
use EloquentReviewVoteRepository;
use EloquentFranchiseAssignmentRepository;
use EloquentLocationHourRepository;
use EloquentTagRepository;
use EloquentAssetCategoryRepository;
use EloquentCompanyRepository;
use EloquentVehicleMakeRepository;
use EloquentVehicleModelRepository;
use EloquentVehicleYearRepository;
use EloquentVehicleStyleRepository;
use EloquentVehicleCommandHistoryRepository;
use EloquentVehicleIncentiveRepository;
use EloquentVehicleIncentiveStyleRepository;
use EloquentVehicleAssetRepository;
use EloquentUsedVehicleRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        /******** NEW REPOSITORY BINDINGS ********/

        App::bind('AdImpressionRepositoryInterface', 'SOE\Repositories\Eloquent\AdImpressionRepository');
        App::bind('AdvertisementRepositoryInterface', 'SOE\Repositories\Eloquent\AdvertisementRepository');
        App::bind('AssetCategoryRepositoryInterface', 'SOE\Repositories\Eloquent\AssetCategoryRepository');
        App::bind('AssetRepositoryInterface', 'SOE\Repositories\Eloquent\AssetRepository');
        App::bind('AssignmentTypeRepositoryInterface', 'SOE\Repositories\Eloquent\AssignmentTypeRepository');
        App::bind('AutoQuoteRepositoryInterface', function($app)
        {
            $repository = new \SOE\Repositories\Eloquent\AutoQuoteRepository(
                App::make('FranchiseRepositoryInterface'),
                App::make('VehicleEntityRepositoryInterface'),
                App::make('VehicleMakeRepositoryInterface'),
                App::make('VehicleStyleRepositoryInterface')
            );
            $repository->registerValidator('create', new \SOE\Services\Validation\Laravel\AutoQuoteCreateValidator($app['validator']));
            return $repository;
        });
        App::bind('BannerEntityRepositoryInterface', 'SOE\Repositories\Eloquent\BannerEntityRepository');
        App::bind('BannerRepositoryInterface', 'SOE\Repositories\Eloquent\BannerRepository');
        App::bind('CategoryRepositoryInterface', 'SOE\Repositories\Eloquent\CategoryRepository');
        App::bind('CityImageRepositoryInterface', 'SOE\Repositories\Eloquent\CityImageRepository');
        App::bind('CjPostRepositoryInterface', 'SOE\Repositories\Eloquent\CjPostRepository');
        App::bind('CjProductRepositoryInterface', 'SOE\Repositories\Eloquent\CjProductRepository');
        App::bind('CompanyPostRepositoryInterface', 'SOE\Repositories\Eloquent\CompanyPostRepository');
        App::bind('CompanyRepositoryInterface', 'SOE\Repositories\Eloquent\CompanyRepository');
        App::bind('CompanyEventRepositoryInterface', '\SOE\Repositories\Eloquent\CompanyEventRepository');
        App::bind('CompanyEventAttendeeRepositoryInterface', '\SOE\Repositories\Eloquent\CompanyEventAttendeeRepository');
        App::bind('ContestRepositoryInterface', 'SOE\Repositories\Eloquent\ContestRepository');
        App::bind('ContestApplicationRepositoryInterface', 'SOE\Repositories\Eloquent\ContestApplicationRepository');
        App::bind('ContestAwardDateRepositoryInterface', 'SOE\Repositories\Eloquent\ContestAwardDateRepository');
        App::bind('ContestDisclaimerRepositoryInterface', 'SOE\Repositories\Eloquent\ContestDisclaimerRepository');
        App::bind('ContestLocationRepositoryInterface', 'SOE\Repositories\Eloquent\ContestLocationRepository');
        App::bind('ContestWinnerRepositoryInterface', 'SOE\Repositories\Eloquent\ContestWinnerRepository');
        App::bind('ContractorApplicationRepositoryInterface', 'SOE\Repositories\Eloquent\ContractorApplicationRepository');
        App::bind('DealerApplicationRepositoryInterface', 'SOE\Repositories\Eloquent\DealerApplicationRepository');
        App::bind('DealerBrandRepositoryInterface', 'SOE\Repositories\Eloquent\DealerBrandRepository');
        App::bind('DealerOrderRepositoryInterface', 'SOE\Repositories\Eloquent\DealerOrderRepository');
        App::bind('DealerRelationRepositoryInterface', 'SOE\Repositories\Eloquent\DealerRelationRepository');
        App::bind('DistrictRepositoryInterface', 'SOE\Repositories\Eloquent\DistrictRepository');
        App::bind('EntityRepositoryInterface', 'SOE\Repositories\Eloquent\EntityRepository');
        App::bind('EventRepositoryInterface', 'SOE\Repositories\Eloquent\EventRepository');
        App::bind('FeatureRepositoryInterface', 'SOE\Repositories\Eloquent\FeatureRepository');
        App::bind('FranchiseDistrictRepositoryInterface', 'SOE\Repositories\Eloquent\FranchiseDistrictRepository');
        App::bind('FranchiseRepositoryInterface', 'SOE\Repositories\Eloquent\FranchiseRepository');
        App::bind('LeadEmailRepositoryInterface', 'SOE\Repositories\Eloquent\LeadEmailRepository');
        App::bind('LocationRepositoryInterface', 'SOE\Repositories\Eloquent\LocationRepository');
        App::bind('MerchantRepositoryInterface', 'SOE\Repositories\Eloquent\MerchantRepository');
        App::bind('NewsletterRepositoryInterface', 'SOE\Repositories\Eloquent\NewsletterRepository');
        App::bind('NewsletterScheduleRepositoryInterface', 'SOE\Repositories\Eloquent\NewsletterScheduleRepository');
        App::bind('NonmemberRepositoryInterface', 'SOE\Repositories\Eloquent\NonmemberRepository');
        App::bind('NoteRepositoryInterface', 'SOE\Repositories\Eloquent\NoteRepository');
        App::bind('OfferRepositoryInterface', 'SOE\Repositories\Eloquent\OfferRepository');
        App::bind('ProjectTagRepositoryInterface', 'SOE\Repositories\Eloquent\ProjectTagRepository');
        App::bind('QuoteRepositoryInterface', 'SOE\Repositories\Eloquent\QuoteRepository');
        App::bind('ReportRepositoryInterface', 'SOE\Repositories\Eloquent\ReportRepository');
        App::bind('ReviewRepositoryInterface', 'SOE\Repositories\Eloquent\ReviewRepository');
        App::bind('SeoContentRepositoryInterface', function($app)
        {
            $repository = new \SOE\Repositories\Eloquent\SeoContentRepository;
            $repository->registerValidator('create', new \SOE\Services\Validation\Laravel\SeoContentCreateValidator($app['validator']));
            return $repository;
        });
        App::bind('ShareEmailRepositoryInterface', 'SOE\Repositories\Eloquent\ShareEmailRepository');
        App::bind('ShareRepositoryInterface', 'SOE\Repositories\Eloquent\ShareRepository');
        App::bind('SohiSurveyRepositoryInterface', 'SOE\Repositories\Eloquent\SohiSurveyRepository');
        App::bind('SuggestionRepositoryInterface', 'SOE\Repositories\Eloquent\SuggestionRepository');
        App::bind('SysLogRepositoryInterface', 'SOE\Repositories\Eloquent\SysLogRepository');
        App::bind('TrackedCallRepositoryInterface', 'SOE\Repositories\Eloquent\TrackedCallRepository');
        App::bind('TrainingPageRepositoryInterface', '\SOE\Repositories\Eloquent\TrainingPageRepository');
        App::bind('TrainingSectionRepositoryInterface', '\SOE\Repositories\Eloquent\TrainingSectionRepository');
        App::bind('UserAssignmentTypeRepositoryInterface', 'SOE\Repositories\Eloquent\UserAssignmentTypeRepository');
        App::bind('UserFavoriteRepositoryInterface', 'SOE\Repositories\Eloquent\UserFavoriteRepository');
        App::bind('UserImpressionRepositoryInterface', 'SOE\Repositories\Eloquent\UserImpressionRepository');
        App::bind('UserLinkClickRepositoryInterface', 'SOE\Repositories\Eloquent\UserLinkClickRepository');
        App::bind('UserLocationRepositoryInterface', 'SOE\Repositories\Eloquent\UserLocationRepository');
        App::bind('UserPrintRepositoryInterface', 'SOE\Repositories\Eloquent\UserPrintRepository');
        App::bind('UsedVehicleRepositoryInterface', 'SOE\Repositories\Eloquent\UsedVehicleRepository');
        App::bind('UserRepositoryInterface', 'SOE\Repositories\Eloquent\UserRepository');
        App::bind('UserViewRepositoryInterface', 'SOE\Repositories\Eloquent\UserViewRepository');
        App::bind('VehicleAssetRepositoryInterface', 'SOE\Repositories\Eloquent\VehicleAssetRepository');
        App::bind('VehicleEntityRepositoryInterface', 'SOE\Repositories\Eloquent\VehicleEntityRepository');
        App::bind('VehicleIncentiveRepositoryInterface', 'SOE\Repositories\Eloquent\VehicleIncentiveRepository');
        App::bind('VehicleMakeRepositoryInterface', 'SOE\Repositories\Eloquent\VehicleMakeRepository');
        App::bind('VehicleModelRepositoryInterface', 'SOE\Repositories\Eloquent\VehicleModelRepository');
        App::bind('VehicleStyleRepositoryInterface', 'SOE\Repositories\Eloquent\VehicleStyleRepository');
        App::bind('VehicleYearRepositoryInterface', 'SOE\Repositories\Eloquent\VehicleYearRepository');
        App::bind('YipitDivisionRepositoryInterface', 'SOE\Repositories\Eloquent\YipitDivisionRepository');
        App::bind('YipitBannedMerchantRepositoryInterface', 'SOE\Repositories\Eloquent\YipitBannedMerchantRepository');
        App::bind('ZipcodeRepositoryInterface', 'SOE\Repositories\Eloquent\ZipcodeRepository');

        /******** OLD REPOSITORY BINDINGS ********/

        App::bind('user', function()
        {
            return new EloquentUserRepository;
        });

        App::bind('userprint', function()
        {
            return new EloquentUserPrintRepository;
        });

        App::bind('userview', function()
        {
            return new EloquentUserViewRepository;
        });

        App::bind('userclipped', function()
        {
            return new EloquentUserClippedRepository;
        });

        App::bind('userredeem', function()
        {
            return new EloquentUserRedeemRepository;
        });

        App::bind('entity', function()
        {
            return new EloquentEntityRepository;
        });

        App::bind('offer', function()
        {
            return new EloquentOfferRepository;
        });

        App::bind('location', function()
        {
            return new EloquentLocationRepository;
        });

        App::bind('merchant', function()
        {
            return new EloquentMerchantRepository;
        });

        App::bind('franchise', function()
        {
            return new EloquentFranchiseRepository;
        });

        App::bind('nonmember', function()
        {
            return new EloquentNonmemberRepository;
        });

        App::bind('role', function()
        {
            return new EloquentRoleRepository;
        });

        App::bind('rule', function()
        {
            return new EloquentRuleRepository;
        });

        App::bind('contest', function()
        {
            return new EloquentContestRepository;
        });

        App::bind('contestapplication', function()
        {
            return new EloquentContestApplicationRepository;
        });

        App::bind('feature', function()
        {
            return new EloquentFeatureRepository;
        });

        App::bind('customeriouser', function()
        {
            return new EloquentCustomerioUserRepository;
        });

        App::bind('banner', function()
        {
            return new EloquentBannerRepository;
        });

        App::bind('userlocation', function()
        {
            return new EloquentUserLocationRepository;
        });

        App::bind('review', function()
        {
            return new EloquentReviewRepository;
        });

        App::bind('share', function()
        {
            return new EloquentShareRepository;
        });

        App::bind('zipcode', function()
        {
            return new EloquentZipcodeRepository;
        });

        App::bind('asset', function()
        {
            return new EloquentAssetRepository;
        });

        App::bind('category', function()
        {
            return new EloquentCategoryRepository;
        });

        App::bind('userimpression', function()
        {
            return new EloquentUserImpressionRepository;
        });

        App::bind('userfavorite', function()
        {
            return new EloquentUserFavoriteRepository;
        });

        App::bind('reviewvote', function()
        {
            return new EloquentReviewVoteRepository;
        });

        App::bind('franchiseassignment', function()
        {
            return new EloquentFranchiseAssignmentRepository;
        });

        App::bind('locationhour', function()
        {
            return new EloquentLocationHourRepository;
        });

        App::bind('tag', function()
        {
            return new EloquentTagRepository;
        });

        App::bind('assetcategory', function()
        {
            return new EloquentAssetCategoryRepository;
        });

        App::bind('company', function()
        {
            return new EloquentCompanyRepository;
        });

        App::bind('vehiclemake', function()
        {
            return new EloquentVehicleMakeRepository;
        });

        App::bind('vehiclemodel', function()
        {
            return new EloquentVehicleModelRepository;
        });

        App::bind('vehicleyear', function()
        {
            return new EloquentVehicleYearRepository;
        });

        App::bind('vehiclestyle', function()
        {
            return new EloquentVehicleStyleRepository;
        });

        App::bind('vehiclecommandhistory', function()
        {
            return new EloquentVehicleCommandHistoryRepository;
        });

        App::bind('vehicleincentive', function()
        {
            return new EloquentVehicleIncentiveRepository;
        });

        App::bind('vehicleincentivestyle', function()
        {
            return new EloquentVehicleIncentiveStyleRepository;
        });

        App::bind('vehicleasset', function()
        {
            return new EloquentVehicleAssetRepository;
        });

        App::bind('usedvehicle', function()
        {
            return new EloquentUsedVehicleRepository;
        });

        // Shortcut so developers don't need to add an Alias in app/config/app.php
        App::booting(function()
        {
            $loader = AliasLoader::getInstance();
            $loader->alias('User', 'SOE\Facades\User');
            $loader->alias('UserPrint', 'SOE\Facades\UserPrint');
            $loader->alias('UserView', 'SOE\Facades\UserView');
            $loader->alias('UserClipped', 'SOE\Facades\UserClipped');
            $loader->alias('UserRedeem', 'SOE\Facades\UserRedeem');
            $loader->alias('Entity', 'SOE\Facades\Entity');
            $loader->alias('Offer', 'SOE\Facades\Offer');
            $loader->alias('Location', 'SOE\Facades\Location');
            $loader->alias('Merchant', 'SOE\Facades\Merchant');
            $loader->alias('Franchise', 'SOE\Facades\Franchise');
            $loader->alias('Nonmember', 'SOE\Facades\Nonmember');
            $loader->alias('Role', 'SOE\Facades\Role');
            $loader->alias('Rule', 'SOE\Facades\Rule');
            $loader->alias('Contest', 'SOE\Facades\Contest');
            $loader->alias('ContestApplication', 'SOE\Facades\ContestApplication');
            $loader->alias('Feature', 'SOE\Facades\Feature');
            $loader->alias('CustomerioUser', 'SOE\Facades\CustomerioUser');
            $loader->alias('Banner', 'SOE\Facades\Banner');
            $loader->alias('UserLocation', 'SOE\Facades\UserLocation');
            $loader->alias('Review', 'SOE\Facades\Review');
            $loader->alias('Share', 'SOE\Facades\Share');
            $loader->alias('Zipcode', 'SOE\Facades\Zipcode');
            $loader->alias('Asset', 'SOE\Facades\Asset');
            $loader->alias('Category', 'SOE\Facades\Category');
            $loader->alias('UserImpression', 'SOE\Facades\UserImpression');
            $loader->alias('UserFavorite', 'SOE\Facades\UserFavorite');
            $loader->alias('ReviewVote', 'SOE\Facades\ReviewVote');
            $loader->alias('FranchiseAssignment', 'SOE\Facades\FranchiseAssignment');
            $loader->alias('LocationHour', 'SOE\Facades\LocationHour');
            $loader->alias('Tag', 'SOE\Facades\Tag');
            $loader->alias('AssetCategory', 'SOE\Facades\AssetCategory');
            $loader->alias('Company', 'SOE\Facades\Company');
            $loader->alias('VehicleMake', 'SOE\Facades\VehicleMake');
            $loader->alias('VehicleModel', 'SOE\Facades\VehicleModel');
            $loader->alias('VehicleYear', 'SOE\Facades\VehicleYear');
            $loader->alias('VehicleStyle', 'SOE\Facades\VehicleStyle');
            $loader->alias('VehicleCommandHistory', 'SOE\Facades\VehicleCommandHistory');
            $loader->alias('VehicleIncentive', 'SOE\Facades\VehicleIncentive');
            $loader->alias('VehicleIncentiveStyle', 'SOE\Facades\VehicleIncentiveStyle');
            $loader->alias('VehicleAsset', 'SOE\Facades\VehicleAsset');
            $loader->alias('UsedVehicle', 'SOE\Facades\UsedVehicle');
        });
    }
}
