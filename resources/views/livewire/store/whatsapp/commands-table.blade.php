<div>
    <div class="space-y-4 bg-white rounded border">
        {{-- Commands List --}}
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="flex justify-between items-center mb-4 px-6 pt-4">
                <h3 class="text-lg font-semibold">Custom Commands</h3>
                <button type="button" wire:click="openAddCommandModal" class="text-white bg-primary hover:bg-primary/90 focus:ring-4 focus:ring-violet-300 font-medium rounded-lg text-sm px-3 py-2 inline-flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Command
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                        <tr>
                            <th scope="col" class="px-6 py-3">Custom Command</th>
                            <th scope="col" class="px-6 py-3">Master Command</th>
                            <th scope="col" class="px-6 py-3">Description</th>
                            <th scope="col" class="px-6 py-3">Parameters</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($storeCommands as $cmd)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <span class="font-medium">{{ $cmd->command }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-gray-600">{{ $cmd->masterCommand ? $cmd->masterCommand->command : '-' }}</span>
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
                                <td class="px-6 py-4">
                                    @if($cmd->is_active)
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-0.5 rounded">Active</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2 py-0.5 rounded">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <button wire:click="openEditCommandModal({{ $cmd->id }})" class="text-blue-600 hover:text-blue-900 mr-3">
                                        Edit
                                    </button>
                                    @if (!$cmd->category_id)
                                    <button wire:click="openDeleteCommandModal({{ $cmd->id }})" class="text-red-600 hover:text-red-900">
                                        Hapus
                                    </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    No custom commands found.
                                    <button type="button" wire:click="openAddCommandModal" class="text-primary hover:underline">
                                        Add your first command
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($storeCommands->hasPages())
            <div class="px-6 py-3">
                {{ $storeCommands->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Add Command Modal -->
    <template x-teleport="body">
      <div class="relative z-[999]" style="display: none;" x-show="$wire.showAddCommandModal" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
          <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl" style="max-height:70vh;display:flex;flex-direction:column;">
              <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 space-y-2 overflow-y-auto" style="flex:1 1 auto;min-height:0;">
                <!-- Modal header -->
                <div class="flex items-start justify-between border-b rounded-t pb-2">
                  <h3 class="text-lg font-semibold text-gray-900">Add Command</h3>
                  <button type="button" wire:click="closeModal" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                  </button>
                </div>
                <!-- Modal body -->
                <div class="pt-2">
                  <div class="grid grid-cols-1 gap-4">
                    <div class="overflow-x-auto">
                      <!-- Tabs for Categories and Individual Commands -->
                      <div class="border-b border-gray-200 mb-4">
                        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="commandTabs" role="tablist">
                          <li class="mr-2" role="presentation">
                            <button class="inline-block p-4 border-b-2 border-primary rounded-t-lg hover:text-primary hover:border-primary" id="categories-tab" data-tabs-target="#categories" type="button" role="tab" aria-controls="categories" aria-selected="true">
                              Command Categories
                            </button>
                          </li>
                          <li class="mr-2" role="presentation">
                            <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-primary hover:border-primary" id="individual-tab" data-tabs-target="#individual" type="button" role="tab" aria-controls="individual" aria-selected="false">
                              Uncategorized Commands
                            </button>
                          </li>
                        </ul>
                      </div>

                      <!-- Tabs Content -->
                      <div id="commandTabsContent">
                        <!-- Categories Section -->
                        <div class="p-4 rounded-lg bg-gray-50" id="categories" role="tabpanel" aria-labelledby="categories-tab">
                          <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 p-4 mb-4 rounded-md">
                            <div class="flex">
                              <svg class="w-5 h-5 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd"></path>
                              </svg>
                              <div>
                                <p class="font-medium">Important:</p>
                                <p class="text-sm">Your store can have only one active category at a time. Importing a new category will replace any existing category and its commands.</p>
                                @if ($currentStoreCategory)
                                  <p class="text-sm mt-2"><strong>Current active category:</strong> {{ $currentStoreCategory->name }}</p>
                                @else
                                  <p class="text-sm mt-2"><strong>No active category.</strong> You can import one from below.</p>
                                @endif
                              </div>
                            </div>
                          </div>

                          @if ($masterCategories->count() > 0)
                            <div class="grid md:grid-cols-2 gap-4">
                              @foreach ($masterCategories as $category)
                                <div class="{{ $currentCategoryMasterId == $category->id ? 'bg-green-50 border-green-200' : 'bg-white' }} rounded-lg border p-4 shadow-sm hover:shadow transition">
                                  <div class="flex justify-between items-start mb-2">
                                    <h4 class="text-lg font-medium text-gray-900">{{ $category->name }}</h4>
                                    <div class="flex flex-col items-end space-y-1">
                                      @if ($currentCategoryMasterId == $category->id)
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded-full">Currently Active</span>
                                      @endif
                                      <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full">{{ $category->commands->count() }} commands</span>
                                    </div>
                                  </div>
                                  <p class="text-gray-600 mb-4 h-12 overflow-hidden">{{ $category->description }}</p>
                                  <div class="flex flex-wrap gap-1 mb-4">
                                    @foreach ($category->commands->slice(0, 5) as $cmd)
                                      <span class="bg-gray-100 text-gray-800 text-xs px-2 py-0.5 rounded">{{ $cmd->command }}</span>
                                    @endforeach
                                    @if ($category->commands->count() > 5)
                                      <span class="bg-gray-100 text-gray-500 text-xs px-2 py-0.5 rounded">+{{ $category->commands->count() - 5 }} more</span>
                                    @endif
                                  </div>
                                  <button
                                    wire:click="addCommand({{ $category->id }}, 'category')"
                                    wire:confirm="{{ $currentCategoryMasterId ? 'This will replace your current category and all its commands. Continue?' : 'Import this command category?' }}"
                                    class="{{ $currentCategoryMasterId == $category->id ? 'bg-green-600 hover:bg-green-700' : 'bg-primary hover:bg-primary/90' }} w-full text-white px-3 py-2 rounded-md text-sm font-medium">
                                    {{ $currentCategoryMasterId == $category->id ? 'Replace Current Category' : 'Import All Commands' }}
                                  </button>
                                </div>
                              @endforeach
                            </div>
                          @else
                            <div class="text-center py-8 px-4">
                              <p class="text-gray-500">No categories available.</p>
                            </div>
                          @endif
                        </div>

                        <!-- Individual Commands Section -->
                        <div class="hidden p-4 rounded-lg bg-gray-50" id="individual" role="tabpanel" aria-labelledby="individual-tab">
                          @if ($uncategorizedMasterCommands->count() > 0)
                            <table class="w-full text-sm text-left">
                              <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                <tr>
                                  <th scope="col" class="px-4 py-3">Command</th>
                                  <th scope="col" class="px-4 py-3">Description</th>
                                  <th scope="col" class="px-4 py-3">Custom Name</th>
                                  <th scope="col" class="px-4 py-3 text-center">Actions</th>
                                </tr>
                              </thead>
                              <tbody>
                                @foreach ($uncategorizedMasterCommands as $cmd)
                                  <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-4">
                                      <span class="font-medium">{{ $cmd->command }}</span>
                                    </td>
                                    <td class="px-4 py-4">
                                      {{ $cmd->description }}
                                    </td>
                                    <td class="px-4 py-4">
                                      <input type="text" wire:model.defer="customCommandNames.{{ $cmd->id }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2" placeholder="Custom command name">
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                      <button wire:click="addCommand({{ $cmd->id }})" class="text-white bg-primary hover:bg-primary/90 px-3 py-1 rounded-md text-sm">Add</button>
                                    </td>
                                  </tr>
                                @endforeach
                              </tbody>
                            </table>
                          @else
                            <div class="text-center py-8 px-4">
                              <p class="text-gray-500">No uncategorized commands available.</p>
                            </div>
                          @endif
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                <button wire:click="closeModal" type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Close</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>

    <!-- Edit Command Modal -->
    <template x-teleport="body">
      <div class="relative z-[999]" style="display: none;" x-show="$wire.showEditCommandModal" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
          <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md" style="max-height:70vh;display:flex;flex-direction:column;">
              <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 space-y-2 overflow-y-auto" style="flex:1 1 auto;min-height:0;">
                <div class="flex items-start justify-between border-b rounded-t pb-2">
                  <h3 class="text-lg font-semibold text-gray-900">Edit Command</h3>
                  <button type="button" wire:click="closeModal" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                  </button>
                </div>
                <div class="pt-2 space-y-4">
                  <div>
                    <label for="custom-command" class="block mb-2 text-sm font-medium text-gray-900">Custom Command</label>
                    <input type="text" id="custom-command" wire:model.defer="editingCommand.command" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                  </div>
                  <div>
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Description</label>
                    <input type="text" id="description" wire:model.defer="editingCommand.description" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                  </div>
                  <!-- Response Template is now shown as plain text, not editable -->
                  <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900">Response Template</label>
                    <div class="bg-gray-100 border border-gray-200 text-gray-700 text-sm rounded-lg p-2.5 whitespace-pre-line">{{ $editingCommand['response_template'] ?? '' }}</div>
                  </div>
                  <div>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input type="checkbox" wire:model.defer="editingCommand.is_active" class="sr-only peer">
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                      <span class="ml-3 text-sm font-medium text-gray-900">Active</span>
                    </label>
                  </div>
                </div>
              </div>
              <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                <button wire:click="updateCommand" type="button" class="inline-flex w-full justify-center items-center rounded-md bg-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary/90 sm:ml-3 sm:w-auto">Save Changes</button>
                <button wire:click="closeModal" type="button" class="inline-flex w-full justify-center items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancel</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>

    <!-- Delete Confirmation Modal -->
    <template x-teleport="body">
      <div class="relative z-[999]" style="display: none;" x-show="$wire.showDeleteModal" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
          <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md" style="max-height:70vh;display:flex;flex-direction:column;">
              <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 space-y-2 overflow-y-auto" style="flex:1 1 auto;min-height:0;">
                <div class="p-6 text-center">
                  <svg aria-hidden="true" class="mx-auto mb-4 text-gray-400 w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                  <h3 class="mb-5 text-lg font-normal text-gray-500">Are you sure you want to delete this command?</h3>
                </div>
              </div>
              <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                <button wire:click="deleteCommand" type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">Yes, I'm sure</button>
                <button wire:click="closeModal" type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">No, cancel</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>

    @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // Initialize tabs when add command modal is shown
        Livewire.on('addCommandModalShown', function() {
          setTimeout(initCommandTabs, 100);
        });

        function initCommandTabs() {
          const tabElements = document.querySelectorAll('#commandTabs button[data-tabs-target]');
          const tabContents = document.querySelectorAll('#commandTabsContent > div');

          // Set first tab as active by default
          if (tabElements.length > 0) {
            tabElements[0].classList.add('border-primary', 'text-primary');
            tabElements[0].classList.remove('border-transparent');

            const targetId = tabElements[0].getAttribute('data-tabs-target');
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
              targetElement.classList.remove('hidden');
            }
          }

          // Add click event to all tab buttons
          tabElements.forEach(tab => {
            tab.addEventListener('click', function() {
              // Reset all tabs
              tabElements.forEach(el => {
                el.classList.remove('border-primary', 'text-primary');
                el.classList.add('border-transparent');
                el.setAttribute('aria-selected', 'false');
              });

              // Hide all tab contents
              tabContents.forEach(content => {
                content.classList.add('hidden');
              });

              // Activate selected tab
              this.classList.add('border-primary', 'text-primary');
              this.classList.remove('border-transparent');
              this.setAttribute('aria-selected', 'true');

              // Show selected tab content
              const targetId = this.getAttribute('data-tabs-target');
              const targetElement = document.querySelector(targetId);
              if (targetElement) {
                targetElement.classList.remove('hidden');
              }
            });
          });
        }
      });
    </script>
    @endpush
</div>
