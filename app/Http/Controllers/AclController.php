<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;

class AclController extends Controller
{
    public function index()
    {
        $principalPermissions = Role::where('id', 1)->with('permissions')->first();
        $adminPermissions = Role::where('id', 2)->with('permissions')->first();
        $teacherPermissions = Role::where('id', 3)->with('permissions')->first();

        return view('acl.index', compact('principalPermissions', 'adminPermissions', 'teacherPermissions'));
    }
}
