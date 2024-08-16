<div>
  <div class="flex items-center space-x-2">
    <div class="w-2 h-2 rounded-full shrink-0
    @if($status == 'active')
    bg-green-600
    @else
    bg-yellow-300
    @endif
    "></div>
    <select wire:model.live="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 !ring-0 !outline-0">
      <option value="" readonly></option>
      <option value="active">Aktif</option>
      <option value="inactive">Tidak Aktif</option>
    </select>
  </div>
  @error('status')
  <div class="text-primary">{{$message}}</div>
  @enderror
</div>
