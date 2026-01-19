<?php

namespace App\Livewire;

use App\Models\Pelanggan;
use Livewire\Component;
use Livewire\WithPagination;

class PelangganCrud extends Component
{
    use WithPagination;

    // Filters
    public string $search = '';

    public int $perPage = 10;

    // Form Data
    public string $nama = '';

    public ?string $email = '';

    public string $no_hp = '';

    public ?string $alamat = '';

    public ?string $kota = '';

    public ?string $provinsi = '';

    public ?string $kode_pos = '';

    public bool $aktif = true;

    public ?string $catatan = '';

    public ?int $pelangganId = null;

    // Modal States
    public bool $isEditing = false;

    public bool $showModal = false;

    public bool $showDeleteModal = false;

    protected $listeners = [
        'refreshPelanggan' => '$refresh',
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    protected function rules(): array
    {
        $rules = [
            'nama' => 'required|string|max:100',
            'email' => 'nullable|email|max:100|unique:pelanggan,email',
            'no_hp' => 'required|string|max:20|unique:pelanggan,no_hp',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'aktif' => 'boolean',
            'catatan' => 'nullable|string',
        ];

        if ($this->isEditing && $this->pelangganId) {
            $rules['email'] = 'nullable|email|max:100|unique:pelanggan,email,'.$this->pelangganId;
            $rules['no_hp'] = 'required|string|max:20|unique:pelanggan,no_hp,'.$this->pelangganId;
        }

        return $rules;
    }

    protected function messages(): array
    {
        return [
            'nama.required' => 'Nama harus diisi.',
            'email.email' => 'Email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'no_hp.required' => 'No. HP harus diisi.',
            'no_hp.unique' => 'No. HP sudah digunakan.',
            'kota.max' => 'Kota tidak boleh lebih dari 100 karakter.',
            'provinsi.max' => 'Provinsi tidak boleh lebih dari 100 karakter.',
            'kode_pos.max' => 'Kode Pos tidak boleh lebih dari 10 karakter.',
        ];
    }

    public function create(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $this->pelangganId = $pelanggan->id;
        $this->nama = $pelanggan->nama;
        $this->email = $pelanggan->email;
        $this->no_hp = $pelanggan->no_hp;
        $this->alamat = $pelanggan->alamat;
        $this->kota = $pelanggan->kota;
        $this->provinsi = $pelanggan->provinsi;
        $this->kode_pos = $pelanggan->kode_pos;
        $this->aktif = $pelanggan->aktif;
        $this->catatan = $pelanggan->catatan;

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        if ($this->isEditing) {
            $this->updatePelanggan();
        } else {
            $this->createPelanggan();
        }

        $this->showModal = false;
        $this->resetForm();
    }

    protected function createPelanggan(): void
    {
        Pelanggan::create([
            'nama' => $this->nama,
            'email' => $this->email,
            'no_hp' => $this->no_hp,
            'alamat' => $this->alamat,
            'kota' => $this->kota,
            'provinsi' => $this->provinsi,
            'kode_pos' => $this->kode_pos,
            'aktif' => $this->aktif,
            'catatan' => $this->catatan,
        ]);

        session()->flash('message', 'Pelanggan berhasil ditambahkan.');
    }

    protected function updatePelanggan(): void
    {
        $pelanggan = Pelanggan::findOrFail($this->pelangganId);

        $pelanggan->update([
            'nama' => $this->nama,
            'email' => $this->email,
            'no_hp' => $this->no_hp,
            'alamat' => $this->alamat,
            'kota' => $this->kota,
            'provinsi' => $this->provinsi,
            'kode_pos' => $this->kode_pos,
            'aktif' => $this->aktif,
            'catatan' => $this->catatan,
        ]);

        session()->flash('message', 'Pelanggan berhasil diperbarui.');
    }

    public function confirmDelete(int $id): void
    {
        $this->pelangganId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $pelanggan = Pelanggan::findOrFail($this->pelangganId);
        $pelanggan->delete();

        session()->flash('message', 'Pelanggan berhasil dihapus.');
        $this->showDeleteModal = false;
        $this->pelangganId = null;
    }

    public function resetForm(): void
    {
        $this->reset([
            'nama',
            'email',
            'no_hp',
            'alamat',
            'kota',
            'provinsi',
            'kode_pos',
            'aktif',
            'catatan',
            'pelangganId',
        ]);
        $this->aktif = true;
        $this->resetValidation();
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function render()
    {
        $pelanggans = Pelanggan::query()
            ->when($this->search, function ($query) {
                $query->where('nama', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%')
                    ->orWhere('no_hp', 'like', '%'.$this->search.'%')
                    ->orWhere('kota', 'like', '%'.$this->search.'%');
            })
            ->orderBy('nama')
            ->paginate($this->perPage);

        return view('livewire.pelanggan-crud', [
            'pelanggans' => $pelanggans,
        ]);
    }
}
