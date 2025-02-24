<div>
  <a href="#create-modal" data-toggle="modal" class="btn btn-sm btn-primary">Create</a>
  <!-- Modal -->
  <div class="modal fade" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Update Category</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="exampleInputEmail1">Name</label>
            <input type="text" class="form-control" wire:model.live.debounce.150ms="name" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter name">
            @error('name')
            <small class="form-text text-muted !text-red-600">{{$message}}</small>
            @enderror
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">
              Icon <small>*optional</small>
            </label>
            <input type="file" class="form-control" wire:model.live.debounce.150ms="icon" placeholder="Enter icon">
            <div wire:loading wire:target="icon" class="text-green-600">Uploading...</div>
            @error('icon')
            <small class="form-text text-muted !text-red-600">{{$message}}</small>
            @enderror
          </div>
        </div>
        <div class="modal-footer flex items-center">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <div wire:loading.remove wire:target="store">
            <button type="button" class="btn btn-primary" wire:click="store">Submit</button>
          </div>
          <div wire:loading wire:target="store">
            <button type="button" class="btn opacity-50 cursor-default">Submit</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
