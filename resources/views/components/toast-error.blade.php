<div id="toast-danger" @alert-error.window="openToast('error',$event)"
:class="open == 'error' ? '!flex animate-slideUp':''"
style="display: none;"
wire:ignore
class="fixed flex right-0 bottom-0 items-center w-full max-w-xs p-4 mb-4 text-white bg-primary rounded-lg shadow z-[9999]" role="alert">
  <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-violet-500 bg-violet-100 rounded-lg">
      <svg class="!w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
          <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>
      </svg>
      <span class="sr-only">Error icon</span>
  </div>
  <div class="ms-3 text-sm font-normal text-white" x-text="message"></div>
  <button type="button" @click="closeToast()" class="ms-auto -mx-1.5 -my-1.5 text-white rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 inline-flex items-center justify-center h-8 w-8" data-dismiss-target="#toast-danger" aria-label="Close">
      <span class="sr-only">Close</span>
      <svg class="!w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
      </svg>
  </button>
</div>
