<div class="mb-5 container max-sm:mt-[3rem] max-sm:pb-[4rem]">
  @php
  $userBanks = auth()->user()->banks;
  @endphp
  <div class="flex items-center justify-between mb-4">
    {{-- <div class="text-lg sm:text-3xl font-semibold w-full text-gray-700 mb-4 sm:mb-6">Detail Saldo</div> --}}
    @if ($page == 'bank')
    <button type="button" class="text-primary px-4 py-2 rounded shrink-0 bg-white border-2 border-primary flex items-center justify-center space-x-1" @click="$wire.set('page', '')">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
      </svg>
      <div>Kembali</div>
    </button>
    @endif
  </div>
  <div class="flex items-start flex-col space-y-4 md:flex-row md:space-y-0 md:space-x-4">
    <div class="shrink-0 w-full sm:w-[280px] py-2 px-4 bg-white shadow rounded items-center sm:py-4 sm:bg-white sm:rounded-lg sm:shadow sm:px-4 sm:pb-4">
      <div class="flex space-x-4 items-center">
        <img src="{{ url('assets/images/wallet.png') }}" alt="" srcset="" class="size-6 shrink-0">
        <div class="w-full">
          <div>Saldo Saya</div>
          <div class="text-lg font-black">Rp. {{number_format(auth()->user()->balance)}}</div>
        </div>
        <button class="shrink-0" type="button">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" class="size-5">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
          </svg>
        </button>
      </div>
      <div class="p-2">
        Lihat penjelasan Saldo <a href="http://" class="text-link">di sini</a>
      </div>
      <hr class="mt-2 mb-4" />
      <div class="flex flex-col space-y-2 md:flex-row md:space-x-2 md:space-y-0 text-white" x-data="{withdrawOpen: false}" @alert-success.window="withdrawOpen = false">
        {{-- <button class="bg-primary py-2 w-full rounded">Deposit</button> --}}
        <button class="bg-primary py-2 w-full rounded" @click="withdrawOpen = true">Tarik Saldo</button>
        <template x-teleport="body">
          <div class="relative z-[999]" style="display: none;" x-show="withdrawOpen" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
              <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                  <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 space-y-2">
                    <!-- CONTENT -->
                    @if ($userBanks->count() > 0)
                      <div class="flex items-center p-4 mb-4 text-sm text-blue-800 border border-blue-300 rounded-lg bg-blue-50" role="alert">
                        <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                          <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                        </svg>
                        <span class="sr-only">Info</span>
                        <div>
                          <span class="font-medium">Catatan!</span> Tarik saldo membutuhkan waktu maksimal 3x24 jam, Jika dalam waktu tersebut belum ada proses silahkan <a class="underline text-link" href="{{url('/')}}" target="_blank">hubungi admin</a>
                        </div>
                      </div>
                      <div class="mb-6">
                        <div for="" class="mb-2">Rekening Penarikan</div>
                        <select wire:model.live.debounce.250ms="ref_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="" required>
                          <option value="">-- Pilih Rekening Penarikan --</option>
                          @foreach ($userBanks as $row)
                          <option value="{{$row->id}}">({{$row->type}}) {{$row->name}} - {{$row->ref}}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="mb-4">
                        <div for="" class="mb-2">Jumlah Penarikan</div>
                        <input wire:model.live.debounce.250ms="amount" type="number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="1234" required />
                        <div class="text-xs">
                          <div>
                            *Minimal <span class="text-red-600">Rp{{number_format($minWithdraw)}}</span>
                          </div>
                          <div>
                            *Maksimal yang dapat ditarik <span class="text-red-600">Rp{{number_format(auth()->user()->balance)}}</span>
                          </div>
                        </div>
                      </div>
                      @error('ref_id')
                      <div class="text-red-600">{{$message}}</div>
                      @enderror
                    @else
                      <div class="flex items-center p-4 mb-4 text-sm text-yellow-800 border border-yellow-300 rounded-lg bg-yellow-50" role="alert">
                        <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                          <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                        </svg>
                        <span class="sr-only">Info</span>
                        <div>
                          <span class="font-medium">Perhatian!</span> Kamu belum memiliki rekening penarikan, silahkan tambahkan terlebih dahulu di menu rekening penarikan di sidebar!
                        </div>
                      </div>
                    @endif
                  </div>
                  <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" class="inline-flex w-full justify-center rounded-md px-3 py-2 text-sm font-semibold
                    @if($userBanks->count() > 0 && $ref_id && $amount > 0)
                    bg-primary text-white
                    @else
                    cursor-default bg-primary/50 text-white
                    @endif
                    shadow-sm sm:ml-3 sm:w-auto"
                    @if($userBanks->count() > 0 && $ref_id && $amount > 0)
                    wire:click="withdraw()"
                    @endif
                    >Tarik Saldo</button>
                    <button type="button" @click="withdrawOpen = false" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancel</button>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </template>

      </div>
      {{-- <a href="#" target="_blank" class="text-green-600 mt-2 block underline">Deposit Manual ? Klik disini</a> --}}
      <a href="https://api.whatsapp.com/send?phone=62895355094422" target="_blank" class="text-red-600 mt-2 block underline">Withdrawal bermasalah ? Lapor disini</a>
      <hr class="mt-3 mb-2" />
      {{-- <div class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group text-xs">
        <img src="{{ url('assets/images/coin.png') }}" alt="" srcset="" class="size-6 shrink-0">
        <div class="ms-3 flex w-full justify-between items-center">
          <div>Koin</div>
          <div>0</div>
        </div>
      </div>
      <div class="p-2">
        Lihat penjelasan Koin <a href="http://" class="text-link">di sini</a>
      </div>
      <hr class="mt-3 mb-2" /> --}}
      <button @click="$wire.set('page', 'bank')" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group bg-gray-100 rounded text-xs">
        <img src="{{ url('assets/images/bank.png') }}" alt="" srcset="" class="size-6 shrink-0">
        <div class="ms-3 flex w-full justify-between items-center">
          <div>Rekening Penarikan</div>
          @if (auth()->user()->banks()->count())
          <div>{{auth()->user()->banks()->count()}}</div>
          @else
          <div class="text-yellow-400">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
              <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2"/>
            </svg>
          </div>
          @endif
        </div>
      </button>
    </div>
    <div class="w-full bg-white shadow rounded p-4">
      @if ($page == 'bank')
      <livewire:user.wallet-bank>
      @else
      <livewire:user.wallet.history>
      @endif
    </div>
  </div>
</div>
