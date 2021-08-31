<?php

namespace App\Http\Controllers\APP;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
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
use App\Models\GlobalSetting;
use App\Models\KML;


class IndexController extends Controller
{
    public function index()
    {
       return view('app.index');

    }
    public function getDataAjaxProvince()
    {
        $province = Province::select('id', 'name')->orderBy('name')->get();
        $ret = '';
        foreach ($province as $p){
            $ret .= '<option value="'.$p->id.'">'.$p->name.'</option>';
        }
        return $ret;
    }
    public function getDataAjaxAnimals(Request $request)
    {
      if($request->stateId!=''){
        $animals = Animal::select(
              'animals.id AS uid',
              DB::RAW('CASE WHEN animal_images.deleted_at is null then IFNULL(animal_images.path, "-") else "-" end  AS image_path'),
              DB::RAW('CASE WHEN animal_videos.deleted_at is null then IFNULL(animal_videos.path, "-") else "-" end AS video_path'),
              'local_name AS name',
              'scientific_name',
              'label_name',
              'animals.updated_at AS ut'
          )
          ->Join('animal_mappings', 'animal_mappings.animal_id', '=', 'animals.id')
          ->Join('provinces', 'animal_mappings.province_id', '=', 'provinces.id')
          ->leftJoin('animal_images', 'animal_images.animal_id', '=', 'animals.id')
          ->leftJoin('animal_videos', 'animal_videos.animal_id', '=', 'animals.id')
          ->whereRaw("(provinces.name = '$request->stateId' or animal_mappings.province_id = '$request->stateId') and (local_name like '%$request->animalName%' or label_name like '%$request->animalName%' or scientific_name like '%$request->animalName%') and animals.deleted_at is null and animal_mappings.deleted_at is null")
          ->orderBy('animals.id', 'DESC')
          ->orderBy('animal_images.is_default', 'DESC')
          ->get();
          // provinces.name = '$request->stateId' or
      }
      else{
        $animals = Animal::select(
          'animals.id AS uid',
          DB::RAW('CASE WHEN animal_images.deleted_at is null then IFNULL(animal_images.path, "-") else "-" end  AS image_path'),
          DB::RAW('CASE WHEN animal_videos.deleted_at is null then IFNULL(animal_videos.path, "-") else "-" end AS video_path'),
          'local_name AS name',
          'scientific_name',
          'label_name',
          'animals.updated_at AS ut'
      )
      ->Join('animal_mappings', 'animal_mappings.animal_id', '=', 'animals.id')
      ->leftJoin('animal_images', 'animal_images.animal_id', '=', 'animals.id')
      ->leftJoin('animal_videos', 'animal_videos.animal_id', '=', 'animals.id')
      ->whereRaw("(local_name like '%$request->animalName%' or label_name like '%$request->animalName%' or scientific_name like '%$request->animalName%')  and animals.deleted_at is null  and animal_mappings.deleted_at is null")
      ->orderBy('animals.id', 'DESC')
      ->orderBy('animal_images.is_default', 'DESC')
      ->get();
      }
      

        $animals = $animals->unique('uid');
        $color = ['red','green','blue','yellow','purple','black','cyan'];
        $ret = [];
        $str ='';
        $strMini ='';
        $animalsColor = [];
        $animalsImagePath = [];
        $i=0;
        foreach ($animals as $an){
            if($i==sizeof($color)){
                $i=0;
            }
            if($an->image_path == '-'){
              $an->image_path = url('dist/img/noimg.jpg');
            }
            if($an->video_path == '-'){
              $an->video_path = url('dist/img/novid.jpg');
            }
          $animalsColor[$an->uid] = $color[$i];
          $animalsImagePath[$an->uid] = $an->image_path;

          $c = '\''.$color[$i].'\'';
            $str .= '<div class="form-group col-sm-12 mb-0">
            <span class="fas fa-circle form-control-feedback" style="color:'.$color[$i].'"></span>
            <label class="label-control animal-name">'.$an->label_name.'</label>
            <label class="detail-arrow" onclick="openOverview('.$an->uid.','.$c.')">Selengkapnya<span class="fas fa-arrow-right form-control-feedback arrow"></span></label>
          </div>
          <div class="form-group col-sm-12 row ml-1">';
            $str.=  '<img src="'.$an->image_path.'" class="rounded animal-image" data-toggle="modal" data-target="#zoomImage" onclick="loadZoomImage('.$an->uid.')">';
            $str.=  '<div class="overlayWrapper rounded" data-toggle="modal" data-target="#zoomVideo" onclick="loadZoomVideo('.$an->uid.')">
              <div class="overlay"><span class="fas fa-play-circle overlayicon"></span></div>
              <video class="rounded animal-video" >
                <source src="'.$an->video_path.'" type="video/mp4">
              </video>
            </div>';
          $str.= '</div>';
          $strMini .= '<div class="form-group justify-content-center text-left pl-3 pb-3 m-0" data-toggle="tooltip" data-placement="right" title="'.$an->label_name.'" onclick="openOverview('.$an->uid.','.$c.')">
              <span class="fas fa-circle form-control-feedback" style="color:'.$color[$i].';font-size:75%" ></span>
              <label class="label-control m-0">'.$an->label_name.'</label>
              <br>
              <img width="100px" src="'.$an->image_path.'" alt="" class="rounded">
            </div>';
          $i++;
        }
        array_push($ret,$str);
        array_push($ret,$strMini);

    // coordinate
        if($request->stateId!=''){
            $animals = Animal::select(
              'animals.id AS uid',
              'local_name AS name',
              'scientific_name',
              'label_name',
              'animal_mappings.latitude AS latitude',
              'animal_mappings.longitude AS longitude',
              'provinces.name AS state'

          )
          ->Join('animal_mappings', 'animal_mappings.animal_id', '=', 'animals.id')
          ->Join('provinces', 'provinces.id', '=', 'animal_mappings.province_id')
          ->whereRaw("(provinces.name = '$request->stateId' or animal_mappings.province_id = '$request->stateId') and (local_name like '%$request->animalName%' or label_name like '%$request->animalName%' or scientific_name like '%$request->animalName%')  and animals.deleted_at is null  and animal_mappings.deleted_at is null")
          ->orderBy('animals.id', 'DESC')
          ->get();
        }
        else{
          $animals = Animal::select(
            'animals.id AS uid',
            'local_name AS name',
            'scientific_name',
            'label_name',
            'animal_mappings.latitude AS latitude',
            'animal_mappings.longitude AS longitude',
            'provinces.name AS state'
        )
        ->Join('animal_mappings', 'animal_mappings.animal_id', '=', 'animals.id')
        ->Join('provinces', 'provinces.id', '=', 'animal_mappings.province_id')
        ->whereRaw("(local_name like '%$request->animalName%' or label_name like '%$request->animalName%' or scientific_name like '%$request->animalName%') and animals.deleted_at is null  and animal_mappings.deleted_at is null")
        ->orderBy('animals.id', 'DESC')
        ->get();
        }
        for ($i=0; $i<sizeof($animals); $i++){
          $animals[$i]->color = $animalsColor[$animals[$i]->uid];
          $animals[$i]->image_path = $animalsImagePath[$animals[$i]->uid];
        }
        array_push($ret,$animals);
        return $ret;
    }

    public function getDataAjaxModalImage(Request $request)
    {
        $animals = Animal::select(
            'animals.id AS uid',
            DB::RAW('IFNULL(animal_images.path, "-") AS image_path'),
            'local_name AS name',
            'scientific_name',
            DB::RAW('IFNULL(animal_images.contributor, "-") AS contributor')
        )
        ->Join('animal_images', 'animal_images.animal_id', '=', 'animals.id')
        ->orderBy('animals.id', 'DESC')
        ->where('animals.id',$request->id)
        ->whereRaw('animal_images.deleted_at is null')        
        ->get();
        
        return $animals;
    }
    public function getDataAjaxModalVideo(Request $request)
    {
        $animals = Animal::select(
            'animals.id AS uid',
            DB::RAW('IFNULL(animal_videos.path, "-") AS video_path'),
            'local_name AS name',
            'scientific_name',
            DB::RAW('IFNULL(animal_videos.contributor, "-") AS contributor')
        )
        ->Join('animal_videos', 'animal_videos.animal_id', '=', 'animals.id')
        ->orderBy('animals.id', 'DESC')
        ->where('animals.id',$request->id)
        ->whereRaw('animal_videos.deleted_at is null')        
        ->get();
        
        return $animals;
    }
    public function getDataAjaxDetail(Request $request)
    {
        $animals = Animal::select(
          'animals.id AS uid',
          DB::RAW('IFNULL(animals.local_name, "-") AS local_name'),
          DB::RAW('IFNULL(animals.scientific_name, "-") AS scientific_name'),
          DB::RAW('IFNULL(animals.label_name, "-") AS label_name'),
          DB::RAW('IFNULL(animals.body_height, "-") AS body_height'),
          DB::RAW('IFNULL(animals.body_length_1, "-") AS body_length_1'),
          DB::RAW('IFNULL(animals.body_length_2, "-") AS body_length_2'),
          DB::RAW('IFNULL(animals.body_tail, "-") AS body_tail'),
          DB::RAW('IFNULL(animals.body_weight, "-") AS body_weight'),
          DB::RAW('IFNULL(animals.description, "-") AS description'),
          DB::RAW('IFNULL(animals.habitat, "-") AS habitat'),
          DB::RAW('IFNULL(weight_units.name, "-") AS weight_unit'),
          DB::RAW('IFNULL(conservation_statuses.name, "-") AS conservation_status'),
          DB::RAW('IFNULL(cites_statuses.name, "-") AS cites_status'),
          DB::RAW('IFNULL(redlist_statuses.name, "-") AS redlist_status'),
          DB::RAW('IFNULL(redlist_statuses.code, "-") AS redlist_code'),
          DB::RAW('IFNULL(redlist_statuses.image, "-") AS redlist_image_path'),
          DB::RAW('IFNULL(kingdoms.name, "-") AS kingdom'),
          DB::RAW('IFNULL(phylums.name, "-") AS phylum'),
          DB::RAW('IFNULL(classes.name, "-") AS class'),
          DB::RAW('IFNULL(ordos.name, "-") AS ordo'),
          DB::RAW('IFNULL(families.name, "-") AS family'),
          DB::RAW('IFNULL(genuses.name, "-") AS genus'),
          DB::RAW('CASE WHEN animal_images.deleted_at is null then IFNULL(animal_images.path, "-") else "-" end  AS image_path'),
          DB::RAW('CASE WHEN animal_videos.deleted_at is null then IFNULL(animal_videos.path, "-") else "-" end AS video_path')
        )
        ->leftJoin('weight_units', 'animals.weight_unit_id', '=', 'weight_units.id')
        ->leftJoin('conservation_statuses', 'animals.conservation_id', '=', 'conservation_statuses.id')
        ->leftJoin('cites_statuses', 'animals.cites_id', '=', 'cites_statuses.id')
        ->leftJoin('redlist_statuses', 'animals.redlist_id', '=', 'redlist_statuses.id')
        ->leftJoin('kingdoms', 'animals.kingdom_id', '=', 'kingdoms.id')
        ->leftJoin('phylums', 'animals.phylum_id', '=', 'phylums.id')
        ->leftJoin('classes', 'animals.class_id', '=', 'classes.id')
        ->leftJoin('ordos', 'animals.ordo_id', '=', 'ordos.id')
        ->leftJoin('families', 'animals.family_id', '=', 'families.id')
        ->leftJoin('genuses', 'animals.genus_id', '=', 'genuses.id')
        ->leftJoin('animal_videos', 'animals.id', '=', 'animal_videos.animal_id')
        ->leftJoin('animal_images', 'animals.id', '=', 'animal_images.animal_id')
        ->orderBy('animals.id', 'DESC')
        ->orderBy('animal_images.is_default', 'DESC')
        ->where('animals.id',$request->id)
        ->first();

        $border = '';
        if($animals->image_path == '-'){
          $animals->image_path = url('dist/img/noimg.jpg');
        }
        if($animals->video_path == '-'){
          $animals->video_path = url('dist/img/novid.jpg');
        }

        $str = '
        <div class="form-group col-sm-12 mt-2 pb-2 p-0" style="border-bottom: 1px solid #BDBDBD;">
        <span class="fas fa-arrow-left form-control-feedback arrow-back" onclick="closeOverview()"></span>
        <label id="label-name" class="label-control animal-name m-0">'.$animals->label_name.'</label>
      </div>
      <div class="form-group col-sm-12 p-0 m-0 text-center">
        <h1 id="local-name" class="label-control animal-name-title">'.$animals->local_name.'</h1>
      </div>
      <div class="form-group col-sm-12 p-0 m-0 text-center">
        <h1 id="scientific-name" class="label-control animal-science-name">'.$animals->scientific_name.'</h1>
      </div>
      <div class="d-block scrollbar-overview">
        <div class="form-group col-sm-12 p-0 m-0">
          <div class="overview-content row">
            <label class="label-control col-sm-5 animal-attr text-left">Tinggi Badan</label>
            <label class="label-control col-sm-6 animal-attr-value text-right">'.$animals->body_height.'</label>
          </div>
          <div class="overview-content row">
            <label class="label-control col-sm-5 animal-attr text-left">Ukuran Badan</label>
            <label class="label-control col-sm-6 animal-attr-value text-right">'.$animals->body_length_1.'-'.$animals->body_length_2.'</label>
          </div>
          <div class="overview-content row">
            <label class="label-control col-sm-5 animal-attr text-left">Panjang Ekor</label>
            <label class="label-control col-sm-6 animal-attr-value text-right">'.$animals->body_tail.'</label>
          </div>
          <div class="overview-content row">
            <label class="label-control col-sm-5 animal-attr text-left">Berat Badan</label>
            <label class="label-control col-sm-6 animal-attr-value text-right">'.$animals->body_weight.' '.$animals->weight_unit.'</span></label>
          </div>
          <div class="overview-content row">
            <label class="label-control col-sm-5 animal-attr text-left">Habitat</label>
            <label id="habitat" class="label-control col-sm-6 animal-attr-value text-right">'.$animals->habitat.'</label>
          </div>
          <div class="overview-content row">
            <label class="label-control col-sm-5 animal-attr text-left">Status Konsevasi</label>
            <label id="conservation-status" class="label-control col-sm-6 animal-attr-value text-right">'.$animals->conservation_status.'</label>
          </div>
        </div>
        <div class="form-group col-sm-12 p-0 m-0 mt-3 pb-2 text-center  justify-content-center row" style="border-bottom: 1px solid #BDBDBD;">';
        if($animals->cites_status!='-' && $animals->redlist_status!='-'){
          $border = 'style="border-left: 1px solid #BDBDBD;"';
        }
        if($animals->cites_status!='-'){
          $str.= '<div class="col-sm-6" >
            <img id="img-cites-status" src="'.url('dist/img/logo/CITES.jpg').'" height="50px">
            <label id="cites-status" class="label-control mt-1">'.$animals->cites_status.'</label>
          </div>';
        }
        if($animals->redlist_status !='-'){
          $str.= '<div class="col-sm-6" '.$border.'>
            <img src="'.url('dist/img/logo/red-list.png').'" height="50px" width="50px">
            <img id="img-redlist-status" src="'.$animals->redlist_image_path.'" heigth="50px" width="50px">
            <label id="redlist-status" class="label-control mt-1">'.$animals->redlist_status.'</label>
          </div>';
        }
        $str.='</div>
        <div class="form-group col-sm-12 mt-2 pb-2 mb-2 p-0" style="border-bottom: 1px solid #BDBDBD;">
          <div class="overview-content">
            <label class="label-control animal-attr-value mb-1">Deskripsi Singkat Satwa</label>
          </div>
          <div class="overview-content">
            <label id="description" class="label-control animal-attr text-justify" style="width: 95%">
            '.$animals->description.'
            </label>
          </div>
        </div>
        <div class="form-group col-sm-12 p-0 m-0 pb-2 mb-2" style="border-bottom: 1px solid #BDBDBD;">
          <div class="overview-content mb-1">
            <label class="label-control animal-attr-value text-left">Klasifikasi Ilmiah</label>
          </div>
          <div class="overview-content row">
            <label class="label-control col-sm-5 animal-attr text-left">Kingdom</label>
            <label id="kingdom" class="label-control col-sm-6 animal-attr-value text-right font-italic">'.$animals->kingdom.'</label>
          </div>
          <div class="overview-content row">
            <label class="label-control col-sm-5 animal-attr text-left">Filum</label>
            <label id="phylum" class="label-control col-sm-6 animal-attr-value text-right font-italic">'.$animals->phylum.'</label>
          </div>
          <div class="overview-content row">
            <label class="label-control col-sm-5 animal-attr text-left">Kelas</label>
            <label id="class" class="label-control col-sm-6 animal-attr-value text-right font-italic">'.$animals->class.'</label>
          </div>
          <div class="overview-content row">
            <label class="label-control col-sm-5 animal-attr text-left">Ordo</label>
            <label id="ordo" class="label-control col-sm-6 animal-attr-value text-right font-italic">'.$animals->ordo.'</label>
          </div>
          <div class="overview-content row">
            <label class="label-control col-sm-5 animal-attr text-left">Family</label>
            <label id="family" class="label-control col-sm-6 animal-attr-value text-right  font-italic">'.$animals->family.'</label>
          </div>
          <div class="overview-content row">
            <label class="label-control col-sm-5 animal-attr text-left">Genus</label>
            <label id="genus" class="label-control col-sm-6 animal-attr-value text-right font-italic">'.$animals->genus.'</label>
          </div>
        </div>
        <div class="form-group col-sm-12 p-0 m-0">
          <div class="overview-content mb-2">
            <label class="label-control animal-attr-value text-left">Gallery Foto dan Video</label>
          </div>
          <div class="form-group col-sm-12 row" style="padding-left: 7%;">';
            $str.=  '<img src="'.$animals->image_path.'" class="rounded animal-image" data-toggle="modal" data-target="#zoomImage" onclick="loadZoomImage('.$animals->uid.')">';
            $str.=  '<div class="overlayWrapper rounded" data-toggle="modal" data-target="#zoomVideo" onclick="loadZoomVideo('.$animals->uid.')">
              <div class="overlay"><span class="fas fa-play-circle overlayicon"></span></div>
              <video class="rounded animal-video" >
                <source src="'.$animals->video_path.'" type="video/mp4">
              </video>
            </div>';
          $str .= '</div>
                  </div>
                </div>';
      $ret=[];
      $animalsImagePath = $animals->image_path;
      array_push($ret,$str);

      $animals = Animal::select(
          'animals.id AS uid',
          'local_name AS name',
          'scientific_name',
          'label_name',
          'animal_mappings.latitude AS latitude',
          'animal_mappings.longitude AS longitude',
          'provinces.name AS state'
      )
      ->Join('animal_mappings', 'animal_mappings.animal_id', '=', 'animals.id')
      ->Join('provinces', 'provinces.id', '=', 'animal_mappings.province_id')
      ->where('animals.id',$request->id)
      ->whereRaw('animal_mappings.deleted_at is null')
      ->orderBy('animals.id', 'DESC')
      ->get();

    for ($i=0; $i<sizeof($animals); $i++){
      $animals[$i]->color = $request->color;
      $animals[$i]->name = $request->name;
      $animals[$i]->image_path = $animalsImagePath;
    }
    array_push($ret,$animals);
        
    return $ret;
    }

    public function getDataAjaxZoomIn()
    {
      $globalSettings = GlobalSetting::all();

      $lat = $globalSettings->where('name', 'lat')->values()->first()->value;
      $long = $globalSettings->where('name', 'long')->values()->first()->value;
      $zoom = $globalSettings->where('name', 'zoom')->values()->first()->value;
      return compact('lat', 'long', 'zoom');
    }

    public function getDataAjaxKml()
    {
      $kmls = KML::select('id', 'path')->get();
      
      return $kmls;
    }
    
}