<?php

namespace App\Http\Controllers\CMS;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use Yajra\Datatables\Datatables;
use Auth;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $roleID = $user->role_id;

        $roles = Role::
        when($roleID, function($query, $roleID) {
            if ($roleID == 2) {
                return $query->where('id', '!=', 1);
            } else if ($roleID == 3) {
                return $query->whereNotIn('id', [1, 2]);
            } else {
                // Do Nothing
            }
        })->get();

        return view('cms.user.index', compact('roles'));
    }

    public function getDataAjax(Request $request)
    {
        $user = Auth::user();
        $roleID = $user->role_id;

        $users = User::select('users.id as uid', 'roles.id as rid', 'role', 'name', 'email', 'updated_at')
        ->join('roles', 'roles.id', '=', 'users.role_id')
        ->when($roleID, function($query, $roleID) {
            if ($roleID == 2) {
                return $query->where('role_id', '!=', 1);
            } else if ($roleID == 3) {
                return $query->whereNotIn('role_id', [1, 2]);
            } else {
                // Do Nothing
            }
        })->get();

        return Datatables::of($users)
        ->addColumn('edit', function ($users) {
            return '<button class="btn btn-sm btn-info btn-edit-user" uid="' . $users->uid .'" rid="' . $users->rid . '"> Ubah </button>';
        })
        ->rawColumns(['edit'])
        ->make(true);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'role_id' => 'required|numeric',
            'name' => 'required|max:50',
            'email' => 'required|email|max:100',
            'password' => 'required|confirmed|min:6',
        ]);
        
        $roleID = $request->role_id;
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;

        try {
            User::create([
                'role_id' => $roleID,
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password)
            ]);

            $request->session()->flash('success', 'Penambahan user sukses dilakukan.');
        } catch (\Exception $e) {
            Log::error('[Line: ' . $e->getLine() .  '] ' . $e->getTraceAsString());
            $request->session()->flash('error', ' [Line: ' . $e->getLine() .  ']' . $e->getTraceAsString());
        }

        return redirect()->back();
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'role_id' => 'required|numeric',
            'name' => 'required|max:50',
            'email' => 'required|email|max:100'
        ]);
        
        $userID = $request->uid;
        $roleID = $request->role_id;
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;

        if ($password) {
            $this->validate($request, [
                'password' => 'required|confirmed|min:6'
            ]);
        }

        $checkExist = User::where('email', $email)->first();

        if ($checkExist && $checkExist->id != $userID) {
            $request->session()->flash('error-update', 'Email sudah dipakai!');
        } else {
            try {
                if (Auth::user()->role_id == 3 && $userID != Auth::id()) {
                    $request->session()->flash('error-update', 'Tidak bisa merubah data Admin lain!');
                } else {
                    if (!$password) {
                        User::where('id', $userID)->update([
                            'role_id' => $roleID,
                            'name' => $name,
                            'email' => $email
                        ]);
                    } else {
                        User::where('id', $userID)->update([
                            'role_id' => $roleID,
                            'name' => $name,
                            'email' => $email,
                            'password' => Hash::make($password)
                        ]);
                    }
                        
                    $request->session()->flash('success', 'Data berhasil diubah.');
                }
            } catch (\Exception $e) {
                Log::error('[Line: ' . $e->getLine() .  '] ' . $e->getTraceAsString());
                $request->session()->flash('error-update', ' [Line: ' . $e->getLine() .  ']' . $e->getTraceAsString());
            }
        }
        
        return redirect()->back();
    }
}
