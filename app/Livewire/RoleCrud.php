<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleCrud extends Component
{
    use WithPagination;

    // Filters
    public $search = '';

    public $perPage = 10;

    // Form Data
    public $name;

    public $selectedPermissions = [];

    public $roleId;

    // Modal States
    public $isEditing = false;

    public $showModal = false;

    public $showDeleteModal = false;

    // Listeners
    protected $listeners = [
        'refreshRoles' => '$refresh',
    ];

    // Reset pagination when search changes
    public function updatedSearch()
    {
        $this->resetPage();
    }

    protected function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255|unique:roles,name',
            'selectedPermissions' => 'array',
        ];

        if ($this->isEditing && $this->roleId) {
            $rules['name'] = 'required|string|max:255|unique:roles,name,'.$this->roleId;
        }

        return $rules;
    }

    public function create(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit($roleId): void
    {
        $role = Role::with('permissions')->findOrFail($roleId);

        $this->roleId = $role->id;
        $this->name = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        if ($this->isEditing) {
            $this->updateRole();
        } else {
            $this->createRole();
        }

        $this->showModal = false;
        $this->resetForm();
    }

    protected function createRole(): void
    {
        $role = Role::create(['name' => $this->name]);

        if (! empty($this->selectedPermissions)) {
            $role->givePermissionTo($this->selectedPermissions);
        }

        session()->flash('message', 'Role created successfully.');
    }

    protected function updateRole(): void
    {
        $role = Role::findOrFail($this->roleId);
        $role->update(['name' => $this->name]);
        $role->syncPermissions($this->selectedPermissions);

        session()->flash('message', 'Role updated successfully.');
    }

    public function confirmDelete($roleId): void
    {
        $this->roleId = $roleId;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $role = Role::findOrFail($this->roleId);

        // Prevent deleting protected roles
        if (in_array($role->name, ['Administrator', 'Operator', 'Guest'])) {
            session()->flash('error', 'Cannot delete protected system role.');
            $this->showDeleteModal = false;

            return;
        }

        $role->delete();

        session()->flash('message', 'Role deleted successfully.');
        $this->showDeleteModal = false;
        $this->roleId = null;
    }

    public function resetForm(): void
    {
        $this->reset(['name', 'selectedPermissions', 'roleId']);
        $this->resetValidation();
    }

    public function render()
    {
        $roles = Role::withCount('permissions')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            })
            ->latest()
            ->paginate($this->perPage);

        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->name)[0];
        });

        return view('livewire.role-crud', [
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }
}
