<div>
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <flux:heading size="xl">Role Management</flux:heading>
        
        @can('roles.create')
            <flux:button wire:click="openCreateModal" variant="primary" icon="plus">
                Create Role
            </flux:button>
        @endcan
    </div>

    {{-- Flash Messages --}}
    @if (session()->has('message'))
        <flux:callout variant="success" icon="check-circle" class="mt-4">
            {{ session('message') }}
        </flux:callout>
    @endif

    @if (session()->has('error'))
        <flux:callout variant="danger" icon="exclamation-triangle" class="mt-4">
            {{ session('error') }}
        </flux:callout>
    @endif

    {{-- Search --}}
    <div class="mt-4 flex items-center gap-4">
        <div class="flex-1">
            <flux:input 
                wire:model.live.debounce.300ms="search" 
                placeholder="Search roles..." 
                icon="magnifying-glass"
            />
        </div>
    </div>

    {{-- Roles Table --}}
    <div class="mt-4 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                <thead class="bg-neutral-50 dark:bg-neutral-800">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-700 dark:text-neutral-300">
                            Role Name
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-700 dark:text-neutral-300">
                            Permissions
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-700 dark:text-neutral-300">
                            Users
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-700 dark:text-neutral-300">
                            Created At
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-neutral-700 dark:text-neutral-300">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-900">
                    @forelse ($roles as $role)
                        <tr wire:key="role-{{ $role->id }}" class="hover:bg-neutral-50 dark:hover:bg-neutral-800">
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="text-sm font-medium text-neutral-900 dark:text-neutral-100">
                                    {{ $role->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-neutral-700 dark:text-neutral-300">
                                    {{ $role->permissions_count }} {{ Str::plural('permission', $role->permissions_count) }}
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="text-sm text-neutral-700 dark:text-neutral-300">
                                    {{ $role->users_count }} {{ Str::plural('user', $role->users_count) }}
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                                {{ $role->created_at->format('M d, Y') }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    @can('roles.edit')
                                        <flux:button 
                                            wire:click="openEditModal({{ $role->id }})" 
                                            variant="ghost" 
                                            size="sm"
                                            icon="pencil"
                                        >
                                            Edit
                                        </flux:button>
                                    @endcan

                                    @can('roles.delete')
                                        <flux:button 
                                            wire:click="confirmDelete({{ $role->id }})" 
                                            variant="ghost" 
                                            size="sm"
                                            icon="trash"
                                        >
                                            Delete
                                        </flux:button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="text-neutral-500 dark:text-neutral-400">
                                    <p class="text-lg font-medium">No roles found</p>
                                    <p class="mt-1 text-sm">Try adjusting your search criteria</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $roles->links() }}
    </div>

    {{-- Create/Edit Modal --}}
    <flux:modal wire:model="showModal" class="max-w-2xl">
        <form wire:submit="save">
            <flux:heading size="lg">{{ $isEditing ? 'Edit Role' : 'Create Role' }}</flux:heading>

            <div class="mt-6 space-y-4">
                <flux:field>
                    <flux:label>Role Name</flux:label>
                    <flux:input wire:model="name" placeholder="Enter role name" />
                    @error('name')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>

                <flux:field>
                    <flux:label>Permissions</flux:label>
                    <div class="mt-2 space-y-4">
                        @foreach ($permissions as $group => $groupPermissions)
                            <div class="rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
                                <h4 class="mb-3 text-sm font-semibold capitalize text-neutral-900 dark:text-neutral-100">
                                    {{ $group }}
                                </h4>
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach ($groupPermissions as $permission)
                                        <label class="flex items-center gap-2">
                                            <flux:checkbox 
                                                wire:model="selectedPermissions" 
                                                value="{{ $permission->name }}"
                                            />
                                            <span class="text-sm text-neutral-700 dark:text-neutral-300">
                                                {{ Str::headline(explode('.', $permission->name)[1] ?? $permission->name) }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('selectedPermissions')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>
            </div>

            <div class="mt-6 flex gap-2">
                <flux:button type="submit" variant="primary">
                    {{ $isEditing ? 'Update' : 'Create' }}
                </flux:button>
                <flux:button type="button" wire:click="$set('showModal', false)" variant="ghost">
                    Cancel
                </flux:button>
            </div>
        </form>
    </flux:modal>

    {{-- Delete Confirmation Modal --}}
    <flux:modal wire:model="showDeleteModal" class="max-w-md">
        <flux:heading size="lg">Delete Role</flux:heading>
        
        <div class="mt-4">
            <p class="text-sm text-neutral-700 dark:text-neutral-300">
                Are you sure you want to delete this role? This action cannot be undone.
            </p>
        </div>

        <div class="mt-6 flex gap-2">
            <flux:button wire:click="delete" variant="danger">
                Delete
            </flux:button>
            <flux:button wire:click="$set('showDeleteModal', false)" variant="ghost">
                Cancel
            </flux:button>
        </div>
    </flux:modal>
</div>
