<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Psy\VarDumper\Dumper;

class PostController extends Controller
{
    public function actuallyUpdatePost(Post $post, Request $request)
    {
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);

        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);

        $post->update($incomingFields);

        return back()->with('success', 'your post has successfully updated');
    }

    public function viewUpdatePost(Post $post)
    {
        return view('edit-single-post', ['post' => $post]);
    }

    public function deletePost(Post $post)
    {
        $post->delete();
        return redirect('/profile/' . auth()->user()->username)->with('success', 'your post successfully deleted');
    }

    public function viewSinglePost(Post $post)
    {
        $post->body = strip_tags(Str::markdown($post->body), '<p><strong><h3><em><br><ul><li><ol><br><hr></br>');
        return view('single-post', ['post' => $post]);
    }

    public function showCreateForm()
    {
        return view('create-post');
    }

    public function storeNewPost(Request $request)
    {
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);

        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id();

        $post = Post::create($incomingFields);


        return redirect()->route('post.viewSinglePost', $post->id)->with('success', 'New Post successfully created');
    }
}
