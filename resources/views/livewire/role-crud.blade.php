<div class="space-y-6">
    {{-- Header & Controls --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h2 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">
            {{ __('Role Management') }}
        </h2>
        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
            <div class="relative w-full sm:w-64">
                <flux:input 
                    wire:model.live.debounce.300ms="search" 
                    icon="magnifying-glass" 
                    placeholder="Search roles..." 
                />
            </div>
            <flux:button wire:click="create" variant="primary" icon="plus">
                {{ __('Add Role') }}
            </flux:button>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session()->has('message'))
        <div class="rounded-md bg-green-50 p-4 text-green-700 dark:bg-green-900/50 dark:text-green-300">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="rounded-md bg-red-50 p-4 text-red-700 dark:bg-red-900/50 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                            {{ __('Role Name') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                            {{ __('Permissions') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                            {{ __('Created') }}
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">{{ __('Actions') }}</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-zinc-200 dark:bg-zinc-900 dark:divide-zinc-700">
                    @forelse ($roles as $role)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center">
                                            <flux:icon.shield-check class="w-5 h-5 text-indigo-600 dark:text-indigo-400" />
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                            {{ $role->name }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-zinc-500 dark:text-zinc-400">
                                    {{ $role->permissions_count }} {{ __('permissions') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                {{ $role->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <flux:button wire:click="edit({{ $role->id }})" size="sm" icon="pencil-square" variant="ghost" class="!px-2" title="Edit" />
                                    <flux:button wire:click="confirmDelete({{ $role->id }})" size="sm" icon="trash" variant="ghost" class="!px-2 text-red-600 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20" title="Delete" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-zinc-500 dark:text-zinc-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <flux:icon.magnifying-glass class="w-8 h-8 opacity-50" />
                                    <p>{{ __('No roles found matching your search.') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if($roles->hasPages())
            <div class="bg-zinc-50 dark:bg-zinc-800 px-4 py-3 border-t border-zinc-200 dark:border-zinc-700">
                {{ $roles->links() }}
            </div>
        @endif
    </div>

    {{-- Create/Edit Modal --}}
    <flux:modal wire:model="showModal" class="md:min-w-[40rem]">
        <div class="mb-4">
            <h3 class="text-lg font-medium leading-6 text-zinc-900 dark:text-zinc-100">
                {{ $isEditing ? __('Edit Role') : __('Create Role') }}
            </h3>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                {{ $isEditing ? __('Update role information') : __('Create a new role and assign permissions') }}
            </p>
        </div>

        <form wire:submit="save" class="space-y-6">
            <flux:input wire:model="name" label="{{ __('Role Name') }}" placeholder="Manager" />

            <div>
                <flux:label>{{ __('Permissions') }}</flux:label>
                <div class="mt-2 space-y-4 max-h-96 overflow-y-auto rounded-md border border-zinc-200 p-4 dark:border-zinc-700">
                    @foreach($permissions as $group => $groupPermissions)
                        <div class="space-y-2">
                            <h4 class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 capitalize">
                                {{ $group }}
                            </h4>
                            <div class="grid grid-cols-2 gap-2 ml-4">
                                @foreach($groupPermissions as $permission)
                                    <flux:checkbox 
                                        wire:model="selectedPermissions" 
                                        value="{{ $permission->name }}" 
                                        label="{{ ucfirst(str_replace($group . '.', '', $permission->name)) }}" 
                                    />
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-6">
                <flux:button wire:click="resetForm" variant="ghost">{{ __('Cancel') }}</flux:button>
                <flux:button type="submit" variant="primary">{{ $isEditing ? __('Update') : __('Create') }}</flux:button>
            </div>
        </form>
    </flux:modal>

    {{-- Delete Confirmation Modal --}}
    <flux:modal wire:model="showDeleteModal" class="md:w-96">
        <div class="space-y-4">
            <div>
                <h3 class="text-lg font-medium text-red-600 dark:text-red-400">{{ __('Confirm Delete') }}</h3>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-2">
                    {{ __('Are you sure you want to delete this role? This action cannot be undone.') }}
                </p>
            </div>
            
            <div class="flex justify-end gap-2">
                <flux:button wire:click="$set('showDeleteModal', false)" variant="ghost">{{ __('Cancel') }}</flux:button>
                <flux:button wire:click="delete" variant="filled" class="bg-red-600 hover:bg-red-700 text-white border-transparent">{{ __('Delete') }}</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
