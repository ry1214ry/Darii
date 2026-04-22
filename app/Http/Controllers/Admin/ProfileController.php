<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $profiles = Profile::latest()->get();
        return view('admin.profiles.index', compact('profiles'));
    }

    public function create()
    {
        return view('admin.profiles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name'     => 'nullable|string|max:255',
            'job_title'     => 'nullable|string|max:255',
            'short_intro'   => 'nullable|string',
            'about_me'      => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'profile_video' => 'nullable|string|max:255',
            'cv_file'       => 'nullable|mimes:pdf|max:4096',
            'email'         => 'nullable|email|max:255',
            'phone'         => 'nullable|string|max:255',
            'address'       => 'nullable|string|max:255',
        ]);

        $imagePath = null;
        $cvPath = null;

        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('profiles', 'public');
        }

        if ($request->hasFile('cv_file')) {
            $cvPath = $request->file('cv_file')->store('cv', 'public');
        }

        Profile::create([
            'full_name'     => $request->full_name,
            'job_title'     => $request->job_title,
            'short_intro'   => $request->short_intro,
            'about_me'      => $request->about_me,
            'profile_image' => $imagePath,
            'profile_video' => $request->profile_video,
            'experience'    => $request->experience,
            'education'     => $request->education,
            'technologies'  => $request->technologies,
            'goals'         => $request->goals,
            'cv_file'       => $cvPath,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'address'       => $request->address,
        ]);

        return redirect()->route('admin.profiles.index')->with('success', 'Profile created successfully.');
    }

    public function show(Profile $profile)
    {
        return view('admin.profiles.show', compact('profile'));
    }

    public function edit(Profile $profile)
    {
        return view('admin.profiles.edit', compact('profile'));
    }

    public function update(Request $request, Profile $profile)
    {
        $request->validate([
            'full_name'     => 'nullable|string|max:255',
            'job_title'     => 'nullable|string|max:255',
            'short_intro'   => 'nullable|string',
            'about_me'      => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'profile_video' => 'nullable|string|max:255',
            'cv_file'       => 'nullable|mimes:pdf|max:4096',
            'email'         => 'nullable|email|max:255',
            'phone'         => 'nullable|string|max:255',
            'address'       => 'nullable|string|max:255',
        ]);

        $imagePath = $profile->profile_image;
        $cvPath = $profile->cv_file;

        if ($request->hasFile('profile_image')) {
            if ($profile->profile_image && Storage::disk('public')->exists($profile->profile_image)) {
                Storage::disk('public')->delete($profile->profile_image);
            }
            $imagePath = $request->file('profile_image')->store('profiles', 'public');
        }

        if ($request->hasFile('cv_file')) {
            if ($profile->cv_file && Storage::disk('public')->exists($profile->cv_file)) {
                Storage::disk('public')->delete($profile->cv_file);
            }
            $cvPath = $request->file('cv_file')->store('cv', 'public');
        }

        $profile->update([
            'full_name'     => $request->full_name,
            'job_title'     => $request->job_title,
            'short_intro'   => $request->short_intro,
            'about_me'      => $request->about_me,
            'profile_image' => $imagePath,
            'profile_video' => $request->profile_video,
            'experience'    => $request->experience,
            'education'     => $request->education,
            'technologies'  => $request->technologies,
            'goals'         => $request->goals,
            'cv_file'       => $cvPath,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'address'       => $request->address,
        ]);

        return redirect()->route('admin.profiles.index')->with('success', 'Profile updated successfully.');
    }

    public function destroy(Profile $profile)
    {
        if ($profile->profile_image && Storage::disk('public')->exists($profile->profile_image)) {
            Storage::disk('public')->delete($profile->profile_image);
        }

        if ($profile->cv_file && Storage::disk('public')->exists($profile->cv_file)) {
            Storage::disk('public')->delete($profile->cv_file);
        }

        $profile->delete();

        return redirect()->route('admin.profiles.index')->with('success', 'Profile deleted successfully.');
    }
}