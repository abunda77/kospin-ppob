<?php

namespace App\Livewire;

use App\Models\TunnelLog;
use Livewire\Attributes\Locked;
use Livewire\Component;

class StartTunnel extends Component
{
    public string $vpsIp = '';

    public string $vpsUser = '';

    public int $proxyPort = 1080;

    public string $status = 'stopped';

    public string $output = '';

    public bool $isLoading = false;

    #[Locked]
    public ?int $currentTunnelId = null;

    #[Locked]
    public ?int $processId = null;

    public function mount(): void
    {
        $this->vpsIp = config('app.vps_ip', '');
        $this->vpsUser = config('app.vps_user', '');
        $this->proxyPort = (int) config('app.proxy_port', 1080);

        $this->checkTunnelStatus();
    }

    public function startTunnel(): void
    {
        $this->validate([
            'vpsIp' => 'required|string',
            'vpsUser' => 'required|string',
            'proxyPort' => 'required|integer|min:1|max:65535',
        ]);

        $this->isLoading = true;

        $header = $this->getTunnelHeader();
        $this->output = $header;

        try {
            $sshCommand = $this->buildSshCommand();

            $this->output .= "\nExecuting command:\n";
            $this->output .= str_replace($this->vpsIp.'@', $this->vpsUser.'@***@', $sshCommand)."\n\n";

            $processId = null;
            $outputBuffer = '';

            if (PHP_OS_FAMILY === 'Windows') {
                $result = $this->startWindowsTunnel($sshCommand, $processId, $outputBuffer);
            } else {
                $result = $this->startUnixTunnel($sshCommand, $processId, $outputBuffer);
            }

            if ($result['success']) {
                $this->processId = $processId;

                $tunnelLog = TunnelLog::create([
                    'vps_ip' => $this->vpsIp,
                    'vps_user' => $this->vpsUser,
                    'proxy_port' => $this->proxyPort,
                    'status' => 'running',
                    'process_id' => $processId,
                    'output' => $this->output.$outputBuffer,
                    'started_at' => now(),
                ]);

                $this->currentTunnelId = $tunnelLog->id;
                $this->status = 'running';
                $this->output .= $outputBuffer."\n\nTunnel started successfully!";
                $this->output .= "\nTunnel ID: {$tunnelLog->id}";
                $this->output .= "\nProcess ID: {$processId}";
            } else {
                $this->output .= "\n\nError: {$result['message']}";
                $this->status = 'error';
            }
        } catch (\Exception $e) {
            $this->output .= "\n\nException: {$e->getMessage()}";
            $this->status = 'error';
        }

        $this->isLoading = false;
    }

    public function stopTunnel(): void
    {
        if (! $this->currentTunnelId) {
            $this->output .= "\n\nNo active tunnel to stop.";

            return;
        }

        $tunnel = TunnelLog::find($this->currentTunnelId);

        if (! $tunnel) {
            $this->output .= "\n\nTunnel not found.";

            return;
        }

        if ($tunnel->process_id) {
            $killed = $this->killProcess($tunnel->process_id);

            if ($killed) {
                $tunnel->update([
                    'status' => 'stopped',
                    'stopped_at' => now(),
                ]);

                $this->output .= "\n\nTunnel stopped successfully!";
                $this->status = 'stopped';
                $this->currentTunnelId = null;
                $this->processId = null;
            } else {
                $this->output .= "\n\nFailed to stop tunnel process.";
            }
        } else {
            $tunnel->update([
                'status' => 'stopped',
                'stopped_at' => now(),
            ]);

            $this->output .= "\n\nTunnel marked as stopped.";
            $this->status = 'stopped';
            $this->currentTunnelId = null;
        }
    }

    public function checkTunnelStatus(): void
    {
        $activeTunnel = TunnelLog::running()->latest()->first();

        if ($activeTunnel) {
            $this->currentTunnelId = $activeTunnel->id;
            $this->processId = $activeTunnel->process_id;
            $this->status = $activeTunnel->status;

            if ($activeTunnel->output) {
                $this->output = $activeTunnel->output;
            }

            if ($this->processId && ! $this->isProcessRunning($this->processId)) {
                $activeTunnel->update([
                    'status' => 'stopped',
                    'stopped_at' => now(),
                ]);
                $this->status = 'stopped';
                $this->currentTunnelId = null;
                $this->processId = null;
            }
        }
    }

    public function clearOutput(): void
    {
        $this->output = '';
    }

    private function getTunnelHeader(): string
    {
        $header = str_repeat('=', 60)."\n";
        $header .= "Starting SSH SOCKS5 Tunnel to VPS\n";
        $header .= str_repeat('=', 60)."\n\n";
        $header .= "VPS IP: {$this->vpsIp}\n";
        $header .= "User: {$this->vpsUser}\n";
        $header .= "Local SOCKS5 Port: {$this->proxyPort}\n\n";
        $header .= "IMPORTANT: Keep this window OPEN while using the proxy!\n";
        $header .= "Press Stop Tunnel button to stop the tunnel.\n";
        $header .= str_repeat('=', 60)."\n";

        return $header;
    }

    private function buildSshCommand(): string
    {
        return sprintf(
            'ssh -D %d -N -v %s@%s',
            $this->proxyPort,
            $this->vpsUser,
            $this->vpsIp
        );
    }

    private function startWindowsTunnel(string $command, &$processId, &$outputBuffer): array
    {
        try {
            $wmiQuery = 'Get-ChildItem \'HKLM:\SOFTWARE\Microsoft\Windows\CurrentVersion\Uninstall\' | Get-ItemProperty | Where-Object {$_.DisplayName -like "*OpenSSH*"}';
            $sshCheck = shell_exec("powershell -Command \"$wmiQuery\"");

            if (empty($sshCheck)) {
                return [
                    'success' => false,
                    'message' => 'SSH client not found. Please install OpenSSH via Settings > Apps > Optional Features',
                ];
            }

            $outputFile = storage_path('app/ssh_tunnel_'.time().'.log');
            $batchFile = storage_path('app/tunnel_'.time().'.bat');

            $batchContent = "@echo off\n";
            $batchContent .= "{$command} > \"{$outputFile}\" 2>&1\n";

            file_put_contents($batchFile, $batchContent);

            $powershellCommand = "Start-Process -FilePath 'cmd.exe' -ArgumentList '/c \"{$batchFile}\"' -WindowStyle Hidden -PassThru | Select-Object -ExpandProperty Id";
            $processId = (int) trim(shell_exec("powershell -Command \"{$powershellCommand}\""));

            sleep(2);

            if (file_exists($outputFile)) {
                $outputBuffer = file_get_contents($outputFile);
            }

            $this->output .= "\nTunnel started in background (Process ID: {$processId})";
            $this->output .= "\nLog file: {$outputFile}";

            return ['success' => true, 'process_id' => $processId];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    private function startUnixTunnel(string $command, &$processId, &$outputBuffer): array
    {
        try {
            $command .= ' > /dev/null 2>&1 & echo $!';

            $processId = (int) trim(shell_exec($command));

            if ($processId > 0) {
                $this->output .= "\nTunnel started in background (Process ID: {$processId})";

                return ['success' => true, 'process_id' => $processId];
            }

            return [
                'success' => false,
                'message' => 'Failed to start tunnel process',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    private function killProcess(int $processId): bool
    {
        try {
            if (PHP_OS_FAMILY === 'Windows') {
                $result = shell_exec("taskkill /F /PID {$processId} 2>&1");

                return str_contains($result, 'SUCCESS');
            }

            $result = shell_exec("kill {$processId} 2>&1");

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function isProcessRunning(int $processId): bool
    {
        try {
            if (PHP_OS_FAMILY === 'Windows') {
                $result = shell_exec("tasklist /FI \"PID eq {$processId}\" 2>&1");

                return str_contains($result, (string) $processId);
            }

            $result = shell_exec("kill -0 {$processId} 2>&1");

            return $result === null || str_contains($result, 'No such process') === false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function render()
    {
        return view('livewire.start-tunnel');
    }
}
