
<x-layout>
  <div class="container py-md-5 container--narrow">
    <h2>
      <img class="avatar-small" src="{{$user->avatar}}" /> {{$username}}
      @auth
        @if (!$sameUser && !$isFollowed)
          <form class="ml-2 d-inline" action="{{ route('create.follow', $username)}}" method="POST">
            @csrf

            <button class="btn btn-primary btn-sm">Follow <i class="fas fa-user-plus"></i></button>
            <!-- <button class="btn btn-danger btn-sm">Stop Following <i class="fas fa-user-times"></i></button> -->
          </form>
          
        @endif

        @if (!$sameUser && $isFollowed)
          <form class="ml-2 d-inline" action="{{ route('delete.follow', $username)}}" method="POST">
            @csrf
            
            <button class="btn btn-primary btn-sm">Unfollow <i class="fas fa-user-plus"></i></button>
            <!-- <button class="btn btn-danger btn-sm">Stop Following <i class="fas fa-user-times"></i></button> -->
          </form>
        @endif
      @endauth
      @can('updateAvatar', $user)
      <a href="{{ route('show.avatarForm', $user)}}" class="btn btn-secondary btn-sm">Manage Avatar</a>
      <div x-data="{ showForm: false}">
        <button @click="showForm = !showForm" class="btn btn-primary btn-sm" type="button"><span x-text="showForm ? 'Hide' : 'Update Avatar Image'"></span></button>

        <form x-show="showForm" action="{{ route('users.avatar.update', $user)}}" method="POST" enctype="multipart/form-data" class="mt-3">
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
      <a href="#" class="profile-nav-link nav-item nav-link active">Posts: {{$postCount}}</a>
      <a href="#" class="profile-nav-link nav-item nav-link">Followers: 3</a>
      <a href="#" class="profile-nav-link nav-item nav-link">Following: 2</a>
    </div>

    <div class="list-group">
        @foreach ($posts as $post)
          <a href="/post/{{$post->id}}" class="list-group-item list-group-item-action">
            <img class="avatar-tiny" src="{{$user->avatar}}" />
            <strong>{{$post->title}} </strong> on {{$post->created_at->format('n/j/Y')}}
          </a>
        @endforeach

    </div>
  </div>
</x-layout>
