<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Setting;

class FrontServiceController extends Controller
{
    public function index()
    {
        $services = Service::where('status', 1)->latest()->get();
        $setting = Setting::latest()->first();

        return $this->renderFrontendPage('ServicesPage', [
            'services' => $services->map(fn ($service) => $this->servicePayload($service))->values()->all(),
        ], $setting, 'Services');
    }
}
