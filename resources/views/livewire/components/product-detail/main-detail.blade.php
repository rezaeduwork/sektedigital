<div>
  <div class="">
    <div class="flex flex-wrap mt-4 mb-6">
      <div class="w-full">
        <!-- breadcrumb -->
        <nav aria-label="breadcrumb">
          <ol class="flex flex-wrap">
            <li class="inline-block text-primary mr-2">
              <a href="#!">
                Home
                <svg xmlns="../www.w3.org/2000/svg.html" class="icon icon-tabler icon-tabler-chevron-right inline-block"
                  width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                  stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M9 6l6 6l-6 6" />
                </svg>
              </a>
            </li>
            <li class="inline-block text-primary mr-2">
              <a href="{{url('shop/'.$product->category->id)}}" wire:navigate>
                {{$product->category->name}}

                <svg xmlns="../www.w3.org/2000/svg.html" class="icon icon-tabler icon-tabler-chevron-right inline-block"
                  width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                  fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M9 6l6 6l-6 6" />
                </svg>
              </a>
            </li>
            <li class="inline-block text-gray-500 active" aria-current="page">{{$product->name}}</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <section class="mb-6 bg-white p-4 shadow-lg">
    <div class="">
      <div class="flex flex-wrap">
        <div class="lg:w-1/2">
          <!-- img slide -->
          @php
          $mainImage = $product->images()->whereType('main')->first();
          $additionalImage = $product->images()->whereType('additional')->get();
          @endphp
          <div class="product" id="product">
            <div class="zoom" onmousemove="zoom(event)"
              style="background-image: url('{{productImage($mainImage)}}')">
              <img src="{{productImage($mainImage)}}" alt="" />
            </div>
            @foreach ($additionalImage as $row)
            <div>
              <div class="zoom" onmousemove="zoom(event)" style="background-image: url('{{productImage($mainImage)}}')">
                <img src="{{productImage($mainImage)}}" alt="" />
              </div>
            </div>
            @endforeach
          </div>
          @if ($additionalImage->count() > 0)
          <div class="product-tools">
            <div class="thumbnails flex gap-3" id="productThumbnails">
              @foreach ($additionalImage as $row)
              <div class="w-1/4">
                <div class="thumbnails-img !border-gray-200">
                  <img src="{{productImage($row)}}" alt="" />
                </div>
              </div>
              @endforeach
            </div>
          </div>
          @endif
          <div class="shrink-0 mt-6 flex items-center justify-start space-x-6">
            <a href="#"
              class="btn text-lg inline-flex items-center gap-x-2 px-4 py-2 justify-center disabled:opacity-50 text-black disabled:pointer-events-none font-semibold">
              <svg xmlns="../www.w3.org/2000/svg.html" class="icon icon-tabler icon-tabler-heart text-primary"
                width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" />
              </svg>
              Simpan (12k)
            </a>
            <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" class="btn text-lg flex items-center gap-x-2 px-4 py-2 justify-center disabled:opacity-50 disabled:pointer-events-none text-black font-semibold">
              <div>Bagikan : </div>
              <button>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                  <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951"/>
                </svg>
              </button>
              <button>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
                  <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334"/>
                </svg>
              </button>
              <button>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-twitter-x" viewBox="0 0 16 16">
                  <path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z"/>
                </svg>
              </button>
              <button>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-link-45deg" viewBox="0 0 16 16">
                  <path d="M4.715 6.542 3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1 1 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4 4 0 0 1-.128-1.287z"/>
                  <path d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 1 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 1 0-4.243-4.243z"/>
                </svg>
              </button>
            </a>
          </div>
        </div>
        <div class="lg:w-1/2 pr-4 pl-4">
          <div class="lg:pl-10 mt-6 md:mt-0">
            <div class="flex flex-col gap-4">
              <div class="flex flex-col">
                <a href="{{url('shop/'.$product->category->id)}}" wire:navigate class="block font-semibold text-gray-500">{{$product->category->name}}</a>
                <h1 class="text-2xl pb-4 pt-2">{{$product->title}}</h1>
                <div class="flex flex-col gap-4">
                  <div class="flex items-center gap-2">
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
                    <span class="text-gray-900 font-semibold">Rp. {{number_format($product->price)}}</span>
                    {{-- <span class="line-through text-gray-500">$35</span> --}}
                    {{-- <span><small class="text-red-600">26% Off</small></span> --}}
                  </div>
                </div>
              </div>
              <hr />
              <!-- hr -->
              <div class="flex flex-col gap-6">
                <div>
                  <!-- input -->
                  <div class="w-1/3 md:w-1/4 lg:w-1/5">
                    <!-- input -->
                    <div class="input-group input-spinner rounded-lg flex justify-between items-center">
                      <input type="button" value="-"
                        class="button-minus w-8 py-1 border-r cursor-pointer border-gray-300" data-field="quantity" />
                      <input type="number" step="1" max="10" value="1" name="quantity"
                        class="quantity-field w-9 px-2 text-center h-7 border-0 bg-transparent" />
                      <input type="button" value="+"
                        class="button-plus w-8 py-1 border-l cursor-pointer border-gray-300" data-field="quantity" />
                    </div>
                  </div>
                </div>
                <div class="flex items-center justify-start gap-2 items-center">
                  <div class="grid shrink-0">
                    <livewire:components.addtocart-icon-label :product="$product">
                  </div>
                  <div class="grid shrink-0">
                    <!-- button -->
                    <!-- btn -->
                    <button type="button"
                      class="btn gap-x-1 bg-primary text-white border-primary disabled:opacity-50 disabled:pointer-events-none hover:text-white hover:bg-primary hover:border-primary active:bg-primary active:border-primary focus:outline-none focus:ring-4 focus:ring-primary justify-center">
                      Beli Langsung
                    </button>
                  </div>
                </div>
                <!-- hr -->
                <hr />
              </div>
              @php
              $stat = [
                [
                  'Dilihat',$product->views()
                ],
                [
                  'Disimpan',$product->views()
                ],
                [
                  'Terjual',$product->views()
                ],
              ];
              @endphp
              <div class="grid grid-cols-3">
                @foreach ($stat as $row)
                <div class="flex items-center space-x-2">
                  <div>{{$row[1]}}</div>
                  <div>{{$row[0]}}</div>
                </div>
                @endforeach
              </div>
              <hr />
              <div class="flex items-center space-x-2">
                <div class="">
                  <div>Dipublish {{\Carbon\Carbon::parse($product->created_at)->diffForHumans()}}</div>
                </div>
                <div>&bull;</div>
                <div class="">
                  <div>Diupdate {{\Carbon\Carbon::parse($product->updated_at)->diffForHumans()}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
