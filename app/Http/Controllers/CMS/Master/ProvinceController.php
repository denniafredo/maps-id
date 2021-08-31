<?php

namespace App\Http\Controllers\CMS\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Province;
use Yajra\Datatables\Datatables;

class ProvinceController extends Controller
{
    public function index()
    {
        return view('cms.master.province-index');
    }

    public function getDataAjax()
    {
        $kingdoms = Province::select('id', 'name','longitude','latitude')->get();

        return Datatables::of($kingdoms)
            ->addColumn('edit', function ($kingdoms) {
                return '<button class="btn btn-sm btn-info btn-edit-taxonomic" uid="' . $kingdoms->id . '" taxonomic_name="' . $kingdoms->name . '" taxonomic_long="' . $kingdoms->longitude . '" taxonomic_lat="' . $kingdoms->latitude . '"> Ubah Data </button>';
            })
            ->rawColumns(['edit'])
            ->make(true);
    }

    public function create(Request $request)
    {
        $taxonimic = $request->taxonomic;
        $latitude = $request->lat;
        $longitude = $request->long;
        $check = Province::firstOrNew([
            'name' => $taxonimic,
            'latitude' => $latitude,
            'longitude' => $longitude
        ]);
        
        if ($check->exists) {
            $request->session()->flash('error', 'Data sudah ada!');
        } else if($longitude=='' || $latitude=='') {
            $request->session()->flash('error', 'Titik tengah provinsi belum dipilih!');
        } else {
            $check->save();
            $request->session()->flash('success', 'Penambahan data sukses dilakukan.');
        }
        
        return redirect()->back();
    }

    public function update(Request $request)
    {
        $id = $request->uid;
        $province = $request->taxonomic;
        $latitude = $request->lat;
        $longitude = $request->long;

        $check = Province::find($id);

        if ($check->name != $province) {
            $checkCount = Province::where('name', $province)->count(); 

            if ($checkCount >= 1) {
                $request->session()->flash('error', 'Data sudah ada!');
            } else {
                Province::where('id', $id)->update(['name' => $province, 'longitude' => $longitude, 'latitude' => $latitude]);
                $request->session()->flash('success', 'Data berhasil diubah.');
            }
        } else {
            Province::where('id', $id)->update(['longitude' => $longitude, 'latitude' => $latitude]);
            $request->session()->flash('success', 'Data berhasil diubah.');
        }

        return redirect()->back();
    }
}
