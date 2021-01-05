<?php

namespace App\Http\Controllers\Chat;

use App\User;
use App\Group;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    public function index()
    {
        return view('chats.index');
    }

    public function create($id)
    {
        $finduser = User::find($id);

        $user['name'] = $finduser->name;
        $user['image'] = ($finduser->image) ? Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/users/'.$finduser->image) : global_asset('assets/media/users/default.jpg');

        $group = Group::create([
            'title' => Str::random(8),
            'type' => 0
        ]);

        $group->users()->attach([$id, auth()->user()->id]);

        return response()->json(['group' => $group, 'user' => $user]);
    }
}
