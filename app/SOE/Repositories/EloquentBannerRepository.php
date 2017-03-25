<?php

class EloquentBannerRepository extends BaseEloquentRepository implements BannerRepository, RepositoryInterface
{
    protected $columns = array(
        'company_id',
        'location_id',
        'post_date',
        'expire_date',
        'path',
        'name',
        'impressions',
        'type',
        'is_location_specific',
        'banner_link',
        'is_deleted',
        'is_paying_category',
        'is_paying_subcategory',
        'asset_type',
    );

    protected $model = 'Banner';

    /**
     * Fill out a contest application given a contest and an applicant.
     *
     * @param  ContestRepository $contest
     * @param  mixed $applicant Either a UserRepository or NonmemberRepository
     * @return mixed
     */
    public function click($clicker)
    {
        $application = ContestApplication::blank();
        $types = class_implements($clicker);
        $data = array();
        if(in_array('UserRepository', $types))
        {
            $data['user_id'] = $clicker->id;
        }
        else if(in_array('NonmemberRepository', $types))
        {
            $data['nonmember_id'] = $clicker->id;
        }
        else
        {
            return;
        }
        $data['latitude'] = $clicker->latitude;
        $data['longitude'] = $clicker->longitude;
        $data['state'] = $clicker->state;
        $data['banner_id'] = $this->primary_key;
        DB::table('banner_click')->insert($data);
    }

}