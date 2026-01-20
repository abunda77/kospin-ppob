<div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Left Panel - Controls -->
        <div class="space-y-4">
            <div class="p-6 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-sm">
                <flux:heading size="lg" class="mb-4">Tunnel Configuration</flux:heading>

                <div class="space-y-4">
                    <flux:field>
                        <flux:label>VPS IP Address</flux:label>
                        <flux:input
                            type="text"
                            wire:model.live="vpsIp"
                            placeholder="192.168.1.100"
                            disabled="{{ $status === 'running' }}"
                        />
                    </flux:field>

                    <flux:field>
                        <flux:label>VPS User</flux:label>
                        <flux:input
                            type="text"
                            wire:model.live="vpsUser"
                            placeholder="username"
                            disabled="{{ $status === 'running' }}"
                        />
                    </flux:field>

                    <flux:field>
                        <flux:label>Local SOCKS5 Port</flux:label>
                        <flux:input
                            type="number"
                            wire:model.live="proxyPort"
                            placeholder="1080"
                            disabled="{{ $status === 'running' }}"
                        />
                    </flux:field>

                    <div class="pt-4 border-t border-zinc-200 dark:border-zinc-700">
                        @if ($status === 'running')
                            <flux:button
                                variant="danger"
                                wire:click="stopTunnel"
                                wire:loading.attr="disabled"
                                class="w-full"
                            >
                                <span wire:loading.remove wire:target="stopTunnel" class="flex items-center gap-2">
                                    <flux:icon.stop-circle class="size-5" />
                                    <span>Stop Tunnel</span>
                                </span>
                                <span wire:loading wire:target="stopTunnel" class="flex items-center gap-2">
                                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>Stopping...</span>
                                </span>
                            </flux:button>
                        @else
                            <flux:button
                                variant="primary"
                                wire:click="startTunnel"
                                wire:loading.attr="disabled"
                                wire:target="startTunnel"
                                class="w-full"
                            >
                                <span wire:loading.remove wire:target="startTunnel" class="flex items-center gap-2">
                                    <flux:icon.play-circle class="size-5" />
                                    <span>Start Tunnel</span>
                                </span>
                                <span wire:loading wire:target="startTunnel" class="flex items-center gap-2">
                                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>Starting...</span>
                                </span>
                            </flux:button>
                        @endif
                    </div>

                    @if ($status === 'running')
                        <flux:callout variant="success">
                            <strong>Tunnel is Active</strong>
                            <p class="mt-1 text-sm">
                                Tunnel ID: {{ $currentTunnelId }}<br>
                                Process ID: {{ $processId }}
                            </p>
                        </flux:callout>
                    @endif
                </div>
            </div>

            <flux:callout variant="info">
                <strong>Tunnel Information</strong>
                <p class="mt-1 text-sm">
                    SSH SOCKS5 tunnel creates a local proxy that routes traffic through your VPS.
                    Use proxy configuration: <code>127.0.0.1:{{ $proxyPort }}</code>
                </p>
            </flux:callout>
        </div>

        <!-- Right Panel - Console Output -->
        <div>
            <div class="p-6 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full {{ $status === 'running' ? 'bg-green-500' : 'bg-zinc-400' }}"></div>
                        <flux:heading size="lg">Console Output</flux:heading>
                    </div>
                    <flux:button
                        variant="ghost"
                        size="sm"
                        wire:click="clearOutput"
                        icon="trash"
                    >
                        Clear
                    </flux:button>
                </div>

                <flux:textarea
                    rows="15"
                    readonly
                    wire:model.live="output"
                    class="font-mono text-sm bg-zinc-900 text-green-400 dark:bg-zinc-950"
                ></flux:textarea>
            </div>
        </div>
    </div>
</div>
