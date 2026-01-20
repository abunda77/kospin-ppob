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
    /**
     * Check current public IP address and verify against registered IP.
     *
     * This page now uses a Livewire component to perform the check on demand.
     *
     * @return \Illuminate\View\View
     *
     * @see resources/views/pages/network/check-ip.blade.php
     * @see app/Livewire/Network/CheckIp.php
     */
    public function checkIp()
    {
        return view('pages.network.check-ip');
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
