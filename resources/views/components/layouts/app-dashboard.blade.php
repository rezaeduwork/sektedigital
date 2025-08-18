<!DOCTYPE html>
<html lang="en">


<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta content="Codescandy" name="author">
  <title>{{strtoupper(auth()->user()->store->name)}}</title>
  <link rel="shortcut icon" type="image/x-icon" href="{{ url('/') }}/logo-only-square-real.png" />

  <!-- Libs CSS -->
  <link href="{{ url('/asset-dashboard') }}/libs/bootstrap-icons/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="{{ url('/asset-dashboard') }}/libs/feather-webfont/dist/feather-icons.css" rel="stylesheet">
  <link href="{{ url('/asset-dashboard') }}/libs/simplebar/dist/simplebar.min.css" rel="stylesheet">


  <!-- Theme CSS -->
  <link rel="stylesheet" href="{{ url('/asset-dashboard') }}/css/theme.min.css?t={{ time() }}">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles
</head>

<body class="bg-gray-50">
  @include('components.toast-container')

  <!-- main -->
  <div>
    <!-- navbar -->
    <nav class="navbar navbar-expand-lg navbar-glass border-b max-sm:!p-2 max-sm:!h-auto">
      <div class="container-fluid max-sm:px-2">
        <div class="d-flex justify-content-between align-items-center w-100">
          <div class="d-flex align-items-center">
            <a class="text-inherit d-block d-xl-none me-4" data-bs-toggle="offcanvas" href="#offcanvasExample"
              role="button" aria-controls="offcanvasExample">
              <svg xmlns="http://www.w3.org/2000/svg" class="size-[28px] sm:size-[32px]" fill="currentColor"
                class="bi bi-text-indent-right" viewBox="0 0 16 16">
                <path
                  d="M2 3.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5zm10.646 2.146a.5.5 0 0 1 .708.708L11.707 8l1.647 1.646a.5.5 0 0 1-.708.708l-2-2a.5.5 0 0 1 0-.708l2-2zM2 6.5a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z" />
              </svg>
            </a>
            <form role="search">
              <label for="search" class="form-label visually-hidden">Search</label>
              <input class="form-control border rounded-md max-sm:!text-xs max-sm:placeholder:!text-xs" type="search" placeholder="Pencarian" aria-label="Search"
                id="search" />
            </form>
          </div>
          <div class="shrink-0">
            <ul class="list-unstyled d-flex align-items-center mb-0 ms-2 sm:ms-5 ms-lg-0 space-x-2 sm:space-x-3">
              <!-- Saldo Info -->
              <li class="d-flex align-items-center pr-2">
                <div class="d-none d-lg-block max-sm:!text-xs me-2">
                  <div class="text-end flex items-center space-x-2">
                    <p class="mb-0 text-muted">Saldo</p>
                    <h5 class="mb-0">Rp {{ number_format(auth()->user()->balance) }}</h5>
                  </div>
                </div>
                <a href="{{ url('user/wallet') }}" class="btn btn-primary btn-sm max-sm:!text-xs max-sm:!py-1 max-sm:!px-2">
                  <i class="bi bi-plus-lg me-1"></i>Top Up
                </a>
              </li>
              <li class="dropdown-center">
                <a class="position-relative btn-icon btn-ghost-secondary btn rounded-circle bg-gray-100 !w-[32px] !h-[32px] sm:!w-[40px] sm:!h-[40px]" href="#"
                  role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bi bi-bell fs-5"></i>
                  <span
                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger mt-2 ms-n2">
                    2
                    <span class="visually-hidden">Notification</span>
                  </span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg !p-0 text-left !left-[unset] !right-0" wire:ignore>
                  <div>
                    <livewire:components.navbar-notification />
                  </div>
                </div>
              </li>
              <li class="dropdown">
                @php
                $chatCount = \App\Models\ChatSession::where(function($query) {
                  $query->where('user_id', auth()->id())->orWhere('user_store_id', auth()->id());
                })->whereHas('chats', function($query) {
                  $query->whereNull('read_at')->where('receiver_id', auth()->id());
                })->count();
                @endphp
                <a class="position-relative btn-icon btn-ghost-secondary btn rounded-circle bg-gray-100 !w-[32px] !h-[32px] sm:!w-[40px] sm:!h-[40px]"
                  href="{{ url('/chat') }}" target="_blank" role="button" aria-expanded="false">
                  <i class="bi bi-chat"></i>
                  <span
                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger mt-2 ms-n2">
                    {{$chatCount}}
                    <span class="visually-hidden">unread messages</span>
                  </span>
                </a>
              </li>
              <li class="dropdown">
                <a href="#" role="button"
                  {{-- data-bs-toggle="dropdown"  --}}
                  aria-expanded="false"
                  class="flex items-center space-x-3">
                  <img src="{{ profile(auth()->user()) }}" alt="" class="avatar !w-[32px] !h-[32px] sm:!w-[40px] sm:!h-[40px] shrink-0 rounded-circle" />
                  <h5 class="max-sm:hidden">{{ \Str::limit(auth()->user()->name, 6) }}</h5>
                </a>

                <div class="dropdown-menu dropdown-menu-end p-0">
                  <div class="lh-1 px-5 py-4 border-bottom">
                    <h5 class="mb-1 h6">{{ auth()->user()->name }}</h5>
                    <small>{{ auth()->user()->email }}</small>
                  </div>

                  <ul class="list-unstyled px-2 py-3">
                    <li>
                      <a class="dropdown-item" href="#!">Home</a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#!">Profile</a>
                    </li>

                    <li>
                      <a class="dropdown-item" href="#!">Settings</a>
                    </li>
                  </ul>
                  <div class="border-top px-5 py-3">
                    <a href="#">Log Out</a>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </nav>


    <div class="main-wrapper">
      <!-- navbar vertical -->
      <!-- navbar -->
      <nav class="navbar-vertical-nav d-none d-xl-block">
        <div class="navbar-vertical">
          <div class="px-4 py-5">
            <a href="{{ url('store') }}" wire:navigate class="navbar-brand">
              <img src="{{ url('/logo-real.png') }}" alt="" />
            </a>
          </div>
          <div class="navbar-vertical-content flex-grow-1" data-simplebar="">
            <livewire:components.navbar-store>
          </div>
        </div>
      </nav>

      <nav class="navbar-vertical-nav offcanvas offcanvas-start navbar-offcanvac" tabindex="-1"
        id="offcanvasExample">
        <div class="navbar-vertical">
          <div class="px-6 py-5 d-flex justify-content-between align-items-center space-x-5">
            <a href="{{ url('store') }}" wire:navigate class="navbar-brand rounded-full">
              <img src="{{ url('/logo.png') }}" alt="" />
            </a>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
          </div>
          <div class="navbar-vertical-content flex-grow-1" data-simplebar="">
            <livewire:components.navbar-store>
          </div>
        </div>
      </nav>

      <!-- main wrapper -->
      <main class="main-content-wrapper sm:!py-[100px]">
        {{ $slot }}
      </main>
    </div>
  </div>

  @include('components.toast-container')


  <!-- Libs JS -->
  <script data-navigate-once src="{{ url('/asset-dashboard') }}/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script data-navigate-once src="{{ url('/asset-dashboard') }}/libs/simplebar/dist/simplebar.min.js"></script>

  <!-- Theme JS -->
  <script data-navigate-once src="{{ url('/asset-dashboard') }}/js/theme.min.js"></script>

  <script data-navigate-once src="{{ url('/asset-dashboard') }}/libs/apexcharts/dist/apexcharts.min.js"></script>
  <script data-navigate-once src="{{ url('/asset-dashboard') }}/js/vendors/chart.js"></script>
  @livewireScripts

</body>


</html>
