<div>
  <div class="mb-4">
    <h4 class="text-lg font-semibold">Profil Saya</h4>
    <p>Kelola informasi profil Anda untuk mengontrol, melindungi dan mengamankan akun</p>
  </div>
  <div class="mb-5">
    <label for="name" class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Nama Lengkap</label>
    <input type="text" id="name" wire:model.live.debounce.250ms="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-300 focus:border-red-300 block w-full p-2.5" placeholder="" required />
    @error('name')
    <small class="text-primary">{{$message}}</small>
    @enderror
  </div>
  <div class="mb-5">
    <label for="email" class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Email</label>
    {{-- <input type="email" id="email" disabled class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-300 focus:border-red-300 block w-full p-2.5" placeholder="" required /> --}}
    <div>{{auth()->user()->email}}</div>
  </div>
  <div class="mb-5">
    <label for="phone" class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Nomor HP</label>
    <input type="number" wire:model.live.debounce.250ms="phone" id="phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-300 focus:border-red-300 block w-full p-2.5" placeholder="" required />
    @error('phone')
    <small class="text-primary">{{$message}}</small>
    @enderror
  </div>
  <div class="mb-5">
    <label for="gender" class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Jenis Kelamin</label>
    <fieldset class="flex items-center space-x-4">

      <div class="flex items-center mb-4">
        <input id="country-option-1" type="radio" name="gender" value="male" class="w-4 h-4 border-gray-300 focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-600 dark:focus:bg-blue-600 dark:bg-gray-700 dark:border-gray-600" wire:model.live="gender">
        <label for="country-option-1" class="block ms-2  text-sm font-medium text-gray-900 dark:text-gray-300">
          Laki - laki
        </label>
      </div>

      <div class="flex items-center mb-4">
        <input id="country-option-2" type="radio" name="gender" value="female" class="w-4 h-4 border-gray-300 focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-600 dark:focus:bg-blue-600 dark:bg-gray-700 dark:border-gray-600" wire:model.live="gender">
        <label for="country-option-2" class="block ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
          Perempuan
        </label>
      </div>
    </fieldset>
    @error('gender')
    <small class="text-primary">{{$message}}</small>
    @enderror
  </div>
  <div class="mb-5">
    <label for="birthdate" class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Tanggal Lahir</label>
    <input type="date" id="birthdate" wire:model.live.debounce.250ms="birthdate" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-300 focus:border-red-300 block w-full p-2.5" placeholder="" required />
    @error('birthdate')
    <small class="text-primary">{{$message}}</small>
    @enderror
  </div>
  <div class="mb-5">
    <label for="address" class="block mb-2 text-sm font-semibold text-gray-900 dark:text-white">Alamat Lengkap</label>
    <textarea id="address" wire:model.live.debounce.250ms="address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-red-300 focus:border-red-300 block w-full p-2.5"
    placeholder="" required></textarea>
    @error('Alamat')
    <small class="text-primary">{{$message}}</small>
    @enderror
  </div>

  <button type="button" wire:click="update" class="text-white bg-primary hover:bg-primary focus:ring-4 focus:ring-red-300 font-semibold rounded-lg text-sm px-5 py-2.5 me-2 mb-2">
    Simpan
  </button>
</div>
