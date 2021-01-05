<?php

Route::get('/', function () {
    return response('This is a web route');
    // return view('home');
});

Route::get('acl', 'AclController@index');
