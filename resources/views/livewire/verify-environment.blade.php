<div>
    <div class="space-y-6">
        <!-- Header -->
        <div class="p-6 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <flux:heading size="lg">Environment Configuration Verification</flux:heading>
                    <flux:text class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">
                        Verify all required environment variables and configuration
                    </flux:text>
                </div>
                <flux:button variant="ghost" wire:click="refreshVerification">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 15m-15.356-2A8.001 8.001 0 012 4v3a8 8 0 018 8v3a8 8 0 01-8-8m0 0v-5a8 8 0 0116 0v5a8 8 0 01-8 8" />
                        </svg>
                        Refresh
                    </div>
                </flux:button>
            </div>
        </div>

        <!-- Overall Status -->
        <div class="p-6 rounded-3xl border shadow-sm {{ $allChecksPassed ? 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800' : 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800' }}">
            <div class="flex items-center gap-3">
                @if ($allChecksPassed)
                    <div class="text-green-600 dark:text-green-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <flux:heading size="md" class="text-green-900 dark:text-green-100">All Checks Passed!</flux:heading>
                        <flux:text class="text-sm text-green-700 dark:text-green-300 mt-1">
                            Your environment is properly configured. You can now use the network connection features.
                        </flux:text>
                    </div>
                @else
                    <div class="text-red-600 dark:text-red-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <flux:heading size="md" class="text-red-900 dark:text-red-100">Some Checks Failed</flux:heading>
                        <flux:text class="text-sm text-red-700 dark:text-red-300 mt-1">
                            Please fix the issues above before using network features.
                        </flux:text>
                    </div>
                @endif
            </div>
        </div>

        <!-- Environment File Check -->
        <div class="p-6 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-sm">
            <flux:heading size="lg" class="mb-4">Environment File Check</flux:heading>
            
            <div class="flex items-center gap-3">
                @if ($envFileExists)
                    <div class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <flux:text class="text-sm text-zinc-900 dark:text-zinc-100">
                            <strong>.env file exists</strong>
                        </flux:text>
                    </div>
                @else
                    <div class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <div>
                        <flux:text class="text-sm text-zinc-900 dark:text-zinc-100">
                            <strong>.env file not found!</strong>
                        </flux:text>
                        <flux:text class="text-xs text-zinc-600 dark:text-zinc-400 mt-1">
                            Please create .env file by copying .env.example
                        </flux:text>
                    </div>
                @endif
            </div>
        </div>

        <!-- Required Variables -->
        <div class="p-6 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-sm">
            <flux:heading size="lg" class="mb-4">Required Environment Variables</flux:heading>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-700">
                            <th class="text-left py-3 px-4 font-semibold text-zinc-900 dark:text-zinc-100">Variable</th>
                            <th class="text-left py-3 px-4 font-semibold text-zinc-900 dark:text-zinc-100">Description</th>
                            <th class="text-left py-3 px-4 font-semibold text-zinc-900 dark:text-zinc-100">Value</th>
                            <th class="text-left py-3 px-4 font-semibold text-zinc-900 dark:text-zinc-100">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requiredVars as $varName => $varData)
                            <tr class="border-b border-zinc-100 dark:border-zinc-800">
                                <td class="py-3 px-4">
                                    <code class="px-2 py-1 rounded bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 text-xs">
                                        {{ $varName }}
                                    </code>
                                </td>
                                <td class="py-3 px-4 text-zinc-700 dark:text-zinc-300">
                                    {{ $varData['description'] }}
                                </td>
                                <td class="py-3 px-4">
                                    @if ($varData['is_placeholder'])
                                        <flux:text class="text-yellow-600 dark:text-yellow-400 font-mono text-xs">
                                            {{ $varData['value'] }}
                                        </flux:text>
                                    @else
                                        <flux:text class="font-mono text-xs text-zinc-600 dark:text-zinc-400">
                                            {{ $varData['value'] }}
                                        </flux:text>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    @if ($varData['is_placeholder'])
                                        <span class="px-2 py-1 rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200 text-xs font-medium">
                                            ⚠️ Placeholder
                                        </span>
                                    @elseif ($varData['is_set'])
                                        <span class="px-2 py-1 rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 text-xs font-medium">
                                            ✅ Set
                                        </span>
                                    @else
                                        <span class="px-2 py-1 rounded-full bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200 text-xs font-medium">
                                            ❌ Not Set
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Optional Variables -->
        <div class="p-6 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-sm">
            <flux:heading size="lg" class="mb-4">Optional Environment Variables</flux:heading>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-700">
                            <th class="text-left py-3 px-4 font-semibold text-zinc-900 dark:text-zinc-100">Variable</th>
                            <th class="text-left py-3 px-4 font-semibold text-zinc-900 dark:text-zinc-100">Description</th>
                            <th class="text-left py-3 px-4 font-semibold text-zinc-900 dark:text-zinc-100">Value</th>
                            <th class="text-left py-3 px-4 font-semibold text-zinc-900 dark:text-zinc-100">Default</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($optionalVars as $varName => $varData)
                            <tr class="border-b border-zinc-100 dark:border-zinc-800">
                                <td class="py-3 px-4">
                                    <code class="px-2 py-1 rounded bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 text-xs">
                                        {{ $varName }}
                                    </code>
                                </td>
                                <td class="py-3 px-4 text-zinc-700 dark:text-zinc-300">
                                    {{ $varData['description'] }}
                                </td>
                                <td class="py-3 px-4">
                                    <flux:text class="font-mono text-xs text-zinc-600 dark:text-zinc-400">
                                        {{ $varData['value'] }}
                                    </flux:text>
                                </td>
                                <td class="py-3 px-4">
                                    <flux:text class="font-mono text-xs text-zinc-500 dark:text-zinc-500">
                                        {{ $varData['default'] ?? 'N/A' }}
                                    </flux:text>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Placeholder Values Warning -->
        @if (isset($placeholdersCheck['has_placeholders']) && $placeholdersCheck['has_placeholders'])
            <flux:callout variant="warning">
                <strong>⚠️ Placeholder Values Detected</strong>
                <p class="mt-1 text-sm">
                    The following variables still have placeholder values that need to be replaced:
                </p>
                <ul class="mt-2 text-sm list-disc list-inside">
                    @foreach ($placeholdersCheck as $varName => $data)
                        @if (!is_numeric($varName))
                            <li class="mt-1">
                                <code>{{ $varName }}</code>: {{ $data['value'] }}
                            </li>
                        @endif
                    @endforeach
                </ul>
                <flux:text class="text-xs text-zinc-600 dark:text-zinc-400 mt-2">
                    Please edit your .env file and replace placeholder values with actual credentials.
                </flux:text>
            </flux:callout>
        @endif

        <!-- Gitignore Check -->
        <div class="p-6 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-sm">
            <flux:heading size="lg" class="mb-4">.gitignore Configuration</flux:heading>
            
            @if (isset($gitignoreCheck['exists']))
                @if ($gitignoreCheck['exists'])
                    <div class="space-y-3">
                        <div class="flex items-center gap-2 text-green-600 dark:text-green-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <flux:text class="text-sm font-medium">.gitignore file exists</flux:text>
                        </div>
                        
                        <div class="space-y-2">
                            @foreach ($gitignoreCheck as $entry => $check)
                                @if (!is_numeric($entry) && isset($check['present']))
                                    <div class="flex items-center justify-between py-2 border-b border-zinc-100 dark:border-zinc-800">
                                        <div class="flex items-center gap-2">
                                            @if ($check['present'])
                                                <div class="w-6 h-6 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                            @else
                                                <div class="w-6 h-6 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </div>
                                            @endif
                                            <code class="px-2 py-1 rounded bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 text-xs">
                                                {{ $entry }}
                                            </code>
                                        </div>
                                        <span class="px-2 py-1 rounded-full {{ $check['present'] ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200' }} text-xs font-medium">
                                            {{ $check['present'] ? 'Present' : 'Missing' }}
                                        </span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @else
                    <flux:callout variant="warning">
                        <strong>⚠️ .gitignore file not found</strong>
                        <flux:text class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">
                            Make sure .gitignore is properly configured to prevent credential exposure.
                        </flux:text>
                    </flux:callout>
                @endif
            @endif
        </div>

        <!-- Next Steps -->
        @if ($allChecksPassed)
            <flux:callout variant="success">
                <strong>Next Steps</strong>
                <ul class="mt-2 text-sm space-y-1 list-disc list-inside">
                    <li>Go to Check IP to verify your current IP</li>
                    <li>Go to Start Tunnel to begin SSH tunneling</li>
                    <li>Go to Sign On VPS to connect to KIOSBANK API</li>
                </ul>
            </flux:callout>
        @endif
    </div>
</div>
