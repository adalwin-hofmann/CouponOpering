<?php

class EloquentRoleRepository extends BaseEloquentRepository implements RoleRepository, RepositoryInterface
{
    protected $columns = array(
        'name',
    );

    protected $model = 'Role';

    /**
     * Retrieve a Role by name.
     *
     * @param  string $name
     * @return RoleRepository
     */
    public function findByName($name)
    {
        $filters = array();
        $filters[] = array('key' => 'name', 'operator' => '=', 'value' => $name);
        $role = $this->get($filters, 1);
        return empty($role) ? $role : $role[0];
    }

    /**
     * Add a Rule to this Role.
     *
     * @param  RuleRepository $rule
     * @return void
     */
    public function attach(RuleRepository $rule)
    {
        if($this->primary_key)
        {
            $existing = DB::table('role_rules')->where('rule_id', '=', $rule->id)->where('role_id', '=', $this->primary_key)->first();
            if(empty($existing))
            {
                DB::table('role_rules')->insert(array('role_id' => $this->primary_key, 'rule_id' => $rule->id));
            }
        }
    }

    /**
     * Remove a Rule from this Role.
     *
     * @param  RuleRepository $rule
     * @return void
     */
    public function remove(RuleRepository $rule)
    {
        DB::table('role_rules')->where('rule_id', '=', $rule->id)->where('role_id', '=', $this->primary_key)->delete();
    }

    /**
     * Get Rules for a Role.
     *
     * @param  array $filters
     * @param  int   $limit
     * @param  int   $page
     * @return array
     */
    public function getRules(array $filters = array(), $limit = 0, $page = 0)
    {
        $query = DB::table('role_rules')->where('role_id', '=', $this->primary_key);
        $query = $this->parseFilters($query, $filters);
        $query = $this->paginator($query, $limit, $page);
        $role_rules = $query->get();

        $ids = array();
        foreach($role_rules as $rr)
        {
            $ids[] = $rr->rule_id;
        }

        $rule_filters = array();
        $rule_filters[] = array('type' => 'whereIn', 'key' => 'id', 'value' => $ids);
        return Rule::get($rule_filters);
    }

}