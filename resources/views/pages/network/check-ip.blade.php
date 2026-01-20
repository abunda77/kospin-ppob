<x-layouts::app title="Check IP Address">
    <div class="px-4 sm:px-6 lg:px-8 py-8">
        <div class="max-w-7xl mx-auto">
            <flux:heading size="xl" class="mb-6">Check IP Address</flux:heading>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Panel - IP Information -->
                <div class="space-y-4">
                    <div class="p-6 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-sm">
                        <flux:heading size="lg" class="mb-4">Current Public IP Address</flux:heading>
                        
                        <div class="space-y-4">
                            <!-- Method 1: ipify -->
                            <div class="p-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/50">
                                <flux:text class="text-sm font-medium text-zinc-600 dark:text-zinc-400 mb-1">
                                    Method 1 (ipify)
                                </flux:text>
                                <flux:text class="text-lg font-mono {{ $results['method1'] === 'Failed' ? 'text-red-600 dark:text-red-400' : 'text-zinc-900 dark:text-zinc-100' }}">
                                    {{ $results['method1'] ?? 'N/A' }}
                                </flux:text>
                            </div>
                            
                            <!-- Method 2: ip-api -->
                            <div class="p-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/50">
                                <flux:text class="text-sm font-medium text-zinc-600 dark:text-zinc-400 mb-1">
                                    Method 2 (ip-api)
                                </flux:text>
                                <flux:text class="text-lg font-mono {{ $results['method2'] === 'Failed' ? 'text-red-600 dark:text-red-400' : 'text-zinc-900 dark:text-zinc-100' }}">
                                    {{ $results['method2'] ?? 'N/A' }}
                                </flux:text>
                                
                                @if($results['location'] && $results['method2'] !== 'Failed')
                                    <flux:text class="text-sm text-zinc-600 dark:text-zinc-400 mt-2">
                                        📍 Location: {{ $results['location'] }}
                                    </flux:text>
                                @endif
                                
                                @if($results['isp'] && $results['method2'] !== 'Failed')
                                    <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                                        🌐 ISP: {{ $results['isp'] }}
                                    </flux:text>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Refresh Button -->
                    <flux:button variant="primary" class="w-full" icon="arrow-path" onclick="window.location.reload()">
                        Refresh IP Check
                    </flux:button>
                </div>
                
                <!-- Right Panel - IP Whitelist Verification -->
                <div>
                    <div class="p-6 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-sm">
                        <flux:heading size="lg" class="mb-4">IP Whitelist Verification</flux:heading>
                        
                        <div class="space-y-4">
                            <!-- Registered IP -->
                            <div class="p-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/50">
                                <flux:text class="text-sm font-medium text-zinc-600 dark:text-zinc-400 mb-1">
                                    Registered IP in Dashboard
                                </flux:text>
                                <flux:text class="text-lg font-mono text-zinc-900 dark:text-zinc-100">
                                    {{ $results['registered_ip'] ?? 'Not Set' }}
                                </flux:text>
                            </div>
                            
                            <!-- Current IP -->
                            <div class="p-4 rounded-xl bg-zinc-50 dark:bg-zinc-800/50">
                                <flux:text class="text-sm font-medium text-zinc-600 dark:text-zinc-400 mb-1">
                                    Current IP
                                </flux:text>
                                <flux:text class="text-lg font-mono text-zinc-900 dark:text-zinc-100">
                                    {{ $results['current_ip'] ?? 'N/A' }}
                                </flux:text>
                            </div>
                            
                            <!-- Match Status -->
                            @if($results['is_match'])
                                <flux:callout variant="success" class="mt-4">
                                    <div class="flex items-start gap-3">
                                        <span class="text-2xl">✅</span>
                                        <div>
                                            <strong>MATCH!</strong>
                                            <p class="mt-1 text-sm">Your current IP matches the registered IP.</p>
                                            <p class="mt-1 text-sm">You can proceed once the approval is granted.</p>
                                        </div>
                                    </div>
                                </flux:callout>
                            @else
                                <flux:callout variant="warning" class="mt-4">
                                    <div class="flex items-start gap-3">
                                        <span class="text-2xl">⚠️</span>
                                        <div>
                                            <strong>WARNING!</strong>
                                            <p class="mt-1 text-sm">Your current IP does NOT match!</p>
                                            <p class="mt-2 text-sm font-medium">Possible reasons:</p>
                                            <ul class="mt-1 text-sm list-disc list-inside space-y-1">
                                                <li>Your IP changed (dynamic IP from ISP)</li>
                                                <li>You registered a different device's IP</li>
                                                <li>You're behind a different network now</li>
                                            </ul>
                                            <p class="mt-2 text-sm font-medium">Action: Update the whitelist with your current IP</p>
                                        </div>
                                    </div>
                                </flux:callout>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
