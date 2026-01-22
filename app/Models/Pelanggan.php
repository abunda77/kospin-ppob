<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Pelanggan extends Model
{
    /** @use HasFactory<\Database\Factories\PelangganFactory> */
    use HasFactory;
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('pelanggan')
            ->setDescriptionForEvent(fn(string $eventName) => "Pelanggan has been {$eventName}");
    }

    protected $table = 'pelanggan';

    protected $fillable = [
        'nama',
        'email',
        'no_hp',
        'alamat',
        'kota',
        'provinsi',
        'kode_pos',
        'aktif',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
        ];
    }
}
