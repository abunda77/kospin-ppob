<x-layouts::app title="Check Port">
    <div class="px-4 sm:px-6 lg:px-8 py-8">
        <div class="max-w-7xl mx-auto">
            <flux:heading size="xl" class="mb-6">Port Status Checker</flux:heading>
            
            <!-- System Information -->
            <div class="mb-6 p-6 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-sm">
                <flux:heading size="lg" class="mb-4">System Information</flux:heading>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/50">
                        <flux:text class="text-sm font-medium text-zinc-600 dark:text-zinc-400 mb-1">
                            Operating System
                        </flux:text>
                        <flux:text class="text-lg font-mono text-zinc-900 dark:text-zinc-100">
                            {{ $results['system_info']['os'] }}
                        </flux:text>
                    </div>
                    
                    <div class="p-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/50">
                        <flux:text class="text-sm font-medium text-zinc-600 dark:text-zinc-400 mb-1">
                            OS Version
                        </flux:text>
                        <flux:text class="text-lg font-mono text-zinc-900 dark:text-zinc-100">
                            {{ $results['system_info']['os_version'] }}
                        </flux:text>
                    </div>
                    
                    <div class="p-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/50">
                        <flux:text class="text-sm font-medium text-zinc-600 dark:text-zinc-400 mb-1">
                            PHP Version
                        </flux:text>
                        <flux:text class="text-lg font-mono text-zinc-900 dark:text-zinc-100">
                            {{ $results['system_info']['php_version'] }}
                        </flux:text>
                    </div>
                </div>
            </div>
            
            <!-- Proxy Port Status -->
            <div class="mb-6 p-6 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-sm">
                <flux:heading size="lg" class="mb-4">🔍 Checking Proxy Port ({{ $results['proxy_port'] }})</flux:heading>
                
                @if($results['proxy_port_open'])
                    <flux:callout variant="success" class="mb-4">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">✅</span>
                            <div>
                                <strong>Port {{ $results['proxy_port'] }} is OPEN</strong>
                                <p class="mt-1 text-sm">SSH Tunnel is running</p>
                            </div>
                        </div>
                    </flux:callout>
                    
                    @if($results['proxy_port_info'])
                        <div class="p-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/50">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <flux:text class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Process</flux:text>
                                    <flux:text class="text-base font-mono text-zinc-900 dark:text-zinc-100">
                                        {{ $results['proxy_port_info']['process'] }}
                                    </flux:text>
                                </div>
                                <div>
                                    <flux:text class="text-sm font-medium text-zinc-600 dark:text-zinc-400">PID</flux:text>
                                    <flux:text class="text-base font-mono text-zinc-900 dark:text-zinc-100">
                                        {{ $results['proxy_port_info']['pid'] }}
                                    </flux:text>
                                </div>
                                <div>
                                    <flux:text class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Address</flux:text>
                                    <flux:text class="text-base font-mono text-zinc-900 dark:text-zinc-100">
                                        {{ $results['proxy_port_info']['address'] }}
                                    </flux:text>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <flux:callout variant="warning">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">❌</span>
                            <div>
                                <strong>Port {{ $results['proxy_port'] }} is CLOSED</strong>
                                <p class="mt-1 text-sm">SSH Tunnel is NOT running</p>
                                <p class="mt-2 text-sm">To start SSH tunnel, click 'Start SSH Tunnel' button</p>
                            </div>
                        </div>
                    </flux:callout>
                @endif
            </div>
            
            <!-- All Open Ports -->
            <div class="p-6 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-sm">
                <flux:heading size="lg" class="mb-4">📋 All Open Ports</flux:heading>
                
                @if($results['total_ports'] > 0)
                    <!-- Summary -->
                    <div class="mb-6 p-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/50">
                        <flux:heading size="base" class="mb-2">📊 Summary</flux:heading>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">Total Open Ports</flux:text>
                                <flux:text class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">
                                    {{ $results['total_ports'] }}
                                </flux:text>
                            </div>
                            <div>
                                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">Common Ports (< 10000)</flux:text>
                                <flux:text class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">
                                    {{ count($results['common_ports']) }}
                                </flux:text>
                            </div>
                            <div>
                                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">High Ports (>= 10000)</flux:text>
                                <flux:text class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">
                                    {{ count($results['high_ports']) }}
                                </flux:text>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Common Ports -->
                    @if(count($results['common_ports']) > 0)
                        <div class="mb-6">
                            <flux:heading size="base" class="mb-3">📌 Common Ports (< 10000)</flux:heading>
                            
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                                    <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Port</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Address</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">PID</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Process</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-700">
                                        @foreach(array_slice($results['common_ports'], 0, 20) as $port)
                                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/30 transition-colors">
                                                <td class="px-4 py-3 whitespace-nowrap text-sm font-mono text-zinc-900 dark:text-zinc-100">
                                                    {{ $port['port'] }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm font-mono text-zinc-600 dark:text-zinc-400">
                                                    {{ $port['address'] }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm font-mono text-zinc-600 dark:text-zinc-400">
                                                    {{ $port['pid'] }}
                                                </td>
                                                <td class="px-4 py-3 text-sm text-zinc-900 dark:text-zinc-100">
                                                    {{ $port['process'] }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            @if(count($results['common_ports']) > 20)
                                <flux:text class="mt-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    ... and {{ count($results['common_ports']) - 20 }} more ports
                                </flux:text>
                            @endif
                        </div>
                    @endif
                    
                    <!-- High Ports -->
                    @if(count($results['high_ports']) > 0)
                        <div>
                            <flux:heading size="base" class="mb-3">📌 High Ports (>= 10000): {{ count($results['high_ports']) }} ports</flux:heading>
                            
                            @if(count($results['high_ports']) <= 10)
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                                        <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Port</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Address</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">PID</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Process</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-700">
                                            @foreach($results['high_ports'] as $port)
                                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/30 transition-colors">
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-mono text-zinc-900 dark:text-zinc-100">
                                                        {{ $port['port'] }}
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-mono text-zinc-600 dark:text-zinc-400">
                                                        {{ $port['address'] }}
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-mono text-zinc-600 dark:text-zinc-400">
                                                        {{ $port['pid'] }}
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-zinc-900 dark:text-zinc-100">
                                                        {{ $port['process'] }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <flux:text class="mb-3 text-sm text-zinc-600 dark:text-zinc-400">
                                    (Too many to display, showing first 5)
                                </flux:text>
                                
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                                        <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Port</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Address</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">PID</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Process</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-700">
                                            @foreach(array_slice($results['high_ports'], 0, 5) as $port)
                                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/30 transition-colors">
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-mono text-zinc-900 dark:text-zinc-100">
                                                        {{ $port['port'] }}
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-mono text-zinc-600 dark:text-zinc-400">
                                                        {{ $port['address'] }}
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-mono text-zinc-600 dark:text-zinc-400">
                                                        {{ $port['pid'] }}
                                                    </td>
                                                    <td class="px-4 py-3 text-sm text-zinc-900 dark:text-zinc-100">
                                                        {{ $port['process'] }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    @endif
                @else
                    <flux:callout variant="warning">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">❌</span>
                            <div>
                                <strong>No open ports found</strong>
                                <p class="mt-1 text-sm">Unable to detect any listening ports on the system</p>
                            </div>
                        </div>
                    </flux:callout>
                @endif
            </div>
            
            <!-- Refresh Button -->
            <div class="mt-6">
                <flux:button variant="primary" class="w-full" icon="arrow-path" onclick="window.location.reload()">
                    Refresh Port Check
                </flux:button>
            </div>
            
            <!-- Footer -->
            <div class="mt-6 p-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/50 text-center">
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    ✓ Port check completed
                </flux:text>
            </div>
        </div>
    </div>
</x-layouts::app>
