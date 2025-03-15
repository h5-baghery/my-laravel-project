<?php
namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;

class FollowController extends Controller
{
    //
    public function createFollow(User $user)
    {
        //can not follow yourself
        if ($user->id == auth()->user()->id) {
            return back()->with('failure', 'you cannot follow yourself');
        }
        //can not follow somone more than once

        $existFollowCheck = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->count();
        if ($existFollowCheck) {
            return back()->with('failure', 'you are lready following the user');
        }

        $newFollow               = new Follow;
        $newFollow->user_id      = auth()->user()->id;
        $newFollow->followeduser = $user->id;
        $newFollow->save();
        return back()->with('success', 'User successfully followed.');

    }

    public function deleteFollow(User $user)
    {
        $existFollowCheck = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->count();
        if ($existFollowCheck) {
            Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->delete();
            return back()->with('success', 'User Unollwed');
        }
    }
}
