/**
 * Back Button Handler Composable
 *
 * Handles back button behavior in PWA mode:
 * - Shows confirmation dialog when user tries to exit the app
 * - Prevents accidental app exits
 * - Works with Inertia.js navigation
 */

import { onMounted, onUnmounted } from 'vue';
import { router } from '@inertiajs/vue3';
import {
    isPWA,
    isAtSessionStart,
    incrementNavigationDepth,
    decrementNavigationDepth,
    resetNavigationDepth
} from '@/utils/pwaNavigation';

export function useBackButtonHandler() {
    let isNavigatingBack = false;
    let popstateHandler = null;

    /**
     * Shows confirmation dialog when user tries to exit the app
     */
    function showExitConfirmation() {
        return new Promise((resolve) => {
            // Use native browser confirm dialog
            const userConfirmed = window.confirm(
                'Are you sure you want to exit the app?'
            );
            resolve(userConfirmed);
        });
    }

    /**
     * Handles the browser back button press
     */
    async function handlePopState(event) {
        // Only handle in PWA mode
        if (!isPWA()) {
            return;
        }

        // Check if we're at the session start
        if (isAtSessionStart()) {
            console.log('[Back Button] User at session start, showing exit confirmation');

            // Ask user for confirmation
            const userConfirmed = await showExitConfirmation();

            if (!userConfirmed) {
                // User cancelled, push a new state to stay in the app
                console.log('[Back Button] User cancelled exit, staying in app');

                // Push current page back into history to prevent exit
                window.history.pushState(
                    window.history.state,
                    '',
                    window.location.href
                );

                // Prevent Inertia from processing this navigation
                event.preventDefault();
                return;
            } else {
                // User confirmed exit, allow the navigation
                // This will typically minimize/close the PWA
                console.log('[Back Button] User confirmed exit');
                // Let the browser handle it naturally
                return;
            }
        } else {
            // Not at session start, allow normal back navigation
            decrementNavigationDepth();
            console.log('[Back Button] Normal back navigation');
        }
    }

    /**
     * Tracks forward navigation to maintain depth counter
     */
    function handleInertiaNavigate(event) {
        // Only track in PWA mode
        if (!isPWA()) {
            return;
        }

        // Check if this is a back/forward navigation
        const isBackNavigation = event.detail?.page?.url === window.location.href;

        if (!isBackNavigation) {
            // This is a forward navigation (link click, etc.)
            incrementNavigationDepth();
            console.log('[Navigation] Forward navigation detected');
        }
    }

    /**
     * Setup event listeners
     */
    function setupListeners() {
        // Listen to browser back button
        popstateHandler = handlePopState;
        window.addEventListener('popstate', popstateHandler);

        // Listen to Inertia navigation events
        router.on('navigate', handleInertiaNavigate);

        console.log('[Back Button Handler] Listeners initialized');
    }

    /**
     * Cleanup event listeners
     */
    function cleanupListeners() {
        if (popstateHandler) {
            window.removeEventListener('popstate', popstateHandler);
        }

        router.off('navigate', handleInertiaNavigate);

        console.log('[Back Button Handler] Listeners cleaned up');
    }

    /**
     * Initialize on component mount
     */
    onMounted(() => {
        if (isPWA()) {
            setupListeners();
        }
    });

    /**
     * Cleanup on component unmount
     */
    onUnmounted(() => {
        if (isPWA()) {
            cleanupListeners();
        }
    });

    return {
        setupListeners,
        cleanupListeners,
        showExitConfirmation
    };
}
