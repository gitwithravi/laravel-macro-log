import './bootstrap';
import '../css/app.css';
import './pwa';

import { createApp, h } from 'vue';
import { createInertiaApp, router } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { initializePWANavigation, isPWA, incrementNavigationDepth } from './utils/pwaNavigation';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Initialize PWA navigation management before creating the app
initializePWANavigation();

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);

        // Set up Inertia navigation tracking for PWA
        if (isPWA()) {
            router.on('navigate', (event) => {
                // Track forward navigation depth
                // Note: Back navigation is handled in useBackButtonHandler composable
                const isBackNavigation = event.detail?.navigationType === 'back_forward';

                if (!isBackNavigation) {
                    incrementNavigationDepth();
                }
            });
        }

        return app;
    },
    progress: {
        color: '#4B5563',
    },
});
