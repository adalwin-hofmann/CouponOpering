<?php

class ApiTestSeeder extends Seeder
{
    protected $table; // table name
    protected $data;

    public function prepare()
    {
        if (\App::environment() != "testing") {
            throw new Exception("Not in a testing environment");
        }

        if (!$this->table) {
            throw new Exception("Table name not set. " .
                "Set with \$this->setTableName(\$name)");
        }

        DB::table($this->table)->delete();
    }

    // Setter Classes
    public function setTableName($name)
    {
        $this->table = $name;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    // Utility Classes
    public function addData($data)
    {
        if (!is_array($data)) {
            throw new Exception("Data must be an array.");
        }

        $this->data[] = $data;
    }

    public function insert()
    {
        DB::table($this->table)->insert($this->data);
    }

}

