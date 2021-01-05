<?php

use App\Group;
use Illuminate\Support\Facades\Broadcast;

// Broadcast::channel('App.User.{id}', function ($user, $id) {
//     return true;
//     return (int) $user->id === (int) $id;
// });

Broadcast::channel('users.{user_id}', function ($user, $id) {
    return true;
    return (int) $user->id === (int) $id;
});

Broadcast::channel('notification.users.{id}', function ($user, $orderId) {
    return true;
});

Broadcast::channel('privatechat.{group}', function ($user, Group $group) {
    if (!$user->groups->where('id', $group->id)->isEmpty()) {
        return ['id' => $user->id, 'name' => $user->name];
    }
});
