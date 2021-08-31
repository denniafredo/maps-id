<?php

namespace App\Http\Controllers\CMS;

use App\Models\Animal;
use App\Models\AnimalImage;
use App\Models\AnimalMapping;
use App\Models\AnimalVideo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CitesStatus;
use App\Models\ClassModel;
use App\Models\ConservationStatus;
use App\Models\Family;
use App\Models\Genus;
use App\Models\Kingdom;
use App\Models\Ordo;
use App\Models\Phylum;
use App\Models\Province;
use App\Models\RedlistStatus;
use App\Models\WeightUnit;
use App\Models\GlobalSetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FaunaController extends Controller
{
    public function index()
    {
        $units = WeightUnit::all();
        $kingdoms = Kingdom::all();
        $phylums = Phylum::all();
        $classes = ClassModel::all();
        $ordos = Ordo::all();
        $families = Family::all();
        $genuses = Genus::all();
        $conservationStatuses = ConservationStatus::all();
        $citesStatuses = CitesStatus::all();
        $redlistStatuses = RedlistStatus::all();
        $provinces = Province::all();
        
        $globalSettings = GlobalSetting::all();

        $lat = $globalSettings->where('name', 'lat')->values()->first()->value;
        $long = $globalSettings->where('name', 'long')->values()->first()->value;
        $zoom = $globalSettings->where('name', 'zoom')->values()->first()->value;

        return view('cms.fauna.index', compact(
            'units',
            'kingdoms',
            'phylums',
            'classes',
            'ordos',
            'families',
            'genuses',
            'conservationStatuses',
            'citesStatuses',
            'redlistStatuses',
            'provinces',
            'lat', 
            'long', 
            'zoom'
        ));
    }

    public function create(Request $request)
    {
        // Required
        $localName = $request->local_name;
        $scientificName = $request->latin_name;
        $labelName = $request->label_name;
        $description = $request->description;
        $bodyLengthMin = $request->body_length_min;
        $bodyLengthMax = $request->body_length_max;
        $height = $request->height;
        $weight = $request->weight;
        $habitat = $request->habitat;

        // Required + Can create if it doesn't exist
        $unit = $request->unit;

        // Not Required + Can create if it doesn't exist
        $kingdom = $request->kingdom;
        $phylum = $request->phylum;
        $class = $request->class;
        $ordo = $request->ordo;
        $family = $request->family;
        $genus = $request->genus;
        $conservationStatus = $request->conservation_status;

        // Not Required
        $tailLength = $request->tail_length;
        $citesStatus = $request->cites_status;
        $redlistStatus = $request->redlist_status;

        // Maps
        try {
            $provinces = json_decode($request->province[0]);
        } catch (\Exception $e) {
            $provinces = [];
            Log::error('[ERROR] CREATE FAUNA (province)');
            Log::error($e->getTraceAsString());
            Log::error('Line: ' . $e->getLine());
        }

        // Files
        $images = $request->file('images');
        $imageContributors = $request->image_contributors;
        $videos = $request->file('videos');
        $videoContributors = $request->video_contributors;

        // Create if it doesn't exists
        if ($unit) {
            $checkUnit = WeightUnit::find($unit);
            if (!$checkUnit) {
                $checkUnit = WeightUnit::create(['name' => $unit]);
            }
            $unitID = $checkUnit->id;
        } else {
            $unitID = null;
        }

        if ($kingdom) {
            $checkKingdom = Kingdom::find($kingdom);
            if (!$checkKingdom) {
                $checkKingdom = Kingdom::create(['name' => $kingdom]);
            }
            $kingdomID = $checkKingdom->id;
        } else {
            $kingdomID = null;
        }

        if ($phylum) {
            $checkPhylum = Phylum::find($phylum);
            if (!$checkPhylum) {
                $checkPhylum = Phylum::create(['name' => $phylum]);
            }
            $phylumID = $checkPhylum->id;
        } else {
            $phylumID = null;
        }

        if ($class) {
            $checkClass = ClassModel::find($class);
            if (!$checkClass) {
                $checkClass = ClassModel::create(['name' => $class]);
            }
            $classID = $checkClass->id;
        } else {
            $classID = null;
        }

        if ($ordo) {
            $checkOrdo = Ordo::find($ordo);
            if (!$checkOrdo) {
                $checkOrdo = Ordo::create(['name' => $ordo]);
            }
            $ordoID = $checkOrdo->id;
        } else {
            $ordoID = null;
        }

        if ($family) {
            $checkFamily = Family::find($family);
            if (!$checkFamily) {
                $checkFamily = Family::create(['name' => $family]);
            }
            $familyID = $checkFamily->id;
        } else {
            $familyID = null;
        }

        if ($genus) {
            $checkGenus = Genus::find($genus);
            if (!$checkGenus) {
                $checkGenus = Genus::create(['name' => $genus]);
            }
            $genusID = $checkGenus->id;
        } else {
            $genusID = null;
        }

        if ($conservationStatus) {
            $checkConservationStatus = ConservationStatus::find($conservationStatus);
            if (!$checkConservationStatus) {
                $checkConservationStatus = ConservationStatus::create(['name' => $conservationStatus]);
            }
            $conservationStatusID = $checkConservationStatus->id;
        } else {
            $conservationStatusID = null;
        }

        try {
            $animal = Animal::create([
                'scientific_name' => $scientificName,
                'local_name' => $localName,
                'label_name' => $labelName,
                'body_height' => $height,
                'body_length_1' => $bodyLengthMin,
                'body_length_2' => $bodyLengthMax,
                'body_tail' => $tailLength,
                'body_weight' => $weight,
                'description' => $description,
                'habitat' => $habitat,
                'weight_unit_id' => $unitID,
                'conservation_id' => $conservationStatusID,
                'cites_id' => $citesStatus,
                'redlist_id' => $redlistStatus,
                'kingdom_id' => $kingdomID,
                'phylum_id' => $phylumID,
                'class_id' => $classID,
                'ordo_id' => $ordoID,
                'family_id' => $familyID,
                'genus_id' => $genusID
            ]);
            $animalID = $animal->id;

            if ($provinces) {
                $listProvinces = Province::all();
                foreach ($provinces as $province) {
                    $provinceID = $listProvinces->where('name', $province->name)->first();

                    if ($provinceID) {
                        $provinceID = $provinceID->id;
                        AnimalMapping::create([
                            'animal_id' => $animal->id,
                            'province_id' => $provinceID,
                            'latitude' => $province->lat,
                            'longitude' => $province->long
                        ]);
                    }
                }
            }

            if ($images) {
                if ($imageContributors[0]) {
                    $imageContributors = explode(',', $imageContributors[0]);
                }

                $counter = 1;
                foreach ($images as $image) {
                    $now = Carbon::now()->format('ymdHis');
                    $extension = '.png';
                    $animalNameFormatted = str_replace(' ', '_', strtolower($localName));
                    $dir = 'public/animal/' . $animalNameFormatted . '/images';
                    $filename = $now . $animalNameFormatted . '_' . $counter . $extension;

                    if (!Storage::disk('animal')->exists($animalNameFormatted . '/images')) {
                        Storage::makeDirectory($dir);
                    }

                    // Insert to Directory
                    Storage::disk('animal')->put($animalNameFormatted . '/images/' . $filename     , file_get_contents($image), 'public');

                    // Insert to DB
                    $url = 'storage/animal/' . $animalNameFormatted . '/images/' . $filename;

                    if ($imageContributors[0]) {
                        $currentContributor = $imageContributors[($counter-1)];
                    } else {
                        $currentContributor = null;
                    }

                    AnimalImage::create([
                        'animal_id' => $animalID,
                        'path' => $url,
                        'filename' => $filename,
                        'contributor' => $currentContributor
                    ]);

                    // Increment Counter
                    $counter++;
                }
            }

            if ($videos) {
                if ($videoContributors[0]) {
                    $videoContributors = explode(',', $videoContributors[0]);
                }

                $counter = 1;
                foreach ($videos as $video) {
                    $now = Carbon::now()->format('ymdHis');
                    $extension = '.mp4';
                    $animalNameFormatted = str_replace(' ', '_', strtolower($localName));
                    $dir = 'public/animal/' . $animalNameFormatted . '/videos';
                    $filename = $now . $animalNameFormatted . '_' . $counter . $extension;

                    if (!Storage::disk('animal')->exists($animalNameFormatted . '/videos')) {
                        Storage::makeDirectory($dir);
                    }

                    // Insert to Directory
                    Storage::disk('animal')->put($animalNameFormatted . '/videos/' . $filename     , file_get_contents($video), 'public');

                    // Insert to DB
                    $url = 'storage/animal/' . $animalNameFormatted . '/videos/' . $filename;

                    if ($videoContributors[0]) {
                        $currentContributor = $videoContributors[($counter-1)];
                    } else {
                        $currentContributor = null;
                    }

                    AnimalVideo::create([
                        'animal_id' => $animalID,
                        'path' => $url,
                        'filename' => $filename,
                        'contributor' => $currentContributor
                    ]);

                    // Increment Counter
                    $counter++;
                }
            }

            $request->session()->flash('success', 'Penambahan data sukses dilakukan. Silahkan TUTUP tab sebelumnya');
        } catch (\Exception $e) {
            Log::error('[Line: ' . $e->getLine() .  '] ' . $e->getTraceAsString());
            $request->session()->flash('error', ' [Line: ' . $e->getLine() .  ']' . $e->getTraceAsString());
        }

        return redirect()->back();
    }
}
