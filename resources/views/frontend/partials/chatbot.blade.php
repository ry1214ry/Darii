@php
    $chatbotQuestions = app(\App\Services\PortfolioChatbotService::class)->sampleQuestions();
    $chatbotTitle = ($setting->site_name ?? 'Portfolio') . ' Assistant';
    $chatbotSubtitle = 'Ask about projects, skills, resume, blog, or contact details.';
    $chatbotIntro = 'Hello. I can answer common questions about this portfolio and guide visitors to the right page.';
    $chatbotNote = 'Answers use the portfolio content currently published on this website.';
@endphp

<div
    class="site-chatbot"
    data-site-chatbot
    data-endpoint="{{ route('chatbot.message') }}"
>
    <button
        type="button"
        class="site-chatbot-toggle"
        data-site-chatbot-toggle
        aria-expanded="false"
        aria-controls="site-chatbot-panel"
    >
        <span class="site-chatbot-toggle-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M7 10h10M7 14h7"></path>
                <path d="M5.5 19.5 7 16.5h11a3.5 3.5 0 0 0 3.5-3.5V8A3.5 3.5 0 0 0 18 4.5H6A3.5 3.5 0 0 0 2.5 8v5A3.5 3.5 0 0 0 6 16.5h.5"></path>
            </svg>
        </span>
        <span>Chat</span>
    </button>

    <section
        id="site-chatbot-panel"
        class="site-chatbot-panel"
        data-site-chatbot-panel
        hidden
        aria-live="polite"
    >
        <header class="site-chatbot-header">
            <div class="site-chatbot-header-copy">
                <p class="site-chatbot-kicker">Portfolio Assistant</p>
                <h2>{{ $chatbotTitle }}</h2>
                <p class="site-chatbot-subtitle">{{ $chatbotSubtitle }}</p>
            </div>

            <button type="button" class="site-chatbot-close" data-site-chatbot-close aria-label="Close chat">
                <span aria-hidden="true">&times;</span>
            </button>
        </header>

        <div class="site-chatbot-messages" data-site-chatbot-messages>
            <article class="site-chatbot-message is-bot">
                <div class="site-chatbot-avatar">AI</div>
                <div class="site-chatbot-bubble">
                    <p>{{ $chatbotIntro }}</p>
                </div>
            </article>
        </div>

        <div class="site-chatbot-prompts">
            <p class="site-chatbot-prompts-title" data-site-chatbot-prompts-title>Popular questions</p>

            <div class="site-chatbot-prompt-grid" data-site-chatbot-prompt-grid>
                @foreach($chatbotQuestions as $chatbotQuestion)
                    <button
                        type="button"
                        class="site-chatbot-prompt"
                        data-site-chatbot-prompt
                        data-question="{{ $chatbotQuestion }}"
                    >
                        {{ $chatbotQuestion }}
                    </button>
                @endforeach
            </div>
        </div>

        <form class="site-chatbot-form" data-site-chatbot-form>
            <label class="visually-hidden" for="site-chatbot-input">Chat message</label>
            <div class="site-chatbot-input-row">
                <input
                    id="site-chatbot-input"
                    type="text"
                    name="message"
                    class="site-chatbot-input"
                    data-site-chatbot-input
                    maxlength="500"
                    placeholder="Ask about this portfolio..."
                    autocomplete="off"
                >
                <button type="submit" class="site-chatbot-send" data-site-chatbot-send>Send</button>
            </div>
        </form>

        <p class="site-chatbot-note">{{ $chatbotNote }}</p>
    </section>
</div>
