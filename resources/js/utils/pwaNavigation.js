/**
 * PWA Navigation Manager
 *
 * Manages browser history for PWA to ensure clean state on fresh app opens.
 * Prevents back button from navigating to pages from previous sessions.
 */

const SESSION_KEY = 'pwa-session-active';
const SESSION_START_URL_KEY = 'pwa-session-start-url';
const HISTORY_SENTINEL_KEY = 'pwa-history-sentinel';

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
    sessionStorage.setItem(SESSION_START_URL_KEY, window.location.href);
}

/**
 * Gets the session start URL
 */
export function getSessionStartUrl() {
    return sessionStorage.getItem(SESSION_START_URL_KEY);
}

/**
 * Clears the browser history stack by using a sentinel state
 * This prevents back button from going to previous session pages
 */
export function clearHistoryStack() {
    console.log('[PWA Navigation] Clearing history stack');

    // Get current state
    const currentState = window.history.state;
    const currentUrl = window.location.href;

    // Add a sentinel marker to indicate this is the start of the session
    const sentinelState = {
        ...currentState,
        [HISTORY_SENTINEL_KEY]: true,
        timestamp: Date.now()
    };

    // Clear history by going back to length 1
    // This is a trick: we replace the entire history with just the current page
    window.history.replaceState(sentinelState, '', currentUrl);

    console.log('[PWA Navigation] History stack cleared, sentinel placed');
}

/**
 * Checks if current history state is at the session sentinel
 */
export function isAtHistorySentinel() {
    return window.history.state?.[HISTORY_SENTINEL_KEY] === true;
}

/**
 * Checks if we're at the session start URL
 */
export function isAtSessionStartUrl() {
    const startUrl = getSessionStartUrl();
    return startUrl && window.location.href === startUrl;
}

/**
 * Pushes a dummy state to enable back button interception
 */
export function pushDummyState() {
    const currentState = window.history.state;
    const dummyState = {
        ...currentState,
        dummy: true,
        timestamp: Date.now()
    };

    window.history.pushState(dummyState, '', window.location.href);
    console.log('[PWA Navigation] Dummy state pushed');
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
        console.log('[PWA Navigation] Fresh session detected');

        // Mark session as active
        markSessionActive();

        // Clear the history stack
        clearHistoryStack();

        // Push a dummy state to enable back button handling
        // This creates: [sentinel] <- [dummy] (current)
        // When user presses back, they go to sentinel, we detect it and push forward again
        setTimeout(() => {
            pushDummyState();
        }, 100);
    } else {
        console.log('[PWA Navigation] Continuing existing session');
    }
}

/**
 * Checks if user is at the start of the session (can't go back further)
 */
export function isAtSessionStart() {
    return isAtHistorySentinel() || isAtSessionStartUrl();
}
