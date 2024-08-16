<div>
  @if ($category)
  <div class="flex items-center justify-center mb-4 space-x-4">
    <img src="{{url('storage/'.$category->icon)}}" alt="" srcset="" class="size-24" />
    <div class="text-[56px] font-bold">{{$category->name}}</div>
  </div>
  @endif
  <div class="border-gray-200 dark:border-gray-700 w-full text-center flex items-center justify-center w-full">
    <ul class="grid grid-cols-2 w-full lg:w-auto lg:flex lg:flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
      <li class="me-2">
        <a href="{{url('shop')}}" class="inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg group" wire:navigate>
          Semua
        </a>
      </li>
      @foreach (\App\Models\CategoryProduct::take(5)->get() as $row)
      <li class="me-2">
        <a href="{{url('shop/'.$row->id)}}" class="
        inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg group
        hover:text-primary hover:border-red-300
        @if(isset($category) && $category->id === $row->id)
        !text-primary !border-red-300
        @endif
        " wire:navigate>
          <img src="{{url('storage/'.$row->icon)}}" alt="" srcset="" class="size-4 mr-2" />
          {{$row->name}}
        </a>
      </li>
      @endforeach
    </ul>
    <div>
      @php
      $totalCategory = \App\Models\CategoryProduct::query()->count();
      @endphp
      @if ($totalCategory > $show)
      <li class="
      inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg group font-bold
      ">
        <div class="bg-gray-100 rounded p-1 px-2">+{{$show - $totalCategory}} Kategori</div>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
          <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
        </svg>

      </li>
      @endif
    </div>
  </div>
</div>
