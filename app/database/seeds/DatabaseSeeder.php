<?php

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();

        $this->call('AssetSeeder');
        $this->call('MerchantSeeder');
        $this->call('CategorySeeder');
        $this->call('ZipcodeSeeder');
        $this->call('EntitySeeder');
    }
}

