<section class="container">
    <div class="flex items-center mb-6">
        <h1 class="text-lg sm:text-2xl font-bold">WhatsApp Bot Commands</h1>
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

    @if ($editingCommand)
        {{-- Command Form --}}
        <div class="p-6 mb-6 bg-white rounded shadow-sm">
            <h2 class="text-lg font-semibold mb-4">{{ $commandId ? 'Edit' : 'Create' }} Command</h2>

            <form wire:submit.prevent="saveCommand">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="command" class="block mb-2 text-sm font-medium text-gray-700">Command (without /)</label>
                        <input type="text" id="command" wire:model="command" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5" placeholder="products">
                        @error('command') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-700">Description</label>
                        <input type="text" id="description" wire:model="description" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5" placeholder="Show all products">
                        @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label for="responseTemplate" class="block mb-2 text-sm font-medium text-gray-700">Response Template</label>
                    <textarea id="responseTemplate" wire:model="responseTemplate" rows="5" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5" placeholder="Welcome to {{store_name}}! Here are our products: ..."></textarea>
                    <div class="mt-1 text-xs text-gray-500">
                        Use {{store_name}} for store name, and {{parameter_name}} for custom parameters
                    </div>
                    @error('responseTemplate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label for="parameters" class="block mb-2 text-sm font-medium text-gray-700">Parameters (comma separated)</label>
                        <input type="text" id="parameters" wire:model="parameters" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5" placeholder="param1,param2">
                        @error('parameters') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="displayOrder" class="block mb-2 text-sm font-medium text-gray-700">Display Order</label>
                        <input type="number" id="displayOrder" wire:model="displayOrder" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5">
                        @error('displayOrder') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center h-full pt-8">
                        <label class="inline-flex items-center">
                            <input type="checkbox" wire:model="isActive" class="form-checkbox h-5 w-5 text-primary">
                            <span class="ml-2 text-sm">Active</span>
                        </label>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-2">
                    <button type="button" wire:click="cancel" class="text-gray-900 bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5">
                        Cancel
                    </button>
                    <button type="submit" class="text-white bg-primary hover:bg-primary/90 focus:ring-4 focus:outline-none focus:ring-violet-300 font-medium rounded-lg text-sm px-5 py-2.5">
                        Save Command
                    </button>
                </div>
            </form>
        </div>
    @endif

    {{-- Commands List --}}
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3">Command</th>
                        <th scope="col" class="px-6 py-3">Description</th>
                        <th scope="col" class="px-6 py-3">Parameters</th>
                        <th scope="col" class="px-6 py-3 text-center">Order</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($commands as $cmd)
                        <tr class="border-b">
                            <td class="px-6 py-4">
                                <span class="font-medium">/{{ $cmd->command }}</span>
                            </td>
                            <td class="px-6 py-4">
                                {{ $cmd->description }}
                            </td>
                            <td class="px-6 py-4">
                                @if (is_array($cmd->parameters) && count($cmd->parameters) > 0)
                                    <span class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded">
                                        {{ implode(', ', $cmd->parameters) }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{ $cmd->display_order }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                No commands found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-3">
            {{ $commands->links() }}
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    @if ($showDeleteModal)
    <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Delete Command
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to delete this command? This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="deleteCommand" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Delete
                    </button>
                    <button wire:click="cancelDelete" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</section>
