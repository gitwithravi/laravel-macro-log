// PWA Service Worker is registered in app.blade.php
// This file contains additional PWA-related functionality

console.log('PWA module loaded');

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
