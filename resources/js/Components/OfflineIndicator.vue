<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const isOffline = ref(!navigator.onLine);

const handleOnline = () => {
    isOffline.value = false;
};

const handleOffline = () => {
    isOffline.value = true;
};

onMounted(() => {
    window.addEventListener('online', handleOnline);
    window.addEventListener('offline', handleOffline);
});

onUnmounted(() => {
    window.removeEventListener('online', handleOnline);
    window.removeEventListener('offline', handleOffline);
});
</script>

<template>
    <transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="opacity-0 translate-y-2"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 translate-y-2"
    >
        <div v-if="isOffline" class="fixed top-0 left-0 right-0 z-50 bg-orange-500 text-white px-4 py-2 text-center text-sm font-medium shadow-lg">
            <div class="flex items-center justify-center gap-2">
                <svg class="w-4 h-4 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd" />
                </svg>
                <span>You're offline. Some features may be unavailable.</span>
            </div>
        </div>
    </transition>
</template>
