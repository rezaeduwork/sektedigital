<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from freshcart-tailwind.codescandy.com/index-5.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 04 Aug 2024 12:44:15 GMT -->

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>{{config('app.name')}}</title>
  <link href="{{ url('/') }}/assets/libs/tiny-slider/dist/tiny-slider.css" rel="stylesheet" />
  <link rel="stylesheet" href="{{ url('/') }}/assets/libs/swiper/swiper-bundle.min.css" />
  <link rel="shortcut icon" type="image/x-icon" href="{{ url('/') }}/logo.png" />

  <!-- Libs CSS -->
  <link rel="preconnect" href="{{url('/')}}/fonts.googleapis.com/index.html" />
  <link rel="preconnect" href="{{url('/')}}/fonts.gstatic.com/index.html" crossorigin />
  <link rel="stylesheet"
    href="{{url('/')}}/fonts.googleapis.com/css2c948.css?family=Inter:wght@100;200;300;400;500;600;700;800;900&amp;display=swap" />
  <link rel="stylesheet" href="{{url('/')}}/cdn.jsdelivr.net/npm/%40tabler/icons-webfont%402.46.0/tabler-icons.min.css" />
  <link rel="stylesheet" href="{{ url('/') }}/assets/libs/simplebar/dist/simplebar.min.css" />

  <!-- Theme CSS -->
  <link rel="stylesheet" href="{{ url('/') }}/assets/css/theme.min.css" />

  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
  <livewire:components.navbar>

  <!-- Modal -->
  <div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body p-6">
          <div class="flex justify-between items-start">
            <div>
              <h5 id="locationModalLabel">Add Your Location</h5>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
              <svg xmlns="../www.w3.org/2000/svg.html" class="icon icon-tabler icon-tabler-x text-gray-700"
                width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M18 6l-12 12" />
                <path d="M6 6l12 12" />
              </svg>
            </button>
          </div>
          <div class="my-5">
            <form action="#">
              <div class="relative">
                <label for="searchLocation" class="invisible hidden">Search</label>
                <input
                  class="border border-gray-300 text-gray-900 rounded-lg focus:shadow-[0_0_0_.25rem_rgba(10,173,10,.25)] focus:ring-primary focus:ring-0 focus:border-primary block p-2 px-3 disabled:opacity-50 disabled:pointer-events-none w-full text-base"
                  type="search" placeholder="Search for area, location more.." id="searchLocation" />
                <button class="absolute right-0 top-0 p-3" type="button">
                  <svg xmlns="../www.w3.org/2000/svg.html" class="icon icon-tabler icon-tabler-search"
                    width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                    <path d="M21 21l-6 -6"></path>
                  </svg>
                </button>
              </div>
            </form>
          </div>
          <div class="my-10 flex justify-center">
            <img src="{{ url('/') }}/assets/images/svg-graphics/delivery-boy.svg" alt="" />
          </div>
        </div>
      </div>
    </div>
  </div>

  @guest
  <livewire:components.modal-auth>
  @endguest

  @include('components.toast-container')
  <nav
    class="!p-0 lg:!py-2 lg:!px-4 navbar relative navbar-expand-lg flex lg:hidden lg:flex-wrap items-center content-between text-black navbar-default"
    aria-label="Offcanvas navbar large">
    <div class="container max-w-7xl mx-auto w-full lg:px-0">
      <div class="offcanvas offcanvas-left lg:visible" tabindex="-1" id="navbar-default">
        <div class="offcanvas-header pb-1">
          <a href="index.html"><img src="{{ url('/') }}/assets/images/logo/freshcart-logo.svg"
              alt="TailwindCSS eCommerce HTML Template" /></a>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close">
            <svg xmlns="../www.w3.org/2000/svg.html" class="icon icon-tabler icon-tabler-x text-gray-700"
              width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
              fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M18 6l-12 12" />
              <path d="M6 6l12 12" />
            </svg>
          </button>
        </div>
        <div class="offcanvas-body lg:flex lg:items-center">
          <div class="block lg:hidden mb-4">
            <form action="#">
              <div class="relative">
                <label for="searhNavbar" class="invisible hidden">Search</label>
                <input
                  class="border border-gray-300 text-gray-900 rounded-lg focus:shadow-[0_0_0_.25rem_rgba(10,173,10,.25)] focus:ring-primary focus:ring-0 focus:border-primary block p-2 px-3 disabled:opacity-50 disabled:pointer-events-none w-full text-base"
                  type="search" placeholder="Search for products" id="searhNavbar" />
                <button class="absolute right-0 top-0 p-3" type="button">
                  <svg xmlns="../www.w3.org/2000/svg.html" class="icon icon-tabler icon-tabler-search"
                    width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                    <path d="M21 21l-6 -6" />
                  </svg>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </nav>

  <livewire:components.navbar-small>
  @auth
  <livewire:components.popover-user>
  @endauth

  <main class="mt-[3rem] lg:mt-[119px] max-w-[968px] mx-auto">
    {{$slot}}
  </main>

  <div class="modal fade" id="quickViewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered lg:min-w-[1140px]">
      <div class="modal-content">
        <div class="modal-body p-8">
          <div class="absolute top-0 right-0 p-3">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
              <svg xmlns="../www.w3.org/2000/svg.html" class="icon icon-tabler icon-tabler-x text-gray-700"
                width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M18 6l-12 12" />
                <path d="M6 6l12 12" />
              </svg>
            </button>
          </div>
          <div class="flex flex-wrap">
            <div class="md:w-1/2">
              <!-- img slide -->
              <div class="product productModal" id="productModal">
                <div class="zoom" onmousemove="zoom(event)"
                  style="
                      background-image: url(assets/images/products/product-single-img-1.jpg);
                    ">
                  <!-- img -->
                  <!-- img -->
                  <img src="{{ url('/') }}/assets/images/products/product-single-img-1.jpg" alt="" />
                </div>
                <div>
                  <div class="zoom" onmousemove="zoom(event)"
                    style="
                        background-image: url(assets/images/products/product-single-img-2.jpg);
                      ">
                    <!-- img -->
                    <img src="{{ url('/') }}/assets/images/products/product-single-img-2.jpg" alt="" />
                  </div>
                </div>
                <div>
                  <div class="zoom" onmousemove="zoom(event)"
                    style="
                        background-image: url(assets/images/products/product-single-img-3.jpg);
                      ">
                    <!-- img -->
                    <img src="{{ url('/') }}/assets/images/products/product-single-img-3.jpg" alt="" />
                  </div>
                </div>
                <div>
                  <div class="zoom" onmousemove="zoom(event)"
                    style="
                        background-image: url(assets/images/products/product-single-img-4.jpg);
                      ">
                    <!-- img -->
                    <img src="{{ url('/') }}/assets/images/products/product-single-img-4.jpg" alt="" />
                  </div>
                </div>
              </div>
              <!-- product tools -->
              <div class="product-tools">
                <div class="thumbnails flex gap-3" id="productModalThumbnails">
                  <div class="w-1/4">
                    <div class="thumbnails-img">
                      <!-- img -->
                      <img src="{{ url('/') }}/assets/images/products/product-single-img-1.jpg" alt="" />
                    </div>
                  </div>
                  <div class="w-1/4">
                    <div class="thumbnails-img">
                      <!-- img -->
                      <img src="{{ url('/') }}/assets/images/products/product-single-img-2.jpg" alt="" />
                    </div>
                  </div>
                  <div class="w-1/4">
                    <div class="thumbnails-img">
                      <!-- img -->
                      <img src="{{ url('/') }}/assets/images/products/product-single-img-3.jpg" alt="" />
                    </div>
                  </div>
                  <div class="w-1/4">
                    <div class="thumbnails-img">
                      <!-- img -->
                      <img src="{{ url('/') }}/assets/images/products/product-single-img-4.jpg" alt="" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="md:w-1/2 pr-4 pl-4">
              <div class="lg:pl-10 mt-6 md:mt-0">
                <div class="flex flex-col gap-4">
                  <!-- content -->
                  <a href="#!" class="block text-primary">Bakery Biscuits</a>
                  <!-- heading -->
                  <h1>Napolitanke Ljesnjak</h1>
                  <div class="flex flex-col gap-2">
                    <div class="flex items-center">
                      <!-- rating -->
                      <!-- rating -->
                      <small class="text-yellow-500 inline-flex items-center">
                        <svg xmlns="../www.w3.org/2000/svg.html" class="icon icon-tabler icon-tabler-star-filled"
                          width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                          fill="none" stroke-linecap="round" stroke-linejoin="round">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                          <path
                            d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z"
                            stroke-width="0" fill="currentColor"></path>
                        </svg>
                        <svg xmlns="../www.w3.org/2000/svg.html" class="icon icon-tabler icon-tabler-star-filled"
                          width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                          fill="none" stroke-linecap="round" stroke-linejoin="round">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                          <path
                            d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z"
                            stroke-width="0" fill="currentColor"></path>
                        </svg>
                        <svg xmlns="../www.w3.org/2000/svg.html" class="icon icon-tabler icon-tabler-star-filled"
                          width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                          fill="none" stroke-linecap="round" stroke-linejoin="round">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                          <path
                            d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z"
                            stroke-width="0" fill="currentColor"></path>
                        </svg>
                        <svg xmlns="../www.w3.org/2000/svg.html" class="icon icon-tabler icon-tabler-star-filled"
                          width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                          fill="none" stroke-linecap="round" stroke-linejoin="round">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                          <path
                            d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z"
                            stroke-width="0" fill="currentColor"></path>
                        </svg>
                        <svg xmlns="../www.w3.org/2000/svg.html" class="icon icon-tabler icon-tabler-star-filled"
                          width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                          fill="none" stroke-linecap="round" stroke-linejoin="round">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                          <path
                            d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z"
                            stroke-width="0" fill="currentColor"></path>
                        </svg>
                      </small>
                      <a href="#" class="text-primary">(30 reviews)</a>
                    </div>
                    <div class="text-md">
                      <span class="text-gray-900 font-semibold">$18</span>
                      <span class="line-through text-gray-500">$24</span>

                      <span><small class="text-red-600">26% Off</small></span>
                    </div>
                  </div>
                  <!-- hr -->
                  <div class="flex flex-col gap-6">
                    <hr />
                    <div>
                      <button type="button"
                        class="btn inline-flex items-center gap-x-2 bg-white text-gray-800 border-gray-300 border disabled:opacity-50 disabled:pointer-events-none hover:text-white hover:bg-gray-700 hover:border-gray-700 active:bg-gray-700 active:border-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-300">
                        250g
                      </button>
                      <!-- btn -->
                      <button type="button"
                        class="btn inline-flex items-center gap-x-2 bg-white text-gray-800 border-gray-300 border disabled:opacity-50 disabled:pointer-events-none hover:text-white hover:bg-gray-700 hover:border-gray-700 active:bg-gray-700 active:border-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-300">
                        500g
                      </button>
                      <!-- btn -->
                      <button type="button"
                        class="btn inline-flex items-center gap-x-2 bg-white text-gray-800 border-gray-300 border disabled:opacity-50 disabled:pointer-events-none hover:text-white hover:bg-gray-700 hover:border-gray-700 active:bg-gray-700 active:border-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-300">
                        1kg
                      </button>
                    </div>
                    <div>
                      <!-- input -->
                      <div class="w-1/3 md:w-1/4 lg:w-1/5">
                        <!-- input -->
                        <div class="input-group input-spinner rounded-lg flex justify-between items-center">
                          <input type="button" value="-"
                            class="button-minus w-8 py-1 border-r cursor-pointer border-gray-300"
                            data-field="quantity" />
                          <input type="number" step="1" max="10" value="1" name="quantity"
                            class="quantity-field w-9 px-2 text-center h-7 border-0 bg-transparent" />
                          <input type="button" value="+"
                            class="button-plus w-8 py-1 border-l cursor-pointer border-gray-300"
                            data-field="quantity" />
                        </div>
                      </div>
                    </div>
                    <div class="flex flex-wrap justify-start gap-2 items-center">
                      <div class="lg:w-1/3 md:w-2/5 w-full grid">
                        <!-- button -->
                        <!-- btn -->
                        <button type="button"
                          class="btn bg-primary text-white border-primary disabled:opacity-50 disabled:pointer-events-none hover:text-white hover:bg-primary hover:border-primary active:bg-primary active:border-primary focus:outline-none focus:ring-4 focus:ring-red-300 justify-center">
                          <svg xmlns="../www.w3.org/2000/svg.html" class="icon icon-tabler icon-tabler-plus mr-2"
                            width="12" height="12" viewBox="0 0 24 24" stroke-width="3"
                            stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M12 5l0 14"></path>
                            <path d="M5 12l14 0"></path>
                          </svg>
                          Add to cart
                        </button>
                      </div>
                      <div class="md:w-1/3 w-full">
                        <!-- btn -->
                        <a href="#"
                          class="mr-1 btn inline-flex items-center gap-x-2 px-0 h-10 w-10 justify-center bg-white text-gray-800 border-gray-300 border disabled:opacity-50 disabled:pointer-events-none hover:text-white hover:bg-gray-700 hover:border-gray-700 active:bg-gray-700 active:border-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-300">
                          <svg xmlns="../www.w3.org/2000/svg.html"
                            class="icon icon-tabler icon-tabler-arrows-exchange" width="20" height="20"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M7 10h14l-4 -4"></path>
                            <path d="M17 14h-14l4 4"></path>
                          </svg>
                        </a>
                        <a href="#"
                          class="btn inline-flex items-center gap-x-2 px-0 h-10 w-10 justify-center bg-white text-gray-800 border-gray-300 border disabled:opacity-50 disabled:pointer-events-none hover:text-white hover:bg-gray-700 hover:border-gray-700 active:bg-gray-700 active:border-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-300">
                          <svg xmlns="../www.w3.org/2000/svg.html" class="icon icon-tabler icon-tabler-heart"
                            width="20" height="20" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" />
                          </svg>
                        </a>
                      </div>
                    </div>
                    <!-- hr -->
                    <hr />
                  </div>
                  <div>
                    <!-- table -->
                    <table class="text-left w-full">
                      <tbody>
                        <tr>
                          <td class="px-6 py-3">Product Code:</td>
                          <td class="px-6 py-3">FBB00255</td>
                        </tr>
                        <tr>
                          <td class="px-6 py-3">Availability:</td>
                          <td class="px-6 py-3">In Stock</td>
                        </tr>
                        <tr>
                          <td class="px-6 py-3">Type:</td>
                          <td class="px-6 py-3">Fruits</td>
                        </tr>
                        <tr>
                          <td class="px-6 py-3">Shipping:</td>
                          <td class="px-6 py-3">
                            <small>
                              01 day shipping.
                              <span class="text-gray-700">( Free pickup today)</span>
                            </small>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div>
                    <!-- dropdown -->
                    <div class="relative">
                      <a class="dropdown-toggle btn inline-flex items-center gap-x-2 bg-white text-gray-800 border-gray-300 border disabled:opacity-50 disabled:pointer-events-none hover:text-white hover:bg-gray-700 hover:border-gray-700 active:bg-gray-700 active:border-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-300"
                        href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Share
                      </a>

                      <ul class="dropdown-menu">
                        <li>
                          <a class="dropdown-item" href="#">
                            <svg xmlns="../www.w3.org/2000/svg.html"
                              class="icon icon-tabler icon-tabler-brand-facebook inline-block" width="18"
                              height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                              fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                              <path d="M7 10v4h3v7h4v-7h3l1 -4h-4v-2a1 1 0 0 1 1 -1h3v-4h-3a5 5 0 0 0 -5 5v2h-3" />
                            </svg>
                            Facebook
                          </a>
                        </li>
                        <li>
                          <a class="dropdown-item" href="#">
                            <svg xmlns="../www.w3.org/2000/svg.html" class="icon icon-tabler icon-tabler-brand-x"
                              width="18" height="18" viewBox="0 0 24 24" stroke-width="2"
                              stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                              <path d="M4 4l11.733 16h4.267l-11.733 -16z" />
                              <path d="M4 20l6.768 -6.768m2.46 -2.46l6.772 -6.772" />
                            </svg>
                            Twitter
                          </a>
                        </li>
                        <li>
                          <a class="dropdown-item" href="#">
                            <svg xmlns="../www.w3.org/2000/svg.html"
                              class="icon icon-tabler icon-tabler-brand-instagram" width="18" height="18"
                              viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                              stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                              <path d="M4 4m0 4a4 4 0 0 1 4 -4h8a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-8a4 4 0 0 1 -4 -4z" />
                              <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                              <path d="M16.5 7.5l0 .01" />
                            </svg>
                            Instagram
                          </a>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- Footer -->
  <!-- footer -->
  <footer class="bg-gray-200 py-8">
    <div class="container max-w-[968px] mx-auto">
      <div class="flex flex-wrap md:gap-4 lg:gap-0 py-4 mb-6">
        <div class="w-full md:w-full lg:w-1/3 flex flex-col gap-4 mb-6">
          <h6>Categories</h6>
          <div class="flex flex-wrap">
            <div class="w-1/2">
              <!-- list -->
              <ul class="flex flex-col gap-2">
                <li>
                  <a href="#!" class="inline-block hover:text-primary">Vegetables & Fruits</a>
                </li>
                <li>
                  <a href="#!" class="inline-block hover:text-primary">Breakfast & instant food</a>
                </li>
                <li>
                  <a href="#!" class="inline-block hover:text-primary">Bakery & Biscuits</a>
                </li>
                <li>
                  <a href="#!" class="inline-block hover:text-primary">Atta, rice & dal</a>
                </li>
                <li>
                  <a href="#!" class="inline-block hover:text-primary">Sauces & spreads</a>
                </li>
                <li>
                  <a href="#!" class="inline-block hover:text-primary">Organic & gourmet</a>
                </li>
                <li>
                  <a href="#!" class="inline-block hover:text-primary">Baby care</a>
                </li>
                <li>
                  <a href="#!" class="inline-block hover:text-primary">Cleaning essentials</a>
                </li>
                <li>
                  <a href="#!" class="inline-block hover:text-primary">Personal care</a>
                </li>
              </ul>
            </div>
            <div class="w-1/2">
              <!-- list -->
              <ul class="flex flex-col gap-2">
                <li>
                  <a href="#!" class="inline-block hover:text-primary">Dairy, bread & eggs</a>
                </li>
                <li>
                  <a href="#!" class="inline-block hover:text-primary">Cold drinks & juices</a>
                </li>
                <li>
                  <a href="#!" class="inline-block hover:text-primary">Tea, coffee & drinks</a>
                </li>
                <li>
                  <a href="#!" class="inline-block hover:text-primary">Masala, oil & more</a>
                </li>
                <li>
                  <a href="#!" class="inline-block hover:text-primary">Chicken, meat & fish</a>
                </li>
                <li>
                  <a href="#!" class="inline-block hover:text-primary">Paan corner</a>
                </li>
                <li>
                  <a href="#!" class="inline-block hover:text-primary">Pharma & wellness</a>
                </li>
                <li>
                  <a href="#!" class="inline-block hover:text-primary">Home & office</a>
                </li>
                <li>
                  <a href="#!" class="inline-block hover:text-primary">Pet care</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="w-full md:w-full lg:w-2/3">
          <div class="flex flex-wrap">
            <div class="w-1/2 sm:w-1/2 md:w-1/4 flex flex-col gap-4 mb-6">
              <h6>Get to know us</h6>
              <!-- list -->
              <ul class="flex flex-col gap-2">
                <li>
                  <a href="#!" class="inline-block hover:text-primary">Company</a>
                </li>
                <li>
                  <a href="#!" class="inline-block hover:text-primary">About</a>
                </li>
                <li><a href="#!" class="inline-block">Blog</a></li>
                <li>
                  <a href="#!" class="inline-block hover:text-primary">Help Center</a>
                </li>
                <li>
                  <a href="#!" class="inline-block hover:text-primary">Our Value</a>
                </li>
              </ul>
            </div>
            <div class="w-1/2 sm:w-1/2 md:w-1/4 flex flex-col gap-4 mb-6">
              <h6>For Consumers</h6>
              <ul class="flex flex-col gap-2">
                <!-- list -->
                <li>
                  <a href="#!" class="inline-block hover:text-primary">Payments</a>
                </li>
                <li>
                  <a href="#!" class="inline-block hover:text-primary">Shipping</a>
                </li>
                <li>
                  <a href="#!" class="inline-block hover:text-primary">Product Returns</a>
                </li>
                <li>
                  <a href="#!" class="inline-block hover:text-primary">FAQ</a>
                </li>
                <li>
                  <a href="shop-checkout.html" class="inline-block">Shop Checkout</a>
                </li>
              </ul>
            </div>
            <div class="w-1/2 sm:w-1/2 md:w-1/4 flex flex-col gap-4">
              <h6>Become a Shopper</h6>
              <ul class="flex flex-col gap-2">
                <!-- list -->
                <li>
                  <a href="#!" class="inline-block hover:text-primary">Shopper Opportunities</a>
                </li>
                <li>
                  <a href="#!" class="inline-block hover:text-primary">Become a Shopper</a>
                </li>
                <li>
                  <a href="#!" class="inline-block hover:text-primary">Earnings</a>
                </li>
                <li>
                  <a href="#!" class="inline-block hover:text-primary">Ideas & Guides</a>
                </li>
                <li>
                  <a href="#!" class="inline-block hover:text-primary">New Retailers</a>
                </li>
              </ul>
            </div>
            <div class="w-1/2 sm:w-1/2 md:w-1/4 flex flex-col gap-4">
              <h6>Freshcart programs</h6>
              <ul class="flex flex-col gap-2">
                <!-- list -->
                <li>
                  <a href="#!" class="inline-block hover:text-primary">Freshcart programs</a>
                </li>
                <li>
                  <a href="#!" class="inline-block hover:text-primary">Gift Cards</a>
                </li>
                <li>
                  <a href="#!" class="inline-block hover:text-primary">Promos & Coupons</a>
                </li>
                <li>
                  <a href="#!" class="inline-block hover:text-primary">Freshcart Ads</a>
                </li>
                <li>
                  <a href="#!" class="inline-block hover:text-primary">Careers</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="border-t py-4 border-gray-300">
        <div class="gap-y-4 flex flex-wrap items-center justify-center lg:justify-start">
          <div class="lg:w-2/5 lg:text-left text-center">
            <div class="flex md:flex-row flex-col gap-3 md:gap-6 items-center">
              <div class="text-gray-900">Payment Partners</div>
              <ul class="flex items-center flex-row gap-4">
                <li>
                  <a href="#!"><img src="{{ url('/') }}/assets/images/payment/amazonpay.svg"
                      alt="amazon pay" /></a>
                </li>
                <li>
                  <a href="#!"><img src="{{ url('/') }}/assets/images/payment/american-express.svg"
                      alt="american express" /></a>
                </li>
                <li>
                  <a href="#!"><img src="{{ url('/') }}/assets/images/payment/mastercard.svg"
                      alt="mastercard" /></a>
                </li>
                <li>
                  <a href="#!"><img src="{{ url('/') }}/assets/images/payment/paypal.svg"
                      alt="paypal" /></a>
                </li>
                <li>
                  <a href="#!"><img src="{{ url('/') }}/assets/images/payment/visa.svg"
                      alt="visa" /></a>
                </li>
              </ul>
            </div>
          </div>
          <div class="lg:w-3/5 flex justify-end">
            <div class="flex flex-col md:flex-row items-center gap-3 md:gap-6">
              <div class="text-gray-900">Get deliveries with FreshCart</div>
              <ul class="flex flex-row gap-2">
                <li>
                  <a href="#!"><img src="{{ url('/') }}/assets/images/appbutton/appstore-btn.svg"
                      alt="" style="width: 140px" /></a>
                </li>
                <li>
                  <a href="#!"><img src="{{ url('/') }}/assets/images/appbutton/googleplay-btn.svg"
                      alt="" style="width: 140px" /></a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="border-t py-4 border-gray-300">
        <div class="flex flex-col md:flex-row items-center gap-3">
          <div class="md:w-1/2">
            <span class="text-sm text-gray-500">
              ©
              <span id="copyright">
                <script>
                  document
                    .getElementById("copyright")
                    .appendChild(
                      document.createTextNode(new Date().getFullYear())
                    );
                </script>
              </span>
              FreshCart TailwindCSS eCommerce HTML Template. Powered by
              <a href="../codescandy.com/index.html" target="_blank" class="text-primary">Codescandy</a>
              .
            </span>
          </div>
          <div class="md:w-1/2 flex md:justify-end items-center">
            <div class="flex flex-row gap-5 items-center">
              <div class="text-gray-500">Follow us on</div>
              <ul class="flex items-center justify-end text-sm gap-1">
                <li>
                  <a href="#!"
                    class="inline-flex justify-center items-center align-middle text-center select-none border font-normal whitespace-no-wrap rounded leading-normal no-underline h-8 w-8 border-gray-300 hover:border-primary hover:text-primary transition ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-facebook"
                      width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5"
                      stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M7 10v4h3v7h4v-7h3l1 -4h-4v-2a1 1 0 0 1 1 -1h3v-4h-3a5 5 0 0 0 -5 5v2h-3" />
                    </svg>
                  </a>
                </li>
                <li>
                  <a href="#!"
                    class="inline-flex justify-center items-center align-middle text-center select-none border font-normal whitespace-no-wrap rounded leading-normal no-underline h-8 w-8 border-gray-300 hover:border-primary hover:text-primary transition ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-x"
                      width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5"
                      stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M4 4l11.733 16h4.267l-11.733 -16z" />
                      <path d="M4 20l6.768 -6.768m2.46 -2.46l6.772 -6.772" />
                    </svg>
                  </a>
                </li>
                <li>
                  <a href="#!"
                    class="inline-flex justify-center items-center align-middle text-center select-none border font-normal whitespace-no-wrap rounded leading-normal no-underline h-8 w-8 border-gray-300 hover:border-primary hover:text-primary transition ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-instagram"
                      width="16" height="16" viewBox="0 0 24 24" stroke-width="1.5"
                      stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M4 4m0 4a4 4 0 0 1 4 -4h8a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-8a4 4 0 0 1 -4 -4z" />
                      <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                      <path d="M16.5 7.5l0 .01" />
                    </svg>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>

  <!-- Javascript-->
  <!-- Libs JS -->
  <script src="{{ url('/') }}/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ url('/') }}/assets/libs/simplebar/dist/simplebar.min.js"></script>

  <!-- Theme JS -->

  <script src="{{ url('/') }}/assets/js/theme.min.js"></script>

  <!-- Swiper JS -->

  <script src="{{ url('/') }}/assets/libs/swiper/swiper-bundle.min.js"></script>
  <script src="{{ url('/') }}/assets/js/vendors/swiper.js?t={{time()}}"></script>
  <script src="{{ url('/') }}/assets/libs/tiny-slider/dist/min/tiny-slider.js"></script>
  <script src="{{ url('/') }}/assets/js/vendors/tns-slider.js"></script>
  <script src="{{ url('/') }}/assets/js/vendors/zoom.js"></script>

  <script src="{{ url('/') }}/assets/js/vendors/countdown.js"></script>
</body>

</html>
