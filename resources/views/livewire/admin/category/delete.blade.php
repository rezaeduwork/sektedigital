<div>
  <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#delete-modal-{{$category->id}}">Hapus</button>
  <div class="modal fade" id="delete-modal-{{$category->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Delete Category</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Are you sure want to delete this category?
        </div>
        <div class="modal-footer flex items-center">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <div wire:loading.remove wire:target="delete">
            <button type="button" class="btn btn-danger" wire:click="delete">Delete</button>
          </div>
          <div wire:loading wire:target="delete">
            <button type="button" class="btn btn-danger opacity-50">Delete</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
