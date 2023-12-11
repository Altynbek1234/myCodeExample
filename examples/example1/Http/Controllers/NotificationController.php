<?php

namespace App\Http\Controllers;

use App\Http\Resources\Notification\NotificationUnreadResource;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function unread()
    {
        $user = Auth::user();
        $notifications = Notification::where('is_read', false)
            ->where('type', 1)->where('user_id', $user->id)->get();

        return NotificationUnreadResource::collection($notifications);
    }

    public function all()
    {
        $user = Auth::user();
        $notifications = Notification::where('type', 1)->where('user_id', $user->id)->get();

        return NotificationUnreadResource::collection($notifications);
    }

    public function show($id)
    {
        $notification = Notification::find($id);
        $notification->is_read = true;
        $notification->save();

        return new NotificationUnreadResource($notification);
    }
}
