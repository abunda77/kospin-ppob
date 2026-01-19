<?php

namespace App\Livewire;

use App\Models\Kategori;
use Livewire\Component;
use Livewire\WithPagination;

class KategoriCrud extends Component
{
    use WithPagination;

    // Filters
    public string $search = '';

    public int $perPage = 10;

    // Form Data
    public string $nama = '';

    public string $kode = '';

    public string $deskripsi = '';

    public bool $aktif = true;

    public int $urutan = 0;

    public ?int $kategoriId = null;

    // Modal States
    public bool $isEditing = false;

    public bool $showModal = false;

    public bool $showDeleteModal = false;

    protected $listeners = [
        'refreshKategori' => '$refresh',
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    protected function rules(): array
    {
        $rules = [
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50|unique:kategori,kode',
            'deskripsi' => 'nullable|string|max:500',
            'aktif' => 'boolean',
            'urutan' => 'integer|min:0',
        ];

        if ($this->isEditing && $this->kategoriId) {
            $rules['kode'] = 'required|string|max:50|unique:kategori,kode,'.$this->kategoriId;
        }

        return $rules;
    }

    protected function messages(): array
    {
        return [
            'nama.required' => 'Nama kategori harus diisi.',
            'kode.required' => 'Kode kategori harus diisi.',
            'kode.unique' => 'Kode kategori sudah digunakan.',
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
        $kategori = Kategori::findOrFail($id);

        $this->kategoriId = $kategori->id;
        $this->nama = $kategori->nama;
        $this->kode = $kategori->kode;
        $this->deskripsi = $kategori->deskripsi ?? '';
        $this->aktif = $kategori->aktif;
        $this->urutan = $kategori->urutan;

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        if ($this->isEditing) {
            $this->updateKategori();
        } else {
            $this->createKategori();
        }

        $this->showModal = false;
        $this->resetForm();
    }

    protected function createKategori(): void
    {
        Kategori::create([
            'nama' => $this->nama,
            'kode' => $this->kode,
            'deskripsi' => $this->deskripsi ?: null,
            'aktif' => $this->aktif,
            'urutan' => $this->urutan,
        ]);

        session()->flash('message', 'Kategori berhasil ditambahkan.');
    }

    protected function updateKategori(): void
    {
        $kategori = Kategori::findOrFail($this->kategoriId);

        $kategori->update([
            'nama' => $this->nama,
            'kode' => $this->kode,
            'deskripsi' => $this->deskripsi ?: null,
            'aktif' => $this->aktif,
            'urutan' => $this->urutan,
        ]);

        session()->flash('message', 'Kategori berhasil diperbarui.');
    }

    public function confirmDelete(int $id): void
    {
        $this->kategoriId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $kategori = Kategori::findOrFail($this->kategoriId);

        // Check if kategori has sub categories
        if ($kategori->subKategori()->count() > 0) {
            session()->flash('error', 'Kategori tidak dapat dihapus karena masih memiliki sub kategori.');
            $this->showDeleteModal = false;

            return;
        }

        $kategori->delete();

        session()->flash('message', 'Kategori berhasil dihapus.');
        $this->showDeleteModal = false;
        $this->kategoriId = null;
    }

    public function resetForm(): void
    {
        $this->reset(['nama', 'kode', 'deskripsi', 'aktif', 'urutan', 'kategoriId']);
        $this->aktif = true;
        $this->urutan = 0;
        $this->resetValidation();
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function render()
    {
        $kategoris = Kategori::query()
            ->withCount('subKategori')
            ->when($this->search, function ($query) {
                $query->where('nama', 'like', '%'.$this->search.'%')
                    ->orWhere('kode', 'like', '%'.$this->search.'%');
            })
            ->orderBy('urutan')
            ->orderBy('nama')
            ->paginate($this->perPage);

        return view('livewire.kategori-crud', [
            'kategoris' => $kategoris,
        ]);
    }
}
