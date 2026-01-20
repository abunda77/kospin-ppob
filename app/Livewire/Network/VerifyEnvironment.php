<?php

namespace App\Livewire\Network;

use Livewire\Component;

class VerifyEnvironment extends Component
{
    public array $requiredVars = [];

    public array $optionalVars = [];

    public array $placeholdersCheck = [];

    public array $gitignoreCheck = [];

    public bool $envFileExists = false;

    public bool $allChecksPassed = false;

    public array $requiredVarDefinitions = [
        'KIOSBANK_API_URL' => 'KIOSBANK API URL',
        'KIOSBANK_API_USERNAME' => 'KIOSBANK API Username',
        'KIOSBANK_API_PASSWORD' => 'KIOSBANK API Password',
        'KIOSBANK_MITRA' => 'KIOSBANK Mitra',
        'KIOSBANK_ACCOUNT_ID' => 'KIOSBANK Account ID',
        'KIOSBANK_MERCHANT_ID' => 'KIOSBANK Merchant ID',
        'KIOSBANK_MERCHANT_NAME' => 'KIOSBANK Merchant Name',
        'KIOSBANK_COUNTER_ID' => 'KIOSBANK Counter ID',
        'VPS_IP' => 'VPS IP Address',
        'VPS_USER' => 'VPS Username',
        'REGISTERED_IP' => 'Registered IP in KIOSBANK Dashboard',
    ];

    public array $optionalVarDefinitions = [
        'VPS_SSH_PORT' => 'VPS SSH Port',
        'USE_PROXY' => 'Use Proxy',
        'PROXY_HOST' => 'Proxy Host',
        'PROXY_PORT' => 'Proxy Port',
    ];

    public array $placeholderValues = [
        'your_username_here',
        'your_password_here',
        'your_account_id_here',
        'your_merchant_id_here',
        'your_merchant_name_here',
        'your_vps_ip_here',
        'your_vps_username_here',
        'your_registered_ip_here',
    ];

    public array $gitignoreEntries = ['.env', 'session_id.txt', 'session_history.txt'];

    public $hasRun = false;
    public $isRunning = false;

    public function mount(): void
    {
        // Don't run on mount
    }

    public function runCheck(): void
    {
        $this->isRunning = true;
        
        // Simulate a small delay for better UX
        sleep(1);
        
        $this->verifyEnvironment();
        $this->hasRun = true;
        $this->isRunning = false;
    }

    public function verifyEnvironment(): void
    {
        $this->envFileExists = file_exists(base_path('.env'));

        $this->verifyRequiredVars();
        $this->verifyOptionalVars();
        $this->checkPlaceholders();
        $this->verifyGitignore();

        $this->determineOverallStatus();
    }

    // ... kept other methods as they are ...

    public function refreshVerification(): void
    {
        $this->runCheck();
    }

    public function render()
    {
        return view('livewire.network.verify-environment');
    }

    private function verifyRequiredVars(): void
    {
        $this->requiredVars = [];

        foreach ($this->requiredVarDefinitions as $varName => $description) {
            $value = env($varName);
            $isSet = $value !== null && $value !== '';
            $displayValue = $this->maskSensitiveValue($varName, $value);

            $this->requiredVars[$varName] = [
                'description' => $description,
                'value' => $displayValue,
                'is_set' => $isSet,
                'is_placeholder' => in_array(strtolower($value), $this->placeholderValues),
            ];
        }
    }

    private function verifyOptionalVars(): void
    {
        $this->optionalVars = [];

        $defaults = [
            'VPS_SSH_PORT' => 22,
            'USE_PROXY' => true,
            'PROXY_HOST' => '127.0.0.1',
            'PROXY_PORT' => 1080,
        ];

        foreach ($this->optionalVarDefinitions as $varName => $description) {
            $value = env($varName, $defaults[$varName] ?? null);

            $this->optionalVars[$varName] = [
                'description' => $description,
                'value' => $value,
                'default' => $defaults[$varName] ?? null,
            ];
        }
    }

    private function checkPlaceholders(): void
    {
        $this->placeholdersCheck = [];

        foreach ($this->requiredVarDefinitions as $varName => $description) {
            $value = env($varName, '');

            if (in_array(strtolower($value), $this->placeholderValues)) {
                $this->placeholdersCheck[$varName] = [
                    'description' => $description,
                    'value' => $value,
                    'is_placeholder' => true,
                ];
            }
        }

        $this->placeholdersCheck['has_placeholders'] = count($this->placeholdersCheck) > 1;
    }

    private function verifyGitignore(): void
    {
        $this->gitignoreCheck = [];

        $gitignorePath = base_path('.gitignore');
        $gitignoreExists = file_exists($gitignorePath);

        $this->gitignoreCheck['exists'] = $gitignoreExists;

        if ($gitignoreExists) {
            $gitignoreContent = file_get_contents($gitignorePath);

            foreach ($this->gitignoreEntries as $entry) {
                $this->gitignoreCheck[$entry] = [
                    'required' => true,
                    'present' => str_contains($gitignoreContent, $entry),
                ];
            }
        } else {
            foreach ($this->gitignoreEntries as $entry) {
                $this->gitignoreCheck[$entry] = [
                    'required' => true,
                    'present' => false,
                ];
            }
        }
    }

    private function maskSensitiveValue(string $varName, ?string $value): string
    {
        if ($value === null || $value === '') {
            return 'NOT SET';
        }

        if (str_contains($varName, 'PASSWORD') || str_contains($varName, 'SECRET')) {
            return str_repeat('*', strlen($value));
        }

        if (strlen($value) > 30) {
            return substr($value, 0, 27).'...';
        }

        return $value;
    }

    private function determineOverallStatus(): void
    {
        $requiredOk = collect($this->requiredVars)->every(fn ($var) => $var['is_set'] && ! $var['is_placeholder']);
        $noPlaceholders = ! ($this->placeholdersCheck['has_placeholders'] ?? false);
        $gitignoreOk = collect($this->gitignoreCheck)
            ->filter(fn ($entry, $key) => ! is_numeric($key) && isset($entry['present']))
            ->every(fn ($entry) => $entry['present']);

        $this->allChecksPassed = $this->envFileExists && $requiredOk && $noPlaceholders && $gitignoreOk;
    }


}
