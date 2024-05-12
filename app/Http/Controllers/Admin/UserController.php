<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserService;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $searchInput = [
            'userName' => $request->input('userName'),
            'email' => $request->input('email')
        ];
        $users = UserService::search($searchInput)->orderBy('created_at', 'desc')->paginate(30);
        return view('admin.users.index', ['users' => $users]);
    }
}
