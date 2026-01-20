<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TunnelLog extends Model
{
    protected $fillable = [
        'vps_ip',
        'vps_user',
        'proxy_port',
        'status',
        'process_id',
        'output',
        'started_at',
        'stopped_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'string',
            'proxy_port' => 'integer',
            'process_id' => 'integer',
            'started_at' => 'datetime',
            'stopped_at' => 'datetime',
        ];
    }

    public function isRunning(): bool
    {
        return $this->status === 'running';
    }

    public function scopeRunning($query)
    {
        return $query->where('status', 'running');
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
