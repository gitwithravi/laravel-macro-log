<script setup>
import { ref, onMounted } from 'vue';

const showInstallPrompt = ref(false);
const deferredPrompt = ref(null);

onMounted(() => {
    // Check if already installed or dismissed
    const dismissed = localStorage.getItem('pwa-install-dismissed');
    const isStandalone = window.matchMedia('(display-mode: standalone)').matches
                      || window.navigator.standalone
                      || document.referrer.includes('android-app://');

    // Listen for the beforeinstallprompt event
    window.addEventListener('beforeinstallprompt', (e) => {
        // Prevent the mini-infobar from appearing on mobile
        e.preventDefault();

        // Stash the event so it can be triggered later
        deferredPrompt.value = e;

        // Show prompt if not dismissed and not already installed
        if (!dismissed && !isStandalone) {
            // Small delay to let the page settle
            setTimeout(() => {
                showInstallPrompt.value = true;
            }, 2000);
        }
    });

    // Listen for app installed event
    window.addEventListener('appinstalled', () => {
        showInstallPrompt.value = false;
        deferredPrompt.value = null;
        localStorage.removeItem('pwa-install-dismissed');
    });
});

const installApp = async () => {
    if (!deferredPrompt.value) return;

    // Show the install prompt
    deferredPrompt.value.prompt();

    // Wait for the user to respond to the prompt
    const { outcome } = await deferredPrompt.value.userChoice;

    if (outcome === 'accepted') {
        console.log('User accepted the install prompt');
    }

    // Clear the deferredPrompt
    deferredPrompt.value = null;
    showInstallPrompt.value = false;
};

const dismissPrompt = () => {
    showInstallPrompt.value = false;
    localStorage.setItem('pwa-install-dismissed', 'true');
};
</script>

<template>
    <teleport to="body">
        <div v-if="showInstallPrompt" class="fixed bottom-20 sm:bottom-4 left-4 right-4 sm:left-auto sm:right-4 sm:w-96 z-40">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-2xl p-4 text-white">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-semibold mb-1">Install Macro Log</h3>
                        <p class="text-sm text-white/90 mb-3">Install the app for quick access and offline viewing.</p>
                        <div class="flex gap-2">
                            <button
                                @click="installApp"
                                class="flex-1 px-3 py-2 bg-white text-indigo-600 text-sm font-medium rounded-lg hover:bg-white/90 transition-colors"
                            >
                                Install
                            </button>
                            <button
                                @click="dismissPrompt"
                                class="px-3 py-2 bg-white/20 hover:bg-white/30 text-white text-sm font-medium rounded-lg transition-colors"
                            >
                                Not Now
                            </button>
                        </div>
                    </div>
                    <button @click="dismissPrompt" class="flex-shrink-0 text-white/70 hover:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </teleport>
</template>
