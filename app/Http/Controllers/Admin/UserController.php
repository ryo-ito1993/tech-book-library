<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\UserService;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $searchInput = [
            'userName' => $request->input('userName'),
            'email' => $request->input('email'),
            'library' => $request->input('library'),
        ];
        $users = UserService::search($searchInput)->orderBy('created_at', 'desc')->paginate(30);
        return view('admin.users.index', ['users' => $users]);
    }

    public function show(User $user)
    {
        return view('admin.users.show', ['user' => $user]);
    }
}
