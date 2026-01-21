<?php

namespace App\Livewire;

use App\Models\DatabaseBackup;
use Illuminate\Support\Facades\Process;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BackupDatabaseCrud extends Component
{
    use WithPagination;

    // Filters
    public string $search = '';

    public int $perPage = 10;

    // Modal States
    public bool $showDeleteModal = false;

    public ?int $backupId = null;

    protected $listeners = [
        'refreshBackups' => '$refresh',
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function createBackup(): void
    {
        try {
            $timestamp = now()->format('Y-m-d-H-i-s');
            $connection = config('database.default');

            // Determine file extension based on database type
            $extension = $connection === 'sqlite' ? 'sqlite' : 'sql';
            $fileName = "backup-{$timestamp}.{$extension}";
            $backupsPath = storage_path('app/backups');
            $filePath = "{$backupsPath}/{$fileName}";

            // Ensure backups directory exists
            if (! is_dir($backupsPath)) {
                mkdir($backupsPath, 0755, true);
            }

            // Create backup record with processing status
            $backup = DatabaseBackup::create([
                'file_name' => $fileName,
                'file_path' => "backups/{$fileName}",
                'file_size' => 0,
                'type' => 'manual',
                'status' => 'processing',
                'created_by' => auth()->id(),
            ]);

            $backupSuccess = false;

            // Handle backup based on database connection type
            if ($connection === 'sqlite') {
                $backupSuccess = $this->backupSqlite($filePath);
            } else {
                $backupSuccess = $this->backupMysql($filePath);
            }

            if ($backupSuccess && file_exists($filePath)) {
                $fileSize = filesize($filePath);

                $backup->update([
                    'file_size' => $fileSize,
                    'status' => 'success',
                ]);

                session()->flash('message', 'Backup database berhasil dibuat.');
            } else {
                $backup->update(['status' => 'failed']);
                session()->flash('error', 'Backup database gagal. Pastikan koneksi database sudah benar.');
            }
        } catch (\Exception $e) {
            if (isset($backup)) {
                $backup->update(['status' => 'failed']);
            }
            session()->flash('error', 'Error: '.$e->getMessage());
        }
    }

    /**
     * Backup SQLite database by copying the database file.
     */
    protected function backupSqlite(string $filePath): bool
    {
        try {
            $dbPath = config('database.connections.sqlite.database');

            // If path is relative, resolve from base path
            if (! file_exists($dbPath)) {
                $dbPath = database_path('database.sqlite');
            }

            if (! file_exists($dbPath)) {
                \Log::error('SQLite database file not found: '.$dbPath);

                return false;
            }

            // Copy the SQLite database file
            if (copy($dbPath, $filePath)) {
                return true;
            }

            return false;
        } catch (\Exception $e) {
            \Log::error('SQLite backup failed: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Backup MySQL database using mysqldump or PDO fallback.
     */
    protected function backupMysql(string $filePath): bool
    {
        $host = config('database.connections.mysql.host', '127.0.0.1');
        $port = config('database.connections.mysql.port', '3306');
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');

        // Try mysqldump first
        $mysqldumpPath = $this->findMysqldump();
        $backupSuccess = false;

        if ($mysqldumpPath) {
            $command = "\"{$mysqldumpPath}\" --host={$host} --port={$port} --user={$username}";

            if (! empty($password)) {
                $command .= " --password={$password}";
            }

            $command .= " {$database} > \"{$filePath}\"";

            $result = Process::run($command);

            if ($result->successful() && file_exists($filePath) && filesize($filePath) > 0) {
                $backupSuccess = true;
            }
        }

        // Fallback to PDO export if mysqldump failed
        if (! $backupSuccess) {
            $backupSuccess = $this->exportDatabaseViaPdo($filePath, $host, $port, $database, $username, $password);
        }

        return $backupSuccess;
    }

    /**
     * Find mysqldump executable path.
     */
    protected function findMysqldump(): ?string
    {
        $paths = [
            'mysqldump',
            'C:\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\mysqldump.exe',
            'C:\\xampp\\mysql\\bin\\mysqldump.exe',
            '/usr/bin/mysqldump',
            '/usr/local/bin/mysqldump',
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        // Check if mysqldump is in PATH
        $result = Process::run(PHP_OS_FAMILY === 'Windows' ? 'where mysqldump 2>nul' : 'which mysqldump 2>/dev/null');
        if ($result->successful() && trim($result->output())) {
            return trim($result->output());
        }

        return null;
    }

    /**
     * Export database using PDO as fallback for MySQL.
     */
    protected function exportDatabaseViaPdo(string $filePath, string $host, string $port, string $database, string $username, string $password): bool
    {
        try {
            $pdo = new \PDO(
                "mysql:host={$host};port={$port};dbname={$database}",
                $username,
                $password,
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );

            $output = "-- Database Backup\n";
            $output .= '-- Generated: '.now()->toDateTimeString()."\n";
            $output .= "-- Database: {$database}\n\n";
            $output .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

            // Get all tables
            $tables = $pdo->query('SHOW TABLES')->fetchAll(\PDO::FETCH_COLUMN);

            foreach ($tables as $table) {
                // Get table structure
                $createTable = $pdo->query("SHOW CREATE TABLE `{$table}`")->fetch(\PDO::FETCH_ASSOC);
                $output .= "-- Table: {$table}\n";
                $output .= "DROP TABLE IF EXISTS `{$table}`;\n";
                $output .= $createTable['Create Table'].";\n\n";

                // Get table data
                $rows = $pdo->query("SELECT * FROM `{$table}`")->fetchAll(\PDO::FETCH_ASSOC);

                if (count($rows) > 0) {
                    $columns = array_keys($rows[0]);
                    $columnList = '`'.implode('`, `', $columns).'`';

                    foreach ($rows as $row) {
                        $values = array_map(function ($value) use ($pdo) {
                            if ($value === null) {
                                return 'NULL';
                            }

                            return $pdo->quote($value);
                        }, array_values($row));

                        $output .= "INSERT INTO `{$table}` ({$columnList}) VALUES (".implode(', ', $values).");\n";
                    }

                    $output .= "\n";
                }
            }

            $output .= "SET FOREIGN_KEY_CHECKS=1;\n";

            file_put_contents($filePath, $output);

            return true;
        } catch (\Exception $e) {
            \Log::error('PDO Backup failed: '.$e->getMessage());

            return false;
        }
    }

    public function download(int $id): StreamedResponse
    {
        $backup = DatabaseBackup::findOrFail($id);
        $filePath = storage_path("app/{$backup->file_path}");

        if (! file_exists($filePath)) {
            session()->flash('error', 'File backup tidak ditemukan.');

            return back();
        }

        $contentType = str_ends_with($backup->file_name, '.sqlite')
            ? 'application/x-sqlite3'
            : 'application/sql';

        return response()->streamDownload(function () use ($filePath) {
            readfile($filePath);
        }, $backup->file_name, [
            'Content-Type' => $contentType,
        ]);
    }

    public function confirmDelete(int $id): void
    {
        $this->backupId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $backup = DatabaseBackup::findOrFail($this->backupId);

        // Delete the actual file
        $filePath = storage_path("app/{$backup->file_path}");
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $backup->delete();

        session()->flash('message', 'Backup berhasil dihapus.');
        $this->showDeleteModal = false;
        $this->backupId = null;
    }

    public function render()
    {
        $backups = DatabaseBackup::query()
            ->when($this->search, function ($query) {
                $query->where('file_name', 'like', '%'.$this->search.'%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.backup-database-crud', [
            'backups' => $backups,
        ]);
    }
}
