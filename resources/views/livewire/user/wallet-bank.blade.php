<div x-data="{show: false}" @alert-success.window="show = false">
  <button class="bg-primary text-white py-2 px-4 rounded mb-4" @click="show = true">Tambah Bank</button>
  <template x-teleport="body">
    <div class="relative z-[999]" style="display: none;" x-show="show" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <!--
        Background backdrop, show/hide based on modal state.

        Entering: "ease-out duration-300"
          From: "opacity-0"
          To: "opacity-100"
        Leaving: "ease-in duration-200"
          From: "opacity-100"
          To: "opacity-0"
      -->
      <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>

      <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
          <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 space-y-2">
              <!-- CONTENT -->
              <div class="mb-5">
                <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipe Akun</label>
                <select wire:model.live.debounce.250ms="type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="bri,bca,mandiri" required>
                  <option value="bank">Bank Akun</option>
                  <option value="ewallet">E-Wallet</option>
                </select>
                @error('type')
                <div class="text-red-600">{{$message}}</div>
                @enderror
              </div>
              <div class="mb-5">
                <label for="bank" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama @if($type == 'bank') Bank @else E-Wallet @endif</label>
                <input type="text" wire:model.live.debounce.250ms="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="@if($type == 'bank') bri,bca,mandiri @else ovo,gopay,shopeepay @endif" required />
                @error('name')
                <div class="text-red-600">{{$message}}</div>
                @enderror
              </div>
              <div class="mb-5">
                <label for="number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nomor @if($type == 'bank') Rekening @else HP/E-Wallet @endif</label>
                <input type="number" wire:model.live.debounce.250ms="ref" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="1234" required />
                @error('ref')
                <div class="text-red-600">{{$message}}</div>
                @enderror
              </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
              <button type="button"
              @if($id)
              wire:click.prevent="doUpdate()"
              @else
              wire:click.prevent="store()"
              @endif
              class="inline-flex w-full justify-center rounded-md bg-primary px-3 py-2 text-sm font-semibold text-white shadow-sm sm:ml-3 sm:w-auto">Simpan</button>
              <button type="button" @click="show = false;$wire.resetProp();" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </div>

  </template>

  <div class="relative overflow-x-auto">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
      <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
          <th scope="col" class="px-6 py-3">
            Tipe
          </th>
          <th scope="col" class="px-6 py-3">
            Nama
          </th>
          <th scope="col" class="px-6 py-3">
            Nomor
          </th>
          <th scope="col" class="px-6 py-3">
            #
          </th>
        </tr>
      </thead>
      <tbody>
        @foreach (auth()->user()->banks as $row)
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
          <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
            {{$row->type}}
          </th>
          <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
            {{$row->name}}
          </th>
          <td class="px-6 py-4">
            {{$row->ref}}
          </td>
          <td class="px-6 py-4 flex items-center space-x-1">
            <button class="size-6 bg-yellow-500 text-white flex items-center justify-center rounded" @click="$wire.setUpdateAttr({{$row->id}},'{{$row->type}}','{{$row->name}}','{{$row->ref}}');show = true;">
              <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="size-3" viewBox="0 0 16 16">
                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
              </svg>
            </button>
            <div class="relative" x-data="{deleteConfirmation: false}" @alert-success.window="deleteConfirmation = false">
              <button class="bg-red-600 text-white size-6 flex items-center justify-center rounded" @click="deleteConfirmation = true">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="size-3" viewBox="0 0 16 16">
                  <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                </svg>
              </button>

              <div x-show="deleteConfirmation" style="display: none;" class="absolute z-10 min-w-[216px] overflow-auto rounded border border-slate-200 bg-white p-4 shadow-lg shadow-sm bottom-[120%] right-0">
                <div class="mb-2">Yakin ingin hapus ?</div>
                <div class="flex items-center space-x-2">
                  <button class="text-xs rounded p-2" @click="deleteConfirmation = false">Batal</button>
                  <button class="text-xs rounded p-2 bg-red-600 text-white" @click="$wire.delete({{$row->id}})">Ya, Lanjutkan</button>
                </div>
              </div>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

</div>
