<div>
  @php
  $availableCarts = auth()->user()->carts()->availableProduct()->get();
  @endphp
  <div class="mt-4">
    <div class="container">
      <div class="flex flex-wrap">
        <div class="w-full">
          <!-- breadcrumb -->
          <nav aria-label="breadcrumb">
            <ol class="flex flex-wrap">
              <li class="inline-block text-primary mr-2">
                <a href="#!">
                  Home
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-right inline-block"
                    width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M9 6l6 6l-6 6"></path>
                  </svg>
                </a>
              </li>
              <li class="inline-block text-primary mr-2">
                <a href="#!">
                  Shop
                  <svg xmlns="http://www.w3.org/2000/svg"
                    class="icon icon-tabler icon-tabler-chevron-right inline-block" width="14" height="14"
                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M9 6l6 6l-6 6"></path>
                  </svg>
                </a>
              </li>
              <li class="inline-block text-gray-500 active" aria-current="page">Cart</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
  <div class="my-5">
    <div class="container">
      <div class="flex flex-wrap">
        <div class="w-full mb-6">
          <!-- card -->
          <h1 class="text-xl font-black">Keranjang</h1>
        </div>
      </div>

      <div class="flex flex-wrap lg:flex-nowrap lg:gap-x-12 gap-y-6">
        <div class="lg:w-2/3 w-full">
          <div class="flex flex-col gap-5">
            <!-- alert -->
            {{-- <div class="bg-violet-500 bg-opacity-25 text-violet-900 rounded-lg py-3 px-4" role="alert">
              You’ve got FREE delivery. Start
              <a href="#!" class="text-violet-950">checkout now!</a>
            </div> --}}
            @if ($availableCarts->count() > 0)
            <div class="flex justify-between items-center w-full">
              <div class="flex items-center">
                <input type="checkbox" wire:model.live="selectedAll"
                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="select-all-checkbox" class="ms-2 text-sm font-medium text-gray-600">Pilih Semua</label>
              </div>
              @if (sizeof($selected) > 0)
              <button type="button" @click="$wire.set('selected', [])" class="font-bold text-link underline">Hapus ({{sizeof($selected)}}) dari keranjang</button>
              @else
              <div></div>
              @endif
            </div>
            @endif
            <ul class="list-none">
              <!-- list group -->
              @forelse ($availableCarts as $row)
              <livewire:components.cart.product-item :key="$row->id.uniqid()" :isSelected="in_array($row->id, $selected)" :cart="$row" :product="$row->product">
              @empty
              <li>Belum ada produk di keranjang, silahkan masukkan barang terlebih dahulu.</li>
              @endforelse
            </ul>
          </div>
        </div>

        <!-- sidebar -->
        <div class="w-full lg:w-1/3 md:w-full">
          <!-- card -->
          <div class="relative card min-w-0">
            <div class="card-body flex flex-col gap-4">
              {{-- <div>
                <div>
                  <p class="mt-1">
                    <span class="text-sm">
                      By placing your order, you agree to be bound by the Freshcart
                      <a href="#!" class="text-primary">Terms of Service</a>
                      and
                      <a href="#!" class="text-primary">Privacy Policy.</a>
                    </span>
                  </p>
                </div>
                <div class="flex flex-col gap-3">
                  <h5>Add Promo or Gift Card</h5>
                  <form class="flex flex-col gap-3">
                    <div class="flex flex-col gap-2">
                      <div>
                        <label for="giftcard" class="mb-2 block text-gray-800">Email address</label>
                        <input type="text"
                          class="border border-gray-300 text-gray-900 rounded-lg focus:shadow-[0_0_0_.25rem_rgba(10,173,10,.25)] focus:ring-primary focus:ring-0 focus:border-primary block p-2 px-3 disabled:opacity-50 disabled:pointer-events-none w-full text-base"
                          id="giftcard" placeholder="Promo or Gift Card">
                      </div>
                      <div class="grid">
                        <button type="submit"
                          class="btn inline-flex items-center gap-x-2 bg-transparent text-gray-800 border-gray-800 disabled:opacity-50 disabled:pointer-events-none hover:text-white hover:bg-gray-900 hover:border-gray-900 active:bg-gray-900 active:border-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300">
                          Redeem
                        </button>
                      </div>
                    </div>

                    <p class="text-gray-500 text-sm">Terms &amp; Conditions apply</p>
                  </form>
                </div>
              </div> --}}
              <h2 class="text-md">Ringkasan Pembelian</h2>
              <div
                class="relative flex flex-col min-w-0 rounded-lg break-words border bg-white border-1 border-gray-300">
                <!-- list group -->
                <ul class="flex flex-col">
                  <!-- list group item -->
                  <li
                    class="relative py-3 px-4 -mb-px border-b border-r-0 border-l-0 border-gray-300 no-underline flex justify-between items-start">
                    <div>
                      <div>Subtotal</div>
                    </div>
                    <span>Rp. {{number_format(totalTransaction(auth()->user()->carts()->whereIn('id', collect($selected))->get()))}}</span>
                  </li>

                  <!-- list group item -->
                  {{-- <li
                    class="relative py-3 px-4 -mb-px border-b border-r-0 border-l-0 border-gray-300 no-underline flex justify-between items-start">
                    <div>
                      <div>Service Fee</div>
                    </div>
                    <span>$3.00</span>
                  </li> --}}
                  <!-- list group item -->
                  <li
                    class="relative py-3 px-4 -mb-px border-r-0 border-l-0 border-gray-300 no-underline flex justify-between items-start">
                    <div>
                      <div class="font-bold text-gray-800">Total Pembayaran</div>
                    </div>
                    <span class="font-bold text-gray-800">Rp. {{number_format(totalTransaction(auth()->user()->carts()->whereIn('id', collect($selected))->get()))}}</span>
                  </li>
                </ul>
                <div class="grid">
                  <!-- btn -->
                  <button
                    class="btn text-center w-full !rounded-t-none flex justify-center
                    @if(sizeof($selected) > 0)
                    bg-primary text-white border-primary hover:text-white hover:bg-primary hover:border-primary active:bg-primary active:border-primary focus:outline-none focus:ring-4 focus:ring-violet-300
                    @else
                    bg-gray-200 text-gray-600 cursor-default
                    @endif
                    disabled:opacity-50 disabled:pointer-events-none
                    btn-lg"
                    @if(sizeof($selected) > 0)
                    wire:click="checkout"
                    @endif
                    type="button"
                    >
                    <div wire:loading.remove wire:key="checkout" wire:target="checkout">Checkout</div>
                    <div wire:loading wire:key="checkout" wire:target="checkout">@include('components.spinner')</div>
                </button>
                </div>
              </div>
              <a href="{{url('shop')}}" class="underline text-gray-600 block text-center font-semibold" wire:navigate>Lanjut Belanja</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
