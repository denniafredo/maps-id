<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConservationStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $conservationStatuses = [
            ['name' => 'Dilindungi']
        ];

        DB::table('conservation_statuses')->insert($conservationStatuses);
    }
}
