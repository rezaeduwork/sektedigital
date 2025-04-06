<div class="space-y-4 bg-white rounded-lg">
  <div class="p-4">
    <div class="flex items-center p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
      <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
      </svg>
      <span class="sr-only">Info</span>
      <div>
        <span class="font-medium">Informasi!</span> Pastikan provider dan nomor HP sesuai.
      </div>
    </div>
    <div class="bg-white text-gray-700 rounded-md flex items-end gap-4">
      <div class="flex items-start flex-col w-[500px]">
        <label class="block mb-2 text-xs font-semibold text-gray-900 shrink-0">Provider</label>
        <select wire:model.live.debounce.400ms="provider" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Pilih Nominal" required>
          <option value="" readonly></option>
          @foreach ($providers as $row)
          <option class="{{$row}}">{{$row}}</option>
          @endforeach
        </select>
      </div>
      <div class="flex flex-col items-start w-full">
        <label class="block mb-2 text-xs font-semibold text-gray-900 shrink-0">Nomor HP</label>
        <input type="number" wire:model.live.debounce.400ms="phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
        placeholder="628xxx atau 08xxx" required />
      </div>
      <div class="flex items-start flex-col w-full">
        <label class="block mb-2 text-xs font-semibold text-gray-900 shrink-0">Nominal</label>
        <select wire:model.live.debounce.400ms="amount" @if(!$this->provider || !$this->phone || !preg_match('/^(08|62)/', $this->phone)) disabled :class="'opacity-[0.5]'" @endif class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Pilih Nominal" required>
          <option value=""></option>
          @foreach ($products as $row)
          <option value="{{$row->id}}">{{$row->title}}</option>
          @endforeach
        </select>
      </div>
      <div class="flex flex-col">
        <button type="button" class="bg-primary text-white font-semibold shrink-0 rounded-md px-6 py-2.5 @if(!$validated) cursor-default opacity-[0.5] @endif"
        @if($validated)
          @click="Livewire.navigate('{{url('i/'.$product->code)}}?provider={{$provider}}&phone={{$phone}}&productId={{$amount}}')"
        @endif
        >
          Beli
        </button>
      </div>
    </div>
    @if ($errors->any())
    <div class="flex items-center p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 mt-4" role="alert">
      <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
      </svg>
      <span class="sr-only">Info</span>
      <div>
        {{$errors->first()}}
      </div>
    </div>
    @endif
  </div>

</div>
