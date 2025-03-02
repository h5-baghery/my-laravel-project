<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $incomingFields = $request->validate([
            'username' => ['required', 'min:3', 'max:20', Rule::unique(table: 'users', column: 'username')],
            'email' => ['required', 'email', Rule::unique(table: 'users', column: 'email')],
            'password' => ['required', 'min:8', 'confirmed']
        ]);
        User::create($incomingFields);
        return "hello from register";
    }
}
