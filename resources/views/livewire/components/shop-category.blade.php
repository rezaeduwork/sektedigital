<div class="">
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
        hover:text-primary hover:border-primary
        @if(isset($category) && $category->id === $row->id)
        !text-primary !border-primary
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
      <button type="button" id="dropdownCategory" data-dropdown-toggle="dropdown" class="
      inline-flex items-center justify-center p-4 border-b-2 border-transparent rounded-t-lg group cursor-pointer text-primary
      ">
        <div class="bg-gray-100 rounded p-1 px-2">+{{$totalCategory - $show}} Kategori</div>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 mt-[3px]">
          <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
        </svg>

      </button>
      <!-- Dropdown menu -->
      <div id="dropdown" class="hidden bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 z-[2]">
        <ul class="px-3 py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownCategory">
          @foreach (\App\Models\CategoryProduct::get()->skip(5) as $row)
          <li class="flex w-full items-center space-x-2">
            <img src="{{url('storage/'.$row->icon)}}" alt="{{$row->name}}" class="size-4">
            <a href="{{url('shop/'.$row->id)}}" class="block p-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white text-left" wire:navigate>{{$row->name}}</a>
          </li>
          @endforeach
        </ul>
      </div>
      @endif
    </div>
  </div>
</div>
