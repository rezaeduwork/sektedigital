<div>
  <div class="flex items-center justify-between mb-4">
    <div class="font-bold">Balance</div>
    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-add-transaction">Add Transaction</button>
    <div class="modal" id="modal-add-transaction" wire:ignore.self>
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Add History</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label>Name</label>
              <input type="text" wire:model.live.debounce.250s="name" class="form-control border-primary">
            </div>
            <div class="form-group">
              <label for="">Type</label>
              <select wire:model.live.debounce.250s="type" class="form-control border-primary">
                <option value=""></option>
                <option value="fund">Fund</option>
                <option value="coin">Coin</option>
              </select>
            </div>
            <div class="form-group">
              <label>Status</label>
              <select wire:model.live.debounce.250s="status" class="form-control border-primary">
                <option value="pending">Pending</option>
                <option value="success">Success</option>
                <option value="reject">Reject</option>
              </select>
            </div>
            <div class="form-group">
              <label>Amount</label>
              <input type="number" wire:model.live.debounce.250s="amount" class="form-control border-primary">
            </div>
            <div class="form-group">
              <label>Description</label>
              <textarea wire:model.live.debounce.250s="description" class="form-control" id="" rows="3"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" wire:click="store()" class="btn btn-primary">Submit</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <table class="table">
    <thead>
      <tr>
        <td>Name</td>
        <td>Date</td>
        <td>Type</td>
        <td>Amount</td>
        <td>Status</td>
        <td>Description</td>
        <td>Action</td>
      </tr>
    </thead>
    <tbody>
      @foreach ($list as $row)
      <tr>
        <td>{{$row->name}}</td>
        <td class="text-sm">{{\Carbon\Carbon::parse($row->created_at)->translatedFormat('l, d F Y H:i')}}</td>
        <td>{{$row->type}}</td>
        <td>{{number_format($row->amount)}}</td>
        <td>{{$row->status}}</td>
        <td>{{$row->description}}</td>
        <td>
          <div class="flex items-center space-x-2">
            <button class="btn btn-xs btn-info">Update</button>
            <button class="btn btn-xs btn-danger" @click="if(confirm('Yakin hapus ?')) $wire.delete({{$row->id}});">Hapus</button>
          </div>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
