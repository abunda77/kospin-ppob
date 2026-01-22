<div>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h2 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">
            {{ __('Activity Logs') }}
        </h2>
        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <div class="w-full sm:w-64">
                <flux:input 
                    wire:model.live.debounce.300ms="search" 
                    icon="magnifying-glass" 
                    placeholder="Search logs..." 
                />
            </div>
            <flux:button variant="danger" wire:click="clearLogs" wire:confirm="{{ __('Are you sure you want to delete all activity logs? This action cannot be undone.') }}">
                {{ __('Clear Logs') }}
            </flux:button>
        </div>
    </div>

    <div class="overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700 shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                            {{ __('User') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                            {{ __('Description') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                            {{ __('Subject') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                            {{ __('Properties') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider dark:text-zinc-400">
                            {{ __('Date') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-zinc-200 dark:bg-zinc-900 dark:divide-zinc-700">
                    @forelse ($activities as $activity)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($activity->causer)
                                    <div class="flex items-center gap-2">
                                        <flux:avatar :name="$activity->causer->name" size="xs" />
                                        <span class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $activity->causer->name }}</span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-2">
                                        <div class="size-6 rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center">
                                            <flux:icon name="computer-desktop" class="size-3 text-zinc-500" />
                                        </div>
                                        <span class="text-sm text-zinc-500 dark:text-zinc-400">System</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-700 dark:text-zinc-300">
                                {{ $activity->description }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                @if($activity->subject)
                                    <span class="font-mono text-xs bg-zinc-100 dark:bg-zinc-800 px-2 py-1 rounded">{{ class_basename($activity->subject_type) }}:{{ $activity->subject_id }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                @if($activity->properties && $activity->properties->count() > 0)
                                    <div class="max-w-xs overflow-hidden text-ellipsis" title="{{ json_encode($activity->properties) }}">
                                        <span class="text-xs font-mono text-zinc-500">{{ Str::limit(json_encode($activity->properties), 40) }}</span>
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                                {{ $activity->created_at->format('d M Y H:i:s') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-zinc-500 dark:text-zinc-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <flux:icon name="magnifying-glass" class="size-8 opacity-50" />
                                    <p>{{ __('No activity logs found.') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($activities->hasPages())
            <div class="bg-zinc-50 dark:bg-zinc-800 px-4 py-3 border-t border-zinc-200 dark:border-zinc-700">
                {{ $activities->links() }}
            </div>
        @endif
    </div>
</div>
