<?php

namespace App\Livewire;

use App\Models\Kategori;
use App\Models\SubKategori;
use Livewire\Component;
use Livewire\WithPagination;

class SubKategoriCrud extends Component
{
    use WithPagination;

    // Filters
    public string $search = '';

    public ?int $filterKategori = null;

    public int $perPage = 10;

    // Form Data
    public ?int $kategoriId = null;

    public string $nama = '';

    public string $kode = '';

    public string $deskripsi = '';

    public bool $aktif = true;

    public int $urutan = 0;

    public ?int $subKategoriId = null;

    // Modal States
    public bool $isEditing = false;

    public bool $showModal = false;

    public bool $showDeleteModal = false;

    protected $listeners = [
        'refreshSubKategori' => '$refresh',
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterKategori(): void
    {
        $this->resetPage();
    }

    protected function rules(): array
    {
        $rules = [
            'kategoriId' => 'required|exists:kategori,id',
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50|unique:sub_kategori,kode',
            'deskripsi' => 'nullable|string|max:500',
            'aktif' => 'boolean',
            'urutan' => 'integer|min:0',
        ];

        if ($this->isEditing && $this->subKategoriId) {
            $rules['kode'] = 'required|string|max:50|unique:sub_kategori,kode,'.$this->subKategoriId;
        }

        return $rules;
    }

    protected function messages(): array
    {
        return [
            'kategoriId.required' => 'Kategori harus dipilih.',
            'kategoriId.exists' => 'Kategori tidak valid.',
            'nama.required' => 'Nama sub kategori harus diisi.',
            'kode.required' => 'Kode sub kategori harus diisi.',
            'kode.unique' => 'Kode sub kategori sudah digunakan.',
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
        $subKategori = SubKategori::findOrFail($id);

        $this->subKategoriId = $subKategori->id;
        $this->kategoriId = $subKategori->kategori_id;
        $this->nama = $subKategori->nama;
        $this->kode = $subKategori->kode;
        $this->deskripsi = $subKategori->deskripsi ?? '';
        $this->aktif = $subKategori->aktif;
        $this->urutan = $subKategori->urutan;

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        if ($this->isEditing) {
            $this->updateSubKategori();
        } else {
            $this->createSubKategori();
        }

        $this->showModal = false;
        $this->resetForm();
    }

    protected function createSubKategori(): void
    {
        SubKategori::create([
            'kategori_id' => $this->kategoriId,
            'nama' => $this->nama,
            'kode' => $this->kode,
            'deskripsi' => $this->deskripsi ?: null,
            'aktif' => $this->aktif,
            'urutan' => $this->urutan,
        ]);

        session()->flash('message', 'Sub Kategori berhasil ditambahkan.');
    }

    protected function updateSubKategori(): void
    {
        $subKategori = SubKategori::findOrFail($this->subKategoriId);

        $subKategori->update([
            'kategori_id' => $this->kategoriId,
            'nama' => $this->nama,
            'kode' => $this->kode,
            'deskripsi' => $this->deskripsi ?: null,
            'aktif' => $this->aktif,
            'urutan' => $this->urutan,
        ]);

        session()->flash('message', 'Sub Kategori berhasil diperbarui.');
    }

    public function confirmDelete(int $id): void
    {
        $this->subKategoriId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $subKategori = SubKategori::findOrFail($this->subKategoriId);

        // Check if sub kategori has products
        if ($subKategori->produkPpob()->count() > 0) {
            session()->flash('error', 'Sub Kategori tidak dapat dihapus karena masih memiliki produk.');
            $this->showDeleteModal = false;

            return;
        }

        $subKategori->delete();

        session()->flash('message', 'Sub Kategori berhasil dihapus.');
        $this->showDeleteModal = false;
        $this->subKategoriId = null;
    }

    public function resetForm(): void
    {
        $this->reset(['kategoriId', 'nama', 'kode', 'deskripsi', 'aktif', 'urutan', 'subKategoriId']);
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
        $subKategoris = SubKategori::query()
            ->with('kategori')
            ->withCount('produkPpob')
            ->when($this->search, function ($query) {
                $query->where('nama', 'like', '%'.$this->search.'%')
                    ->orWhere('kode', 'like', '%'.$this->search.'%');
            })
            ->when($this->filterKategori, function ($query) {
                $query->where('kategori_id', $this->filterKategori);
            })
            ->orderBy('urutan')
            ->orderBy('nama')
            ->paginate($this->perPage);

        $kategoris = Kategori::where('aktif', true)->orderBy('urutan')->orderBy('nama')->get();

        return view('livewire.sub-kategori-crud', [
            'subKategoris' => $subKategoris,
            'kategoris' => $kategoris,
        ]);
    }
}
