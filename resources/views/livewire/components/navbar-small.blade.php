<div class="bg-white fixed bottom-0 w-full z-50 shadow-2xl block lg:hidden text-center">
  <div class="flex items-center">
    <div class="w-1/4 icon-hover py-1.5 leading-[.85]">
      <div class="dropdown">
        <a href="#" class="text-reset" data-bs-toggle="dropdown" aria-expanded="false">
          <div>
            <div class="relative inline-block m-auto mb-1">
              <svg xmlns="../www.w3.org/2000/svg.html" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-bell">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" />
                <path d="M9 17v1a3 3 0 0 0 6 0v-1" />
              </svg>
              @if (auth()->user()->unreadNotifications()->count() > 0)
              <span class="absolute -top-1 inline-block p-[5px] h-4 w-4 left-3 text-xs flex items-center justify-center bg-red-700 text-white font-semibold rounded-full">{{ auth()->user()->unreadNotifications->count() }}</span>
              @endif
            </div>
            <p class="mb-0 small">Notification</p>
          </div>
        </a>

        <div class="dropdown-menu dropdown-menu-lg !p-0 text-left">
          <div>
            <livewire:components.navbar-notification />
          </div>
        </div>
      </div>
    </div>
    <div class="w-1/4 icon-hover py-1.5 leading-[.85]">
      <!-- Button -->
      <button class="collapsed lg:hidden" type="button" @click="Livewire.navigate('{{url('chat')}}')">
        <div class="relative inline-block m-auto mb-1">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="mx-auto" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-brand-hipchat"
            style="transform">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path
              d="M17.802 17.292s.077 -.055 .2 -.149c1.843 -1.425 3 -3.49 3 -5.789c0 -4.286 -4.03 -7.764 -9 -7.764c-4.97 0 -9 3.478 -9 7.764c0 4.288 4.03 7.646 9 7.646c.424 0 1.12 -.028 2.088 -.084c1.262 .82 3.104 1.493 4.716 1.493c.499 0 .734 -.41 .414 -.828c-.486 -.596 -1.156 -1.551 -1.416 -2.29z" />
            <path d="M7.5 13.5c2.5 2.5 6.5 2.5 9 0" />
          </svg>
          @php
          $chatCount = \App\Models\ChatSession::where(function($query) {
            $query->where('user_id', auth()->id())->orWhere('user_store_id', auth()->id());
          })->whereHas('chats', function($query) {
            $query->whereNull('read_at')->where('receiver_id', auth()->id());
          })->count();
          @endphp
          @if ($chatCount > 0)
          <span class="absolute -top-1 inline-block p-[5px] h-4 w-4 left-3 text-xs flex items-center justify-center bg-red-700 text-white font-semibold rounded-full">{{$chatCount}}</span>
          @endif
        </div>
        <p class="mb-0 small">Pesan</p>
      </button>
    </div>
    <div class="w-1/4 icon-hover py-1.5 leading-[.85]">
      <a data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" href="#offcanvasExample" role="button"
        aria-controls="offcanvasRight" class="text-reset m-auto inline-block text-center">
        <svg xmlns="../www.w3.org/2000/svg.html" width="24" height="24" class="mx-auto mb-1" viewBox="0 0 24 24"
          fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
          stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-shopping-cart">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
          <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
          <path d="M17 17h-11v-14h-2" />
          <path d="M6 5l14 1l-1 7h-13" />
        </svg>
        <p class="mb-0 small">Keranjang</p>
      </a>
    </div>

    <div class="w-1/4 icon-hover py-1.5 leading-[.85]">
      @guest
      <a href="#" class="text-reset m-auto inline-block" data-bs-toggle="modal"
        data-bs-target="#userModal">
        <svg xmlns="../www.w3.org/2000/svg.html" width="24" height="24" class="mx-auto" viewBox="0 0 24 24"
          fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
          stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-circle">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
          <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
          <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" />
        </svg>
        <p class="mb-0 small">Akun</p>
      </a>
      @endguest
      @auth
      <a href="#"
      data-bs-toggle="offcanvas"
      data-bs-target="#navbar-profile" aria-controls="navbar-profile" aria-expanded="false"
      aria-label="Toggle navigation"
      class="text-reset m-auto inline-block">
        <div class="m-auto flex items-center space-x-2">
          <img src="{{profile(auth()->user())}}"
          alt="" srcset=""
          class="w-[24px] h-[24px] object-contain object-center bg-gray-200 rounded-full mx-auto mb-1"
          >
        </div>
        <p class="mb-0 small">{{\Str::limit(auth()->user()->name, 12)}}</p>
      </a>
      @endauth
    </div>
  </div>
</div>
