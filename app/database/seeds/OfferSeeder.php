<?php

class OffersSeeder extends Seeder
{
    public function run()
    {
        if (\App::environment() != "testing") {
            throw new \Exception("Not in a testing environment");
        }

        DB::table('offers')->delete();

        $offers = array(
            array(
            ),
        );

        foreach ($offers as $offer) {
            Offer::create($offer);
        }
    }
}

