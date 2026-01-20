<div>
    <div class="flex justify-center mb-6">
        <flux:button wire:click="runCheck" variant="primary" icon="play" icon-variant="mini" class="w-full sm:w-auto">
            <span wire:loading.remove wire:target="runCheck">Start Port Check</span>
            <span wire:loading wire:target="runCheck">Running Scan...</span>
        </flux:button>
    </div>

    @if($isRunning) <!-- Note: isRunning might not update immediately due to Livewire lifecycle, wire:loading above handles button state, but for the area below we can use wire:loading too -->
        <!-- But since runCheck is a method, we can toggle the property manually or stick to wire:loading -->
    @endif

    <div wire:loading wire:target="runCheck" class="w-full">
         <div class="p-6 rounded-xl bg-zinc-950 border border-zinc-800 shadow-lg font-mono text-sm">
            <div class="flex items-center gap-2 text-green-400">
                <span class="animate-pulse">> Scanning ports...</span>
            </div>
        </div>
    </div>

    @if($results && !$isRunning)
        <div wire:loading.remove wire:target="runCheck" class="p-6 rounded-xl bg-zinc-950 border border-zinc-800 shadow-lg font-mono text-sm overflow-x-auto text-zinc-300">
            <!-- Simulated Console Output -->
            <div class="text-zinc-500 mb-2 select-none">$ system-info</div>
            <div class="mb-4 pl-4 border-l-2 border-zinc-800">
                <div>OS: <span class="text-zinc-100">{{ $results['system_info']['os'] }} {{ $results['system_info']['os_version'] }}</span></div>
                <div>PHP: <span class="text-zinc-100">{{ $results['system_info']['php_version'] }}</span></div>
            </div>

            <div class="text-zinc-500 mb-2 select-none">$ check-proxy --port={{ $results['proxy_port'] }}</div>
            <div class="mb-4 pl-4 border-l-2 border-zinc-800">
                @if($results['proxy_port_open'])
                    <div>Status: <span class="text-green-400 font-bold">OPEN</span></div>
                    <div>Process: {{ $results['proxy_port_info']['process'] ?? 'Unknown' }} (PID: {{ $results['proxy_port_info']['pid'] ?? '?' }})</div>
                @else
                    <div>Status: <span class="text-red-400 font-bold">CLOSED</span></div>
                    <div class="text-yellow-500 mt-1">Warning: SSH Tunnel might not be running.</div>
                @endif
            </div>

            <div class="text-zinc-500 mb-2 select-none">$ list-ports --all</div>
            <div class="mb-2 pl-4 border-l-2 border-zinc-800">
                <div>Found {{ $results['total_ports'] }} open ports.</div>
            </div>

            <div class="mt-4 min-w-[600px]">
                <div class="text-zinc-500 border-b border-zinc-800 pb-2 mb-2 flex select-none">
                    <div class="w-24">PORT</div>
                    <div class="w-40">ADDRESS</div>
                    <div class="w-24">PID</div>
                    <div class="flex-1">PROCESS</div>
                </div>
                
                @foreach($results['open_ports'] as $port)
                    <div class="flex hover:bg-zinc-900/50 py-1 transition-colors group">
                        <div class="w-24 text-yellow-500 group-hover:text-yellow-400">{{ $port['port'] }}</div>
                        <div class="w-40 text-blue-400 group-hover:text-blue-300">{{ $port['address'] }}</div>
                        <div class="w-24 text-purple-400 group-hover:text-purple-300">{{ $port['pid'] }}</div>
                        <div class="flex-1 text-zinc-400 group-hover:text-zinc-200">{{ $port['process'] }}</div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-4 text-green-400 animate-pulse">
                $ _
            </div>
        </div>
    @elseif(!$isRunning)
        <div wire:loading.remove wire:target="runCheck" class="text-center text-zinc-500 mt-12 mb-12">
            <div class="text-6xl mb-4 opacity-20">⚡</div>
            <p>Ready to scan system ports.</p>
        </div>
    @endif
</div>
