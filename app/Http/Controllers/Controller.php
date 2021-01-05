<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Backend;
use View;
use App\Http\Controllers\GlobalController;
use Exception;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function __construct() 
    {
        $backend=null;
        try{
            $backend=Backend::first();
        }catch(Exception $e){
            $backend=null;
        }
        // Fetch the Site Settings object
        $primary=$backend ? ($backend->theme ? substr($backend->theme,0,7) : '#1a6ca8') :'#1a6ca8';

        $hover=$backend ? ($backend->theme ? substr($backend->theme,0,7).'a3' : '#1a6ca8a3') :'#1a6ca8a3';
        $this->site_settings=[
            'theme'=>[
                'primary'=>$primary,
                'primary-hover'=>$hover
            ]
        ];
        View::share('site_settings', $this->site_settings);
    }
}
