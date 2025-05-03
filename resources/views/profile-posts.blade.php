<x-profile :sharedData="$sharedData" doctitle="{{$sharedData['username']}}'s Profile">

  <div class="list-group">
    @if ($posts)
    @foreach ($posts as $post)
    <x-post :post="$post" hideAuthor/>
    @endforeach
    @endif
  </div>
</x-profile>
