<div class="relative z-[999]" style="display: none;" x-show="depositOpen" aria-labelledby="modal-title" role="dialog" aria-modal="true">
  <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>

  <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
      <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 space-y-2">
          <!-- CONTENT -->
          <div class="flex items-center p-4 mb-4 text-sm text-blue-800 border border-blue-300 rounded-lg bg-blue-50" role="alert">
            <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
              <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <span class="sr-only">Info</span>
            <div>
              <span class="font-medium">Catatan!</span> Deposit akan segera ditambahkan ke saldo Anda setelah pembayaran berhasil.
            </div>
          </div>

          <div class="mb-4">
            <div for="" class="mb-2">Jumlah Deposit</div>
            <input wire:model.live.debounce.250ms="depositAmount" type="number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="50000" required />
            <div class="text-xs">
              <div>
                *Minimal <span class="text-red-600">Rp{{number_format($minDeposit)}}</span>
              </div>
            </div>
          </div>

          <div class="mb-6">
            <div for="" class="mb-2">Metode Pembayaran</div>
            <select wire:model.live="paymentMethod" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="" required>
              <option value="">-- Pilih Metode Pembayaran --</option>
              @foreach ($paymentMethods as $method)
              <option value="{{ $method['code'] }}">
                {{ $method['name'] }}
                @if($method['gateway_display_name'])
                  ({{ $method['gateway_display_name'] }})
                @endif
              </option>
              @endforeach
            </select>
            @if(count($paymentMethods) === 0)
              <div class="text-xs text-gray-500 mt-2">
                Tidak ada metode pembayaran yang tersedia. Pastikan ada gateway yang aktif.
              </div>
            @endif
          </div>

          @error('depositAmount')
          <div class="text-red-600">{{$message}}</div>
          @enderror

          @error('paymentMethod')
          <div class="text-red-600">{{$message}}</div>
          @enderror
        </div>

        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
          <button
            type="button"
            class="inline-flex w-full justify-center rounded-md px-3 py-2 text-sm font-semibold
            @if($depositAmount >= $minDeposit && $paymentMethod)
            bg-primary text-white hover:bg-primary/90
            @else
            cursor-default bg-primary/50 text-white
            @endif
            shadow-sm sm:ml-3 sm:w-auto"
            @if($depositAmount >= $minDeposit && $paymentMethod)
            wire:click="initiateDeposit"
            wire:loading.attr="disabled"
            @endif
          >
            <span wire:loading.remove wire:target="initiateDeposit">Deposit Sekarang</span>
            <span wire:loading wire:target="initiateDeposit" class="inline-flex items-center">
              <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Memproses...
            </span>
          </button>
          <button
            type="button"
            @click="depositOpen = false"
            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto"
          >
            Batal
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
