<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Project extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'image',
        'demo_video',
        'description',
        'technology_used',
        'project_url',
        'github_url',
        'status',
        'is_featured',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function demoVideoUrl(): ?string
    {
        if (blank($this->demo_video)) {
            return null;
        }

        if (Str::startsWith($this->demo_video, ['http://', 'https://'])) {
            return $this->demo_video;
        }

        return asset('storage/' . ltrim($this->demo_video, '/'));
    }

    public function demoVideoEmbedUrl(): ?string
    {
        if (blank($this->demo_video) || ! Str::startsWith($this->demo_video, ['http://', 'https://'])) {
            return null;
        }

        $parts = parse_url($this->demo_video);

        if (! is_array($parts)) {
            return null;
        }

        $host = Str::lower($parts['host'] ?? '');
        $path = trim((string) ($parts['path'] ?? ''), '/');
        parse_str((string) ($parts['query'] ?? ''), $query);

        if (Str::contains($host, 'youtu.be') || Str::contains($host, 'youtube.com')) {
            $videoId = null;

            if (Str::contains($host, 'youtu.be')) {
                $videoId = collect(explode('/', $path))->filter()->first();
            } elseif (isset($query['v'])) {
                $videoId = $query['v'];
            } elseif (Str::startsWith($path, 'embed/')) {
                $videoId = Str::after($path, 'embed/');
            } elseif (Str::startsWith($path, 'shorts/')) {
                $videoId = Str::after($path, 'shorts/');
            }

            if (! $videoId) {
                return null;
            }

            $queryString = http_build_query([
                'autoplay' => 1,
                'mute' => 1,
                'controls' => 0,
                'loop' => 1,
                'playlist' => $videoId,
                'playsinline' => 1,
                'rel' => 0,
                'modestbranding' => 1,
                'iv_load_policy' => 3,
                'fs' => 0,
            ]);

            return 'https://www.youtube.com/embed/' . $videoId . '?' . $queryString;
        }

        if (Str::contains($host, 'vimeo.com')) {
            $segments = array_values(array_filter(explode('/', $path)));
            $videoId = null;

            if (Str::contains($host, 'player.vimeo.com')) {
                $videoIndex = array_search('video', $segments, true);
                if ($videoIndex !== false && isset($segments[$videoIndex + 1])) {
                    $videoId = $segments[$videoIndex + 1];
                }
            } else {
                $videoId = $segments[0] ?? null;
            }

            if (! $videoId) {
                return null;
            }

            $queryString = http_build_query([
                'autoplay' => 1,
                'muted' => 1,
                'loop' => 1,
                'background' => 1,
                'autopause' => 0,
                'title' => 0,
                'byline' => 0,
                'portrait' => 0,
            ]);

            return 'https://player.vimeo.com/video/' . $videoId . '?' . $queryString;
        }

        return null;
    }

}
