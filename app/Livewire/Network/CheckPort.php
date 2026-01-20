<?php

namespace App\Livewire\Network;

use Livewire\Component;

class CheckPort extends Component
{
    public $results = null;
    public $isRunning = false;

    public function runCheck()
    {
        $this->isRunning = true;

        $results = [
            'system_info' => [
                'os' => PHP_OS_FAMILY,
                'os_version' => php_uname('r'),
                'php_version' => PHP_VERSION,
            ],
            'proxy_port' => config('app.proxy_port', 1080),
            'proxy_port_open' => false,
            'proxy_port_info' => null,
            'open_ports' => [],
            'common_ports' => [],
            'high_ports' => [],
            'total_ports' => 0,
        ];

        // Get all open ports
        $openPorts = $this->getOpenPorts();
        $results['open_ports'] = $openPorts;
        $results['total_ports'] = count($openPorts);

        // Check if proxy port is open
        $proxyPort = $results['proxy_port'];
        foreach ($openPorts as $port) {
            if ($port['port'] == $proxyPort) {
                $results['proxy_port_open'] = true;
                $results['proxy_port_info'] = $port;
                break;
            }
        }

        // Categorize ports
        foreach ($openPorts as $port) {
            if ($port['port'] < 10000) {
                $results['common_ports'][] = $port;
            } else {
                $results['high_ports'][] = $port;
            }
        }

        $this->results = $results;
        $this->isRunning = false;
    }

    /**
     * Get all open ports on the system.
     */
    private function getOpenPorts(): array
    {
        $ports = [];

        if (PHP_OS_FAMILY === 'Windows') {
            $ports = $this->getOpenPortsWindows();
        } else {
            $ports = $this->getOpenPortsUnix();
        }

        // Sort by port number
        usort($ports, function ($a, $b) {
            return $a['port'] <=> $b['port'];
        });

        return $ports;
    }

    /**
     * Get open ports on Windows using netstat.
     */
    private function getOpenPortsWindows(): array
    {
        $ports = [];

        try {
            // Run netstat command to get listening ports
            $output = shell_exec('netstat -ano | findstr LISTENING');

            if ($output) {
                $lines = explode("\n", trim($output));

                foreach ($lines as $line) {
                    if (empty(trim($line))) {
                        continue;
                    }

                    // Parse netstat output
                    // Format: TCP    0.0.0.0:80    0.0.0.0:0    LISTENING    1234
                    preg_match('/\s+(TCP|UDP)\s+([0-9.:]+):(\d+)\s+.*?\s+(\d+)\s*$/', $line, $matches);

                    if (count($matches) >= 5) {
                        $address = $matches[2];
                        $port = (int) $matches[3];
                        $pid = (int) $matches[4];

                        // Get process name
                        $processName = $this->getProcessNameWindows($pid);

                        $ports[] = [
                            'port' => $port,
                            'address' => $address === '0.0.0.0' || $address === '::' ? '*' : $address,
                            'pid' => $pid,
                            'process' => $processName,
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            // Silently fail
        }

        // Remove duplicates (same port might appear multiple times)
        $uniquePorts = [];
        $seenPorts = [];

        foreach ($ports as $port) {
            $key = $port['port'];
            if (! isset($seenPorts[$key])) {
                $seenPorts[$key] = true;
                $uniquePorts[] = $port;
            }
        }

        return $uniquePorts;
    }

    /**
     * Get open ports on Unix/Linux using netstat or ss.
     */
    private function getOpenPortsUnix(): array
    {
        $ports = [];

        try {
            // Try ss first (more modern)
            $output = shell_exec('ss -tlnp 2>/dev/null || netstat -tlnp 2>/dev/null');

            if ($output) {
                $lines = explode("\n", trim($output));

                foreach ($lines as $line) {
                    if (empty(trim($line)) || strpos($line, 'LISTEN') === false) {
                        continue;
                    }

                    // Parse output
                    preg_match('/:(\d+)\s+.*?users:\(\("([^"]+)",pid=(\d+)/', $line, $matches);

                    if (count($matches) >= 4) {
                        $port = (int) $matches[1];
                        $processName = $matches[2];
                        $pid = (int) $matches[3];

                        $ports[] = [
                            'port' => $port,
                            'address' => '*',
                            'pid' => $pid,
                            'process' => $processName,
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            // Silently fail
        }

        return $ports;
    }

    /**
     * Get process name by PID on Windows.
     */
    private function getProcessNameWindows(int $pid): string
    {
        try {
            $output = shell_exec("tasklist /FI \"PID eq {$pid}\" /FO CSV /NH 2>nul");

            if ($output) {
                // Parse CSV output
                $lines = explode("\n", trim($output));
                if (count($lines) > 0) {
                    $parts = str_getcsv($lines[0]);
                    if (count($parts) > 0) {
                        return $parts[0];
                    }
                }
            }
        } catch (\Exception $e) {
            // Silently fail
        }

        return 'Unknown';
    }

    public function render()
    {
        return view('livewire.network.check-port');
    }
}
