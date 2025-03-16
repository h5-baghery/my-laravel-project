<x-profile :sharedData="$sharedData">

  <div class="list-group">
    @if ($followings)
      @foreach ($followings as $following)
        <a href="/following/{{$following->userFollowed->id}}" class="list-group-item list-group-item-action">
          <img class="avatar-tiny" src="{{$following->userFollowed->avatar}}" />
          {{$following->userFollowed->username}}
        </a>
      @endforeach
    @endif
  </div>
</x-profile>
