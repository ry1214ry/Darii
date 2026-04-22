import { useEffect, useRef, useState } from 'react';
import {
    getCsrfToken,
    isExternalUrl,
    isRouteActive,
    resolveProfileVideo,
    textParagraphs,
} from './utils';

export function TextProse({ text, fallback = '-' }) {
    const paragraphs = textParagraphs(text);

    return (
        <div className="site-prose">
            {paragraphs.length > 0
                ? paragraphs.map((paragraph, index) => <p key={index}>{paragraph}</p>)
                : <p>{fallback}</p>}
        </div>
    );
}

export function TypingText({
    words,
    className,
    typingSpeed = 90,
    pauseDuration = 1500,
    fallback,
}) {
    const filteredWords = (words ?? []).filter(Boolean);
    const [text, setText] = useState(filteredWords[0] ?? fallback ?? '');

    useEffect(() => {
        if (filteredWords.length <= 1) {
            setText(filteredWords[0] ?? fallback ?? '');
            return undefined;
        }

        let wordIndex = 0;
        let charIndex = 0;
        let isDeleting = false;
        let timeoutId;

        const tick = () => {
            const currentWord = filteredWords[wordIndex];

            charIndex = isDeleting ? charIndex - 1 : charIndex + 1;
            setText(currentWord.slice(0, charIndex));

            let nextDelay = typingSpeed;

            if (!isDeleting && charIndex === currentWord.length) {
                nextDelay = pauseDuration;
                isDeleting = true;
            } else if (isDeleting && charIndex === 0) {
                isDeleting = false;
                wordIndex = (wordIndex + 1) % filteredWords.length;
                nextDelay = typingSpeed * 0.8;
            } else if (isDeleting) {
                nextDelay = typingSpeed * 0.6;
            }

            timeoutId = window.setTimeout(tick, nextDelay);
        };

        setText('');
        timeoutId = window.setTimeout(tick, typingSpeed);

        return () => window.clearTimeout(timeoutId);
    }, [fallback, pauseDuration, typingSpeed, filteredWords.join('|')]);

    return <strong className={className}>{text || fallback}</strong>;
}

export function ProjectMedia({
    project,
    mediaClass = 'card-media',
    placeholderText = 'Project Preview',
}) {
    const placeholderClass = `${mediaClass} card-media-placeholder`.trim();

    if (project?.demo_video_url) {
        if (project.demo_video_embed_url) {
            return (
                <iframe
                    src={project.demo_video_embed_url}
                    className={`${mediaClass} project-video-embed`}
                    title={`${project.title} demo video`}
                    loading="lazy"
                    allow="autoplay; encrypted-media; picture-in-picture"
                    referrerPolicy="strict-origin-when-cross-origin"
                    tabIndex={-1}
                />
            );
        }

        return (
            <video
                className={`${mediaClass} project-video-player`}
                autoPlay
                muted
                loop
                playsInline
                preload="metadata"
                disablePictureInPicture
                controlsList="nodownload noplaybackrate nofullscreen"
                tabIndex={-1}
            >
                <source src={project.demo_video_url} />
                Your browser does not support video playback.
            </video>
        );
    }

    if (project?.image_url) {
        return <img src={project.image_url} className={mediaClass} alt={project.title} />;
    }

    return <div className={placeholderClass}>{placeholderText}</div>;
}

export function ProfileVideoSection({
    profile,
    eyebrow = 'Profile Video',
    title = 'Watch my profile video',
    description = 'A quick video section for showing an introduction, portfolio clip, or external profile video link.',
}) {
    const video = resolveProfileVideo(profile);

    if (!video.videoUrl) {
        return null;
    }

    return (
        <section className="section-block section-tight">
            <div className="container">
                <div className="video-section-card">
                    <div className="section-heading">
                        <div>
                            <p className="section-eyebrow">{eyebrow}</p>
                            <h2>{title}</h2>
                        </div>
                        <p>{description}</p>
                    </div>

                    {(video.embedType === 'youtube' || video.embedType === 'vimeo') && (
                        <div className="video-frame">
                            <iframe
                                src={video.embedUrl}
                                title="Profile video"
                                loading="lazy"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowFullScreen
                            />
                        </div>
                    )}

                    {video.embedType === 'file' && (
                        <div className="video-frame">
                            <video controls playsInline preload="metadata" poster={video.posterUrl ?? undefined}>
                                <source src={video.videoUrl} />
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    )}

                    {video.embedType === 'external' && (
                        <div className="video-link-card">
                            <div className="video-link-copy">
                                <p className="section-eyebrow mb-2">External Video</p>
                                <h3>Open the profile video in a new tab</h3>
                                <p className="mb-0">The current video link is stored and available, but this type of URL is better opened directly outside the site.</p>
                            </div>

                            <a href={video.videoUrl} target="_blank" rel="noopener noreferrer" className="site-btn site-btn-primary">
                                Watch Profile Video
                            </a>
                        </div>
                    )}
                </div>
            </div>
        </section>
    );
}

export function Pagination({ pagination }) {
    if (!pagination?.links?.length) {
        return null;
    }

    return (
        <nav aria-label="Pagination">
            <div className="chip-row compact pagination-links">
                {pagination.links.map((link, index) => (
                    link.url ? (
                        <a
                            key={`${link.label}-${index}`}
                            href={link.url}
                            className={`site-chip site-chip-link pagination-chip${link.active ? ' is-active' : ''}`}
                        >
                            {link.label}
                        </a>
                    ) : (
                        <span key={`${link.label}-${index}`} className="site-chip pagination-chip is-disabled">
                            {link.label}
                        </span>
                    )
                ))}
            </div>
        </nav>
    );
}

export function SiteHeader({
    site,
    urls,
    currentRoute,
    theme,
    navOpen,
    onToggleTheme,
    onToggleNav,
    onCloseNav,
}) {
    const navItems = [
        { label: 'Home', href: urls.home, routes: ['home'] },
        { label: 'About', href: urls.about, routes: ['about'] },
        { label: 'Projects', href: urls.projects, routes: ['projects', 'projects.show'] },
        { label: 'Services', href: urls.services, routes: ['services'] },
        { label: 'Blog', href: urls.blog, routes: ['blog', 'blog.show'] },
        { label: 'Resume', href: urls.resume, routes: ['resume'] },
        { label: 'Contact', href: urls.contact, routes: ['contact'] },
    ];

    return (
        <header className="site-header">
            <div className="container">
                <nav className="navbar site-navbar">
                    <a className="site-brand text-decoration-none" href={urls.home}>
                        <span className="site-brand-mark">RD</span>
                        <span className="site-brand-copy">
                            <strong>{site.site_name ?? 'Roeun Dary Portfolio'}</strong>
                            <span>Laravel Developer</span>
                        </span>
                    </a>

                    <div className={`site-nav-wrap${navOpen ? ' is-open' : ''}`} id="site-nav-panel">
                        <div className="site-nav-list">
                            {navItems.map((item) => (
                                <a
                                    key={item.label}
                                    className={`site-nav-link${isRouteActive(currentRoute, item.routes) ? ' is-active' : ''}`}
                                    href={item.href}
                                    onClick={onCloseNav}
                                >
                                    {item.label}
                                </a>
                            ))}
                        </div>

                        <a className="site-btn site-btn-primary site-nav-cta" href={urls.contact} onClick={onCloseNav}>Let's Talk</a>
                    </div>

                    <div className="site-navbar-actions">
                        <button
                            type="button"
                            className="site-theme-toggle"
                            onClick={onToggleTheme}
                            aria-label={theme === 'dark' ? 'Switch to light mode' : 'Switch to dark mode'}
                            aria-pressed={theme === 'dark'}
                        >
                            <span className="site-theme-icon-wrap" aria-hidden="true">
                                <svg className="site-theme-icon site-theme-icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
                                    <circle cx="12" cy="12" r="4.2" />
                                    <path d="M12 2.8v2.3M12 18.9v2.3M21.2 12h-2.3M5.1 12H2.8M18.5 5.5l-1.6 1.6M7.1 16.9l-1.6 1.6M18.5 18.5l-1.6-1.6M7.1 7.1 5.5 5.5" />
                                </svg>
                                <svg className="site-theme-icon site-theme-icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
                                    <path d="M20 15.2A8.6 8.6 0 1 1 8.8 4a7.1 7.1 0 0 0 11.2 11.2Z" />
                                </svg>
                            </span>
                            <span className="site-theme-toggle-state visually-hidden">{theme === 'dark' ? 'Dark' : 'Light'}</span>
                        </button>

                        <button
                            type="button"
                            className="site-nav-toggle"
                            onClick={onToggleNav}
                            aria-label="Toggle navigation menu"
                            aria-expanded={navOpen}
                            aria-controls="site-nav-panel"
                        >
                            <span className="site-nav-toggle-box" aria-hidden="true">
                                <span className="site-nav-toggle-line" />
                                <span className="site-nav-toggle-line" />
                                <span className="site-nav-toggle-line" />
                            </span>
                        </button>
                    </div>
                </nav>
            </div>
        </header>
    );
}

export function SiteFooter({ site, urls }) {
    return (
        <footer className="site-footer">
            <div className="container">
                <div className="site-footer-grid">
                    <div className="site-footer-block">
                        <a className="site-brand text-decoration-none" href={urls.home}>
                            <span className="site-brand-mark">RD</span>
                            <span className="site-brand-copy">
                                <strong>{site.site_name ?? 'Roeun Dary Portfolio'}</strong>
                                <span>Modern web portfolio</span>
                            </span>
                        </a>
                        <p className="site-footer-copy mt-4 mb-0">{site.footer_text ?? 'Portfolio website. All rights reserved.'}</p>
                    </div>

                    <div className="site-footer-block">
                        <p className="site-footer-title">Explore</p>
                        <div className="site-footer-links">
                            <a href={urls.home}>Home</a>
                            <a href={urls.about}>About</a>
                            <a href={urls.projects}>Projects</a>
                            <a href={urls.services}>Services</a>
                            <a href={urls.blog}>Blog</a>
                            <a href={urls.resume}>Resume</a>
                            <a href={urls.contact}>Contact</a>
                        </div>
                    </div>

                    <div className="site-footer-block">
                        <p className="site-footer-title">Contact</p>
                        <ul className="site-footer-contact">
                            <li>{site.contact_email ?? 'Email not set'}</li>
                            <li>{site.contact_phone ?? 'Phone not set'}</li>
                            <li>{site.address ?? 'Address not set'}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
    );
}

export function Chatbot({ config }) {
    const [isOpen, setIsOpen] = useState(false);
    const [messages, setMessages] = useState([
        {
            role: 'bot',
            text: 'Hello. I can answer around 20 common questions about this portfolio and guide visitors to the right page.',
            links: [],
        },
    ]);
    const [prompts, setPrompts] = useState(config?.sampleQuestions ?? []);
    const [input, setInput] = useState('');
    const [isBusy, setIsBusy] = useState(false);
    const messagesRef = useRef(null);

    useEffect(() => {
        const handleKeyDown = (event) => {
            if (event.key === 'Escape') {
                setIsOpen(false);
            }
        };

        document.addEventListener('keydown', handleKeyDown);

        return () => document.removeEventListener('keydown', handleKeyDown);
    }, []);

    useEffect(() => {
        if (messagesRef.current) {
            messagesRef.current.scrollTop = messagesRef.current.scrollHeight;
        }
    }, [isBusy, messages]);

    if (!config?.endpoint) {
        return null;
    }

    const submitMessage = async (rawMessage) => {
        const message = String(rawMessage ?? '').trim();

        if (!message || isBusy) {
            return;
        }

        setIsOpen(true);
        setMessages((current) => [...current, { role: 'user', text: message, links: [] }]);
        setInput('');
        setIsBusy(true);

        try {
            const response = await fetch(config.endpoint, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                },
                body: JSON.stringify({ message }),
            });

            const payload = await response.json();

            if (!response.ok) {
                throw payload;
            }

            setMessages((current) => [
                ...current,
                {
                    role: 'bot',
                    text: payload.answer,
                    links: payload.links ?? [],
                },
            ]);
            setPrompts(payload.suggestions ?? []);
        } catch (error) {
            const failureMessage = error?.errors?.message
                ? 'Please enter a valid question with up to 500 characters.'
                : 'The assistant could not answer right now. Please try again or use the contact page.';

            setMessages((current) => [
                ...current,
                {
                    role: 'bot',
                    text: failureMessage,
                    links: [{ label: 'Contact page', url: '/contact' }],
                },
            ]);
        } finally {
            setIsBusy(false);
        }
    };

    return (
        <div className={`site-chatbot${isBusy ? ' is-loading' : ''}`} data-site-chatbot>
            <button
                type="button"
                className="site-chatbot-toggle"
                onClick={() => setIsOpen((current) => !current)}
                aria-expanded={isOpen}
                aria-controls="site-chatbot-panel"
            >
                <span className="site-chatbot-toggle-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
                        <path d="M7 10h10M7 14h7" />
                        <path d="M5.5 19.5 7 16.5h11a3.5 3.5 0 0 0 3.5-3.5V8A3.5 3.5 0 0 0 18 4.5H6A3.5 3.5 0 0 0 2.5 8v5A3.5 3.5 0 0 0 6 16.5h.5" />
                    </svg>
                </span>
                <span>Chat</span>
            </button>

            <section
                id="site-chatbot-panel"
                className={`site-chatbot-panel${isOpen ? ' is-open' : ''}`}
                hidden={!isOpen}
                aria-live="polite"
            >
                <header className="site-chatbot-header">
                    <div>
                        <p className="site-chatbot-kicker">Portfolio Assistant</p>
                        <h2>{config.title}</h2>
                        <p className="site-chatbot-subtitle">Ask about services, projects, skills, resume, blog, or contact details.</p>
                    </div>

                    <button type="button" className="site-chatbot-close" onClick={() => setIsOpen(false)} aria-label="Close chat">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </header>

                <div className="site-chatbot-messages" ref={messagesRef}>
                    {messages.map((message, index) => (
                        <article
                            key={`${message.role}-${index}`}
                            className={`site-chatbot-message ${message.role === 'user' ? 'is-user' : 'is-bot'}`}
                        >
                            <div className="site-chatbot-avatar">{message.role === 'user' ? 'You' : 'AI'}</div>
                            <div className="site-chatbot-bubble">
                                {message.text.split('\n').filter(Boolean).map((line, lineIndex) => (
                                    <p key={lineIndex}>{line.trim()}</p>
                                ))}

                                {(message.links ?? []).length > 0 && (
                                    <div className="site-chatbot-links">
                                        {message.links.map((link, linkIndex) => (
                                            <a
                                                key={`${link.label}-${linkIndex}`}
                                                className="site-chatbot-link"
                                                href={link.url}
                                                target={isExternalUrl(link.url) ? '_blank' : undefined}
                                                rel={isExternalUrl(link.url) ? 'noopener noreferrer' : undefined}
                                            >
                                                {link.label}
                                            </a>
                                        ))}
                                    </div>
                                )}
                            </div>
                        </article>
                    ))}

                    {isBusy && (
                        <article className="site-chatbot-message is-bot is-typing">
                            <div className="site-chatbot-avatar">AI</div>
                            <div className="site-chatbot-bubble">
                                <div className="site-chatbot-dots">
                                    <span />
                                    <span />
                                    <span />
                                </div>
                            </div>
                        </article>
                    )}
                </div>

                <div className="site-chatbot-prompts">
                    <p className="site-chatbot-prompts-title">
                        {prompts.length > 0 ? 'Popular questions' : 'Suggested follow-up questions'}
                    </p>

                    <div className="site-chatbot-prompt-grid">
                        {prompts.map((prompt, index) => (
                            <button
                                key={`${prompt}-${index}`}
                                type="button"
                                className="site-chatbot-prompt"
                                onClick={() => submitMessage(prompt)}
                            >
                                {prompt}
                            </button>
                        ))}
                    </div>
                </div>

                <form
                    className="site-chatbot-form"
                    onSubmit={(event) => {
                        event.preventDefault();
                        submitMessage(input);
                    }}
                >
                    <label className="visually-hidden" htmlFor="site-chatbot-input">Chat message</label>
                    <div className="site-chatbot-input-row">
                        <input
                            id="site-chatbot-input"
                            type="text"
                            name="message"
                            className="site-chatbot-input"
                            maxLength={500}
                            placeholder="Ask a question about the portfolio..."
                            autoComplete="off"
                            value={input}
                            onChange={(event) => setInput(event.target.value)}
                            disabled={isBusy}
                        />
                        <button type="submit" className="site-chatbot-send" disabled={isBusy}>Send</button>
                    </div>
                </form>

                <p className="site-chatbot-note">Answers are generated from the portfolio content currently published on this website.</p>
            </section>
        </div>
    );
}
