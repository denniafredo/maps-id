<?php

namespace App\Http\Controllers\CMS\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ordo;
use Yajra\Datatables\Datatables;

class OrdoController extends Controller
{
    public function index()
    {
        return view('cms.master.ordo-index');
    }

    public function getDataAjax()
    {
        $kingdoms = Ordo::select('id', 'name')->get();

        return Datatables::of($kingdoms)
            ->addColumn('edit', function ($kingdoms) {
                return '<button class="btn btn-sm btn-info btn-edit-taxonomic" uid="' . $kingdoms->id . '" taxonomic_name="' . $kingdoms->name . '"> Ubah Data </button>';
            })
            ->rawColumns(['edit'])
            ->make(true);
    }

    public function create(Request $request)
    {
        $taxonimic = $request->taxonomic;
        
        $check = Ordo::firstOrNew([
            'name' => $taxonimic
        ]);

        if ($check->exists) {
            $request->session()->flash('error', 'Data sudah ada!');
        } else {
            $check->save();
            $request->session()->flash('success', 'Penambahan data sukses dilakukan.');
        }

        return redirect()->back();
    }

    public function update(Request $request)
    {
        $id = $request->uid;
        $taxonimic = $request->taxonomic;

        $check = Ordo::where('name', $taxonimic)->count();

        if ($check >= 1) {
            $request->session()->flash('error', 'Data sudah ada!');
        } else {$check = Ordo::where('id', $id)->update(['name' => $taxonimic]);
            $request->session()->flash('success', 'Data berhasil diubah.');
        }

        return redirect()->back();
    }
}
