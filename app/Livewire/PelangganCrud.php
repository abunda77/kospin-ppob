<?php

namespace App\Livewire;

use App\Imports\PelangganImport;
use App\Models\Pelanggan;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class PelangganCrud extends Component
{
    use WithFileUploads;
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

    // Import Properties
    public ?TemporaryUploadedFile $importFile = null;

    public string $importMode = 'append';

    public bool $showImportModal = false;

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

    // Export Methods
    public function exportExcel()
    {
        return redirect()->route('pelanggan.export.excel');
    }

    public function exportPdf()
    {
        return redirect()->route('pelanggan.export.pdf');
    }

    public function downloadTemplate()
    {
        return redirect()->route('pelanggan.export.template');
    }

    // Import Methods
    public function updatedImportFile()
    {
        // Reset validation before showing modal
        $this->resetValidation('importFile');

        // Validate file
        try {
            $this->validate([
                'importFile' => 'required|file|mimes:xlsx,csv,xls|max:2048',
            ], [
                'importFile.required' => 'File harus dipilih.',
                'importFile.mimes' => 'File harus berformat XLSX, XLS, atau CSV.',
                'importFile.max' => 'Ukuran file maksimal 2MB.',
            ]);

            // If validation passes, show the modal
            $this->showImportModal = true;
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, show error message
            session()->flash('error', $e->validator->errors()->first('importFile'));
            $this->importFile = null;
        }
    }

    public function showImportConfirmation(): void
    {
        $this->validate([
            'importFile' => 'required|file|mimes:xlsx,csv,xls|max:2048',
        ], [
            'importFile.required' => 'File harus dipilih.',
            'importFile.mimes' => 'File harus berformat XLSX, XLS, atau CSV.',
            'importFile.max' => 'Ukuran file maksimal 2MB.',
        ]);

        $this->showImportModal = true;
    }

    public function executeImport(): void
    {
        // Log untuk debugging
        \Log::info('executeImport called', [
            'importFile' => $this->importFile ? $this->importFile->getClientOriginalName() : 'null',
            'importMode' => $this->importMode,
        ]);

        // Validate import file exists
        if (! $this->importFile) {
            \Log::error('Import failed: File not found');
            session()->flash('error', 'File tidak ditemukan. Silakan upload ulang file.');
            $this->showImportModal = false;

            return;
        }

        // Validate import mode is selected
        if (! in_array($this->importMode, ['append', 'replace'])) {
            \Log::error('Import failed: Invalid mode', ['mode' => $this->importMode]);
            session()->flash('error', 'Silakan pilih mode import.');

            return;
        }

        try {
            $replaceMode = $this->importMode === 'replace';

            \Log::info('Starting import', [
                'mode' => $replaceMode ? 'replace' : 'append',
                'file' => $this->importFile->getClientOriginalName(),
            ]);

            // Execute import
            Excel::import(
                new PelangganImport($replaceMode),
                $this->importFile->getRealPath()
            );

            $message = $replaceMode
                ? 'Data pelanggan berhasil diimport dan data lama telah ditimpa.'
                : 'Data pelanggan berhasil diimport.';

            session()->flash('message', $message);
            \Log::info('Import success', ['mode' => $replaceMode ? 'replace' : 'append']);

            // Close modal and reset
            $this->showImportModal = false;
            $this->importFile = null;
            $this->importMode = 'append';

            // Refresh data
            $this->dispatch('refreshPelanggan');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];

            foreach ($failures as $failure) {
                $errorMessages[] = "Baris {$failure->row()}: ".implode(', ', $failure->errors());
            }

            \Log::error('Import validation failed', ['errors' => $errorMessages]);
            session()->flash('error', 'Validasi gagal: '.implode(' | ', $errorMessages));
            $this->showImportModal = false;
        } catch (\Exception $e) {
            \Log::error('Import exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Terjadi kesalahan saat import: '.$e->getMessage());
            $this->showImportModal = false;
        }
    }

    public function cancelImport(): void
    {
        $this->showImportModal = false;
        $this->importFile = null;
        $this->importMode = 'append';
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
