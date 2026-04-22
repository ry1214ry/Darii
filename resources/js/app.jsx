import { createRoot } from 'react-dom/client';
import { AppShell } from './frontend/App';

const rootElement = document.getElementById('frontend-app');
const initialPage = window.__FRONTEND_PAGE__;

if (rootElement && initialPage) {
    createRoot(rootElement).render(<AppShell initialPage={initialPage} />);
}
