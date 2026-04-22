import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

if (csrfToken) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
}

const getPreferredTheme = () => {
    const savedTheme = window.localStorage.getItem('site-theme');

    if (savedTheme === 'light' || savedTheme === 'dark') {
        return savedTheme;
    }

    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
};

const applySiteTheme = (theme) => {
    const normalizedTheme = theme === 'dark' ? 'dark' : 'light';

    document.documentElement.setAttribute('data-theme', normalizedTheme);
    window.localStorage.setItem('site-theme', normalizedTheme);

    document.querySelectorAll('[data-site-theme-toggle]').forEach((toggle) => {
        toggle.setAttribute('aria-pressed', String(normalizedTheme === 'dark'));
        toggle.setAttribute('aria-label', normalizedTheme === 'dark' ? 'Switch to light mode' : 'Switch to dark mode');
    });

    document.querySelectorAll('[data-site-theme-state]').forEach((label) => {
        label.textContent = normalizedTheme === 'dark' ? 'Dark' : 'Light';
    });
};

const initTypingLabels = () => {
    document.querySelectorAll('[data-typing-words]').forEach((element) => {
        let words = [];

        try {
            words = JSON.parse(element.dataset.typingWords || '[]');
        } catch {
            words = [];
        }

        words = words.filter((word) => typeof word === 'string' && word.trim().length > 0);

        if (words.length === 0) {
            return;
        }

        const typingSpeed = Number(element.dataset.typingSpeed || 90);
        const pauseDuration = Number(element.dataset.typingPause || 1500);

        let wordIndex = 0;
        let charIndex = 0;
        let isDeleting = false;

        const tick = () => {
            const currentWord = words[wordIndex];

            if (isDeleting) {
                charIndex -= 1;
            } else {
                charIndex += 1;
            }

            element.textContent = currentWord.slice(0, charIndex);

            let nextDelay = typingSpeed;

            if (!isDeleting && charIndex === currentWord.length) {
                nextDelay = pauseDuration;
                isDeleting = true;
            } else if (isDeleting && charIndex === 0) {
                isDeleting = false;
                wordIndex = (wordIndex + 1) % words.length;
                nextDelay = typingSpeed * 0.8;
            } else if (isDeleting) {
                nextDelay = typingSpeed * 0.6;
            }

            window.setTimeout(tick, nextDelay);
        };

        element.textContent = '';
        window.setTimeout(tick, typingSpeed);
    });
};

const initSiteNavbar = () => {
    const navPanel = document.querySelector('[data-site-nav-panel]');
    const navToggle = document.querySelector('[data-site-nav-toggle]');

    if (!navPanel || !navToggle) {
        return;
    }

    const closeNav = () => {
        navPanel.classList.remove('is-open');
        navToggle.setAttribute('aria-expanded', 'false');
    };

    navToggle.addEventListener('click', () => {
        const isOpen = navPanel.classList.toggle('is-open');
        navToggle.setAttribute('aria-expanded', String(isOpen));
    });

    navPanel.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 991) {
                closeNav();
            }
        });
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 991) {
            closeNav();
        }
    });
};

const initHomeLoader = () => {
    const page = document.body;
    const loader = document.querySelector('[data-site-loader]');

    if (!page?.dataset.siteHomeLoader || !loader) {
        return;
    }

    window.setTimeout(() => {
        page.classList.add('is-home-ready');
        loader.setAttribute('aria-hidden', 'true');
    }, 3000);

    window.setTimeout(() => {
        loader.remove();
    }, 3600);
};

const initPortfolioChatbot = () => {
    const chatbot = document.querySelector('[data-site-chatbot]');

    if (!chatbot) {
        return;
    }

    const endpoint = chatbot.dataset.endpoint;
    const panel = chatbot.querySelector('[data-site-chatbot-panel]');
    const toggle = chatbot.querySelector('[data-site-chatbot-toggle]');
    const closeButton = chatbot.querySelector('[data-site-chatbot-close]');
    const form = chatbot.querySelector('[data-site-chatbot-form]');
    const input = chatbot.querySelector('[data-site-chatbot-input]');
    const sendButton = chatbot.querySelector('[data-site-chatbot-send]');
    const messages = chatbot.querySelector('[data-site-chatbot-messages]');
    const promptGrid = chatbot.querySelector('[data-site-chatbot-prompt-grid]');
    const promptsTitle = chatbot.querySelector('[data-site-chatbot-prompts-title]');

    if (!endpoint || !panel || !toggle || !closeButton || !form || !input || !sendButton || !messages || !promptGrid) {
        return;
    }

    const renderLinks = (container, links = []) => {
        if (!Array.isArray(links) || links.length === 0) {
            return;
        }

        const actions = document.createElement('div');
        actions.className = 'site-chatbot-links';

        links.forEach((link) => {
            if (!link?.label || !link?.url) {
                return;
            }

            const anchor = document.createElement('a');
            const resolvedUrl = new URL(link.url, window.location.origin);
            const isExternal = resolvedUrl.origin !== window.location.origin;

            anchor.className = 'site-chatbot-link';
            anchor.href = resolvedUrl.toString();
            anchor.textContent = link.label;

            if (isExternal) {
                anchor.target = '_blank';
                anchor.rel = 'noopener noreferrer';
            }

            actions.append(anchor);
        });

        if (actions.childElementCount > 0) {
            container.append(actions);
        }
    };

    const renderMessage = (role, text, links = []) => {
        const article = document.createElement('article');
        article.className = `site-chatbot-message ${role === 'user' ? 'is-user' : 'is-bot'}`;

        const avatar = document.createElement('div');
        avatar.className = 'site-chatbot-avatar';
        avatar.textContent = role === 'user' ? 'You' : 'AI';

        const bubble = document.createElement('div');
        bubble.className = 'site-chatbot-bubble';

        String(text || '')
            .split('\n')
            .map((line) => line.trim())
            .filter(Boolean)
            .forEach((line) => {
                const paragraph = document.createElement('p');
                paragraph.textContent = line;
                bubble.append(paragraph);
            });

        renderLinks(bubble, links);

        article.append(avatar, bubble);
        messages.append(article);
        messages.scrollTop = messages.scrollHeight;
    };

    const createTypingIndicator = () => {
        const typing = document.createElement('article');
        typing.className = 'site-chatbot-message is-bot is-typing';
        typing.setAttribute('data-site-chatbot-typing', 'true');

        const avatar = document.createElement('div');
        avatar.className = 'site-chatbot-avatar';
        avatar.textContent = 'AI';

        const bubble = document.createElement('div');
        bubble.className = 'site-chatbot-bubble';

        const dots = document.createElement('div');
        dots.className = 'site-chatbot-dots';
        dots.innerHTML = '<span></span><span></span><span></span>';

        bubble.append(dots);
        typing.append(avatar, bubble);

        return typing;
    };

    const setPromptButtons = (questions, title = 'Suggested follow-up questions') => {
        const promptItems = Array.isArray(questions) ? questions.filter(Boolean) : [];

        promptGrid.innerHTML = '';

        if (promptsTitle) {
            promptsTitle.textContent = promptItems.length > 0 ? title : 'Popular questions';
        }

        promptItems.forEach((question) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'site-chatbot-prompt';
            button.dataset.siteChatbotPrompt = 'true';
            button.dataset.question = question;
            button.textContent = question;

            promptGrid.append(button);
        });
    };

    const openChatbot = () => {
        panel.hidden = false;
        panel.classList.add('is-open');
        toggle.setAttribute('aria-expanded', 'true');
        window.setTimeout(() => input.focus(), 80);
    };

    const closeChatbot = () => {
        panel.classList.remove('is-open');
        panel.hidden = true;
        toggle.setAttribute('aria-expanded', 'false');
    };

    const setBusy = (isBusy) => {
        input.disabled = isBusy;
        sendButton.disabled = isBusy;
        chatbot.classList.toggle('is-loading', isBusy);
    };

    const submitMessage = async (rawMessage) => {
        const message = String(rawMessage || '').trim();

        if (!message) {
            return;
        }

        openChatbot();
        renderMessage('user', message);
        input.value = '';
        setBusy(true);

        const typingIndicator = createTypingIndicator();
        messages.append(typingIndicator);
        messages.scrollTop = messages.scrollHeight;

        try {
            const response = await window.axios.post(endpoint, { message });
            typingIndicator.remove();

            renderMessage('bot', response.data.answer, response.data.links || []);
            setPromptButtons(response.data.suggestions || []);
        } catch (error) {
            typingIndicator.remove();

            let failureMessage = 'The assistant could not answer right now. Please try again or use the contact page.';

            if (error.response?.status === 422) {
                failureMessage = 'Please enter a valid question with up to 500 characters.';
            }

            renderMessage('bot', failureMessage, [
                { label: 'Contact page', url: '/contact' },
            ]);
        } finally {
            setBusy(false);
            input.focus();
        }
    };

    toggle.addEventListener('click', () => {
        if (panel.hidden) {
            openChatbot();
            return;
        }

        closeChatbot();
    });

    closeButton.addEventListener('click', closeChatbot);

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !panel.hidden) {
            closeChatbot();
        }
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        await submitMessage(input.value);
    });

    promptGrid.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-site-chatbot-prompt]');

        if (!button) {
            return;
        }

        await submitMessage(button.dataset.question || button.textContent);
    });
};

document.addEventListener('DOMContentLoaded', () => {
    applySiteTheme(getPreferredTheme());
    initHomeLoader();
    initTypingLabels();
    initSiteNavbar();
    initPortfolioChatbot();

    document.querySelectorAll('[data-site-theme-toggle]').forEach((toggle) => {
        toggle.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
            applySiteTheme(currentTheme === 'dark' ? 'light' : 'dark');
        });
    });
});
