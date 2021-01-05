<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Backend;
use Config;
use Artisan;

class BackendSettingController extends Controller
{
    //
    public function index()
    {
        return view('admin.settings.backend');
    }

    public function changeColor(Request $request)
    {
        $b=Backend::first();
        if($b){
            $b->update(['theme'=>$request->theme]);
        }else{
            $b=Backend::create(['user_id'=>auth()->user()->id,'theme'=>$request->theme]);
        }
        return back()->with('success','Theme changed successful.');
    }
    
}
