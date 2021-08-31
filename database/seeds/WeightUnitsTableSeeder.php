<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WeightUnitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $weightUnits = [
            ['name' => 'gram'],
            ['name' => 'kilogram']
        ];

        DB::table('weight_units')->insert($weightUnits);
    }
}
