<!DOCTYPE html>
<html lang="en">


<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta content="Codescandy" name="author">
  <title>Kios Sekte</title>
  <link rel="shortcut icon" type="image/x-icon" href="{{ url('/') }}/logo.png" />

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
  <!-- main -->
  <div>
    <!-- navbar -->
    <nav class="navbar navbar-expand-lg navbar-glass border-b">
      <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center w-100">
          <div class="d-flex align-items-center">
            <a class="text-inherit d-block d-xl-none me-4" data-bs-toggle="offcanvas" href="#offcanvasExample"
              role="button" aria-controls="offcanvasExample">
              <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor"
                class="bi bi-text-indent-right" viewBox="0 0 16 16">
                <path
                  d="M2 3.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5zm10.646 2.146a.5.5 0 0 1 .708.708L11.707 8l1.647 1.646a.5.5 0 0 1-.708.708l-2-2a.5.5 0 0 1 0-.708l2-2zM2 6.5a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z" />
              </svg>
            </a>
            <form role="search">
              <label for="search" class="form-label visually-hidden">Search</label>
              <input class="form-control border rounded-md" type="search" placeholder="Pencarian" aria-label="Search"
                id="search" />
            </form>
          </div>
          <div>
            <ul class="list-unstyled d-flex align-items-center mb-0 ms-5 ms-lg-0 space-x-3">
              <li class="dropdown ms-4">
                <a class="position-relative btn-icon btn-ghost-secondary btn rounded-circle bg-gray-100"
                  href="{{ url('/') }}" target="_blank" role="button" aria-expanded="false">
                  <i class="bi bi-globe fs-5"></i>
                </a>
              </li>
              <li class="dropdown-center">
                <a class="position-relative btn-icon btn-ghost-secondary btn rounded-circle bg-gray-100" href="#"
                  role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bi bi-bell fs-5"></i>
                  <span
                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger mt-2 ms-n2">
                    2
                    <span class="visually-hidden">unread messages</span>
                  </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-lg p-0 border-0">
                  <div class="border-bottom p-5 d-flex justify-content-between align-items-center">
                    <div>
                      <h5 class="mb-1">Notifications</h5>
                      <p class="mb-0 small">You have 2 unread messages</p>
                    </div>
                    <a href="#!" class="text-muted">
                      <a href="#" class="btn btn-ghost-secondary btn-icon rounded-circle" data-bs-toggle="tooltip"
                        data-bs-placement="bottom" data-bs-title="Mark all as read">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
                          class="bi bi-check2-all text-success" viewBox="0 0 16 16">
                          <path
                            d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 10.293 1.854 7.146a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0l7-7zm-4.208 7-.896-.897.707-.707.543.543 6.646-6.647a.5.5 0 0 1 .708.708l-7 7a.5.5 0 0 1-.708 0z" />
                          <path d="m5.354 7.146.896.897-.707.707-.897-.896a.5.5 0 1 1 .708-.708z" />
                        </svg>
                      </a>
                    </a>
                  </div>
                  <div data-simplebar style="height: 250px">
                    <!-- List group -->
                    <ul class="list-group list-group-flush notification-list-scroll fs-6">
                      <!-- List group item -->
                      <li class="list-group-item px-5 py-4 list-group-item-action active">
                        <a href="#!" class="text-muted">
                          <div class="d-flex">
                            <img src="{{ url('/asset-dashboard') }}/images/avatar/avatar-1.jpg" alt=""
                              class="avatar avatar-md rounded-circle" />
                            <div class="ms-4">
                              <p class="mb-1">
                                <span class="text-dark">Your order is placed</span>
                                waiting for shipping
                              </p>
                              <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                  fill="currentColor" class="bi bi-clock text-muted" viewBox="0 0 16 16">
                                  <path
                                    d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z" />
                                  <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z" />
                                </svg>
                                <small class="ms-2">1 minute ago</small>
                              </span>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li class="list-group-item px-5 py-4 list-group-item-action">
                        <a href="#!" class="text-muted">
                          <div class="d-flex">
                            <img src="{{ profile(auth()->user()) }}" alt=""
                              class="avatar avatar-md rounded-circle" />
                            <div class="ms-4">
                              <p class="mb-1">
                                <span class="text-dark">Jitu Chauhan</span>
                                answered to your pending order list with notes
                              </p>
                              <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                  fill="currentColor" class="bi bi-clock text-muted" viewBox="0 0 16 16">
                                  <path
                                    d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z" />
                                  <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z" />
                                </svg>
                                <small class="ms-2">2 days ago</small>
                              </span>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li class="list-group-item px-5 py-4 list-group-item-action">
                        <a href="#!" class="text-muted">
                          <div class="d-flex">
                            <img src="{{ url('/asset-dashboard') }}/images/avatar/avatar-2.jpg" alt=""
                              class="avatar avatar-md rounded-circle" />
                            <div class="ms-4">
                              <p class="mb-1">
                                <span class="text-dark">You have new messages</span>
                                2 unread messages
                              </p>
                              <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                  fill="currentColor" class="bi bi-clock text-muted" viewBox="0 0 16 16">
                                  <path
                                    d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z" />
                                  <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z" />
                                </svg>
                                <small class="ms-2">3 days ago</small>
                              </span>
                            </div>
                          </div>
                        </a>
                      </li>
                    </ul>
                  </div>
                  <div class="border-top px-5 py-4 text-center">
                    <a href="#!">View All</a>
                  </div>
                </div>
              </li>
              <li class="dropdown ms-4">
                <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"
                  class="flex items-center space-x-3">
                  <img src="{{ profile(auth()->user()) }}" alt="" class="avatar avatar-md rounded-circle" />
                  <h5 class="">{{ auth()->user()->name }}</h5>
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
              <img src="{{ url('/logo.png') }}" alt="" />
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
      <main class="main-content-wrapper">
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
