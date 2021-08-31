<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KalimantanUtaraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $provinces = [
            ['name' => 'Kalimantan Utara', 'latitude' => 3.0645350706739407, 'longitude' => 116.25196294377069]
        ];

        DB::table('provinces')->insert($provinces);
    }
}
