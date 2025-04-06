<div class="rounded space-y-4">
  <div class="space-y-4">
    <div>
      <h2 class="font-semibold mb-2">Game</h2>
      <input type="text" value="{{$provider}}" disabled class="w-full p-0 border border-none bg-transparent outline-none ring-none rounded">
    </div>
    <div>
      <h2 class="font-semibold mb-2">UID</h2>
      <input type="number" value="{{$account}}" disabled class="w-full p-0 border border-none bg-transparent outline-none ring-none rounded">
    </div>
  </div>
  @include('components.product-instant-feature')
</div>
