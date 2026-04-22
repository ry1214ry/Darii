<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Profile;
use App\Models\Setting;
use App\Models\SocialLink;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $setting = Setting::latest()->first();
        $profile = Profile::latest()->first();
        $socialLinks = SocialLink::where('status', 1)->get();

        return $this->renderFrontendPage('ContactPage', [
            'profile' => $this->profilePayload($profile),
            'socialLinks' => $socialLinks->map(fn ($socialLink) => $this->socialLinkPayload($socialLink))->values()->all(),
        ], $setting, 'Contact');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        ContactMessage::create([
            'name'    => $request->name,
            'email'   => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'is_read' => 0,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Your message has been sent successfully.',
            ]);
        }

        return redirect()->back()->with('success', 'Your message has been sent successfully.');
    }
}
