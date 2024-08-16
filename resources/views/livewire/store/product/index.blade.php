<section class="container" x-data="{ searchCategory: '' }">
  <div class="flex flex-col items-center justify-between md:flex-row w-full mb-5">
    <h1 class="text-2xl font-bold mb-5 md:mb-0">
      <div>Dagangan Saya</div>
    </h1>
    <a href="{{ url('/store/product/create') }}" wire:navigate
      class="py-3 px-6 text-base font-normal w-full md:w-auto flex items-center justify-center space-x-2 text-white bg-primary rounded">
      <span>
        Buat Dagangan Baru
      </span>
    </a>
  </div>
  <div class="table-responsive-xl p-6 mb-6 mb-lg-0 space-y-4  bg-white rounded border">
    @php
    $tabs = [
      'Semua',
      'Tidak Aktif',
    ];
    @endphp
    <div
      class="font-300 text-center text-black border-b border-gray-200 text-lg">
      <ul class="block md:flex px-2">
        @foreach ($tabs as $row)
        <li class="me-2 shrink-0">
          <a href="#"
            @click="$wire.set('tab', '{{$row}}')"
            class="
            inline-block p-4
            @if($tab == $row)
            text-primary border-primary border-b-4
            @else
            hover:text-primary
            @endif
            rounded-t-lg
            "
            aria-current="tab">{{$row}}</a>
        </li>
        @endforeach
      </ul>
    </div>
    <div class="p-0 grid grid-cols-3 gap-2 mb-5">
      <div class="">
        <input type="text" id="name" wire:model.live.debounce.250ms="name"
          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-300 focus:border-red-300 block w-full p-2.5"
          placeholder="Cari Nama Produk" />
      </div>
      <div class=" relative">
        <button id="dropdownSearchButton" data-dropdown-toggle="dropdownSearch" data-dropdown-placement="bottom"
          class="flex items-center justify-between bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-300 focus:border-red-300 block w-full p-2.5"
          type="button">
          <div>
            {{ $category_id ? \App\Models\CategoryProduct::find($category_id)->name ?? 'Kategori tidak ada' : 'Cari Kategori' }}
          </div>
          <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 10 6">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m1 1 4 4 4-4" />
          </svg>
        </button>

        <!-- Dropdown menu -->
        <div id="dropdownSearch" class="z-10 hidden bg-white rounded-lg shadow w-full">
          <div class="p-3">
            <label for="input-group-search" class="sr-only">Search</label>
            <div class="relative">
              <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                  viewBox="0 0 20 20">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                </svg>
              </div>
              <input type="text" @input.debounce.250ms="searchCategory = $event.target.value;"
                class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Pencarian">
            </div>
          </div>
          <ul class="px-3 pb-3 overflow-y-auto text-sm text-gray-700" aria-labelledby="dropdownSearchButton">
            @foreach (\App\Models\CategoryProduct::all() as $row)
              <li class="inline-block p-2 bg-gray-50 cursor-pointer"
                x-show="!searchCategory || ('{{ $row->name }}').toLowerCase().includes(searchCategory.toLowerCase())"
                @click="$wire.set('category_id', {{ $row->id }})">
                {{ $row->name }}
              </li>
            @endforeach
          </ul>
        </div>
      </div>
      <div class="">
        <select
          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-300 focus:border-red-300 block w-full p-2.5">
          <option value="">Urutkan</option>
          <option value="newest">Terbaru</option>
          <option value="oldest">Terlama</option>
        </select>
      </div>
    </div>


    <div class="relative overflow-x-auto sm:rounded-lg">
      <table class="w-full text-sm text-left rtl:text-right text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-100">
          <tr>
            <th scope="col" class="px-6 py-3">
              Nama
            </th>
            <th scope="col" class="px-6 py-3">
              Kategori
            </th>
            <th scope="col" class="px-6 py-3">
              Stok
            </th>
            <th scope="col" class="px-6 py-3">
              Harga
            </th>
            <th scope="col" class="px-6 py-3">
              Status
            </th>
            <th scope="col" class="px-6 py-3">
              #
            </th>
          </tr>
        </thead>
        <tbody>
          @forelse ($list as $row)
            <tr class="odd:bg-white even:bg-gray-50 border-b">
              <th scope="row" class="px-6 py-4 font-medium text-gray-900">
                <div class="max-w-[300px]">{{ $row->title }}</div>
              </th>
              <td class="px-6 py-4">
                <div class="flex items-center space-x-2 ">
                  @if ($row->category)
                    <img src="{{ url('storage/' . $row->category->icon) }}" alt="" srcset=""
                      class="w-[24px] h-[24px]" />
                    <div>{{ $row->category->name ?? '-' }}</div>
                  @endif
                </div>
              </td>
              <td class="px-6 py-4">
                {{ $row->stock }}
              </td>
              <td class="px-6 py-4">
                Rp. {{ number_format($row->price) }}
              </td>
              <td class="px-6 py-4 relative">
                {{-- <button type="button" id="dropdownDefaultButton{{$row->id}}" class="flex items-center space-x-1" data-dropdown-toggle="dropdown">
                  @if ($row->status == 'active')
                  <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded">Aktif</span>
                  @else
                  <span class="bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded">Tidak Aktif</span>
                  @endif
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                  </svg>
                </button> --}}
                <livewire:components.store.edit-status :key="uniqid().$row->id" :product="$row">
              </td>
              <td class="px-6 py-4 flex items-center space-x-2">
                <a href="{{url('store/product/edit/'.$row->slug)}}" class="p-2 rounded bg-blue-600 text-white shrink-0" wire:navigate>
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                  </svg>
                </a>
                <button type="button" class="p-2 rounded bg-primary text-white shrink-0" data-modal-target="modal-delete-{{$row->id}}" data-modal-toggle="modal-delete-{{$row->id}}">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                  </svg>
                </button>

                <div id="modal-delete-{{$row->id}}"
                class="hidden bg-black bg-opacity-50 overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-[9999] justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                  <div class="relative p-4 w-full max-w-md max-h-full">
                    <div class="relative bg-white rounded-lg shadow">
                      <button type="button"
                        class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                        data-modal-hide="modal-delete-{{$row->id}}">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                          viewBox="0 0 14 14">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                      </button>
                      <div class="p-4 md:p-5 text-center">
                        <svg class="mx-auto mb-4 text-gray-400 w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                          fill="none" viewBox="0 0 20 20">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-500">Are you sure you want to delete this product?</h3>
                        <button type="button"
                        class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                          Ya, Hapus
                        </button>
                        <button data-modal-hide="modal-delete-{{$row->id}}" type="button"
                        class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">
                          Batal
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
                {{-- <div class="relative" x-data="{show: false}">
                  <button type="button" class="p-2 rounded bg-white border shrink-0" @click="show = !show" @click.outside="show = false">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="size-4">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                    </svg>
                  </button>
                  <div style="display: none;" x-show="show" class="z-10 absolute top-0 right-[100%] bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                    <ul class="text-sm text-gray-700 dark:text-gray-200 space-y-2">
                      <li>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Restock</a>
                      </li>
                    </ul>
                  </div>
                </div> --}}
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="p-4">
                Belum ada produk,
                <a href="{{ url('store/product/create') }}" wire:navigate>klik disini untuk membuat satu</a>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if ($list->count() > 0)
      <div>
        {{ $list->links('vendor.livewire.tailwind') }}
      </div>
    @endif
  </div>

</section>
