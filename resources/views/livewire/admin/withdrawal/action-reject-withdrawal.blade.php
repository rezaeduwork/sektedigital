<div>
  <button class="btn btn-xs btn-info flex items-center space-x-1" data-toggle="modal" data-target="#show-note-modal-{{$history->id}}">
    <div>Show Note</div>
  </button>
  <div class="modal fade" id="show-note-modal-{{$history->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Show Note</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          @php
          $data = json_decode($history->data);
          @endphp
          @if ($data && $data->note)
          {{$data->note}}
          @else
          Empty note
          @endif
        </div>
        <div class="modal-footer flex items-center">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>
