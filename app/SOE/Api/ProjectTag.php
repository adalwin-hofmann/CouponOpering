<?php
namespace SOE\Api;

class ProjectTag extends Api implements ApiInterface, ProjectTagApi
{
    public function __construct(
        \ProjectTagRepositoryInterface $repository,
        \FranchiseRepositoryInterface $franchiseRepository,
        \UserRepositoryInterface $userRepository
    )
    {
        $this->repository = $repository;
        $this->franchiseRepository = $franchiseRepository;
        $this->userRepository = $userRepository;
    }

    public function find()
    {
        return $this->format($this->repository->find(\Input::get('id')));
    }

    public function create()
    {
    }

    public function get()
    {
    }

    public function update()
    {
    }

    /**
     * Get an array of project tags associated with a franchise, based on franchise_id.
     *
     * @api
     *
     * @return mixed
     */
    public function getFranchiseTags()
    {
        $franchise_id = \Input::get('franchise_id');
        $franchise = $this->franchiseRepository->find($franchise_id);
        return $this->format($this->repository->getFranchiseTags($franchise));
    }
}
