<div>
  <div class="">
    <div class="flex flex-wrap mt-6 mb-6">
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
            <li class="inline-block text-gray-500 active" aria-current="page">{{$product->title}}</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <section class="mb-6 p-0">
    <div class="">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="">
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
          <div class="shrink-0 mt-4 sm:mt-6 flex items-center justify-center sm:justify-start sm:space-x-6">
            {{-- <a href="#"
              class="btn text-xs sm:text-lg inline-flex items-center gap-x-2 px-4 py-0 sm:py-2 justify-center disabled:opacity-50 text-black disabled:pointer-events-none font-semibold">
              <svg xmlns="../www.w3.org/2000/svg.html" class="icon icon-tabler icon-tabler-heart text-primary size-[16px] sm:size-[20px]"
                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" />
              </svg>
              Simpan (12k)
            </a> --}}
            <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" class="btn text-xs sm:text-lg flex items-center gap-x-2 px-4 py-0 sm:py-2 justify-center disabled:opacity-50 disabled:pointer-events-none text-black font-semibold">
              <div>Bagikan : </div>
              <button onclick="copyLink()" class="hover:opacity-80">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-link-45deg" viewBox="0 0 16 16">
                  <path d="M4.715 6.542 3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1 1 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4 4 0 0 1-.128-1.287z"/>
                  <path d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 1 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 1 0-4.243-4.243z"/>
                </svg>
              </button>
            </a>
          </div>
        </div>
        <script>
        function copyLink() {
          const url = window.location.href;
          navigator.clipboard.writeText(url).then(() => {
            alert("Link berhasil disalin!");
          });
        }
        </script>
        <hr class="sm:hidden" />
        <div class="rounded-lg">
          <div class="sm:pl-10 sm:mt-6 md:mt-0">
            <div class="flex flex-col gap-4">
              <div class="flex flex-col">
                <div class="flex max-sm:items-center sm:items-start flex-col sm:space-y-2 max-sm:space-x-2 pb-4">
                  <a href="{{url('shop/'.$product->category->id)}}" wire:navigate class="flex items-center space-x-2 justify-center font-semibold text-gray-500 max-sm:text-center text-sm bg-gray-100 p-2 rounded-lg">
                    <img src="{{url('storage/'.$product->category->icon)}}" alt="{{$product->category->name}}" class="size-[16px]">
                    <div>{{$product->category->name}}</div>
                  </a>
                  <h1 class="text-lg max-sm:text-center">{{$product->title}}</h1>
                </div>
                <div class="flex flex-col gap-4">
                  <div class="text-2xl max-sm:text-center">
                    <span class="text-red-600 font-semibold">Rp. {{number_format($product->price)}}</span>
                    {{-- <span class="line-through text-gray-500">$35</span> --}}
                    {{-- <span><small class="text-violet-600">26% Off</small></span> --}}
                  </div>
                  <div class="flex items-center max-sm:justify-center gap-2">
                    <!-- rating -->
                    <!-- rating -->
                    <small class="text-yellow-300 inline-flex items-center">
                      <svg xmlns="../www.w3.org/2000/svg.html" class="icon icon-tabler icon-tabler-star-filled"
                        width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path
                          d="M8.243 7.34l-6.38 .925l-.113 .023a1 1 0 0 0 -.44 1.684l4.622 4.499l-1.09 6.355l-.013 .11a1 1 0 0 0 1.464 .944l5.706 -3l5.693 3l.1 .046a1 1 0 0 0 1.352 -1.1l-1.091 -6.355l4.624 -4.5l.078 -.085a1 1 0 0 0 -.633 -1.62l-6.38 -.926l-2.852 -5.78a1 1 0 0 0 -1.794 0l-2.853 5.78z"
                          stroke-width="0" fill="currentColor"></path>
                      </svg>
                    </small>
                    <a href="#" class="text-primary">({{$product->ratings()->count()}} reviews)</a>
                  </div>
                </div>
              </div>
              <hr />
              <!-- hr -->
              <div class="flex flex-col max-sm:items-center gap-4 sm:gap-6">
                @if (!$inCart)
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
                @endif
                <div class="w-full flex max-sm:flex-col items-center justify-start gap-2">
                  @if ($inCart)
                  <div class="grid w-full text-center">
                    Sudah di keranjang
                  </div>
                  @else
                  <div class="grid w-full">
                    <livewire:components.addtocart-icon-label :product="$product">
                  </div>
                  <div class="grid w-full">
                    <!-- button -->
                    <!-- btn -->
                    <button type="button"
                      @guest
                      data-bs-toggle="modal" data-bs-target="#userModal" href="#"
                      @endguest
                      @auth
                      @click="$wire.autoCart()"
                      @endauth
                      class="btn gap-x-1 bg-primary text-white border-primary disabled:opacity-50 disabled:pointer-events-none hover:text-white hover:bg-primary hover:border-primary justify-center">
                      Order Langsung
                    </button>
                  </div>
                  @endif
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
                  'Dikeranjang',$product->inCartsCount()
                ],
                [
                  'Terjual',$product->inTransactionFinished()
                ],
              ];
              @endphp
              <div class="grid grid-cols-3">
                @foreach ($stat as $row)
                <div class="flex items-center max-sm:justify-center space-x-2">
                  <div>{{$row[1]}}</div>
                  <div>{{$row[0]}}</div>
                </div>
                @endforeach
              </div>
              <hr />
              <div class="flex items-center max-sm:justify-center space-x-2">
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
