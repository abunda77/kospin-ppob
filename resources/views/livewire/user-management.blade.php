<div>
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <flux:heading size="xl">User Management</flux:heading>
        
        @can('users.create')
            <flux:button wire:click="openCreateModal" variant="primary" icon="plus">
                Create User
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
                placeholder="Search users by name or email..." 
                icon="magnifying-glass"
            />
        </div>
    </div>

    {{-- Users Table --}}
    <div class="mt-4 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                <thead class="bg-neutral-50 dark:bg-neutral-800">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-700 dark:text-neutral-300">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-700 dark:text-neutral-300">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-700 dark:text-neutral-300">
                            Role
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
                    @forelse ($users as $user)
                        <tr wire:key="user-{{ $user->id }}" class="hover:bg-neutral-50 dark:hover:bg-neutral-800">
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <flux:avatar :initials="$user->initials()" />
                                    <div class="text-sm font-medium text-neutral-900 dark:text-neutral-100">
                                        {{ $user->name }}
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="text-sm text-neutral-700 dark:text-neutral-300">
                                    {{ $user->email }}
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @if ($user->roles->first())
                                    <flux:badge variant="primary">
                                        {{ $user->roles->first()->name }}
                                    </flux:badge>
                                @else
                                    <flux:badge variant="ghost">
                                        No Role
                                    </flux:badge>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    @can('users.edit')
                                        <flux:button 
                                            wire:click="openEditModal({{ $user->id }})" 
                                            variant="ghost" 
                                            size="sm"
                                            icon="pencil"
                                        >
                                            Edit
                                        </flux:button>
                                    @endcan

                                    @can('users.delete')
                                        <flux:button 
                                            wire:click="confirmDelete({{ $user->id }})" 
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
                                    <p class="text-lg font-medium">No users found</p>
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
        {{ $users->links() }}
    </div>

    {{-- Create/Edit Modal --}}
    <flux:modal wire:model="showModal" class="max-w-md">
        <form wire:submit="save">
            <flux:heading size="lg">{{ $isEditing ? 'Edit User' : 'Create User' }}</flux:heading>

            <div class="mt-6 space-y-4">
                <flux:field>
                    <flux:label>Name</flux:label>
                    <flux:input wire:model="name" placeholder="Enter user name" />
                    @error('name')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>

                <flux:field>
                    <flux:label>Email</flux:label>
                    <flux:input type="email" wire:model="email" placeholder="Enter email address" />
                    @error('email')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>

                <flux:field>
                    <flux:label>Role</flux:label>
                    <flux:select wire:model="selectedRole" placeholder="Select a role">
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </flux:select>
                    @error('selectedRole')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>

                <flux:field>
                    <flux:label>Password {{ $isEditing ? '(leave blank to keep current)' : '' }}</flux:label>
                    <flux:input type="password" wire:model="password" placeholder="Enter password" />
                    @error('password')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>

                <flux:field>
                    <flux:label>Confirm Password</flux:label>
                    <flux:input type="password" wire:model="password_confirmation" placeholder="Confirm password" />
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
        <flux:heading size="lg">Delete User</flux:heading>
        
        <div class="mt-4">
            <p class="text-sm text-neutral-700 dark:text-neutral-300">
                Are you sure you want to delete this user? This action cannot be undone.
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
