<div class="relative container mx-auto">
    <div class="bg-white overflow-hidden border sm:rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Add PPOB Products</h2>

        <!-- Flash Message -->
        @if (session()->has('message'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p>{{ session('message') }}</p>
            </div>
        @endif
        @if (session()->has('payment-message'))
            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4" role="alert">
                <p>{{ session('payment-message') }}</p>
            </div>
        @endif
        @if (session()->has('payment-error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p>{{ session('payment-error') }}</p>
            </div>
        @endif

        <!-- Search and Filter Section -->
        <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                <input type="text" wire:model.live.debounce.300ms="search" id="search"
                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                    placeholder="Search by name, code...">
            </div>

            <div>
                <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                <select id="category" wire:model.live="category"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="brand" class="block text-sm font-medium text-gray-700">Brand</label>
                <select id="brand" wire:model.live="brand"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">All Brands</option>
                    @foreach($brands as $b)
                        <option value="{{ $b }}">{{ $b }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Products Table -->
        @if($selectedProduct)
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h3 class="text-lg font-medium text-gray-900">Add Product to Store</h3>
                <div class="mt-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <p><span class="font-medium">Base Price:</span> Rp {{ number_format($selectedProduct->price) }}</p>

                            <div class="mt-3">
                                <label for="sellingPrice" class="block text-sm font-medium text-gray-700">Selling Price</label>
                                <div class="mt-1 flex border rounded-lg">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                        Rp
                                    </span>
                                    <input type="number" wire:model="sellingPrice" id="sellingPrice"
                                        class="flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300">
                                </div>
                                @error('sellingPrice') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="mt-3">
                                <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                                <input type="number" wire:model="quantity" id="quantity" min="1"
                                    class="mt-1 block w-full border sm:text-sm border-gray-300 rounded-md">
                                @error('quantity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="mt-3">
                                <label for="paymentMethod" class="block text-sm font-medium text-gray-700">Payment Method</label>
                                <select id="paymentMethod" wire:model="paymentMethod"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white border rounded-lg focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select Payment Method</option>
                                    @foreach($paymentMethods as $method)
                                        <option value="{{ $method['code'] }}">{{ $method['name'] }}</option>
                                    @endforeach
                                </select>
                                @error('paymentMethod') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div>
                            <p class="flex items-center justify-between"><span class="font-medium">Product:</span> <span class="font-bold">{{ $selectedProduct->title }}</span></p>
                            <p class="flex items-center justify-between"><span class="font-medium">Code:</span> <span class="font-bold">{{ $selectedProduct->code }}</span></p>
                            <p class="flex items-center justify-between"><span class="font-medium">Category:</span> <span class="font-bold">{{ $selectedProduct->category }}</span></p>
                            <p class="flex items-center justify-between"><span class="font-medium">Brand:</span> <span class="font-bold">{{ $selectedProduct->brand }}</span></p>
                            <p class="flex items-center justify-between"><span class="font-medium">Provider:</span> <span class="font-bold">{{ $selectedProduct->provider }}</span></p>

                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <div class="flex justify-between items-center">
                                    <span class="font-medium">Unit Price:</span>
                                    <span>Rp {{ number_format($selectedProduct->price) }}</span>
                                </div>
                                <div class="flex justify-between items-center mt-1">
                                    <span class="font-medium">Quantity:</span>
                                    <span wire:key="quantity-display">{{ $quantity }}</span>
                                </div>
                                <div class="flex justify-between items-center mt-1">
                                    <span class="font-medium">Subtotal:</span>
                                    <span wire:key="subtotal-display" class="font-semibold">Rp {{ number_format($this->subtotal) }}</span>
                                </div>
                                <div class="flex justify-between items-center mt-2 pt-2 border-t border-gray-200 font-bold text-primary">
                                    <span>Total:</span>
                                    <span wire:key="total-display">Rp {{ number_format($this->subtotal) }}</span>
                                </div>
                            </div>

                            <div class="mt-4 flex justify-end">
                                <button type="button" wire:click="cancelSelection" class="mr-2 bg-gray-200 py-2 px-4 border border-gray-300 border rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Cancel
                                </button>
                                <button type="button" wire:click="initPayment" wire:loading.attr="disabled" wire:target="initPayment" class="bg-indigo-600 py-2 px-4 border border-transparent border rounded-lg text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 relative">
                                    <span wire:loading.remove wire:target="initPayment">Pay & Add to Store</span>
                                    <span wire:loading wire:target="initPayment" class="inline-flex items-center">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('title')">
                                            Product
                                            @if($sortField === 'title')
                                                @if($sortDirection === 'asc') &uarr; @else &darr; @endif
                                            @endif
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('code')">
                                            Code
                                            @if($sortField === 'code')
                                                @if($sortDirection === 'asc') &uarr; @else &darr; @endif
                                            @endif
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Category
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Brand
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" wire:click="sortBy('price')">
                                            Harga Dasar
                                            @if($sortField === 'price')
                                                @if($sortDirection === 'asc') &uarr; @else &darr; @endif
                                            @endif
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Harga Jual
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($products as $product)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">{{ $product->title }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $product->code }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $product->category }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $product->brand }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                Rp {{ number_format($product->price) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                @php
                                                $exists = Auth::user()->store->storeProductInstants()->where('product_instant_id', $product->id)->exists();
                                                @endphp
                                                @if(!$exists)
                                                <input wire:model.defer="productPrices.{{ $product->id }}" type="number" class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-1.5" placeholder="Harga Jual" value="{{ $product->price }}" min="{{ $product->price }}">
                                                @else
                                                <span class="text-green-600 text-xs font-medium">Produk sudah tersedia</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                @if(!$exists)
                                                <button wire:click="addToStore({{ $product->id }})" class="text-indigo-600 hover:text-indigo-900 relative" wire:loading.attr="disabled" wire:target="addToStore({{ $product->id }})">
                                                    <span wire:loading.remove wire:target="addToStore({{ $product->id }})">Tambah ke Toko</span>
                                                    <span wire:loading wire:target="addToStore({{ $product->id }})" class="inline-flex items-center">
                                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                        </svg>
                                                        Memproses...
                                                    </span>
                                                </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                                No products found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- Payment Modal -->
    @if($paymentReference)
    <div class="absolute inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center z-50 rounded-lg"
         x-data="{
            pollingEnabled: true,
            startPolling() {
                if (this.pollingEnabled) {
                    setTimeout(() => {
                        @this.checkPaymentStatus();
                        this.startPolling();
                    }, 5000); // Check every 5 seconds
                }
            }
         }"
         x-init="startPolling()">
        <div class="bg-white border p-6 w-full max-h-full overflow-y-auto rounded-lg">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Pembayaran</h3>
                <button type="button" wire:click="cancelSelection" x-on:click="pollingEnabled = false" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            @if($paymentStatus === 'paid')
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                    <p class="font-bold">Payment Successful!</p>
                    <p>Your product has been added to your store inventory.</p>
                </div>
            @elseif(in_array($paymentStatus, ['expired', 'failed', 'cancelled']))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                    <p class="font-bold">Payment {{ ucfirst($paymentStatus) }}!</p>
                    <p>Please try again with a different payment method.</p>
                </div>
            @else
                <h1 class="text-xl font-bold text-center text-gray-800 mb-2">Selesaikan pembayaran dalam</h1>
                <div class="text-center text-orange-500 text-2xl font-bold mb-4"
                    x-data="countdown('{{$paymentExpiry ?? time() + 86400}}')"
                    x-init="startCountdown()"
                    x-text="timeLeft"></div>
                <p class="text-center text-gray-600 mb-2">Batas Akhir Pembayaran</p>
                <p class="text-center font-bold text-gray-800">{{ $paymentExpiry ? \Carbon\Carbon::createFromTimestamp($paymentExpiry)->translatedFormat('l, d F Y H:i') : \Carbon\Carbon::now()->addDay()->translatedFormat('l, d F Y H:i') }}</p>

                <div class="border-t border-gray-200 my-6"></div>
                @php
                    $payment = \App\Models\Payment::where('id', $paymentId)->first();
                    $paymentDetail = null;
                    if ($payment) {
                      $paymentDetail = checkPayment($payment);
                    }
                @endphp
                @if(in_array($paymentMethod, ['QRIS2', 'QRIS']))
                    <img src="{{$paymentDetail['qr_url']}}" alt="" srcset="" class="w-full sm:w-1/2 h-auto mb-6 mx-auto">
                @else
                    @if(isset($paymentDetail['pay_code']) && $paymentDetail['pay_code'])
                    <div class="flex flex-col border py-4 items-center justify-between my-6 bg-gray-50">
                        <div class="text-black text-lg">Kode Pembayaran</div>
                        <div class="text-black font-bold text-lg">{{ $paymentDetail['pay_code'] }}</div>
                    </div>
                    @endif
                @endif

                <div class="mt-6 mb-2">
                    <div class="flex items-center justify-between">
                        <p class="text-black text-lg">Total Tagihan</p>
                        <p class="text-red-600 font-bold text-lg">Rp{{ number_format($paymentAmount, 0, ',', '.') }}</p>
                    </div>
                </div>

                @if($selectedProduct)
                <div class="my-3 bg-gray-50 p-3 rounded-lg">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Product:</span>
                        <span class="font-medium">{{ $selectedProduct->title }}</span>
                    </div>
                    <div class="flex justify-between text-sm mt-1">
                        <span class="text-gray-600">Unit Price:</span>
                        <span>Rp {{ number_format($selectedProduct->price, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm mt-1">
                        <span class="text-gray-600">Quantity:</span>
                        <span>{{ $quantity }} unit(s)</span>
                    </div>
                    <div class="flex justify-between font-medium mt-2 pt-2 border-t border-gray-300">
                        <span>Subtotal:</span>
                        <span>Rp {{ number_format($paymentAmount, 0, ',', '.') }}</span>
                    </div>
                </div>
                @endif

                @if(isset($paymentInstructions) && $paymentMethod)


                    <div class="mb-6 space-y-4 border rounded p-4">
                        <div>
                            <div class="font-semibold text-lg">Cara Pembayaran</div>
                        </div>
                        @foreach($paymentInstructions as $row)
                            @if(isset($row['title']) && isset($row['steps']))
                            <div class="border-b pb-4">
                                <div class="font-semibold mb-2">{{ $row['title'] }}</div>
                                <div class="space-y-1">
                                    @foreach($row['steps'] as $index => $itemStep)
                                    <div class="flex items-start space-x-1">
                                        <div class="w-[18px] shrink-0">{{ $index + 1 }}.</div>
                                        <div>{!! $itemStep !!}</div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                @endif

                <div class="flex max-sm:flex-col justify-between max-sm:space-y-2 sm:space-x-2">
                    <button type="button" wire:click="checkPaymentStatus" class="bg-primary text-white px-4 py-2 rounded-lg shadow hover:bg-primary w-full text-center">
                        Cek Status Pembayaran
                    </button>
                    <button type="button" wire:click="cancelSelection" x-on:click="pollingEnabled = false" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg shadow hover:bg-gray-300 w-full text-center">
                        Batalkan
                    </button>
                </div>
            @endif
        </div>
    </div>
    @endif

    @include('components.scripts.countdown')
</div>
