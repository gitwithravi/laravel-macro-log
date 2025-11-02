<script setup>
import { ref, onMounted } from 'vue';

const showUpdatePrompt = ref(false);
const updateHandler = ref(null);

onMounted(() => {
    // Listen for service worker update
    window.addEventListener('swUpdateAvailable', (event) => {
        showUpdatePrompt.value = true;
        updateHandler.value = event.detail;
    });
});

const updateApp = () => {
    if (updateHandler.value) {
        updateHandler.value();
    }
    showUpdatePrompt.value = false;
};

const dismissUpdate = () => {
    showUpdatePrompt.value = false;
};
</script>

<template>
    <teleport to="body">
        <div v-if="showUpdatePrompt" class="fixed bottom-20 sm:bottom-4 left-4 right-4 sm:left-auto sm:right-4 sm:w-96 z-50">
            <div class="bg-white rounded-xl shadow-2xl border border-gray-200 p-4">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-semibold text-gray-900 mb-1">Update Available</h3>
                        <p class="text-sm text-gray-600 mb-3">A new version of Macro Log is available. Update now for the latest features and improvements.</p>
                        <div class="flex gap-2">
                            <button
                                @click="updateApp"
                                class="flex-1 px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors"
                            >
                                Update Now
                            </button>
                            <button
                                @click="dismissUpdate"
                                class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors"
                            >
                                Later
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </teleport>
</template>
