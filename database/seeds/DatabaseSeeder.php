<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(WeightUnitsTableSeeder::class);
        $this->call(CitesStatusesTableSeeder::class);
        $this->call(RedlistStatusesTableSeeder::class);
        $this->call(ProvincesTableSeeder::class);
        $this->call(ConservationStatusesTableSeeder::class);
        $this->call(GlobalSettingTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(NewUserTableSeeder::class);
        $this->call(KalimantanUtaraSeeder::class);
    }
}
