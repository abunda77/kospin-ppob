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
     * This page now uses a Livewire component to perform the check on demand.
     *
     * @return \Illuminate\View\View
     *
     * @see resources/views/pages/network/check-port.blade.php
     * @see app/Livewire/Network/CheckPort.php
     */
    public function checkPort()
    {
        return view('pages.network.check-port');
    }

    public function verifyEnvironment()
    {
        return view('pages.network.verify-environment');
    }
}
