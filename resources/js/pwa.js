// Only load PWA register in production or when explicitly enabled
if (import.meta.env.PROD || import.meta.env.VITE_PWA_DEV === 'true') {
    // Dynamically import to avoid errors when module doesn't exist
    import('virtual:pwa-register')
        .then(({ registerSW }) => {
            if ('serviceWorker' in navigator) {
                const updateSW = registerSW({
                    immediate: true,
                    onNeedRefresh() {
                        // Dispatch custom event for update prompt component
                        window.dispatchEvent(new CustomEvent('swUpdateAvailable', {
                            detail: updateSW
                        }));
                    },
                    onOfflineReady() {
                        console.log('App ready to work offline');
                    },
                    onRegistered(registration) {
                        console.log('Service Worker registered:', registration);
                    },
                    onRegisterError(error) {
                        console.error('Service Worker registration failed:', error);
                    }
                });
            }
        })
        .catch((error) => {
            console.log('PWA not available in development mode:', error.message);
        });
}

// Handle online/offline status
window.addEventListener('online', () => {
    console.log('Back online');
    // Trigger sync if offline queue exists
    document.dispatchEvent(new CustomEvent('app:online'));
});

window.addEventListener('offline', () => {
    console.log('Gone offline');
    document.dispatchEvent(new CustomEvent('app:offline'));
});

// Export for use in components
export function isOnline() {
    return navigator.onLine;
}
