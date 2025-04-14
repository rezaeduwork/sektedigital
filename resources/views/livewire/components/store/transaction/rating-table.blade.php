<div class="p-2 sm:p-4">
  <table class="table">
    <thead>
      <tr>
        <th class="text-left max-sm:text-xs">Product</th>
        <th class="text-left max-sm:text-xs">User</th>
        <th class="text-left max-sm:text-xs">Rating</th>
        <th class="text-left max-sm:text-xs">Feedback</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($list as $rating)
      <tr wire:key="rating-item-{{$rating->id}}">
        <td>
          <div class="flex items-center space-x-2">
            <img src="{{ productImage($rating->product->mainImage()) }}" alt="{{ $rating->product->name }}" class="size-8 sm:size-10 shrink-0 rounded">
            <div>
              <h3 class="text-sm font-semibold">{{ $rating->product->name }}</h3>
              <p class="text-xs text-gray-500">{{ $rating->product->category->name }}</p>
            </div>
          </div>
        </td>
        <td class="max-sm:text-xs">{{ $rating->user->name }}</td>
        <td class="">
          <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 text-yellow-400">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
            </svg>
            <div class="max-sm:text-xs">{{$rating->rating}}</div>
          </div>
        </td>
        <td class="max-sm:text-xs">{{ $rating->feedback }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div class="mt-4">
    {{$list->links()}}
  </div>

</div>
