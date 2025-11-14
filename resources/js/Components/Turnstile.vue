<script setup>
import { ref, onMounted, onBeforeUnmount, watch } from 'vue';

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    },
    siteKey: {
        type: String,
        required: true
    },
    theme: {
        type: String,
        default: 'light', // 'light', 'dark', or 'auto'
    },
    size: {
        type: String,
        default: 'normal', // 'normal', 'compact'
    }
});

const emit = defineEmits(['update:modelValue', 'verified', 'error', 'expired']);

const turnstileContainer = ref(null);
const widgetId = ref(null);
const isScriptLoaded = ref(false);

const loadTurnstileScript = () => {
    return new Promise((resolve, reject) => {
        // Check if script already exists
        if (window.turnstile) {
            isScriptLoaded.value = true;
            resolve();
            return;
        }

        // Check if script is already being loaded
        const existingScript = document.querySelector('script[src*="turnstile"]');
        if (existingScript) {
            existingScript.addEventListener('load', () => {
                isScriptLoaded.value = true;
                resolve();
            });
            existingScript.addEventListener('error', reject);
            return;
        }

        // Load the script
        const script = document.createElement('script');
        script.src = 'https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit';
        script.async = true;
        script.defer = true;

        script.onload = () => {
            isScriptLoaded.value = true;
            resolve();
        };
        script.onerror = reject;

        document.head.appendChild(script);
    });
};

const renderTurnstile = () => {
    if (!window.turnstile || !turnstileContainer.value) return;

    // Remove existing widget if any
    if (widgetId.value !== null) {
        try {
            window.turnstile.remove(widgetId.value);
        } catch (e) {
            console.warn('Error removing Turnstile widget:', e);
        }
    }

    // Render new widget
    widgetId.value = window.turnstile.render(turnstileContainer.value, {
        sitekey: props.siteKey,
        theme: props.theme,
        size: props.size,
        callback: (token) => {
            emit('update:modelValue', token);
            emit('verified', token);
        },
        'error-callback': () => {
            emit('update:modelValue', '');
            emit('error');
        },
        'expired-callback': () => {
            emit('update:modelValue', '');
            emit('expired');
        },
    });
};

onMounted(async () => {
    try {
        await loadTurnstileScript();
        // Wait a bit for the script to fully initialize
        setTimeout(() => {
            renderTurnstile();
        }, 100);
    } catch (error) {
        console.error('Failed to load Turnstile:', error);
        emit('error', error);
    }
});

onBeforeUnmount(() => {
    if (widgetId.value !== null && window.turnstile) {
        try {
            window.turnstile.remove(widgetId.value);
        } catch (e) {
            console.warn('Error removing Turnstile widget on unmount:', e);
        }
    }
});

// Watch for siteKey changes (useful if it's dynamically set)
watch(() => props.siteKey, () => {
    if (isScriptLoaded.value) {
        renderTurnstile();
    }
});

// Expose reset method
const reset = () => {
    if (widgetId.value !== null && window.turnstile) {
        window.turnstile.reset(widgetId.value);
        emit('update:modelValue', '');
    }
};

defineExpose({ reset });
</script>

<template>
    <div>
        <div ref="turnstileContainer" class="turnstile-widget"></div>
    </div>
</template>

<style scoped>
.turnstile-widget {
    display: flex;
    justify-content: center;
}
</style>
