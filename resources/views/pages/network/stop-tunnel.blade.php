<x-layouts::app title="Stop Tunnel">
    <div class="px-4 sm:px-6 lg:px-8 py-8">
        <div class="max-w-7xl mx-auto">
            <flux:heading size="xl" class="mb-6">Stop Tunnel</flux:heading>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Panel - Controls -->
                <div class="space-y-4">
                    <div class="p-6 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-sm">
                        <flux:heading size="lg" class="mb-4">Core Operations</flux:heading>
                        
                        <div class="space-y-3">
                            <flux:button variant="danger" class="w-full" icon="stop-circle">
                                Stop Tunnel
                            </flux:button>
                            
                            <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                                Close SSH tunnel window
                            </flux:text>
                        </div>
                    </div>
                    
                    <flux:callout variant="info">
                        <strong>Coming Soon</strong>
                        <p class="mt-1 text-sm">This feature is currently under development.</p>
                    </flux:callout>
                </div>
                
                <!-- Right Panel - Console Output -->
                <div>
                    <div class="p-6 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <flux:heading size="lg">Console Output</flux:heading>
                            <flux:button variant="ghost" size="sm" icon="trash">
                                Clear
                            </flux:button>
                        </div>
                        
                        <flux:textarea 
                            rows="15" 
                            readonly 
                            class="font-mono text-sm bg-zinc-900 text-green-400 dark:bg-zinc-950"
                            placeholder="Output will appear here..."
                        ></flux:textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
