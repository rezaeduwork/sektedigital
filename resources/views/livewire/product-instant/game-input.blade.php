<div class="rounded space-y-4">
  <div class="space-y-4">
    @foreach (collect($informations)->filter(function($item) {return $item['value'] !== null;}) as $row)
    <div>
      <h2 class="font-semibold mb-2">{{$row['label']}}</h2>
      <input type="text" value="{{$row['value']}}" disabled class="w-full p-0 border border-none bg-transparent outline-none ring-none rounded">
    </div>
    @endforeach
  </div>
  @include('components.product-instant-feature')
</div>
