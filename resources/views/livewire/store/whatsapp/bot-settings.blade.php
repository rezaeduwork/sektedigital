<div class="container">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-lg sm:text-2xl font-bold">WhatsApp Bot Settings</h1>

        <div class="flex space-x-2">
            <a href="{{ url('store/whatsapp') }}" class="inline-block text-gray-700 bg-white hover:bg-gray-100 border border-gray-300 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5">
                Back to WhatsApp
            </a>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Bot Activation -->
        <div class="p-6 bg-white rounded-lg border border-gray-200 shadow-sm">
            <h2 class="text-lg font-semibold mb-4">Bot Activation</h2>

            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-700 mb-1">Enable/Disable WhatsApp Bot</p>
                    <p class="text-sm text-gray-500">When disabled, bot will not respond to any messages</p>
                </div>

                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" @if($isActive) checked @endif wire:click="toggleBotActive" class="sr-only peer">
                    <div :class="{'bg-gray-200': {{!$isActive}} }" class="w-14 h-7 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300
                    rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:translate-x-1/4
                    peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[4px]
                    after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6
                    after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>
        </div>

        <!-- Welcome Message -->
        <div class="p-6 bg-white rounded-lg border border-gray-200 shadow-sm">
            <h2 class="text-lg font-semibold mb-4">Welcome Message</h2>

            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-gray-700 mb-1">Enable/Disable Welcome Message</p>
                    <p class="text-sm text-gray-500">Send a message when customer first contacts your bot</p>
                </div>

                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" wire:model.live="welcomeMessageEnabled" wire:click="toggleWelcomeMessage" class="sr-only peer">
                    <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300
                    rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:translate-x-1/4
                    peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[4px]
                    after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6
                    after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>

            <div class="mt-6 {{ $welcomeMessageEnabled ? '' : 'opacity-50 pointer-events-none' }}">
                <label for="welcomeMessage" class="block mb-2 text-sm font-medium text-gray-900">Welcome Message Text</label>
                <div class="mb-2 text-xs text-gray-500">
                    <p>You can use basic formatting:</p>
                    <p>*bold* for <strong>bold text</strong></p>
                    <p>_italic_ for <em>italic text</em></p>
                    <p>Use {store_name} to include your store name</p>
                </div>
                <textarea id="welcomeMessage" rows="6" wire:model="welcomeMessageText" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter welcome message here..."></textarea>

                <div class="flex justify-end mt-4 space-x-3">
                    <button wire:click="resetWelcomeMessage" type="button" class="py-2.5 px-4 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200">
                        Reset to Default
                    </button>
                    <button wire:click="saveWelcomeMessage" type="button" class="text-white bg-primary hover:bg-primary/90 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                        Save Message
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 p-6 bg-white rounded-lg border border-gray-200 shadow-sm">
        <h2 class="text-lg font-semibold mb-4">Preview Message Format</h2>

        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
            <div class="flex items-start mb-4">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                        <span class="text-sm font-medium text-gray-600">U</span>
                    </div>
                </div>
                <div class="ml-3 bg-gray-200 py-2 px-4 rounded-lg rounded-tl-none max-w-md">
                    <p class="text-sm">Hello</p>
                </div>
            </div>

            <div class="flex items-start flex-row-reverse">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 rounded-full bg-primary/20 flex items-center justify-center">
                        <span class="text-sm font-medium text-primary">B</span>
                    </div>
                </div>
                <div class="mr-3 bg-primary/10 py-2 px-4 rounded-lg rounded-tr-none max-w-md whitespace-pre-line">
                    <p class="text-sm prose prose-sm">
                        {!! nl2br(str_replace('*', '<strong>', str_replace('_', '<em>', $welcomeMessageEnabled ? $welcomeMessageText : 'Welcome message is disabled.'))) !!}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
