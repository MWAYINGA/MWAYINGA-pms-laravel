<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CreateSessionController extends Controller
{
    //
    public function index(Request $request){
        Session::put('storeUuid',$request->storeUuid);
        return back();
    }
}
