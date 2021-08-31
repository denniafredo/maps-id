<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GlobalSettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            ['name' => 'long', 'value' => 114.2382907202174],
            ['name' => 'lat', 'value' => -0.7580890549189405],
            ['name' => 'zoom', 'value' => 4.666666666666667],
        ];

        DB::table('global_settings')->insert($settings);
    }
}
