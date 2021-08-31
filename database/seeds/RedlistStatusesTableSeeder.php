<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RedlistStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $redlistStatuses = [
            ['name' => 'Vulnerable', 'code' => 'VU', 'image' => 'redlist_statuses/VU.png'],
            ['name' => 'Near Threatened', 'code' => 'nt', 'image' => 'redlist_statuses/nt.png'],
            ['name' => 'Least Concern', 'code' => 'lc', 'image' => 'redlist_statuses/lc.png'],
            ['name' => 'Extinct', 'code' => 'EX', 'image' => 'redlist_statuses/EX.png'],
            ['name' => 'Extinct In The Wild', 'code' => 'EX', 'image' => 'redlist_statuses/EW.png'],
            ['name' => 'Endangered', 'code' => 'EX', 'image' => 'redlist_statuses/EN.png'],
            ['name' => 'Critically Endangered', 'code' => 'CR', 'image' => 'redlist_statuses/CR.png'],
            ['name' => 'Data Deficent', 'code' => 'dd', 'image' => 'redlist_statuses/dd.png'],
        ];

        DB::table('redlist_statuses')->insert($redlistStatuses);
    }
}
