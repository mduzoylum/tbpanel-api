<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function send(Request $request)
    {
        Message::create([
            'user_id' => auth()->user()->id,
            'user_type' => 'customer',
            'message' => $request->get('message')
        ]);

        return response()->json(['message' => 'Message sent successfully']);
    }

    public function get()
    {
        return Message::orderBy('created_at', 'asc')->get()->toArray();
    }
}
