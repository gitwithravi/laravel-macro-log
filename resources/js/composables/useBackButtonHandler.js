/**
 * Back Button Handler Composable
 *
 * Handles back button behavior in PWA mode:
 * - Shows confirmation dialog when user tries to exit the app
 * - Prevents accidental app exits
 * - Works with Inertia.js navigation
 */

import { onMounted, onUnmounted } from 'vue';
import {
    isPWA,
    isAtHistorySentinel,
    pushDummyState
} from '@/utils/pwaNavigation';

export function useBackButtonHandler() {
    let popstateHandler = null;

    /**
     * Shows confirmation dialog when user tries to exit the app
     */
    function showExitConfirmation() {
        const userConfirmed = window.confirm(
            'Are you sure you want to exit the app?'
        );
        return userConfirmed;
    }

    /**
     * Handles the browser back button press
     */
    function handlePopState(event) {
        // Only handle in PWA mode
        if (!isPWA()) {
            return;
        }

        console.log('[Back Button] popstate event fired', {
            state: window.history.state,
            url: window.location.href,
            isAtSentinel: isAtHistorySentinel()
        });

        // Check if we've hit the sentinel (session start)
        if (isAtHistorySentinel()) {
            console.log('[Back Button] At session sentinel, intercepting back navigation');

            // Ask user for confirmation
            const userConfirmed = showExitConfirmation();

            if (!userConfirmed) {
                // User cancelled, push state forward to stay in app
                console.log('[Back Button] User cancelled exit, pushing forward');
                pushDummyState();
            } else {
                // User confirmed exit
                console.log('[Back Button] User confirmed exit, allowing navigation');
                // The app will close naturally or go to the system back behavior
            }
        }
    }

    /**
     * Setup event listeners
     */
    function setupListeners() {
        // Listen to browser back button
        popstateHandler = handlePopState;
        window.addEventListener('popstate', popstateHandler);

        console.log('[Back Button Handler] Listeners initialized');
    }

    /**
     * Cleanup event listeners
     */
    function cleanupListeners() {
        if (popstateHandler) {
            window.removeEventListener('popstate', popstateHandler);
        }

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
