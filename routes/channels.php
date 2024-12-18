<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Broadcast::channel('counseling_messages', function ($user, $receiverId) {
//     // You can add your logic here to check if the user is authorized to listen to this channel
//     return true; // or your authorization logic
// });
