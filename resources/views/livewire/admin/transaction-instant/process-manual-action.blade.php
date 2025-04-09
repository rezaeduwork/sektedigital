<div>
  <button class="btn btn-xs btn-warning" type="button" data-toggle="modal" data-target="#process-{{$transaction->id}}">Process Manual</button>
  <div class="modal fade" id="process-{{$transaction->id}}" tabindex="-1" aria-labelledby="process-{{$transaction->id}}Label" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-lg" id="process-{{$transaction->id}}Label">Process Transaksi</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="alert alert-warning break-word whitespace-normal text-md text-base">
            Dengan memproses transaksi ini, saldo dari provider akan terpotong sesuai dengan jumlah yang dibayarkan oleh pengguna. Apakah anda yakin ingin memproses transaksi ini?
          </div>
        </div>
        <div class="modal-footer flex items-center">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <div wire:loading.remove wire:target="process">
            <button type="button" class="btn btn-success" wire:click="process">Proses</button>
          </div>
          <div wire:loading wire:target="process">
            <button type="button" class="btn btn-success opacity-50">Proses</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
