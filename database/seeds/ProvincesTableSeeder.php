<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvincesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $provinces = [
            ['name' => 'Aceh', 'latitude' => 4.330255795952752, 'longitude' => 96.99895136700235],
            ['name' => 'Bali', 'latitude' => -8.359236065610517, 'longitude' => 115.18607428633703],
            ['name' => 'Bangka-Belitung', 'latitude' => -2.3157992232836477, 'longitude' => 106.02286835320402],
            ['name' => 'Banten', 'latitude' => -6.438788784302631, 'longitude' => 106.12703380361084],
            ['name' => 'Bengkulu', 'latitude' => -3.3905035428917216, 'longitude' => 102.18043437950814],
            ['name' => 'Gorontalo', 'latitude' => '0.6969495069471776', 'longitude' => '122.3667149406243'],
            ['name' => 'Irian Jaya Barat', 'latitude' => -1.6605588963550701, 'longitude' => 132.92993390065004],
            ['name' => 'Jakarta Raya', 'latitude' => -6.21217999278916, 'longitude' => 106.82873382364264],
            ['name' => 'Jambi', 'latitude' => -1.6286491779772945, 'longitude' => 102.76682444769877],
            ['name' => 'Jawa Barat', 'latitude' => -6.906224490844025, 'longitude' => 107.62195965871074],
            ['name' => 'Jawa Tengah', 'latitude' => -7.318285551612462, 'longitude' => 110.01660940808358],
            ['name' => 'Jawa Timur', 'latitude' => -7.841266494664978, 'longitude' => 112.61371723496033],
            ['name' => 'Kalimantan Barat', 'latitude' => -0.01669888795915142, 'longitude' => 111.12877014828338],
            ['name' => 'Kalimantan Selatan', 'latitude' => -3.0051717700263083, 'longitude' => 115.41976102866265],
            ['name' => 'Kalimantan Tengah', 'latitude' => -1.3453982143063712, 'longitude' => 113.62031324011652],
            ['name' => 'Kalimantan Timur', 'latitude' => 0.4262381743334771, 'longitude' => 116.36101064113295],
            ['name' => 'Kepulauan Riau', 'latitude' => 3.882626149646015, 'longitude' => 108.2115619257446],
            ['name' => 'Lampung', 'latitude' => -4.802189742162128, 'longitude' => 105.00074933715271],
            ['name' => 'Maluku', 'latitude' => -3.1391980637842067, 'longitude' => 129.16109977214532],
            ['name' => 'Maluku Utara', 'latitude' => 0.6686806567503112, 'longitude' => 128.0598617378352],
            ['name' => 'Nusa Tenggara Barat', 'latitude' => -8.79035422553899, 'longitude' => 117.32854118545164],
            ['name' => 'Nusa Tenggara Timur', 'latitude' => -8.71278924297215, 'longitude' => 121.07807858237354],
            ['name' => 'Papua', 'latitude' => -4.1210927201431815, 'longitude' => 138.5774315754718],
            ['name' => 'Riau', 'latitude' => 0.4976418375211864, 'longitude' => 101.6727933315376],
            ['name' => 'Sulawesi Barat', 'latitude' => '-2.2922732617993518', 'longitude' => '119.39070513649035'],
            ['name' => 'Sulawesi Selatan', 'latitude' => '-3.41764789786842', 'longitude' => '119.99487427035812'],
            ['name' => 'Sulawesi Tengah', 'latitude' => '-1.033215560302736', 'longitude' => '120.40116427928928'],
            ['name' => 'Sulawesi Tenggara', 'latitude' => '-3.6939527999813464', 'longitude' => '121.82200587103576'],
            ['name' => 'Sulawesi Utara', 'latitude' => '0.8491965693558683', 'longitude' => '124.41531703835375'], 
            ['name' => 'Sumatera Barat', 'latitude' => -0.5127203384910786, 'longitude' => 100.4996623007227],
            ['name' => 'Sumatera Selatan', 'latitude' => -3.0972210524897577, 'longitude' => 104.12876389600804],
            ['name' => 'Sumatera Utara', 'latitude' => 2.3804515236624866, 'longitude' => 99.10915613457237],
            ['name' => 'Yogyakarta', 'latitude' => -7.89783146983882, 'longitude' => 110.40817415908673],
            
        ];

        DB::table('provinces')->insert($provinces);
    }
}
