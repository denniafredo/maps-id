<?php

namespace App\Http\Controllers\CMS;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GlobalSetting;

class MapController extends Controller
{
    public function index()
    {
        $globalSettings = GlobalSetting::all();

        $lat = $globalSettings->where('name', 'lat')->values()->first()->value;
        $long = $globalSettings->where('name', 'long')->values()->first()->value;
        $zoom = $globalSettings->where('name', 'zoom')->values()->first()->value;

        return view('cms.map.index', compact('lat', 'long', 'zoom'));
    }

    public function update(Request $request)
    {
        $lat = $request->lat;
        $long = $request->long;
        $zoom = $request->zoom;

        GlobalSetting::updateOrCreate(['name' => 'lat'], ['value' => $lat]);
        GlobalSetting::updateOrCreate(['name' => 'long'], ['value' => $long]);
        GlobalSetting::updateOrCreate(['name' => 'zoom'], ['value' => $zoom]);

        $request->session()->flash('success', 'Data berhasil diubah.');

        return redirect()->back();
    }
}
