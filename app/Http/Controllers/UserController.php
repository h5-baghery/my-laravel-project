<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

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

        $user      = auth()->user();
        $file_name = $user->id . "-" . uniqid() . ".jpg";
        $manager   = new ImageManager(new Driver());
        $image     = $manager->read($request->file('avatar'));
        $imageData = $image->cover(120, 120)->toJpeg();
        Storage::disk('public')->put('avatars/' . $file_name, $imageData);
        // $user->update(['avatar' => $file_name]);

        $oldAvatar = $user->avatar;

        $user->avatar = $file_name;
        $user->save();

        if ($oldAvatar != '/fallback-avatar.jpg') {
            Storage::disk('public')->delete(str_replace("/storage", "", $oldAvatar));
        }

        // $request->file('avatar')->store(['avatars' => 'public']);

        return back()->with('success', 'Congrats on the new avatar.');

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
