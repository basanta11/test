<?php

namespace App\Http\Controllers\Chat;

use App\User;
use App\Group;
use App\Message;
use App\GroupUser;
use App\CourseDetail;
use App\StudentDetail;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();
        $formattedUser = User::where('id', auth()->user()->id)
        ->get()
        ->map(function($data) {
            return [
                'id' => $data['id'],
                'name' => $data['name'],
                'image' => $data['image'] == null ? global_asset('assets/media/users/default.jpg') : Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/users/'.$data['image']),
            ];
        })->first();
        $groupId = $request->group_id;
        
        $message = $user->messages()->create([
            'user_id' => $user->id,
            'message' => $request->message,
            'group_id' => $request->group_id,
        ]);

        Group::where('id', $groupId)->update(['latest' => date('Y-m-d H:i:s')]);

        // Announce that a new message has been posted
        broadcast(new MessageSent($message, $formattedUser, $groupId))->toOthers();

        return ['status' => 'OK'];
    }

    public function getEverything()
    {
        $data['authuser'] = $data['latestChat'] = $data['latestChatDetails'] = $data['messages'] = $data['groups'] = [];

        $data['authuser'] = User::where('id', auth()->user()->id)
            ->get()
            ->map(function($data) {
                return [
                    'id' => $data['id'],
                    'name' => $data['name'],
                    'image' => $data['image'] == null ? global_asset('assets/media/users/default.jpg') : Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/users/'.$data['image']),
                ];
            })->first();

        $latestGroups = auth()->user()->groups()->orderBy('latest', 'desc');

        if (!empty($latestGroups->first())) {
            $data['latestChat'] = $latestGroups->first()->id;
            $data['latestChatDetails'] = GroupUser::where('group_id', $data['latestChat'])
                ->where('user_id', '!=', auth()->user()->id)
                ->with('user:id,name,image')
                ->first();
            $data['messages'] = Message::where('group_id', $data['latestChat'])->with('user:id,name,image')->get()
                ->map(function($data) {
                    return [
                        'id' => $data['id'],
                        'message' => $data['message'],
                        'created_at' => date('Y-m-d h:i a', strtotime($data['created_at'])),
                        'user' => [
                            'id' => $data['user']['id'],
                            'name' => $data['user']['name'],
                            'image' => $data['user']['image'] == null ? global_asset('assets/media/users/default.jpg') : Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/users/'.$data['user']['image']),
                        ]
                    ];
                });
        }

        $data['groups'] = GroupUser::whereIn('group_id', auth()->user()->groups->pluck('id'))
            ->where('user_id', '!=', auth()->user()->id)
            ->with('user:id,name,image')
            ->get()
            ->map(function($data) {
                return [
                    'id' => $data['group_id'],
                    'name' => $data['user']['name'],
                    'image' => $data['user']['image'] == null ? global_asset('assets/media/users/default.jpg') : Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/users/'.$data['user']['image'])
                ];
            });

        if ( auth()->user()->hasRole('Teacher') ) {
            $except = GroupUser::whereIn('group_id', auth()->user()->groups->pluck('id'))->where('user_id', '!=', auth()->user()->id)->pluck('user_id');

            $sections = auth()->user()->course_details->where('status', 1)->pluck('section_id');

            $students = StudentDetail::whereIn('section_id', $sections)->pluck('user_id');

            $data['chatters'] = User::whereIn('id', $students)->where('role_id', 4)->whereNotIn('id', $except)->where('status', 1)->select('id', 'name')->get();
        }
        else {
            $except = GroupUser::whereIn('group_id', auth()->user()->groups->pluck('id'))->where('user_id', '!=', auth()->user()->id)->pluck('user_id');

            $section = auth()->user()->student_detail->section_id;

            $teachers = CourseDetail::where('section_id', $section)->where('status', 1)->select('user_id')->groupBy('user_id')->get();

            $data['chatters'] = User::whereIn('id', $teachers)->where('role_id', 3)->whereNotIn('id', $except)->where('status', 1)->select('id', 'name')->get();
        }

        return response()->json(['result' => $data]);
    }

    public function loadMessages($groupId)
    {
        $messages = Message::where('group_id', $groupId)->with('user:id,name,image')->get()
            ->map(function($data) {
                return [
                    'id' => $data['id'],
                    'message' => $data['message'],
                    'created_at' => date('Y-m-d h:i a', strtotime($data['created_at'])),
                    'user' => [
                        'id' => $data['user']['id'],
                        'name' => $data['user']['name'],
                        'image' => $data['user']['image'] == null ? global_asset('assets/media/users/default.jpg') : Storage::disk(config('app.storage_driver'))->url(config('app.filesystem_suffix') . tenant()->id . '/users/'.$data['user']['image']),
                    ]
                ];
            });

        $groupuser = GroupUser::where('group_id', $groupId)
            ->where('user_id', '!=', auth()->user()->id)
            ->with('user:id,name,image')
            ->first();

        return response()->json(['messages' => $messages, 'groupuser' => $groupuser]);
    }
}
