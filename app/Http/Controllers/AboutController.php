<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Skill;
use App\Models\Setting;

class AboutController extends Controller
{
    public function index()
    {
        $profile = Profile::latest()->first();
        $skills = Skill::where('status', 1)->orderBy('sort_order')->get();
        $setting = Setting::latest()->first();

        return $this->renderFrontendPage('AboutPage', [
            'profile' => $this->profilePayload($profile),
            'skills' => $skills->map(fn ($skill) => $this->skillPayload($skill))->values()->all(),
        ], $setting, 'About');
    }
}
