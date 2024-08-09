<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessagesController extends Controller
{
    public function send(Request $request, $user)
    {
        Message::create([
            'user_id' => $user,
            'user_type' => 'admin',
            'message' => $request->get('message')
        ]);

        return response()->json(['message' => 'Message sent successfully']);
    }

    public function get($user)
    {
        return Message::where(function ($query) use ($user) {
            $query->where('user_id', $user)
                ->where('user_type', 'customer');
        })
            ->orWhere(function ($query) use ($user) {
                $query->where('user_id', $user)
                    ->where('user_type', 'admin');
            })
            ->orderBy('created_at')
            ->get()
            ->toArray();
    }

    public function userList()
    {
        return Message::with('user:id,name,surname')
        ->select('user_id', DB::raw('MAX(created_at) as last_message_time'))
        ->where(function ($query) {
            $query->where('user_type', 'admin')
                ->orWhere('user_type', 'customer');
        })
            ->groupBy('user_id')
            ->orderByDesc('last_message_time')
            ->get()
            ->toArray();
    }


}
