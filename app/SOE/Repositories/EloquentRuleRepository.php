<?php

class EloquentRuleRepository extends BaseEloquentRepository implements RuleRepository, RepositoryInterface
{
    protected $columns = array(
        'group',
        'action',
        'description',
    );

    protected $model = 'Rule';

    /**
     * Retrieve a Rule by group and action combination.
     *
     * @param  string $group
     * @param  string $action
     * @return RuleRepository
     */
    public function findByGroupAndAction($group, $action)
    {
        $filters = array();
        $filters[] = array('key' => 'group', 'operator' => '=', 'value' => $group);
        $filters[] = array('key' => 'action', 'operator' => '=', 'value' => $action);
        $rule = $this->get($filters, 1);
        return empty($rule) ? $rule : $rule[0];
    }

}