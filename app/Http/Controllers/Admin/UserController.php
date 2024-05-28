<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\UserService;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {
    }

    public function index(Request $request): View
    {
        $searchInput = [
            'userName' => $request->input('userName'),
            'email' => $request->input('email'),
            'library' => $request->input('library'),
        ];
        $users = $this->userService->search($searchInput)->orderBy('created_at', 'desc')->paginate(30);
        return view('admin.users.index', ['users' => $users]);
    }

    public function show(User $user): View
    {
        return view('admin.users.show', ['user' => $user]);
    }
}
