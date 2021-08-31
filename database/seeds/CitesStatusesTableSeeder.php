<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitesStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $citesStatuses = [
            ['name' => 'Apppendix I'],
            ['name' => 'Apppendix II'],
            ['name' => 'Apppendix III'],
        ];

        DB::table('cites_statuses')->insert($citesStatuses);
    }
}
