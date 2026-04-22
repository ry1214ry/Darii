<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Setting;

class ResumeController extends Controller
{
    public function index()
    {
        $profile = Profile::latest()->first();
        $setting = Setting::latest()->first();

        return $this->renderFrontendPage('ResumePage', [
            'profile' => $this->profilePayload($profile),
        ], $setting, 'Resume');
    }
}
