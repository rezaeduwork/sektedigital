<li class="py-3 border-gray-300 border-t space-y-4">
  <div class="flex items-center justify-between space-x-5">
    <div class="shrink-0">
      <div class="flex items-center">
        <input id="product-{{ $cart->id }}-checkbox" type="checkbox" value="{{ $cart->id }}"
          @change="$wire.select({{ $cart->id }})" @if ($isSelected) checked @endif
          class="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary">
      </div>
    </div>
    <div class="w-full">
      <div class="flex flex-row gap-5">
        <img src="{{ productImage($product->mainImage()) }}" alt="Ecommerce" class="w-16 h-16">
        <div class="flex flex-col gap-2">
          <div>
            <!-- title -->
            <a href="#" class="text-inherit">
              <h6 class="font-black text-lg">{{ $product->title }}</h6>
            </a>
            <span class="text-gray-500 text-sm flex items-center space-x-1">
              <img src="{{ categoryImage($product->category) }}" alt="" srcset="" class="size-4">
              <span>{{ $product->category->name }}</span>
            </span>
          </div>
        </div>
      </div>
    </div>
    <!-- price -->
    <div class="text-right shrink-0 space-y-4">
      <a href="" class="flex items-center space-x-2 text-gray-500 text-xs shrink-0">
        <img src="{{ storeProfile($product->store) }}" alt="Ecommerce" class="w-5 h-5">
        <div class="">{{ $product->store->name }}</div>
      </a>
      <span
        class="
        @if ($product->stock > 1) bg-green-100 text-green-800
        @else
        bg-violet-100 text-primary @endif
        text-xs font-medium px-2.5 py-0.5 rounded flex items-center justify-center">
        @if ($product->stock == 1)
          <img src="{{ url('assets/images/fire.png') }}" alt="" srcset="" class="size-3 mr-1" />
          <div class="">Stok Terakhir</div>
        @else
          Stok {{ $product->stock }}
        @endif
      </span>
    </div>
  </div>
  <div class="flex items-center justify-end w-full space-x-5">
    <div class="relative z-0 w-full">
      <input type="text" id="floating_standard{{ $cart->id }}"
        wire:model.live.debounce.250ms="note"
        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-primary peer"
        placeholder=" " />
      <label for="floating_standard{{ $cart->id }}"
        class="absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-primary peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto">Catatan
        untuk penjual (Optional)</label>
    </div>
    <!-- input group -->
    <div class="">
      <!-- input -->
      <div class="input-group input-spinner rounded-lg flex justify-between items-center">
        <button type="button"
          class="
        button-plus w-8 py-1 border-r cursor-pointer border-gray-300 flex items-center justify-center
        @if ($cart->quantity <= 1) bg-gray-100 !cursor-default @endif
        "
          wire:click="changeQuantity({{ $cart->id }},'minus')">
          <div wire:loading.remove wire:target="changeQuantity({{ $cart->id }},'minus')" wire:key="minus">-</div>
          <div wire:loading wire:target="changeQuantity({{ $cart->id }},'minus')" wire:key="minus">
            @include('components.spinner')</div>
        </button>
        <input type="number" max="{{ $product->stock }}"
          @input.debounce.250ms="
          $wire.changeQuantity({{ $cart->id }},parseInt($event.target.value))
        "
          value="{{ $cart->quantity }}" class="quantity-field w-9 px-2 text-center h-7 border-0 bg-transparent">
        <button type="button"
          class="
        button-plus w-8 py-1 border-l cursor-pointer border-gray-300 flex items-center justify-center
        @if ($product->stock <= $cart->quantity) bg-gray-100 !cursor-default @endif
        "
          wire:click="changeQuantity({{ $cart->id }},'plus')">
          <div wire:loading.remove wire:target="changeQuantity({{ $cart->id }},'plus')" wire:key="plus">+</div>
          <div wire:loading wire:target="changeQuantity({{ $cart->id }},'plus')" wire:key="plus">
            @include('components.spinner')</div>
        </button>
      </div>
    </div>
    <!-- text -->
    <div class="text-sm">
      <a href="#!" class="text-primary flex items-center gap-1"
      x-on:click="$dispatch('delete-cart',{cartId: {{$cart->id}} })"
      >
        <span class="align-text-bottom">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="14"
            height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
            stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <path d="M4 7l16 0"></path>
            <path d="M10 11l0 6"></path>
            <path d="M14 11l0 6"></path>
            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
          </svg>
        </span>
        <span class="text-gray-500 text-sm">Hapus</span>
      </a>
    </div>
  </div>
</li>
