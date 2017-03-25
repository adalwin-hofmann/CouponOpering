<?php namespace SOE\Repositories\Eloquent;

class CjPostRepository extends BaseRepository implements \CjPostRepositoryInterface, \BaseRepositoryInterface
{
    protected $columns = array(
        'file_location',
        'is_locked',
        'status',
        'parsed_at'
    );

    protected $model = 'CjPost';

    public function getForParsing()
    {
        $parse = $this->query()->where('is_locked', '0')->where('status', 'parse')->first();
        if($parse)
        {
            $parse->is_locked = 1;
            $parse->status = 'parsing';
            $parse->save();
        }

        return $parse;
    }
}