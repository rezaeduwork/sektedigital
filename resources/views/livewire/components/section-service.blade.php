<div>
  <style>
    .background-image {
      position: relative;
      overflow: hidden;
    }

    .background-image::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.6); /* Adjust the rgba value to control darkness */
      z-index: 1; /* Ensure the overlay is above the background but below any content */
    }

    .background-image > * {
      position: relative;
      z-index: 2; /* Ensure content is above the overlay */
    }
  </style>
  <div class="flex">
    <div class="space-y-10">
      <div class="space-y-6">
        <div class="w-full flex items-center justify-between" id="ppob-section">
          <h2 class="text-md lg:text-lg flex items-center">
            <img src="{{url('assets/images/instant.png')}}" alt="" srcset="" class="w-[24px] h-[24px]" />
            <div class="ms-3 font-black text-2xl">Top Up Cepat</div>
          </h2>
        </div>
        <div class="space-y-4">
          <template x-if="true">
            <livewire:components.service.ppob>
          </template>
          <template x-if="true">
            <div class="flex items-center space-x-2">
              @foreach (config('product') as $row)
                @if($row['status'] == 'active')
                <button onclick="Livewire.navigate('{{url('i/'.$row['code'])}}')" class="border rounded-full bg-gray-100 px-3 py-1 text-xs text-black flex items-center space-x-1">
                  <img src="{{url($row['image'])}}" alt="" srcset="" class="size-4 shrink-0">
                  <div class="">{{$row['title']}}</div>
                </button>
                @else
                <button class="border rounded-full bg-gray-100 opacity-50 px-3 py-1 text-xs text-black flex items-center space-x-1 cursor-default">
                  <img src="{{url($row['image'])}}" alt="" srcset="" class="size-4 shrink-0">
                  <div class="">{{$row['title']}}</div>
                </button>
                @endif
              @endforeach
            </div>
          </template>
        </div>
      </div>
      <div class="space-y-6">
        <div class="flex" id="category-section">
          <div class="w-full flex items-center justify-between">
            <h2 class="text-md lg:text-lg flex items-center">
              <img src="{{url('assets/images/category.png')}}" alt="" srcset="" class="w-[24px] h-[24px]" />
              <div class="ms-3 font-black text-2xl">Semua Kategori</div>
            </h2>
          </div>
        </div>
        <div class="grid md:grid-cols-4 lg:grid-cols-7 gap-4 lg:gap-4">
          @foreach (\App\Models\CategoryProduct::all() as $row)
          <!-- col -->
          <div class="shrink-0">
            <a href="{{url('shop/'.$row->id)}}" class="text-decoration-none text-inherit" wire:navigate>
              <!-- card -->
              <div class="border hover:shadow-md rounded-tr-3xl rounded-bl-3xl relative" style="background-image: url('{{url('assets/images/violet-category-compresseds.png')}}'); background-size: cover; background-position: center;">
                <!-- Overlay -->
                <div class="card-body text-center py-4 z-10">
                  <div class="flex justify-center">
                    <!-- img -->
                    <img src="{{url('storage/'.$row->icon)}}" alt="{{$row->name}}" class="mb-3">
                  </div>
                  <!-- text -->
                  <div class="truncate">{{$row->name}}</div>
                </div>
              </div>
            </a>
          </div>
          @endforeach
        </div>
      </div>

    </div>
  </div>
  {{-- <div class="flex pt-6">
    <div class="w-full flex items-center justify-between">
      <h2 class="text-md lg:text-lg flex items-center">
        <img src="{{url('assets/images/categories.png')}}" alt="" srcset="" class="w-[24px] h-[24px]">
        <div class="ms-2 font-black text-2xl">Pilihan Kategori</div>
      </h2>
    </div>
  </div> --}}
</div>
