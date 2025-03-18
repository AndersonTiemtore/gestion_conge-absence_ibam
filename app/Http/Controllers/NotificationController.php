<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    //
    public function markAsRead(Request $request , $id)
    {
        // return response()->json(['success', 'reussi']);
        $notification = Auth::user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
            return redirect()->back();
        }
        return redirect()->back();
    }

    // public function markAllAsRead()
    // {
    //     Auth::user()->unreadNotifications->markAsRead();
    //     return back();
    // }
}
