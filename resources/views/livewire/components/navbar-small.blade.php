<div class="bg-white fixed bottom-0 w-full z-50 shadow-2xl block lg:hidden text-center">
  <div class="flex items-center">
    <div class="w-1/4 icon-hover py-4 flex flex-col justify-center items-center">
      <!-- Button -->
      <button class="navbar-toggler collapsed lg:hidden" type="button" data-bs-toggle="offcanvas"
        data-bs-target="#navbar-default" aria-controls="navbar-default" aria-expanded="false"
        aria-label="Toggle navigation">
        <svg xmlns="../www.w3.org/2000/svg.html" width="24" height="24" viewBox="0 0 24 24"
          fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
          stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-indent-increase">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <path d="M20 6l-11 0" />
          <path d="M20 12l-7 0" />
          <path d="M20 18l-11 0" />
          <path d="M4 8l4 4l-4 4" />
        </svg>
      </button>
    </div>
    <div class="w-1/4 icon-hover py-4 leading-[.85]">
      <div class="dropdown">
        <a href="#" class="text-reset" data-bs-toggle="dropdown" aria-expanded="false">
          <div>
            <div class="relative inline-block m-auto">
              <svg xmlns="../www.w3.org/2000/svg.html" width="24" height="24" viewBox="0 0 24 24"
                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-bell">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" />
                <path d="M9 17v1a3 3 0 0 0 6 0v-1" />
              </svg>
              <span
                class="absolute -top-3 inline-block p-[5px] h-5 w-5 left-3 text-sm align-baseline leading-none bg-violet-600 text-white font-semibold rounded-full">1</span>
            </div>
            <p class="mb-0 hidden xl:block small">Notification</p>
          </div>
        </a>

        <div class="dropdown-menu dropdown-menu-lg !p-0 text-left">
          <div>
            <h6 class="px-4 border-b py-2 mb-0">Notification</h6>
            <p class="mb-0 px-4 py-3">
              <a href="#" class="text-primary">Sign in</a>
              or
              <a href="#" class="text-primary">register</a>
              in or so you don t have to enter your details every time
            </p>
          </div>
        </div>
      </div>
    </div>
    <div class="w-1/4 icon-hover py-4 leading-[.85]">
      <a data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" href="#offcanvasExample" role="button"
        aria-controls="offcanvasRight" class="text-reset m-auto inline-block">
        <svg xmlns="../www.w3.org/2000/svg.html" width="24" height="24" viewBox="0 0 24 24"
          fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
          stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-shopping-cart">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
          <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
          <path d="M17 17h-11v-14h-2" />
          <path d="M6 5l14 1l-1 7h-13" />
        </svg>
      </a>
    </div>

    <div class="w-1/4 icon-hover py-4 leading-[.85]">
      @guest
      <a href="#" class="text-reset m-auto inline-block" data-bs-toggle="modal"
        data-bs-target="#userModal">
        <svg xmlns="../www.w3.org/2000/svg.html" width="24" height="24" viewBox="0 0 24 24"
          fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
          stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-circle">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
          <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
          <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" />
        </svg>
      </a>
      @endguest
      @auth
      <a href="#"
      data-bs-toggle="offcanvas"
      data-bs-target="#navbar-profile" aria-controls="navbar-profile" aria-expanded="false"
      aria-label="Toggle navigation"
      class="text-reset m-auto inline-block">
        <div class="m-auto flex items-center space-x-2">
          <img src="https://plus.unsplash.com/premium_photo-1683121366070-5ceb7e007a97?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8dXNlcnxlbnwwfHwwfHx8MA%3D%3D"
          alt="" srcset=""
          class="w-[36px] h-[36px] object-contain object-center bg-gray-200 rounded-full"
          >
        </div>
      </a>
      @endauth
    </div>
  </div>
</div>
