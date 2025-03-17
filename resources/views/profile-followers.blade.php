<x-profile :sharedData="$sharedData">

  <div class="list-group">
    @if ($followers)
      @foreach ($followers as $follower)
        <a href="{{route('profile', $follower->userFollows)}}" class="list-group-item list-group-item-action">
          <img class="avatar-tiny" src="{{$follower->userFollows->avatar}}" />
          {{$follower->userFollows->username}}
        </a>
      @endforeach
    @endif
  </div>
</x-profile>
