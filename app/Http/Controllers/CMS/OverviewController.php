<?php

namespace App\Http\Controllers\CMS;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;

use App\Models\Animal;
use App\Models\AnimalImage;
use App\Models\AnimalMapping;
use App\Models\AnimalVideo;
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
use Exception;

class OverviewController extends Controller
{
    public function index()
    {
        $animals = Animal::all();
        $animalCount = $animals->count();

        return view('cms.overview.index', compact('animalCount'));
    }

    public function getDataAjax()
    {
        $animals = Animal::select(
            'animals.id AS uid',
            DB::RAW('IFNULL(animal_images.path, "-") AS path'),
            'local_name AS name',
            'scientific_name',
            DB::RAW('IFNULL(conservation_statuses.name, "-") AS conservation'),
            'animals.updated_at AS ut'
        )
        ->leftJoin('conservation_statuses', 'conservation_statuses.id', '=', 'animals.conservation_id')
        ->leftJoin('animal_images', 'animal_images.animal_id', '=', 'animals.id')
        ->whereNull('animal_images.deleted_at')
        ->orderBy('animals.id', 'DESC')
        ->orderBy('animal_images.is_default', 'DESC')
        ->get();

        // Karena groupBy di laravel itu defaulnya beda, pake unique saja
        $animals = $animals->unique('uid');

        return Datatables::of($animals)
        ->addColumn('name', function ($animals) {
            $path = str_replace('/cms/', '/', $animals->path);
            $uid = Crypt::encryptString($animals->uid);

            if ($animals->path == '-') {
                $path = url('default_empty_image.png');
            } else {
                $path = url(str_replace('/cms/', '/', $animals->path));
            }

            return '
            <div class="user-block" uid="' . $uid . '">
                <img class="img-circle" src="' . $path . '" alt="User Image">
                <span class="username">' . $animals->name . '</span>
                <span class="description">' . $animals->scientific_name . '</span>
            </div>
            ';
        })
        ->addColumn('updated_at', function ($animals) {
            return Carbon::parse($animals->ut)->format('d-m-Y H:i:s');
        })
        ->addColumn('delete', function ($animals) {
            return '<button class="btn btn-sm btn-danger btn-delete-animal" uid="' . $animals->uid .'"> Hapus </button>';
        })
        ->rawColumns(['name', 'updated_at', 'delete'])
        ->make(true);
    }

    public function updateIndex(Request $request)
    {
        $uid = $request->uid;
        $page = $request->page;

        try {
            $animalID = Crypt::decryptString($uid);

            if ($page == 1) {
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

                $animal = Animal::where('id', $animalID)->first();
    
                return view('cms.overview.update.index1', compact(
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
                    
                    'animal',
                    'uid'
                ));
            } else if ($page == 2) {
                $animal = Animal::where('id', $animalID)->first();
                $provinces = Province::all();
                $animalMappings = AnimalMapping::select(
                    'provinces.name', 
                    'animal_mappings.longitude', 
                    'animal_mappings.latitude'
                )
                ->join('provinces', 'provinces.id', '=', 'animal_mappings.province_id')
                ->where('animal_id', $animal->id)
                ->get();

                return view('cms.overview.update.index2', compact(                    
                    'provinces',                    
                    'animal',
                    'uid',
                    'animalMappings'
                ));
            } else if ($page == 3) {
                $animal = Animal::where('id', $animalID)->first();
                $animalImageDefaultID = AnimalImage::where('animal_id', $animalID)->where('is_default', true)->first();
                $animalImageDefaultID = $animalImageDefaultID ? $animalImageDefaultID->id : null;
                $provinces = Province::all();
                $animalMappings = AnimalMapping::select(
                    'provinces.name', 
                    'animal_mappings.longitude', 
                    'animal_mappings.latitude'
                )
                ->join('provinces', 'provinces.id', '=', 'animal_mappings.province_id')
                ->where('animal_id', $animal->id)
                ->get();

                $animalImage = AnimalImage::where('animal_id', $animalID)->get();
                $detailImage = [];
                if (count($animalImage) > 0) {
                    $temp = [];
                    foreach ($animalImage as $image) {
                        $temp['caption'] = $image->filename;
                        $temp['path'] = url($image->path);
                        $temp['key'] = 'image_' . $image->id;
                        $temp['contributor'] = $image->contributor == null ? '' : str_replace("'", "|||", $image->contributor);
                        $temp['id'] = $image->id;
                        array_push($detailImage, $temp);
                    }
                }
                $detailImage = json_encode(json_encode($detailImage)); // encode twice is needed
                
                $animalVideo = AnimalVideo::where('animal_id', $animalID)->get();
                $detailVideo = [];
                if (count($animalVideo) > 0) {
                    $temp = [];
                    foreach ($animalVideo as $video) {
                        $temp['caption'] = $video->filename;
                        $temp['path'] = url($video->path);
                        $temp['key'] = 'video_' . $video->id;
                        $temp['contributor'] = $video->contributor == null ? '' : str_replace("'", "|||", $video->contributor);
                        $temp['id'] = $video->id;
                        array_push($detailVideo, $temp);
                    }
                }
                $detailVideo = json_encode(json_encode($detailVideo)); // encode twice is needed

                return view('cms.overview.update.index3', compact(                    
                    'provinces',                    
                    'animal',
                    'uid',
                    'animalMappings',
                    'detailImage',
                    'detailVideo',
                    'animalImageDefaultID'
                ));
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }

    public function update(Request $request)
    {
        $uid = $request->uid;
        $page = $request->page;

        try {
            $animalID = Crypt::decryptString($uid);
        } catch (\Exception $e) {
            abort(404);
        }

        if ($page == 1) {
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
                $animal = Animal::where('id', $animalID)->update([
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
                $request->session()->flash('success', 'Perubahan data sukses dilakukan');
            } catch (\Exception $e) {
                Log::error('[Line: ' . $e->getLine() .  '] ' . $e->getTraceAsString());
                $request->session()->flash('error', ' [Line: ' . $e->getLine() .  ']' . $e->getTraceAsString());
            }

            return redirect()->back();
        } else if ($page == 2) {
            // Maps
            try {
                $provinces = json_decode($request->province[0]);
            } catch (\Exception $e) {
                $provinces = [];
                Log::error('[ERROR] CREATE FAUNA (province)');
                Log::error($e->getTraceAsString());
                Log::error('Line: ' . $e->getLine());
            }

            try {
                if ($provinces) {
                    $listProvinces = Province::all();
                    $animalMappings = AnimalMapping::where('animal_id', $animalID)->get();
                    $animalMappingIDs = [];
                    if (count($animalMappings) > 0) {
                        $animalMappingIDs = $animalMappings->pluck('id')->toArray();
                    }

                    foreach ($provinces as $province) {
                        $provinceID = $listProvinces->where('name', $province->name)->first();

                        if ($provinceID) {
                            $provinceID = $provinceID->id;
                            $check = AnimalMapping::firstOrNew([
                                'animal_id' => $animalID,
                                'province_id' => $provinceID,
                                'latitude' => $province->lat,
                                'longitude' => $province->long
                            ]);

                            if (!$check->exists) {
                                $check->save();
                            }

                            if (count($animalMappingIDs) > 0) {
                                if (($key = array_search($check->id, $animalMappingIDs)) !== false) {
                                    unset($animalMappingIDs[$key]);
                                }
                            }
                        }

                        if (count($animalMappingIDs) > 0) {
                            AnimalMapping::whereIn('id', $animalMappingIDs)->delete();
                        }
                    }
                } else {
                    AnimalMapping::where('animal_id', $animalID)->delete();
                }
                $request->session()->flash('success', 'Perubahan data sukses dilakukan');
            } catch (\Exception $e) {
                Log::error('[Line: ' . $e->getLine() .  '] ' . $e->getTraceAsString());
                $request->session()->flash('error', ' [Line: ' . $e->getLine() .  ']' . $e->getTraceAsString());
            }

            return redirect()->back();
        } else if ($page == 3) {
            try {
                $animal = Animal::where('id', $animalID)->first();
                $localName = $animal->local_name;
                // Files
                $images = $request->file('images');
                $imageContributors = $request->image_contributors;
                $oldImageContributors = $request->old_image_contributors;
                $videos = $request->file('videos');
                $videoContributors = $request->video_contributors;
                $oldVideoContributors = $request->old_video_contributors;
                $defaultImage = $request->default_image;
                $isDefaultImageFromOld = true;

                if ($defaultImage) {
                    if (strpos($defaultImage, 'old_') !== false) {
                        $defaultImage = str_replace('old_', '', $defaultImage);
                    } else {
                        $isDefaultImageFromOld = false;
                        $defaultImage = str_replace('new_', '', $defaultImage);
                    }
                }

                if ($images) {
                    // Reset default Image
                    AnimalImage::query()->update(['is_default' => false]);

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

                        $animalImage = AnimalImage::create([
                            'animal_id' => $animalID,
                            'path' => $url,
                            'filename' => $filename,
                            'contributor' => $currentContributor
                        ]);

                        // Update default Image
                        if ($isDefaultImageFromOld == false) {
                            if ($counter == $defaultImage) {
                                AnimalImage::where('id', $animalImage->id)->update(['is_default' => true]);
                            }
                        }

                        // Increment Counter
                        $counter++;
                    }
                }

                if ($isDefaultImageFromOld) {
                    // Reset default Image
                    AnimalImage::query()->update(['is_default' => false]);

                    // Update default Image
                    AnimalImage::where('id', $defaultImage)->update(['is_default' => true]);
                }

                if ($oldImageContributors) {
                    $oldImageContributors = json_decode($oldImageContributors[0]);

                    if (count($oldImageContributors) > 0) {
                        foreach ($oldImageContributors as $oldImageContributor) {
                            AnimalImage::where('id', $oldImageContributor->id)->update(['contributor' => $oldImageContributor->contributor]);
                        }
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

                if ($oldVideoContributors) {
                    $oldVideoContributors = json_decode($oldVideoContributors[0]);

                    if (count($oldVideoContributors) > 0) {
                        foreach ($oldVideoContributors as $oldVideoContributor) {
                            AnimalVideo::where('id', $oldVideoContributor->id)->update(['contributor' => $oldVideoContributor->contributor]);
                        }
                    }
                }
                
                $request->session()->flash('success', 'Perubahan data sukses dilakukan');
            } catch (\Exception $e) {
                Log::error('[Line: ' . $e->getLine() .  '] ' . $e->getTraceAsString());
                $request->session()->flash('error', ' [Line: ' . $e->getLine() .  ']' . $e->getTraceAsString());
            }

            return redirect()->back();
        }
    }

    public function deletePhoto(Request $request)
    {
        $key = $request->key;

        if(strpos($key, 'image_') !== false) {
            $key = str_replace('image_', '', $key);

            $animal = AnimalImage::find($key);
            if ($animal) {
                $path = str_replace('storage/animal/', '', $animal->path);
                Storage::disk('animal')->delete($path);
                $animal->update(['is_default' => false]);
                $animal->delete();
            }
        } else if(strpos($key, 'video_') !== false) {
            $key = str_replace('video_', '', $key);

            $animal = AnimalVideo::find($key);
            if ($animal) {
                $path = str_replace('storage/animal/', '', $animal->path);
                Storage::disk('animal')->delete($path);
                $animal->delete();
            }
        }

        return response()->json(['status' => 'ok']);
    } 

    public function deleteAnimalAjax(Request $request)
    {
        $animalID = $request->uid;

        try {
            // Delete Image
            $animalImages = AnimalImage::where('animal_id', $animalID)->get();
            if (count($animalImages) > 0) {
                $animalImageIDs = $animalImages->pluck('id');

                foreach ($animalImages as $image) {
                    $path = str_replace('storage/animal/', '', $image->path);
                    Storage::disk('animal')->delete($path);
                }
                
                AnimalImage::whereIn('animal_id', $animalImageIDs)->update(['is_default' => false]);
                AnimalImage::whereIn('animal_id', $animalImageIDs)->delete();
            }

            // Delete Video
            $animalVideo = AnimalVideo::where('animal_id', $animalID)->get();
            if (count($animalVideo) > 0) {
                $animalImageIDs = $animalVideo->pluck('id');

                foreach ($animalVideo as $image) {
                    $path = str_replace('storage/animal/', '', $image->path);
                    Storage::disk('animal')->delete($path);
                }
                
                AnimalVideo::whereIn('animal_id', $animalImageIDs)->delete();
            }

            // Delete Animal Mapping
            AnimalMapping::where('animal_id', $animalID)->delete();

            // Delete Animal
            Animal::find($animalID)->delete();

            return response()->json(['status' => 'OK']);
        } catch (\Exception $e) {
            Log::error('[Line: ' . $e->getLine() .  '] ' . $e->getTraceAsString());
            return response()->json(['status' => 'Error']);
        }
    }
}
