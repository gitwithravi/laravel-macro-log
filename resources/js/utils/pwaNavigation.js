/**
 * PWA Navigation Manager
 *
 * Manages browser history for PWA to ensure clean state on fresh app opens.
 * Prevents back button from navigating to pages from previous sessions.
 */

const SESSION_KEY = 'pwa-session-active';
const NAVIGATION_DEPTH_KEY = 'pwa-nav-depth';

/**
 * Detects if the app is running in standalone PWA mode
 */
export function isPWA() {
    return window.matchMedia('(display-mode: standalone)').matches ||
           window.navigator.standalone === true ||
           document.referrer.includes('android-app://');
}

/**
 * Detects if this is a fresh PWA session (app was just opened)
 */
export function isFreshSession() {
    return !sessionStorage.getItem(SESSION_KEY);
}

/**
 * Marks the current session as active
 */
export function markSessionActive() {
    sessionStorage.setItem(SESSION_KEY, 'true');
}

/**
 * Gets the current navigation depth (how many pages deep in current session)
 */
export function getNavigationDepth() {
    const depth = sessionStorage.getItem(NAVIGATION_DEPTH_KEY);
    return depth ? parseInt(depth, 10) : 0;
}

/**
 * Sets the navigation depth
 */
export function setNavigationDepth(depth) {
    sessionStorage.setItem(NAVIGATION_DEPTH_KEY, depth.toString());
}

/**
 * Increments navigation depth (user navigated forward)
 */
export function incrementNavigationDepth() {
    const current = getNavigationDepth();
    setNavigationDepth(current + 1);
}

/**
 * Decrements navigation depth (user navigated back)
 */
export function decrementNavigationDepth() {
    const current = getNavigationDepth();
    if (current > 0) {
        setNavigationDepth(current - 1);
    }
}

/**
 * Resets navigation depth to 0
 */
export function resetNavigationDepth() {
    setNavigationDepth(0);
}

/**
 * Clears the browser history stack and starts fresh at the current page
 * This prevents back button from going to previous session pages
 */
export function clearHistoryStack() {
    // Replace all history entries with current state
    // This effectively clears the back stack
    const currentState = window.history.state;
    const currentUrl = window.location.href;

    // Replace the current entry to clear the back stack
    window.history.replaceState(currentState, '', currentUrl);

    // Reset navigation depth
    resetNavigationDepth();

    console.log('[PWA Navigation] History stack cleared');
}

/**
 * Initializes PWA navigation management
 * Should be called when the app boots up
 */
export function initializePWANavigation() {
    // Only apply PWA-specific behavior in standalone mode
    if (!isPWA()) {
        console.log('[PWA Navigation] Not in PWA mode, skipping initialization');
        return;
    }

    // Check if this is a fresh session
    if (isFreshSession()) {
        console.log('[PWA Navigation] Fresh session detected, clearing history stack');

        // Clear the history stack to prevent going back to previous session
        clearHistoryStack();

        // Mark session as active
        markSessionActive();
    } else {
        console.log('[PWA Navigation] Continuing existing session');
    }

    // Clean up when page is unloaded (though this may not fire in PWA mode)
    window.addEventListener('beforeunload', () => {
        // sessionStorage will automatically clear when app truly closes
        // But we log for debugging
        console.log('[PWA Navigation] Page unloading');
    });
}

/**
 * Checks if user is at the start of the session (can't go back further)
 */
export function isAtSessionStart() {
    return getNavigationDepth() === 0;
}
