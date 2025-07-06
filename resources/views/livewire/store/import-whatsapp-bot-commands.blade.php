<section class="container">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-lg sm:text-2xl font-bold">Import WhatsApp Bot Commands</h1>
        <div>
            <a href="{{ route('store.whatsapp-bot-commands') }}" class="btn btn-sm btn-outline">
                Back to Commands
            </a>
            <button wire:click="showImportModal" class="btn btn-sm btn-primary">
                Import Selected Commands
            </button>
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

    <div class="p-6 mb-6 bg-white rounded shadow-sm">
        <div class="mb-6">
            <h2 class="text-lg font-semibold mb-3">Filter Commands</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Select Category</label>
                    <div class="flex items-center gap-2">
                        <select id="category" wire:model="selectedCategory" wire:change="updatedSelectedCategory" class="select select-bordered w-full">
                            <option value="">All Categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->name }} {{ $category->is_master ? '(Global)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <button wire:click="toggleUncategorizedCommands" class="btn btn-sm {{ $showUncategorizedCommands ? 'btn-primary' : 'btn-outline' }}">
                            {{ $showUncategorizedCommands ? 'All Commands' : 'Uncategorized' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th class="w-16 text-center"></th>
                        <th>Command</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Handler</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($availableCommands as $command)
                        <tr class="hover">
                            <td class="text-center">
                                <input type="checkbox" wire:model="selectedCommands" value="{{ $command->id }}" class="checkbox checkbox-primary">
                            </td>
                            <td class="font-medium">/{{ $command->command }}</td>
                            <td>{{ $command->description }}</td>
                            <td>
                                @if ($command->category)
                                    {{ $command->category->name }}
                                @else
                                    <span class="text-gray-500">Uncategorized</span>
                                @endif
                            </td>
                            <td>
                                @if ($command->handler_class)
                                    <span class="badge badge-accent">Dynamic</span>
                                @else
                                    <span class="badge badge-ghost">Static</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-gray-500">
                                No commands available for import. You may have already imported all commands from this category.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Import Confirmation Modal -->
    <div class="modal" id="import-modal" wire:model="showImportModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-modal="true" x-data="{ show: false }" x-show="show" @open-modal.window="show = ($event.detail === 'import-modal')" @close-modal.window="show = false" x-cloak>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Commands</h5>
                    <button type="button" class="btn-close" wire:click="cancelImport" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to import {{ count($selectedCommands) }} command(s) to your store?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="cancelImport">Cancel</button>
                    <button type="button" class="btn btn-primary" wire:click="importCommands">Import Commands</button>
                </div>
            </div>
        </div>
    </div>
</section>
