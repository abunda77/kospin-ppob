<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class NetworkConnectionController extends Controller
{
    public function signOnVps()
    {
        return view('pages.network.sign-on-vps');
    }

    public function startTunnel()
    {
        return view('pages.network.start-tunnel');
    }

    public function stopTunnel()
    {
        return view('pages.network.stop-tunnel');
    }

    /**
     * Check current public IP address and verify against registered IP.
     *
     * This method implements the functionality from the Python check_ip.py script.
     * It performs the following operations:
     *
     * 1. Fetches the current public IP using two methods:
     *    - Method 1: ipify API (https://api.ipify.org)
     *    - Method 2: ip-api (http://ip-api.com) - also provides location and ISP info
     *
     * 2. Retrieves the registered IP from environment configuration (REGISTERED_IP)
     *
     * 3. Compares current IP with registered IP to verify whitelist status
     *
     * 4. Returns results to the view including:
     *    - IP addresses from both methods
     *    - Location and ISP information (from ip-api)
     *    - Match status (whether current IP matches registered IP)
     *    - Appropriate warnings if IPs don't match
     *
     * @return \Illuminate\View\View
     *
     * @see resources/views/pages/network/check-ip.blade.php
     */
    public function checkIp()
    {
        $results = [
            'method1' => null,
            'method2' => null,
            'registered_ip' => config('app.registered_ip'),
            'current_ip' => null,
            'is_match' => false,
            'location' => null,
            'isp' => null,
        ];

        // Method 1: ipify
        try {
            $response = Http::timeout(5)->get('https://api.ipify.org?format=json');
            if ($response->successful()) {
                $results['method1'] = $response->json()['ip'];
            } else {
                $results['method1'] = 'Failed';
            }
        } catch (\Exception $e) {
            $results['method1'] = 'Failed';
        }

        // Method 2: ip-api
        try {
            $response = Http::timeout(5)->get('http://ip-api.com/json/');
            if ($response->successful()) {
                $data = $response->json();
                $results['method2'] = $data['query'];
                $results['location'] = ($data['city'] ?? '').', '.($data['country'] ?? '');
                $results['isp'] = $data['isp'] ?? '';
            } else {
                $results['method2'] = 'Failed';
            }
        } catch (\Exception $e) {
            $results['method2'] = 'Failed';
        }

        // Determine current IP
        if ($results['method1'] !== 'Failed') {
            $results['current_ip'] = $results['method1'];
        } elseif ($results['method2'] !== 'Failed') {
            $results['current_ip'] = $results['method2'];
        } else {
            $results['current_ip'] = 'Failed';
        }

        // Check if current IP matches registered IP
        if ($results['registered_ip'] &&
            ($results['method1'] === $results['registered_ip'] ||
             $results['method2'] === $results['registered_ip'])) {
            $results['is_match'] = true;
            $results['current_ip'] = $results['method1'] === $results['registered_ip']
                ? $results['method1']
                : $results['method2'];
        }

        return view('pages.network.check-ip', compact('results'));
    }

    /**
     * Check open ports on the system.
     *
     * This method implements the functionality from the Python cekport_gui.py script.
     * It performs the following operations:
     *
     * 1. Retrieves all open ports (LISTENING state) on the system
     * 2. Checks if the proxy port (from config) is open
     * 3. Categorizes ports into:
     *    - Common ports (< 10000)
     *    - High ports (>= 10000)
     * 4. Provides system information
     *
     * @return \Illuminate\View\View
     *
     * @see resources/views/pages/network/check-port.blade.php
     */
    public function checkPort()
    {
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

        return view('pages.network.check-port', compact('results'));
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

    public function verifyEnvironment()
    {
        return view('pages.network.verify-environment');
    }
}
