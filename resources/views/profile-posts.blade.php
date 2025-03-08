
<x-layout>
  <div class="container py-md-5 container--narrow">
    <h2>
      <img class="avatar-small" src="https://gravatar.com/avatar/b9408a09298632b5151200f3449434ef?s=128" /> {{$username}}
      <form class="ml-2 d-inline" action="#" method="POST">
        @csrf
        <button class="btn btn-primary btn-sm">Follow <i class="fas fa-user-plus"></i></button>
        <!-- <button class="btn btn-danger btn-sm">Stop Following <i class="fas fa-user-times"></i></button> -->
        @can('updateAvatar', $user)
        <a href="{{ route('show.avatarForm', $user)}}" class="btn btn-secondary btn-sm">Manage Avatar</a>
        <div x-data="{ showForm: false}">
          <button @click="showForm = !showForm" class="btn btn-primary btn-sm">Show Avatar Form</button>

          <form x-show="showForm" action="{{ route('users.avatar.update', $user)}}" method="POST" enctype="multipart/form-data" class="mt-3">
            @csrf
            <input type="file" name="avatar" class="form-control mb-2">
            <button type="submit" class="btn btn-success">Upload</button>
          </form>
        </div>
        @endcan
      </form>
    </h2>

    <div class="profile-nav nav nav-tabs pt-2 mb-4">
      <a href="#" class="profile-nav-link nav-item nav-link active">Posts: {{$postCount}}</a>
      <a href="#" class="profile-nav-link nav-item nav-link">Followers: 3</a>
      <a href="#" class="profile-nav-link nav-item nav-link">Following: 2</a>
    </div>

    <div class="list-group">
        @foreach ($posts as $post) 
          <a href="/post/{{$post->id}}" class="list-group-item list-group-item-action">
            <img class="avatar-tiny" src="https://gravatar.com/avatar/b9408a09298632b5151200f3449434ef?s=128" />
            <strong>{{$post->title}} </strong> on {{$post->created_at->format('n/j/Y')}}
          </a>
        @endforeach

    </div>
  </div>
</x-layout>