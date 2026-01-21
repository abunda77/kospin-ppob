<?php

use App\Livewire\BackupDatabaseCrud;
use App\Models\DatabaseBackup;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;

beforeEach(function () {
    // Create backup permission if not exists
    Permission::firstOrCreate(['name' => 'backup.view']);
    Permission::firstOrCreate(['name' => 'backup.create']);
    Permission::firstOrCreate(['name' => 'backup.delete']);
});

it('requires authentication to access backup database page', function () {
    $this->get(route('backup-database.index'))
        ->assertRedirect(route('login'));
});

it('requires backup.view permission to access backup database page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('backup-database.index'))
        ->assertForbidden();
});

it('allows user with backup.view permission to access the page', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('backup.view');

    $this->actingAs($user)
        ->get(route('backup-database.index'))
        ->assertSuccessful()
        ->assertSeeLivewire(BackupDatabaseCrud::class);
});

it('displays the backup database page title', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('backup.view');

    $this->actingAs($user)
        ->get(route('backup-database.index'))
        ->assertSee('Backup Database');
});

it('renders the backup database livewire component', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('backup.view');

    Livewire::actingAs($user)
        ->test(BackupDatabaseCrud::class)
        ->assertSuccessful()
        ->assertSee('Backup Database');
});

it('displays backup records in the table', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('backup.view');

    $backup = DatabaseBackup::create([
        'file_name' => 'test-backup-2026-01-21.sql',
        'file_path' => 'backups/test-backup-2026-01-21.sql',
        'file_size' => 1024000,
        'type' => 'manual',
        'status' => 'success',
        'created_by' => $user->id,
    ]);

    Livewire::actingAs($user)
        ->test(BackupDatabaseCrud::class)
        ->assertSee('test-backup-2026-01-21.sql');
});

it('can search backup records', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('backup.view');

    DatabaseBackup::create([
        'file_name' => 'backup-2026-01-20.sql',
        'file_path' => 'backups/backup-2026-01-20.sql',
        'file_size' => 1024,
        'type' => 'manual',
        'status' => 'success',
        'created_by' => $user->id,
    ]);

    DatabaseBackup::create([
        'file_name' => 'backup-2026-01-21.sql',
        'file_path' => 'backups/backup-2026-01-21.sql',
        'file_size' => 2048,
        'type' => 'manual',
        'status' => 'success',
        'created_by' => $user->id,
    ]);

    Livewire::actingAs($user)
        ->test(BackupDatabaseCrud::class)
        ->set('search', '2026-01-21')
        ->assertSee('backup-2026-01-21.sql')
        ->assertDontSee('backup-2026-01-20.sql');
});

it('shows delete confirmation modal', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('backup.view');

    $backup = DatabaseBackup::create([
        'file_name' => 'test-backup.sql',
        'file_path' => 'backups/test-backup.sql',
        'file_size' => 1024,
        'type' => 'manual',
        'status' => 'success',
        'created_by' => $user->id,
    ]);

    Livewire::actingAs($user)
        ->test(BackupDatabaseCrud::class)
        ->call('confirmDelete', $backup->id)
        ->assertSet('showDeleteModal', true)
        ->assertSet('backupId', $backup->id);
});

it('can delete a backup record', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('backup.view');

    $backup = DatabaseBackup::create([
        'file_name' => 'test-backup-delete.sql',
        'file_path' => 'backups/test-backup-delete.sql',
        'file_size' => 1024,
        'type' => 'manual',
        'status' => 'success',
        'created_by' => $user->id,
    ]);

    Livewire::actingAs($user)
        ->test(BackupDatabaseCrud::class)
        ->call('confirmDelete', $backup->id)
        ->call('delete');

    expect(DatabaseBackup::find($backup->id))->toBeNull();
});

it('displays correct status badges', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('backup.view');

    DatabaseBackup::create([
        'file_name' => 'success-backup.sql',
        'file_path' => 'backups/success-backup.sql',
        'file_size' => 1024,
        'type' => 'manual',
        'status' => 'success',
        'created_by' => $user->id,
    ]);

    Livewire::actingAs($user)
        ->test(BackupDatabaseCrud::class)
        ->assertSee('success');
});
