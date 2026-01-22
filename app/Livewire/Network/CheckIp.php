<?php

namespace App\Livewire\Network;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class CheckIp extends Component
{
    public $results = null;

    public $isRunning = false;

    public function runCheck()
    {
        $this->isRunning = true;

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

        $this->results = $results;
        $this->isRunning = false;
    }

    public function render()
    {
        return view('livewire.network.check-ip');
    }
}
