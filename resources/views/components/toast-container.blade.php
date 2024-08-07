<div wire:ignore x-data="{
  open: '',
  message: '',
  openToast(type,event) {
    this.open = type;
    this.message = event.detail.message;
    setTimeout(() => {
      this.closeToast()
    }, 3000);
  },
  closeToast() {
    this.open = '';
    this.message = '';
  }
}"
>
  @include('components.toast-success')
  @include('components.toast-error')
</div>
