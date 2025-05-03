<x-layout :doctitle="$doctitle">
    <div class="container py-md-5 container--narrow">
      <h2>
        <img class="avatar-small" src="{{$sharedData['user']->avatar}}" /> {{$sharedData['username']}}
        @auth
          @if (!$sharedData['sameUser'] && !$sharedData['isFollowed'])
            <form class="ml-2 d-inline" action="{{ route('create.follow', $sharedData['username'])}}" method="POST">
              @csrf
  
              <button class="btn btn-primary btn-sm">Follow <i class="fas fa-user-plus"></i></button>
              <!-- <button class="btn btn-danger btn-sm">Stop Following <i class="fas fa-user-times"></i></button> -->
            </form>
            
          @endif
  
          @if (!$sharedData['sameUser'] && $sharedData['isFollowed'])
            <form class="ml-2 d-inline" action="{{ route('delete.follow', $sharedData['username'])}}" method="POST">
              @csrf
              
              <button class="btn btn-primary btn-sm">Unfollow <i class="fas fa-user-plus"></i></button>
              <!-- <button class="btn btn-danger btn-sm">Stop Following <i class="fas fa-user-times"></i></button> -->
            </form>
          @endif
        @endauth
        @can('updateAvatar', $sharedData['user'])
        <div x-data="{ showForm: false}">
          <button @click="showForm = !showForm" class="btn btn-primary btn-sm" type="button"><span x-text="showForm ? 'Hide' : 'Update Avatar Image'"></span></button>
  
          <form x-show="showForm" action="{{ route('users.avatar.update', $sharedData['user'])}}" method="POST" enctype="multipart/form-data" class="mt-3">
            @csrf
            <input type="file" name="avatar" class="form-control mb-2">
            @error('avatar')
              <p class="alert small alert-danger shadow-sm">{{$message}}</p>
            @enderror
            <button type="submit" class="btn btn-success">Upload</button>
          </form>
        </div>
        @endcan
      </h2>
  
      <div class="profile-nav nav nav-tabs pt-2 mb-4">
        <a href="{{ route('profile.posts', $sharedData['username'])}}" class="profile-nav-link nav-item nav-link {{ Route::is('profile.posts') ? 'active' : ''}}">Posts: {{$sharedData['postCount']}}</a>
        <a href="{{ route('profile.followers', $sharedData['username'])}}" class="profile-nav-link nav-item nav-link {{ Route::is('profile.followers') ? 'active' : ''}}">Followers: {{$sharedData['followersCount']}}</a>
        <a href="{{ route('profile.following', $sharedData['username'])}}" class="profile-nav-link nav-item nav-link {{ Route::is('profile.following') ? 'active' : ''}}">Following: {{$sharedData['followingsCount']}}</a>
      </div>
      <div class="profile-slot-content">
          {{$slot}}
      </div>
    </div>
  </x-layout>