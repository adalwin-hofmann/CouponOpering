<?php

class EloquentCustomerioUserRepository extends BaseEloquentRepository implements CustomerioUserRepository, RepositoryInterface
{
    protected $columns = array(
        'custio_created_at',
        'unsubscribed',
        'unsubscribed_at',
        'email',
        'user_id',
        'zip',
        'latitude',
        'longitude',
        'version',
        'franchise_id',
    );

    protected $model = 'CustomerioUser';

    /**
     * Retrieve a CustomerioUser by email address.
     *
     * @param  string  $email
     * @return mixed
     */
    public function findByEmail($email)
    {
        $filters = array();
        $filters[] = array('key' => 'email', 'operator' => '=', 'value' => $email);
        $user = CustomerioUser::get($filters, 1);
        return empty($user['objects']) ? array() : $user['objects'][0];
    }

}