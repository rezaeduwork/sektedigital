<div class="w-full">
  <div class="modal-content p-8">
    <div class="flex justify-between items-center mb-4">
      <h3 class="font-bold text-gray-800" id="userModalLabel">Daftar Akun</h3>

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
          <label for="fullName" class="mb-2 block text-gray-800">Nama Lengkap</label>
          <input type="text"
            wire:model="name"
            class="form-control border border-gray-300 text-gray-900 rounded-lg !ring-none !outline-none focus:border-primary block p-2 px-3 disabled:opacity-50 disabled:pointer-events-none w-full text-base"
            id="fullName" required />
          @error('name')
          <small class="text-primary">{{$message}}</small>
          @enderror
        </div>
        <div class="mb-3">
          <label for="email" class="mb-2 block text-gray-800">Alamat Email</label>
          <input type="email"
          wire:model="email"
          class="form-control border border-gray-300 text-gray-900 rounded-lg !ring-none !outline-none focus:border-primary block p-2 px-3 disabled:opacity-50 disabled:pointer-events-none w-full text-base"
          id="email" autocomplete="email" required />
          @error('email')
          <small class="text-primary">{{$message}}</small>
          @enderror
        </div>
        <div class="mb-5">
          <label for="password" class="mb-2 block text-gray-800">Password</label>
          <input type="password"
          wire:model="password"
          class="form-control border border-gray-300 text-gray-900 rounded-lg !ring-none !outline-none focus:border-primary block p-2 px-3 disabled:opacity-50 disabled:pointer-events-none w-full text-base"
          id="password" required />
          @error('password')
          <small class="text-primary">{{$message}}</small>
          @enderror
          {{-- <span class="block mt-1 text-sm text-gray-500">
            By Signup, you agree to our
            <a href="#!" class="text-primary">Terms of Service</a>
            &
            <a href="#!" class="text-primary">Privacy Policy</a>
          </span> --}}
        </div>

        <button type="button"
          wire:click="register"
          class="btn w-full inline-flex items-center gap-x-2 bg-primary text-white border-primary disabled:opacity-50 disabled:pointer-events-none hover:text-white hover:bg-primary hover:border-primary active:bg-primary active:border-primary focus:outline-none focus:ring-4 focus:ring-red-300 justify-center">
          <span wire:loading.remove wire:key="register" wire:target="register">Daftar</span>
          <span wire:loading wire:key="register" wire:target="register">@include('components.spinner')</span>
        </button>
      </form>
    </div>
    <div class="modal-footer flex border-0 justify-center mt-3">
      Sudah Punya Akun ?
      <a href="#" @click="$wire.dispatch('toggle-form')" class="text-primary ml-1">Masuk</a>
    </div>
  </div>
</div>
