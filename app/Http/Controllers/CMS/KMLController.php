<?php

namespace App\Http\Controllers\CMS;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use App\Models\KML;

class KMLController extends Controller
{
    public function index()
    {
        return view('cms.kml.index');
    }

    public function getDataAjax()
    {
        $kmls = KML::select('id', 'path')->get();

        return Datatables::of($kmls)
            ->addColumn('delete', function ($kmls) {
                return '<button class="btn btn-sm btn-danger btn-delete-kml" uid="' . $kmls->id . '"> Hapus Data </button>';
            })
            ->rawColumns(['delete'])
            ->make(true);
    }

    public function create(Request $request)
    {
        try {
            // Files
            $kmls = $request->file('kmls');

            if ($kmls) {
                foreach ($kmls as $kml) {
                    $filename = $kml->getClientOriginalName();

                    // Insert to Directory
                    Storage::disk('kml')->put($filename, file_get_contents($kml), 'public');

                    // Insert to DB
                    $url = 'storage/kml/' . $filename;

                    KML::updateOrCreate([
                        'path' => $url
                    ]);
                }
            }

            $request->session()->flash('success', 'Penambahan data sukses dilakukan.');
        } catch (\Exception $e) {
            Log::error('[Line: ' . $e->getLine() .  '] ' . $e->getTraceAsString());
            $request->session()->flash('error', ' [Line: ' . $e->getLine() .  ']' . $e->getTraceAsString());
        }
        
        return redirect()->back();
    }

    public function deleteAjax(Request $request)
    {
        $uid = $request->uid;
        try {
            $kml = KML::find($uid);
            if ($kml) {
                Storage::disk('kml')->delete($kml->path);
                $kml->delete();
            }

            return response()->json(['status'=> 'OK']);
        } catch (\Exception $e) {
            Log::error('[Line: ' . $e->getLine() .  '] ' . $e->getTraceAsString());
            
            return response()->json(['status'=> 'ERROR']);
        }
    }
}
