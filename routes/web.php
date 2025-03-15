<?php

use App\Http\Controllers\FollowController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

//user related routes
Route::get('/', [UserController::class, "showCorrectHomepage"])->name('login');
Route::get('/admin', function () {
    return 'only admins can see this page';
})->middleware('can:visitAdminPages');

Route::post('/register', [UserController::class, "register"])->middleware('guest');
Route::post('/login', [UserController::class, "login"])->middleware('guest');
Route::post('/logout', [UserController::class, "logout"])->middleware('mustBeLoggedIn');
Route::get('/profile/{user:username}', [UserController::class, 'viewPostsProfile'])->name('posts.profile');
Route::get('/manage-avatar', [UserController::class, 'showAvatarForm'])->name('show.avatarForm');
Route::post('/manage-avatar', [UserController::class, 'updateAvatar'])->name('users.avatar.update');

//Follow
Route::post('/create-follow/{user:username}', [FollowController::class, 'createFollow'])->name('create.follow')->middleware('mustBeLoggedIn');
Route::post('/delete-follow/{user:username}', [FollowController::class, 'deleteFollow'])->name('delete.follow')->middleware('mustBeLoggedIn');

//blog post related routes
Route::get('/create-post', [PostController::class, 'showCreateForm'])->middleware('mustBeLoggedIn');
Route::post('/create-post', [PostController::class, 'storeNewPost'])->middleware('mustBeLoggedIn');
Route::get('/post/{post}', [PostController::class, 'viewSinglePost'])->name('post.viewSinglePost')->middleware('mustBeLoggedIn');
Route::get('/post/{post}/edit', [PostController::class, 'viewUpdatePost'])->name('post.viewUpdate')->middleware('can:update,post');
Route::delete('/post/{post}', [PostController::class, 'deletePost'])->name('post.delete')->middleware('can:delete,post');
Route::put('/post/{post}', [PostController::class, 'actuallyUpdatePost'])->name('post.actuallyUpdate')->middleware('can:update,post');
