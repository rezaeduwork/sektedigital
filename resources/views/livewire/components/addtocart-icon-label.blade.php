<button type="button"
@auth
wire:click="add(1)"
@endauth
@guest
data-bs-toggle="modal" data-bs-target="#userModal" href="#"
@endguest role="button"
class="btn gap-x-1 bg-primary shrink-0 text-white border-primary disabled:opacity-50 disabled:pointer-events-none hover:text-white hover:bg-primary hover:border-primary active:bg-primary active:border-primary focus:outline-none focus:ring-4 focus:ring-primary justify-center">
  <div wire:loading.remove wire:target="add" wire:key="add{{$product->id}}">
    <div class="flex items-center space-x-1">
      <svg xmlns="../www.w3.org/2000/svg.html" width="18" height="18" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
        class="icon icon-tabler icons-tabler-outline icon-tabler-shopping-bag">
        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
        <path
          d="M6.331 8h11.339a2 2 0 0 1 1.977 2.304l-1.255 8.152a3 3 0 0 1 -2.966 2.544h-6.852a3 3 0 0 1 -2.965 -2.544l-1.255 -8.152a2 2 0 0 1 1.977 -2.304z" />
        <path d="M9 11v-5a3 3 0 0 1 6 0v5" />
      </svg>
      <span>
        Masukkan Keranjang
      </span>
    </div>
  </div>
  <span wire:loading wire:target="add" wire:key="add{{$product->id}}">@include('components.spinner')</span>
</button>
