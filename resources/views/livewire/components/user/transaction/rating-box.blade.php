<div>
  <div class="mt-4 p-4 bg-gray-100 rounded-lg border border-gray-200">
    <div class="font-semibold sm:text-lg mb-3">Bantu toko dengan memberikan rating 😊</div>
    <div class="flex flex-rating items-center gap-2 mb-2">
        <div class="flex" onmouseout="resetStars({{$rating}})">
          @for ($i = 1; $i <= 5; $i++)
          <button @click.prevent="$wire.set('rating',{{ $i }})" class="focus:outline-none" :key="'star'.$i">
            <svg class="w-5 h-5 cursor-pointer {{ $i <= $rating ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-400 transition-all duration-100"
            fill="currentColor"
            viewBox="0 0 20 20"
            onmouseover="highlightStars({{ $i }})"
            >
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
          </button>
          @endfor
        </div>
    </div>
    <textarea
    rows="2"
    wire:model.live="feedback"
    class="resize-none w-full mb-4 block p-2.5 text-xs placeholder:text-xs sm:text-sm sm:placeholder:text-xs text-gray-900 bg-gray-50 rounded-lg border border-gray-300"
    placeholder="Tulis feedback"></textarea>

    <button
    class="
    max-sm:text-xs max-sm:w-full
    @if ($rating > 0)
    bg-green-600 hover:bg-green-700
    @else
    bg-green-600/50
    cursor-default
    @endif
    transition-colors duration-200 text-white font-semibold px-5 py-2 shrink-0 rounded"
    @if ($rating > 0)
    wire:click.prevent="storeRating()"
    @endif
    >
      @if ($detailTx->product->ratings()->whereTransaction_id($this->detailTx->transaction_id)->whereUser_id(auth()->id())->first())
      Ubah Rating
      @else
      Simpan Rating
      @endif
    </button>
  </div>

  <script>
  function highlightStars(rating) {
    const stars = document.querySelectorAll('.flex-rating svg');
    stars.forEach((star, index) => {
      if (index < rating) {
        star.classList.add('text-yellow-400');
        star.classList.remove('text-gray-300');
      } else {
        star.classList.remove('text-yellow-400');
        star.classList.add('text-gray-300');
      }
    });
  }

  function resetStars(currentRating) {
    const stars = document.querySelectorAll('.flex-rating svg');
    stars.forEach((star, index) => {
      if (index < currentRating) {
        star.classList.add('text-yellow-400');
        star.classList.remove('text-gray-300');
      } else {
        star.classList.remove('text-yellow-400');
        star.classList.add('text-gray-300');
      }
    });
  }
  </script>
</div>
