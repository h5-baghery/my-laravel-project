<x-profile :sharedData="$sharedData">

  <div class="list-group">
    @if ($posts)
    @foreach ($posts as $post)
    <a href="{{route('post.viewSinglePost', $post)}}" class="list-group-item list-group-item-action">
      <img class="avatar-tiny" src="{{$post->user->avatar}}" />
      <strong>{{$post->title}} </strong> on {{$post->created_at->format('n/j/Y')}}
    </a>
    @endforeach
    @endif
  </div>
</x-profile>
