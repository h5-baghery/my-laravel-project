<?php
namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class UserController extends Controller
{

    private function getSharedData($user)
    {
        // $posts = $user->posts()->latest()->get();
        // $user = User::get();

        $sameUser   = $user->id == auth()->user()?->id;
        $isFollowed = Follow::where([['user_id', '=', auth()->user()?->id], ['followeduser', '=', $user->id]])->count();
        View::share('sharedData', ['username' => $user->username, 'postCount' => $user->posts()->count(), 'followersCount' => $user->followers()->count(), 'followingsCount' => $user->followings()->count(), 'user' => $user, 'sameUser' => $sameUser, 'isFollowed' => $isFollowed]);
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

    public function viewProfile(User $user)
    {
        $this->getSharedData($user);
        return view('profile-posts', ['posts' => $user->posts()?->latest()->get()]);
    }

    public function viewProfileFollowers(User $user)
    {
        $this->getSharedData($user);
        return view('profile-followers', ['followers' => $user->followers()?->latest()->get()]);
    }

    public function viewProfileFollowings(User $user)
    {
        $this->getSharedData($user);
        return view('profile-followings', ['followings' => $user->followings()->latest()->get()]);
    }

    public function showCorrectHomepage()
    {
        if (auth()->check()) {
            return view('homepage-feed', ['posts' => auth()->user()->feedPosts()->latest()->paginate(4)]);
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
