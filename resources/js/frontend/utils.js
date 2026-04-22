export function splitCsv(value) {
    return String(value ?? '')
        .split(',')
        .map((item) => item.trim())
        .filter(Boolean);
}

export function uniqueValues(values) {
    return [...new Set((values ?? []).filter(Boolean))];
}

export function limitText(value, limit) {
    const normalized = String(value ?? '').replace(/\s+/g, ' ').trim();

    if (!normalized || normalized.length <= limit) {
        return normalized;
    }

    return `${normalized.slice(0, limit).trimEnd()}...`;
}

export function textParagraphs(value) {
    return String(value ?? '')
        .split(/\r?\n+/)
        .map((item) => item.trim())
        .filter(Boolean);
}

export function formatIndex(index) {
    return String(index + 1).padStart(2, '0');
}

export function isRouteActive(currentRoute, routeNames) {
    return routeNames.includes(currentRoute);
}

export function isExternalUrl(url) {
    if (!url) {
        return false;
    }

    try {
        return new URL(url, window.location.origin).origin !== window.location.origin;
    } catch {
        return false;
    }
}

export function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
}

export function resolveProfileVideo(profile) {
    const videoUrl = String(profile?.profile_video_url ?? profile?.profile_video ?? '').trim();

    if (!videoUrl) {
        return {
            videoUrl: '',
            embedType: null,
            embedUrl: null,
            posterUrl: profile?.profile_image_url ?? null,
        };
    }

    const youtubeMatch = videoUrl.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([^&?/]+)/i);
    if (youtubeMatch) {
        return {
            videoUrl,
            embedType: 'youtube',
            embedUrl: `https://www.youtube.com/embed/${youtubeMatch[1]}?rel=0`,
            posterUrl: profile?.profile_image_url ?? null,
        };
    }

    const vimeoMatch = videoUrl.match(/vimeo\.com\/(?:video\/)?([0-9]+)/i);
    if (vimeoMatch) {
        return {
            videoUrl,
            embedType: 'vimeo',
            embedUrl: `https://player.vimeo.com/video/${vimeoMatch[1]}`,
            posterUrl: profile?.profile_image_url ?? null,
        };
    }

    if (/\.(mp4|webm|ogg|mov)(\?.*)?$/i.test(videoUrl)) {
        return {
            videoUrl,
            embedType: 'file',
            embedUrl: null,
            posterUrl: profile?.profile_image_url ?? null,
        };
    }

    return {
        videoUrl,
        embedType: 'external',
        embedUrl: null,
        posterUrl: profile?.profile_image_url ?? null,
    };
}
