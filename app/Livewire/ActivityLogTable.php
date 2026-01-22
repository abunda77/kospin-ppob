<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

class ActivityLogTable extends Component
{
    use WithPagination;

    public $search = '';

    public function clearLogs()
    {
        Activity::truncate();

        $this->resetPage();

        $this->dispatch('start-tunnel-log-cleared'); // Optional: dispatch event if needed, but not required based on prompt
    }

    public function render()
    {
        $activities = Activity::query()
            ->with('causer', 'subject')
            ->latest()
            ->when($this->search, function ($query) {
                $query->where('description', 'like', '%'.$this->search.'%')
                    ->orWhere('log_name', 'like', '%'.$this->search.'%')
                    ->orWhereHas('causer', function ($q) {
                        $q->where('name', 'like', '%'.$this->search.'%');
                    });
            })
            ->paginate(10);

        return view('livewire.activity-log-table', [
            'activities' => $activities,
        ]);
    }
}
