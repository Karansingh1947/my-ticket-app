<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a list of users (Admin only).
     */
    public function index(Request $request)
    {
        $users = User::select('id', 'name', 'email', 'is_admin', 'created_at')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('Users/Index', [
            'users' => $users,
            'auth' => [
                'user' => $request->user(),
            ],
        ]);
    }

}
