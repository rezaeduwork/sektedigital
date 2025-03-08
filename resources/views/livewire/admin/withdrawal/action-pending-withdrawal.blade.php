<div class="shrink-0 flex items-center space-x-1">
  <div>
    <button class="btn btn-xs btn-success flex items-center space-x-1" data-toggle="modal" data-target="#approve-modal-{{$history->id}}">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-all" viewBox="0 0 16 16">
        <path d="M8.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L2.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093L8.95 4.992zm-.92 5.14.92.92a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 1 0-1.091-1.028L9.477 9.417l-.485-.486z"/>
      </svg>
      <div>Finish</div>
    </button>
    <div class="modal fade" id="approve-modal-{{$history->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Delete Category</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            Are you sure want to approve this withdrawal?
          </div>
          <div class="modal-footer flex items-center">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <div wire:loading.remove wire:target="approve({{$history->id}})">
              <button type="button" class="btn btn-warning" wire:click="approve({{$history->id}})">Approve</button>
            </div>
            <div wire:loading wire:target="approve({{$history->id}})">
              <button type="button" class="btn btn-warning opacity-50">
                <div>Approve</div>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div>
    <button class="btn btn-xs btn-danger flex items-center space-x-1" data-toggle="modal" data-target="#reject-modal-{{$history->id}}">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
      </svg>
      <div>Reject</div>
    </button>
    <div class="modal fade" id="reject-modal-{{$history->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Reject Withdrawal</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <label for="">Note</label>
            <textarea wire:model.live.debounce.250ms="note" cols="4" class="form-control"></textarea>
          </div>
          <div class="modal-footer flex items-center">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <div wire:loading.remove wire:target="reject({{$history->id}})">
              <button type="button" class="btn btn-warning" wire:click="reject({{$history->id}})">Reject</button>
            </div>
            <div wire:loading wire:target="reject({{$history->id}})">
              <button type="button" class="btn btn-warning opacity-50">
                <div>Approve</div>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
