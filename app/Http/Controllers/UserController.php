<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    public function showAvatarForm()
    {
        return 'hihi';
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:1000',
        ]);

        $request->file('avatar')->store('avatars', 'public');
        return 'Successful';
    }

    public function viewPostsProfile(User $user)
    {
        $posts = $user->posts()->latest()->get();
        // $user = User::get();
        return view('profile-posts', ['username' => $user->username, 'posts' => $posts, 'postCount' => $posts->count(), 'user' => $user]);
    }

    public function showCorrectHomepage()
    {
        if (auth()->check()) {
            return view('homepage-feed');
        } else {
            return view('homepage');
        }
    }
    public function login(Request $request)
    {
        $incomingFields = $request->validate([
            'loginusername' => 'required',
            'loginpassword' => 'required',
        ]);
        if (auth()->attempt(['username' => $incomingFields['loginusername'], 'password' => $incomingFields['loginpassword']])) {
            $request->session()->regenerate();
            return redirect('/')->with('success', 'you logedin succesfully');
        } else {
            return redirect('/')->with('failure', 'invalid username or password');
        }
    }

    public function logout()
    {
        auth()->logout();
        return redirect('/')->with('success', 'you logedout succesfully');
    }

    public function register(Request $request)
    {
        $incomingFields = $request->validate([
            'username' => ['required', 'min:3', 'max:20', Rule::unique(table: 'users', column: 'username')],
            'email'    => ['required', 'email', Rule::unique(table: 'users', column: 'email')],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);
        $user = User::create($incomingFields);
        auth()->login($user);
        return redirect('/')->with('success', 'you registered succesfully');
    }
}
