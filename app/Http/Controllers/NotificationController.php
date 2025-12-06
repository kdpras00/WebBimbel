<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
    public function unreadCount()
    {
        return response()->json([
            'count' => \Illuminate\Support\Facades\Auth::user()->unreadNotifications->count()
        ]);
    }

    public function index()
    {
        $notifications = \Illuminate\Support\Facades\Auth::user()
            ->notifications()
            ->take(10)
            ->get()
            ->map(function ($n) {
                return [
                    'id' => $n->id,
                    'data' => $n->data,
                    'read_at' => $n->read_at,
                    'created_at' => $n->created_at->diffForHumans(),
                ];
            });

        return response()->json($notifications);
    }

    public function markAsRead($id)
    {
        $notification = \Illuminate\Support\Facades\Auth::user()
            ->notifications()
            ->where('id', $id)
            ->first();

        if ($notification) {
            $notification->markAsRead();
        }

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        \Illuminate\Support\Facades\Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }
