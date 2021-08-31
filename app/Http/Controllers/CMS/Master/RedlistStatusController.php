<?php

namespace App\Http\Controllers\CMS\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RedlistStatusController extends Controller
{
    public function index()
    {
        return view('cms.master.redlist-status-index');
    }
}
