<div>
  <button data-toggle="modal" data-target="#show-deleted-modal" class="btn btn-secondary btn-sm">Approved Withdrawal ({{\App\Models\UserBalance::whereType('withdraw')->whereStatus('success')->count()}})</button>
  <div class="modal fade" id="show-deleted-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Approved Withdrawal</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          {{-- Search --}}
          <div class="input-group input-group-sm mb-2" style="width: 250px">
            <input
              type="text"
              wire:model.live.debounce.150ms="search"
              class="form-control float-right"
              placeholder="Search"
            />
          </div>
          <table class="table table-hover text-nowrap text-xs">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Balance</th>
                <th>Amount</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
            @foreach ($list as $row)
            <tr wire:key="{{'table'.$row->id}}">
              <td>{{$row->id}}</td>
              <td>{{$row->user->name}}</td>
              <td>{{$row->user->phone}}</td>
              <td class="">Rp{{number_format($row->user->balance,0,',','.')}}</td>
              <td class="text-red-600 font-semibold">Rp{{number_format($row->amount,0,',','.')}}</td>
            </tr>
            @endforeach
            </tbody>
          </table>
          {{-- LOAD MORE --}}
          @if ($perPage < $total)
          <div class="flex items-center justify-center">
            <button wire:click="loadMore" class="btn btn-secondary flex items-center space-x-1 text-xs">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 animate-spin" wire:loading wire:target="loadMore">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
              </svg>
              <div>
                Load More
              </div>
            </button>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
