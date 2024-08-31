<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function get(Request $request)
    {
        $data = User::search()->paginate($request->get('limit', 10));
        return $this->paginateResponse(UserResource::collection($data), $data);
    }

}
