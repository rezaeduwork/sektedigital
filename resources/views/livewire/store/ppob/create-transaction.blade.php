<div>
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold">Create PPOB Transaction</h2>
            <a href="{{ route('store.ppob.manage') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                Back to Products
            </a>
        </div>

        @if (session()->has('message'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p>{{ session('message') }}</p>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Product Info -->
            <div class="bg-gray-50 p-4 rounded">
                <h3 class="font-medium text-lg text-gray-900 mb-2">Product Information</h3>

                <div class="mb-3">
                    <span class="text-sm font-medium text-gray-500">Product:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ $product->title }}</span>
                </div>

                <div class="mb-3">
                    <span class="text-sm font-medium text-gray-500">Code:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ $product->code }}</span>
                </div>

                <div class="mb-3">
                    <span class="text-sm font-medium text-gray-500">Category:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ $product->category }}</span>
                </div>

                <div class="mb-3">
                    <span class="text-sm font-medium text-gray-500">Brand:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ $product->brand }}</span>
                </div>

                <div class="mb-3">
                    <span class="text-sm font-medium text-gray-500">Price:</span>
                    <span class="text-sm text-gray-900 ml-2">Rp {{ number_format($product->selling_price) }}</span>
                </div>

                <div class="mb-3">
                    <span class="text-sm font-medium text-gray-500">Stock:</span>
                    <span class="text-sm text-gray-900 ml-2">{{ $product->stock === -1 ? 'Unlimited' : $product->stock }}</span>
                </div>

                @if($product->highlight)
                <div class="mt-4 p-3 bg-blue-50 text-blue-700 rounded text-sm">
                    {{ $product->highlight }}
                </div>
                @endif
            </div>

            <!-- Transaction Form -->
            <div>
                <h3 class="font-medium text-lg text-gray-900 mb-4">Customer Information</h3>

                @foreach($formFields as $index => $field)
                <div class="mb-4">
                    <label for="field-{{ $index }}" class="block text-sm font-medium text-gray-700">{{ $field['label'] }}</label>
                    <input
                        type="{{ $field['type'] }}"
                        id="field-{{ $index }}"
                        wire:model="formFields.{{ $index }}.value"
                        class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                        {{ $field['required'] ? 'required' : '' }}
                    >
                    @error("formFields.{$index}.value")
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                @endforeach

                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                Please double-check customer information before submitting.
                                <br>This transaction cannot be cancelled once processed.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <button
                        type="button"
                        wire:click="processTransaction"
                        wire:loading.attr="disabled"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full"
                    >
                        <span wire:loading.remove wire:target="processTransaction">
                            Process Transaction
                        </span>
                        <span wire:loading wire:target="processTransaction">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
