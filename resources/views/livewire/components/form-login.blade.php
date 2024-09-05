<div class="w-full">
  <div class="modal-content p-8">
    <div class="flex justify-between items-center mb-4">
      <h3 class="font-bold text-primary text-lg" id="userModalLabel">Masuk Member</h3>

      <button type="button" class="btn-close bg-primary text-white p-1 rounded-full" data-bs-dismiss="modal" aria-label="Close">
        <svg xmlns="../www.w3.org/2000/svg.html" class="icon icon-tabler icon-tabler-x" width="24"
          height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
          stroke-linecap="round" stroke-linejoin="round">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <path d="M18 6l-12 12" />
          <path d="M6 6l12 12" />
        </svg>
      </button>
    </div>
    <div class="modal-body">
      <form class="needs-validation" novalidate>
        <div class="mb-3">
          <label for="email" class="mb-2 block text-gray-800">Alamat Email</label>
          <input type="email"
            wire:model.live.debounce.250ms="email"
            class="form-control border border-gray-300 text-gray-900 rounded-lg !ring-none !outline-none focus:border-primary block p-2 px-3 disabled:opacity-50 disabled:pointer-events-none w-full text-base"
            id="email" autocomplete="email" required />
          @error('email')
          <small class="text-primary">{{$message}}</small>
          @enderror
        </div>
        <div class="mb-5">
          <label for="password" class="mb-2 block text-gray-800">Password</label>
          <input type="password"
            wire:model.live.debounce.250ms="password"
            class="form-control border border-gray-300 text-gray-900 rounded-lg !ring-none !outline-none focus:border-primary block p-2 px-3 disabled:opacity-50 disabled:pointer-events-none w-full text-base"
            id="password" required />
          @error('password')
          <small class="text-primary">{{$message}}</small>
          @enderror
        </div>

        <button type="button"
          wire:click="login"
          wire:loading.remove
          wire:target="login"
          wire:key="login"
          class="btn w-full inline-flex items-center gap-x-2 bg-primary text-white border-primary disabled:opacity-50 disabled:pointer-events-none hover:text-white hover:bg-primary hover:border-primary active:bg-primary active:border-primary focus:outline-none focus:ring-4 focus:ring-violet-300 justify-center">
          Masuk
        </button>
        <button type="button"
          wire:loading.class="!inline-flex"
          wire:target="login"
          wire:key="login"
          class="btn w-full hidden items-center gap-x-2 bg-primary text-white border-primary disabled:opacity-50 disabled:pointer-events-none hover:text-white hover:bg-primary hover:border-primary active:bg-primary active:border-primary focus:outline-none focus:ring-4 focus:ring-violet-300 justify-center">
          @include('components.spinner')
        </button>
      </form>
    </div>
    <div class="modal-footer flex border-0 justify-center mt-3">
      Belum Punya Akun ?
      <a href="#" @click="$wire.dispatch('toggle-form')" class="text-primary ml-1">Daftar</a>
    </div>
  </div>
</div>
