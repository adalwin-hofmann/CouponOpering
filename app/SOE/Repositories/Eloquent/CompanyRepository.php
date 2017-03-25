<?php namespace SOE\Repositories\Eloquent;

class CompanyRepository extends BaseRepository implements \CompanyRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'name',
        'slug',
        'landing_image',
        'logo_image',
        'description',
        'slogan',
        'own_market',
        'has_corporate',
        'has_custom_colors',
        'radius',
        'latitude',
        'longitude',
        'address',
        'address2',
        'city',
        'state',
        'zip',
        'phone',
        'is_demo',
        'is_active',
        'username',
        'api_key',
    );

    protected $model = 'Company';

    /**
     * Retrieve a company by username, api_key
     *
     * @param string $username
     * @param string $api_key
     * @return mixed
     */
    public function authenticate($username, $api_key)
    {
        return $this->query()->where('username', $username)->where('api_key', $api_key)->first();
    }
}

/**
 * Handle the Company created event.
 *
 * @param SOE\DB\Company $company
 * @return void
 */
\SOE\DB\Company::created(function($company)
{
    $company->username = \PseudoCrypt::hash($company->id);
    $company->api_key = bin2hex(openssl_random_pseudo_bytes(4));
    $company->save();
});