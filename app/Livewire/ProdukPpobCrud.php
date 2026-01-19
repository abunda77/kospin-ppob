<?php

namespace App\Livewire;

use App\Models\ProdukPpob;
use App\Models\SubKategori;
use Livewire\Component;
use Livewire\WithPagination;

class ProdukPpobCrud extends Component
{
    use WithPagination;

    // Filters
    public string $search = '';

    public int $perPage = 10;

    public ?int $filterSubKategoriId = null;

    // Form Data
    public string $kode = '';

    public string $nama_produk = '';

    public ?int $sub_kategori_id = null;

    public float $hpp = 0;

    public float $biaya_admin = 0;

    public float $fee_mitra = 0;

    public float $markup = 0;

    public float $harga_beli = 0;

    public float $harga_jual = 0;

    public float $profit = 0;

    public bool $aktif = true;

    public ?int $produkId = null;

    // Modal States
    public bool $isEditing = false;

    public bool $showModal = false;

    public bool $showDeleteModal = false;

    protected $listeners = [
        'refreshProdukPpob' => '$refresh',
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterSubKategoriId(): void
    {
        $this->resetPage();
    }

    public function updatedHpp(): void
    {
        $this->calculatePrice();
    }

    public function updatedBiayaAdmin(): void
    {
        $this->calculatePrice();
    }

    public function updatedFeeMitra(): void
    {
        $this->calculatePrice();
    }

    public function updatedMarkup(): void
    {
        $this->calculatePrice();
    }

    protected function calculatePrice(): void
    {
        // Harga Beli = HPP + Beaya Admin
        $this->harga_beli = (float) $this->hpp + (float) $this->biaya_admin;

        // Harga Jual = Harga Beli + Markup + Fee Mitra
        $this->harga_jual = $this->harga_beli + (float) $this->markup + (float) $this->fee_mitra;

        // Profit = Harga Jual - Harga Beli
        $this->profit = $this->harga_jual - $this->harga_beli;
    }

    protected function rules(): array
    {
        $rules = [
            'kode' => 'required|string|max:50|unique:produk_ppob,kode',
            'nama_produk' => 'required|string|max:255',
            'sub_kategori_id' => 'required|integer|exists:sub_kategori,id',
            'hpp' => 'required|numeric|min:0',
            'biaya_admin' => 'required|numeric|min:0',
            'fee_mitra' => 'required|numeric|min:0',
            'markup' => 'required|numeric|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'profit' => 'required|numeric|min:0',
            'aktif' => 'boolean',
        ];

        if ($this->isEditing && $this->produkId) {
            $rules['kode'] = 'required|string|max:50|unique:produk_ppob,kode,'.$this->produkId;
        }

        return $rules;
    }

    protected function messages(): array
    {
        return [
            'kode.required' => 'Kode produk harus diisi.',
            'kode.unique' => 'Kode produk sudah digunakan.',
            'nama_produk.required' => 'Nama produk harus diisi.',
            'sub_kategori_id.required' => 'Sub kategori harus dipilih.',
            'sub_kategori_id.exists' => 'Sub kategori tidak valid.',
            'hpp.required' => 'HPP harus diisi.',
            'hpp.numeric' => 'HPP harus berupa angka.',
            'biaya_admin.numeric' => 'Biaya admin harus berupa angka.',
            'fee_mitra.numeric' => 'Fee mitra harus berupa angka.',
            'markup.numeric' => 'Markup harus berupa angka.',
            'harga_beli.numeric' => 'Harga beli harus berupa angka.',
            'harga_jual.numeric' => 'Harga jual harus berupa angka.',
            'profit.numeric' => 'Profit harus berupa angka.',
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
        $produk = ProdukPpob::findOrFail($id);

        $this->produkId = $produk->id;
        $this->kode = $produk->kode;
        $this->nama_produk = $produk->nama_produk;
        $this->sub_kategori_id = $produk->sub_kategori_id;
        $this->hpp = (float) $produk->hpp;
        $this->biaya_admin = (float) $produk->biaya_admin;
        $this->fee_mitra = (float) $produk->fee_mitra;
        $this->markup = (float) $produk->markup;
        $this->harga_beli = (float) $produk->harga_beli;
        $this->harga_jual = (float) $produk->harga_jual;
        $this->profit = (float) $produk->profit;
        $this->aktif = $produk->aktif;

        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        if ($this->isEditing) {
            $this->updateProduk();
        } else {
            $this->createProduk();
        }

        $this->showModal = false;
        $this->resetForm();
    }

    protected function createProduk(): void
    {
        ProdukPpob::create([
            'kode' => $this->kode,
            'nama_produk' => $this->nama_produk,
            'sub_kategori_id' => $this->sub_kategori_id,
            'hpp' => $this->hpp,
            'biaya_admin' => $this->biaya_admin,
            'fee_mitra' => $this->fee_mitra,
            'markup' => $this->markup,
            'harga_beli' => $this->harga_beli,
            'harga_jual' => $this->harga_jual,
            'profit' => $this->profit,
            'aktif' => $this->aktif,
        ]);

        session()->flash('message', 'Produk PPOB berhasil ditambahkan.');
    }

    protected function updateProduk(): void
    {
        $produk = ProdukPpob::findOrFail($this->produkId);

        $produk->update([
            'kode' => $this->kode,
            'nama_produk' => $this->nama_produk,
            'sub_kategori_id' => $this->sub_kategori_id,
            'hpp' => $this->hpp,
            'biaya_admin' => $this->biaya_admin,
            'fee_mitra' => $this->fee_mitra,
            'markup' => $this->markup,
            'harga_beli' => $this->harga_beli,
            'harga_jual' => $this->harga_jual,
            'profit' => $this->profit,
            'aktif' => $this->aktif,
        ]);

        session()->flash('message', 'Produk PPOB berhasil diperbarui.');
    }

    public function confirmDelete(int $id): void
    {
        $this->produkId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $produk = ProdukPpob::findOrFail($this->produkId);
        $produk->delete();

        session()->flash('message', 'Produk PPOB berhasil dihapus.');
        $this->showDeleteModal = false;
        $this->produkId = null;
    }

    public function resetForm(): void
    {
        $this->reset([
            'kode',
            'nama_produk',
            'sub_kategori_id',
            'hpp',
            'biaya_admin',
            'fee_mitra',
            'markup',
            'harga_beli',
            'harga_jual',
            'profit',
            'aktif',
            'produkId',
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
        $produks = ProdukPpob::query()
            ->with('subKategori.kategori')
            ->when($this->search, function ($query) {
                $query->where('nama_produk', 'like', '%'.$this->search.'%')
                    ->orWhere('kode', 'like', '%'.$this->search.'%');
            })
            ->when($this->filterSubKategoriId, function ($query) {
                $query->where('sub_kategori_id', $this->filterSubKategoriId);
            })
            ->orderBy('nama_produk')
            ->paginate($this->perPage);

        $subKategoris = SubKategori::query()
            ->with('kategori')
            ->where('aktif', true)
            ->orderBy('urutan')
            ->orderBy('nama')
            ->get();

        return view('livewire.produk-ppob-crud', [
            'produks' => $produks,
            'subKategoris' => $subKategoris,
        ]);
    }
}
