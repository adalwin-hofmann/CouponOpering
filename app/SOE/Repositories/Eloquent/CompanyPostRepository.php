<?php namespace SOE\Repositories\Eloquent;

class CompanyPostRepository extends BaseRepository implements \CompanyPostRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'company_id',
        'type',
        'path',
        'content'
    );

    protected $model = 'CompanyPost';

    /**
     * Store a posted company file.
     *
     * @param \SOE\DB\Company $company
     * @param string $type
     * @param file $file
     * @return mixed
     */
    public function postFile(\SOE\DB\Company $company, $type, $file)
    {
        $fileStore = \App::make('FileStoreInterface');
        $stored = $fileStore->store($file, 'company_post/'.$company->slug.'/'.$type.'/');
        if(!$stored)
            throw new \Exception('error uploading file');
        $postfile = $this->create(array(
            'company_id' => $company->id,
            'type' => $type,
            'path' => $stored
        ));
        return $postfile;
    }
}