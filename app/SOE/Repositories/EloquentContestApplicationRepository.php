<?php

class EloquentContestApplicationRepository extends BaseEloquentRepository implements ContestApplicationRepository, RepositoryInterface
{
    protected $columns = array(
        'contest_id',
        'user_id',
        'nonmember_id',
        'name',
        'city',
        'state',
        'zip',
        'phone',
        'email',
        'address',
        'allow_emails',
    );

    protected $model = 'ContestApplication';

    /**
     * Fill out a contest application given a contest and an applicant.
     *
     * @param  ContestRepository $contest
     * @param  mixed $applicant Either a UserRepository or NonmemberRepository
     * @return mixed
     */
    public function fillOut(ContestRepository $contest, $applicant)
    {
        $input = Input::all();
        $rules = array(
            'name' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'phone' => 'required',
            'email' => 'required|email'
        );
        $validator = Validator::make($input, $rules);
        if($validator->fails())
        {
            return $validator;
        }

        $application = ContestApplication::blank();
        $types = class_implements($applicant);
        if(in_array('UserRepository', $types))
        {
            $application->user_id = $applicant->id;
        }
        else if(in_array('NonmemberRepository', $types))
        {
            $application->nonmember_id = $applicant->id;
        }
        else
        {
            return;
        }

        $application->contest_id = $contest->id;
        $application->name = Input::get('name');
        $application->address = Input::get('address');
        $application->city = Input::get('city');
        $application->state = Input::get('state');
        $application->zip = Input::get('zip');
        $application->phone = Input::get('phone');
        $application->email = Input::get('email');
        $application->save();

        return $application;
    }

}