<section class="container">
    <div class="flex items-center mb-6">
        <h1 class="text-lg sm:text-2xl font-bold">WhatsApp Bot Command Categories</h1>
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
    @endif    <div class="mb-6 flex justify-between">
        <div>
            <a href="{{ route('store.whatsapp-bot-commands') }}" class="text-primary hover:underline mr-4">
                ← Back to Commands
            </a>
        </div>
        <div class="space-x-2">
            <button wire:click="showImportModal" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                Import Master Categories
            </button>
            <button wire:click="createCategory" class="text-white bg-primary hover:bg-primary/90 focus:ring-4 focus:ring-violet-300 font-medium rounded-lg text-sm px-5 py-2.5">
                Add Category
            </button>
        </div>
    </div>

    @if ($editingCategory)
        {{-- Category Form --}}
        <div class="p-6 mb-6 bg-white rounded shadow-sm">
            <h2 class="text-lg font-semibold mb-4">{{ $categoryId ? 'Edit' : 'Create' }} Category</h2>

            <form wire:submit.prevent="saveCategory">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-700">Category Name</label>
                        <input type="text" id="name" wire:model="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5" placeholder="Main Commands">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-700">Description</label>
                        <input type="text" id="description" wire:model="description" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5" placeholder="Main set of bot commands">
                        @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
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
                        Save Category
                    </button>
                </div>
            </form>
        </div>
    @endif

    {{-- Categories List --}}
    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3">Name</th>
                        <th scope="col" class="px-6 py-3">Description</th>
                        <th scope="col" class="px-6 py-3 text-center">Order</th>
                        <th scope="col" class="px-6 py-3 text-center">Commands</th>
                        <th scope="col" class="px-6 py-3 text-center">Status</th>
                        <th scope="col" class="px-6 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $cat)
                        <tr class="border-b">
                            <td class="px-6 py-4">
                                <span class="font-medium">{{ $cat->name }}</span>
                            </td>
                            <td class="px-6 py-4">
                                {{ $cat->description ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{ $cat->display_order }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{ $cat->commands->count() }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button wire:click="toggleActive({{ $cat->id }})" type="button" class="cursor-pointer">
                                    @if($cat->is_active)
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Active</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">Inactive</span>
                                    @endif
                                </button>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ url('store/whatsapp/bot/commands?category=' . $cat->id) }}" wire:navigate class="font-medium text-blue-600 hover:underline">
                                        View Commands
                                    </a>
                                    <button wire:click="editCategory({{ $cat->id }})" class="font-medium text-blue-600 hover:underline">
                                        Edit
                                    </button>
                                    <button wire:click="confirmDelete({{ $cat->id }})" class="font-medium text-red-600 hover:underline">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No categories found. Create your first category or import master categories.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-3">
            {{ $categories->links() }}
        </div>
    </div>

    {{-- Import Instructions --}}
    <div class="p-6 bg-white rounded-lg shadow-sm mb-8">
        <h2 class="text-lg font-semibold mb-2">Using Command Categories</h2>
        <p class="mb-4">
            Command categories help you organize your WhatsApp bot commands into logical groups. This makes it easier for you to manage commands
            and for your customers to understand what each command does.
        </p>

        <h3 class="font-medium mb-2">Benefits of Using Categories:</h3>
        <ul class="list-disc list-inside mb-4 pl-4">
            <li>Group related commands together</li>
            <li>Import specific command sets for your store needs</li>
            <li>Activate or deactivate entire categories at once</li>
            <li>Create a structured help menu for customers</li>
        </ul>

        <h3 class="font-medium mb-2">How to Get Started:</h3>
        <ol class="list-decimal list-inside pl-4">
            <li>Click "Import Master Categories" to use pre-defined categories</li>
            <li>Or create your own custom categories using "Add Category"</li>
            <li>Navigate to the Commands page and assign commands to categories</li>
        </ol>
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
                                Delete Category
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to delete this category? This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="deleteCategory" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
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

    {{-- Import Categories Modal --}}
    @if ($showImportModal)
    <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Import Master Categories
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-4">
                                    Select the master categories you want to import to your store.
                                </p>

                                @if(count($availableMasterCategories) === 0)
                                    <div class="p-4 text-sm text-gray-700 bg-gray-100 rounded-lg">
                                        There are no master categories available to import.
                                    </div>
                                @else
                                    <div class="max-h-60 overflow-y-auto border rounded p-2">
                                        @foreach($availableMasterCategories as $category)
                                            <div class="flex items-center p-2 hover:bg-gray-50">
                                                <input id="category-{{ $category->id }}"
                                                    type="checkbox"
                                                    value="{{ $category->id }}"
                                                    wire:model="selectedMasterCategories"
                                                    class="w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary">
                                                <label for="category-{{ $category->id }}" class="ml-2 w-full text-sm font-medium text-gray-900 cursor-pointer">
                                                    {{ $category->name }}
                                                    <span class="block text-xs text-gray-500">{{ $category->description }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="importCategories" type="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                        @if(count($availableMasterCategories) === 0) disabled @endif>
                        Import Selected
                    </button>
                    <button wire:click="cancelImport" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</section>
