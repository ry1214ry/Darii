<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Profile extends Model
{
    protected $fillable = [
        'full_name',
        'job_title',
        'short_intro',
        'about_me',
        'profile_image',
        'profile_video',
        'experience',
        'education',
        'technologies',
        'goals',
        'cv_file',
        'email',
        'phone',
        'address',
    ];

    public function cvPath(): ?string
    {
        if (filled($this->cv_file) && Storage::disk('public')->exists($this->cv_file)) {
            return $this->cv_file;
        }

        return collect(Storage::disk('public')->files('cv'))
            ->filter(fn (string $path) => Str::endsWith(Str::lower($path), '.pdf'))
            ->sort()
            ->first();
    }

    public function cvUrl(): ?string
    {
        $path = $this->cvPath();

        return $path ? asset('storage/' . ltrim($path, '/')) : null;
    }

    public function cvDownloadName(): string
    {
        $name = Str::of($this->full_name ?: 'cv')
            ->lower()
            ->replaceMatches('/[^a-z0-9]+/', '-')
            ->trim('-');

        return ($name ?: 'cv') . '.pdf';
    }
}
