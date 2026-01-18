<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class UserCrud extends Component
{
    use WithPagination;

    // Filters
    public $search = '';
    public $perPage = 10;

    // Form Data
    public $name;
    public $email;
    public $password;
    public $selectedRoles = [];
    public $userId;

    // Modal States
    public $isEditing = false;
    public $showModal = false;
    public $showDeleteModal = false;

    // Listeners are implicit in Livewire 3/4 usually, or wire:click is used directly.
    // Keeping listeners for external events if any.
    protected $listeners = [
        'refreshUsers' => '$refresh',
        // 'create' => 'create', // We'll call create() directly from the view
    ];

    // Reset pagination when search changes
    public function updatedSearch()
    {
        $this->resetPage();
    }

    protected function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'selectedRoles' => 'array',
        ];

        if ($this->isEditing && $this->userId) {
            $rules['email'] = 'required|email|max:255|unique:users,email,'.$this->userId;
            $rules['password'] = 'nullable|string|min:8';
        }

        return $rules;
    }

    public function create(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit($userId): void
    {
        $user = User::with('roles')->findOrFail($userId);

        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        // Password not filled for security
        $this->selectedRoles = $user->roles->pluck('name')->toArray();
        
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        if ($this->isEditing) {
            $this->updateUser();
        } else {
            $this->createUser();
        }

        $this->showModal = false;
        $this->resetForm();
    }

    protected function createUser(): void
    {
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        if (! empty($this->selectedRoles)) {
            $user->assignRole($this->selectedRoles);
        }

        session()->flash('message', 'User created successfully.');
    }

    protected function updateUser(): void
    {
        $user = User::findOrFail($this->userId);
        
        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if (! empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        $user->update($data);
        $user->syncRoles($this->selectedRoles);

        session()->flash('message', 'User updated successfully.');
    }

    public function confirmDelete($userId): void
    {
        $this->userId = $userId;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $user = User::findOrFail($this->userId);

        // Prevent deleting self
        if ($user->id === auth()->id()) {
             session()->flash('error', 'You cannot delete your own account.');
             $this->showDeleteModal = false;
             return;
        }

        $user->delete();

        session()->flash('message', 'User deleted successfully.');
        $this->showDeleteModal = false;
        $this->userId = null;
    }

    public function resetForm(): void
    {
        $this->reset(['name', 'email', 'password', 'selectedRoles', 'userId']);
        $this->resetValidation();
    }

    public function render()
    {
        $users = User::with('roles')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate($this->perPage);

        $roles = Role::all();

        return view('livewire.user-crud', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }
}
