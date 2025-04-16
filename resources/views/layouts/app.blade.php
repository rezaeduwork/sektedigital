<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>{{$title}}</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link rel="stylesheet" href="{{ url('/') }}/assets/css/theme.min.css" />
  @yield('blog-custom-css')
  <style>

  </style>
</head>
<body>
  <header class="max-sm:border-b sm:shadow w-full fixed top-0 left-0 z-20 bg-white max-sm:h-[47px]">
    <div class="container max-w-[968px] mx-auto">
      <div class="flex w-full items-center justify-between py-1 sm:py-3 gap-2 sm:gap-4">
        <div class="flex items-center justify-center md:justify-start shrink-0">
          <a href="{{ url('/') }}" wire:navigate>
            <img src="{{ url('logo-real.png') }}" alt="" srcset="" class="h-[39px] w-auto rounded-lg max-sm:hidden" />
            <img src="{{ url('logo-only-square-real.png') }}" alt="" srcset="" class="h-[39px] w-auto rounded-lg sm:hidden" />
          </a>
        </div>
        <div class="flex items-center sm:space-x-4 w-full max-sm:flex-row-reverse">
          <div class="w-full">
            <form action="#">
              <div class="flex"
              x-data="{
                inputValue: '',
                text: '',
                textArray: ['Cari Produk Disini!'],
                textIndex: 0,
                charIndex: 0,
                typeSpeed: 150,
                placeholder() {
                  var ival = setInterval(() => {
                    let current = this.textArray[ this.textIndex ];
                    this.text = current.substring(0, this.charIndex);
                    this.charIndex += 1;
                    if(this.text == 'Cari Produk Disini!') {
                      clearInterval(ival)
                      ival = null
                    }
                  }, (Math.floor(Math.random() * 200) + this.typeSpeed) );
                },
                init() {
                  this.placeholder()
                }
              }"
              >
                <label for="searchProducts" class="invisible hidden">Search</label>
                <input
                  @enter="Livewire.navigate('{{url('shop')}}?q='+inputValue);return false;"
                  x-model="inputValue"
                  class="border !border-gray-300 text-gray-900 rounded-l-lg !ring-none !outline-none focus:border-gray-300 !shadow-primary block p-2 sm:p-2 sm:px-3 disabled:opacity-50 disabled:pointer-events-none w-full text-xs placeholder:text-xs sm:placeholder:text-sm sm:text-sm"
                  :placeholder="text" id="searchProducts" @keyup.enter="Livewire.navigate('{{url('shop')}}?q='+inputValue)" />
                <button
                  class="px-2 sm:!px-4 rounded-none !rounded-r-lg btn text-xs sm:text-sm font-normal inline-flex items-center gap-x-2 !bg-primary text-white !border-primary disabled:opacity-50 disabled:pointer-events-none hover:text-white hover:!bg-primary hover:border-primary active:bg-primary active:border-primary focus:outline-none focus:ring-4 focus:ring-violet-100"
                  type="button" @click="Livewire.navigate('{{url('shop')}}?q='+inputValue)">
                  Cari
                </button>
              </div>
            </form>
          </div>
        </div>

        <div class="shrink-0">
          <div class="flex items-center justify-end space-x-5 mx-1">
            @guest
              <button
                class="mr-4 btn inline-flex items-center gap-x-2 bg-white !text-primary shadow !border-primary disabled:opacity-50 disabled:pointer-events-none hover:bg-primary hover:text-white hover:border-primary focus:outline-none"
                type="button" data-bs-toggle="modal" data-bs-target="#userModal">
                <span>
                  Masuk
                </span>
              </button>
            @endguest
            @auth
              <div class="dropdown">
                <a href="{{url('profile')}}" class="text-reset" data-bs-toggle="dropdown" aria-expanded="false">
                  <div class="leading-snug">
                    <div class="m-auto flex items-center space-x-2">
                      <img src="{{ profile(auth()->user()) }}" alt="" srcset=""
                        class="w-[36px] h-[36px] object-contain object-center bg-gray-200 rounded-full border-2 border-gray-500">
                      <div>{{ \Str::limit(auth()->user()->name, 20, '...') }}</div>
                    </div>
                  </div>
                </a>
              </div>
            @endauth
          </div>
        </div>
      </div>
    </div>
  </header>
  <main class="my-[47px] sm:my-[119px] max-w-[968px] mx-auto">
    @yield('content')
  </main>
</body>
</html>
