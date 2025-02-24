<div>
  <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#restore-modal-{{$product->id}}">Restore</button>
  <div class="modal fade" id="restore-modal-{{$product->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Restore Product</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Are you sure want to restore this category?
        </div>
        <div class="modal-footer flex items-center">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <div wire:loading.remove wire:target="restore">
            <button type="button" class="btn btn-danger" wire:click="restore">Restore</button>
          </div>
          <div wire:loading wire:target="restore">
            <button type="button" class="btn btn-danger opacity-50">Restore</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
