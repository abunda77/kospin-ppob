<?php

namespace App\Http\Controllers;

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

    public function checkIp()
    {
        return view('pages.network.check-ip');
    }

    public function checkPort()
    {
        return view('pages.network.check-port');
    }

    public function verifyEnvironment()
    {
        return view('pages.network.verify-environment');
    }
}
