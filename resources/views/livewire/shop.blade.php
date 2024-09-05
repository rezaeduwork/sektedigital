<div class="mt-4">
  <div class="container">
    <livewire:components.shop-category :category="$category">
      <hr />
      <div class="flex flex-col md:flex-row justify-between lg:items-center mb-6 gap-3 mt-4">
        <div>
          <p>
            <span class="text-gray-900">24</span>
            Products found
          </p>
        </div>

        <!-- icon -->
        <div class="flex flex-col md:flex-row justify-between md:items-center gap-3">
          <div class="flex gap-3">
            <div class="flex-grow">
              <!-- select option -->
              <select
                class="text-xs py-2 block w-full border-gray-300 rounded-lg focus:border-gray-300 focus:ring-gray-100 disabled:opacity-50 disabled:pointer-events-none pl-3 pr-7">
                <option selected>Show: 50</option>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="30">30</option>
              </select>
            </div>
            <div>
              <!-- select option -->
              <select
                class="text-xs py-2 block w-full border-gray-300 rounded-lg focus:border-gray-300 focus:ring-gray-100 disabled:opacity-50 disabled:pointer-events-none pl-3 pr-7">
                <option selected="">Sort by: Featured</option>
                <option value="Low to High">Price: Low to High</option>
                <option value="High to Low">Price: High to Low</option>
                <option value="Release Date">Release Date</option>
                <option value="Avg. Rating">Avg. Rating</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <div class="grid lg:grid-cols-3 md:grid-cols-3 gap-4">
        @foreach ([1, 2, 3, 4, 5, 6, 7, 8, 9] as $row)
          <div class="relative rounded-lg break-words border bg-white border-gray-300 outline-none shadow-none">
            <div class="flex-auto p-4">
              <div class="text-center relative flex justify-center">
                <div class="absolute top-0 left-0">
                  <span
                    class="inline-block p-1 text-center font-semibold text-sm align-baseline leading-none rounded bg-violet-600 text-white">Sale</span>
                </div>
                <a href="#!"><img src="assets/images/products/product-img-1.jpg" alt="Grocery Ecommerce Template"
                    class="w-full h-auto"></a>
              </div>
              <div class="flex flex-col gap-3">
                <a href="#!" class="text-decoration-none text-gray-500"><small>Snack &amp; Munchies</small></a>
                <div class="flex flex-col gap-2">
                  <h3 class="text-base truncate"><a href="shop-single.html" class="hover:text-primary">Haldiram's Sev
                      Bhujia</a></h3>
                  <div class="flex items-center">
                    <div class="flex flex-row gap-3">
                      <small class="text-yellow-500 flex items-center">
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
                        <svg xmlns="../www.w3.org/2000/svg.html" class="icon icon-tabler icon-tabler-star-half-filled"
                          width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                          fill="none" stroke-linecap="round" stroke-linejoin="round">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                          <path
                            d="M12 1a.993 .993 0 0 1 .823 .443l.067 .116l2.852 5.781l6.38 .925c.741 .108 1.08 .94 .703 1.526l-.07 .095l-.078 .086l-4.624 4.499l1.09 6.355a1.001 1.001 0 0 1 -1.249 1.135l-.101 -.035l-.101 -.046l-5.693 -3l-5.706 3c-.105 .055 -.212 .09 -.32 .106l-.106 .01a1.003 1.003 0 0 1 -1.038 -1.06l.013 -.11l1.09 -6.355l-4.623 -4.5a1.001 1.001 0 0 1 .328 -1.647l.113 -.036l.114 -.023l6.379 -.925l2.853 -5.78a.968 .968 0 0 1 .904 -.56zm0 3.274v12.476a1 1 0 0 1 .239 .029l.115 .036l.112 .05l4.363 2.299l-.836 -4.873a1 1 0 0 1 .136 -.696l.07 -.099l.082 -.09l3.546 -3.453l-4.891 -.708a1 1 0 0 1 -.62 -.344l-.073 -.097l-.06 -.106l-2.183 -4.424z"
                            stroke-width="0" fill="currentColor"></path>
                        </svg>
                      </small>
                      <div class="flex flex-row gap-1">
                        <span class="text-gray-500 text-sm">4.5</span>
                        <span class="text-gray-500 text-sm">(149)</span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="flex justify-between items-center">
                  <div>
                    <span class="text-gray-900 font-semibold">$18</span>
                    <span class="line-through text-gray-500">$24</span>
                  </div>
                  <div>
                    <button type="button"
                      class="btn inline-flex items-center gap-x-1 bg-primary text-white border-primary disabled:opacity-50 disabled:pointer-events-none hover:text-white hover:bg-primary hover:border-primary active:bg-primary active:border-primary focus:outline-none focus:none btn-sm">
                      <svg xmlns="../www.w3.org/2000/svg.html" class="icon icon-tabler icon-tabler-plus"
                        width="14" height="14" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M12 5l0 14"></path>
                        <path d="M5 12l14 0"></path>
                      </svg>
                      <span>Add</span>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
  </div>
</div>
