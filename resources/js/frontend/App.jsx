import { useEffect, useState } from 'react';
import { Chatbot, SiteFooter, SiteHeader } from './components';
import { pageComponents } from './pages';

function UnknownPage() {
    return (
        <section className="section-block">
            <div className="container">
                <div className="page-hero-card">
                    <p className="section-eyebrow">Frontend</p>
                    <h1>Page not found</h1>
                    <p>The requested frontend component is not registered in the React app.</p>
                </div>
            </div>
        </section>
    );
}

function HomeLoader() {
    return (
        <div className="site-loader" role="status" aria-live="polite" aria-label="Loading Roeun Dary homepage">
            <div className="site-loader-shell">
                <p className="site-loader-copy">Loading Home Page</p>
                <h1 className="site-loader-title">ROEUN DARY</h1>
                <span className="site-loader-progress" aria-hidden="true">
                    <span />
                </span>
            </div>
        </div>
    );
}

export function AppShell({ initialPage }) {
    const pageProps = initialPage?.props ?? {};
    const app = pageProps.app ?? {};
    const site = app.site ?? {};
    const urls = app.urls ?? {};
    const currentRoute = app.currentRoute ?? '';
    const PageComponent = pageComponents[initialPage?.component] ?? UnknownPage;
    const isHomePage = currentRoute === 'home' || initialPage?.component === 'HomePage';

    const [theme, setTheme] = useState(() => (
        document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light'
    ));
    const [navOpen, setNavOpen] = useState(false);
    const [isHomeReady, setIsHomeReady] = useState(!isHomePage);
    const [showHomeLoader, setShowHomeLoader] = useState(isHomePage);

    useEffect(() => {
        document.documentElement.setAttribute('data-theme', theme);
        window.localStorage.setItem('site-theme', theme);
    }, [theme]);

    useEffect(() => {
        const handleResize = () => {
            if (window.innerWidth > 991) {
                setNavOpen(false);
            }
        };

        window.addEventListener('resize', handleResize);

        return () => window.removeEventListener('resize', handleResize);
    }, []);

    useEffect(() => {
        if (!isHomePage) {
            document.body.classList.remove('has-home-loader', 'is-home-ready');
            setIsHomeReady(true);
            setShowHomeLoader(false);
            return undefined;
        }

        document.body.classList.add('has-home-loader');
        document.body.classList.remove('is-home-ready');
        setIsHomeReady(false);
        setShowHomeLoader(true);

        const readyTimer = window.setTimeout(() => {
            document.body.classList.add('is-home-ready');
            setIsHomeReady(true);
        }, 3000);

        const removeTimer = window.setTimeout(() => {
            setShowHomeLoader(false);
            document.body.classList.remove('has-home-loader', 'is-home-ready');
        }, 3600);

        return () => {
            window.clearTimeout(readyTimer);
            window.clearTimeout(removeTimer);
            document.body.classList.remove('has-home-loader', 'is-home-ready');
        };
    }, [isHomePage]);

    return (
        <>
            {showHomeLoader && isHomePage && <HomeLoader />}

            <div className={`site-shell${isHomeReady ? ' is-home-ready' : ''}`}>
                <SiteHeader
                    site={site}
                    urls={urls}
                    currentRoute={currentRoute}
                    theme={theme}
                    navOpen={navOpen}
                    onToggleTheme={() => setTheme((currentTheme) => currentTheme === 'dark' ? 'light' : 'dark')}
                    onToggleNav={() => setNavOpen((isOpen) => !isOpen)}
                    onCloseNav={() => setNavOpen(false)}
                />

                <main className="site-main">
                    <PageComponent {...pageProps} />
                </main>

                <SiteFooter site={site} urls={urls} />
                <Chatbot config={app.chatbot} />
            </div>
        </>
    );
}
