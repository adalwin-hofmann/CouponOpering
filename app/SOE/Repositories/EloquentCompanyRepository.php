<?php

class EloquentCompanyRepository extends BaseEloquentRepository implements CompanyRepository, RepositoryInterface
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
    );

    protected $model = 'Company';

}