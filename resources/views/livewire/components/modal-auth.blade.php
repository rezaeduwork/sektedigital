<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true" wire:ignore.self>
  <div class="modal-dialog modal-dialog-centered">
    @if ($form == 'login')
    <livewire:components.form-login>
    @else
    <livewire:components.form-register>
    @endif
  </div>
</div>
