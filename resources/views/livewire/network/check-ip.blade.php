<div>
    <div class="flex justify-center mb-6">
        <flux:button wire:click="runCheck" variant="primary" icon="play" icon-variant="mini" class="w-full sm:w-auto">
            <span wire:loading.remove wire:target="runCheck">Start IP Check</span>
            <span wire:loading wire:target="runCheck">Checking IP...</span>
        </flux:button>
    </div>

    <div wire:loading wire:target="runCheck" class="w-full">
         <div class="p-6 rounded-xl bg-zinc-950 border border-zinc-800 shadow-lg font-mono text-sm">
            <div class="flex items-center gap-2 text-green-400">
                <span class="animate-pulse">> Connecting to external IP services...</span>
            </div>
        </div>
    </div>

    @if($results && !$isRunning)
        <div wire:loading.remove wire:target="runCheck" class="p-6 rounded-xl bg-zinc-950 border border-zinc-800 shadow-lg font-mono text-sm text-zinc-300">
            <!-- Simulated Console Output -->
            <div class="text-zinc-500 mb-2 select-none">$ check-ip --method=ipify</div>
            <div class="mb-4 pl-4 border-l-2 border-zinc-800">
                <div>Public IP: <span class="{{ $results['method1'] === 'Failed' ? 'text-red-400' : 'text-blue-400' }}">{{ $results['method1'] ?? 'N/A' }}</span></div>
            </div>

            <div class="text-zinc-500 mb-2 select-none">$ check-ip --method=ip-api</div>
            <div class="mb-4 pl-4 border-l-2 border-zinc-800">
                <div>Public IP: <span class="{{ $results['method2'] === 'Failed' ? 'text-red-400' : 'text-blue-400' }}">{{ $results['method2'] ?? 'N/A' }}</span></div>
                @if($results['location'])
                    <div>Location: <span class="text-zinc-100">{{ $results['location'] }}</span></div>
                @endif
                @if($results['isp'])
                    <div>ISP: <span class="text-zinc-100">{{ $results['isp'] }}</span></div>
                @endif
            </div>

            <div class="text-zinc-500 mb-2 select-none">$ verify-whitelist --current-ip={{ $results['current_ip'] }}</div>
            <div class="mb-4 pl-4 border-l-2 border-zinc-800">
                <div>Registered IP: <span class="text-zinc-500">{{ $results['registered_ip'] ?? 'Not Set' }}</span></div>
                
                @if($results['is_match'])
                    <div class="mt-2 text-green-400 font-bold">
                        [SUCCESS] IP MATCHED!
                    </div>
                    <div class="text-green-500/80">You are authorized to access the system.</div>
                @else
                    <div class="mt-2 text-red-400 font-bold">
                        [WARNING] IP MISMATCH!
                    </div>
                    <div class="text-red-500/80">Your current IP does not match the registered IP.</div>
                @endif
            </div>
            
            <div class="mt-4 text-green-400 animate-pulse">
                $ _
            </div>
        </div>
    @elseif(!$isRunning)
        <div wire:loading.remove wire:target="runCheck" class="text-center text-zinc-500 mt-12 mb-12">
            <div class="text-6xl mb-4 opacity-20">🌐</div>
            <p>Ready to check public IP address.</p>
        </div>
    @endif
</div>
