<div>
  <a href="#update-modal-{{$gateway->id}}" data-toggle="modal" class="btn btn-xs btn-primary">
    <i class="fas fa-edit"></i> Configure
  </a>

  <!-- Modal -->
  <div class="modal fade" id="update-modal-{{$gateway->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">
            Configure {{$gateway->display_name}} Gateway
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Display Name -->
          <div class="form-group">
            <label for="display_name">Display Name</label>
            <input type="text" class="form-control" wire:model="display_name" placeholder="Enter display name">
            @error('display_name')
            <small class="form-text text-danger">{{$message}}</small>
            @enderror
          </div>

          <!-- Description -->
          <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" wire:model="description" rows="2" placeholder="Enter description"></textarea>
            @error('description')
            <small class="form-text text-danger">{{$message}}</small>
            @enderror
          </div>

          <!-- Status Toggle -->
          <div class="form-group">
            <label for="environment">Status</label>
            <select class="form-control" wire:model="status">
              <option value="inactive">Inactive</option>
              <option value="active">Active</option>
            </select>
          </div>

          <hr>

          <!-- Environment -->
          <div class="form-group">
            <label for="environment">Environment</label>
            <select class="form-control" wire:model="environment">
              <option value="sandbox">Sandbox / Testing</option>
              <option value="production">Production / Live</option>
            </select>
          </div>

          <hr>
          <h6 class="text-muted mb-3">API Credentials</h6>

          <!-- Dynamic Fields Based on Gateway Type -->
          @if($gateway->name === 'tripay')
            <!-- Tripay Fields -->
            <div class="form-group">
              <label for="api_key">API Key</label>
              <input type="text" class="form-control" wire:model="api_key" placeholder="Enter Tripay API Key">
            </div>

            <div class="form-group">
              <label for="private_key">Private Key</label>
              <input type="text" class="form-control" wire:model="private_key" placeholder="Enter Tripay Private Key">
            </div>

            <div class="form-group">
              <label for="merchant_code">Merchant Code</label>
              <input type="text" class="form-control" wire:model="merchant_code" placeholder="Enter Tripay Merchant Code">
            </div>

          @elseif($gateway->name === 'xendit')
            <!-- Xendit Fields -->
            <div class="form-group">
              <label for="api_key">API Key</label>
              <input type="text" class="form-control" wire:model="api_key" placeholder="Enter Xendit API Key">
            </div>

            <div class="form-group">
              <label for="callback_token">Callback Token</label>
              <input type="text" class="form-control" wire:model="callback_token" placeholder="Enter Xendit Callback Token">
            </div>

          @elseif($gateway->name === 'sakurupiah')
            <!-- SakuRupiah Fields -->
            <div class="form-group">
              <label for="api_id">API ID</label>
              <input type="text" class="form-control" wire:model="api_id" placeholder="Enter SakuRupiah API ID">
            </div>

            <div class="form-group">
              <label for="api_key">API Key</label>
              <input type="text" class="form-control" wire:model="api_key" placeholder="Enter SakuRupiah API Key">
            </div>

          @elseif($gateway->name === 'paymenku')
            <!-- Paymenku Fields -->
            <div class="form-group">
              <label for="api_key">API Key</label>
              <input type="text" class="form-control" wire:model="api_key" placeholder="Enter Paymenku API Key">
            </div>
          @endif

        </div>
        <div class="modal-footer flex items-center">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <div wire:loading.remove wire:target="update">
            <button type="button" class="btn btn-primary" wire:click="update">
              <i class="fas fa-save"></i> Save Changes
            </button>
          </div>
          <div wire:loading wire:target="update">
            <button type="button" class="btn btn-primary opacity-50 cursor-default">
              <i class="fas fa-spinner fa-spin"></i> Saving...
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
