<script setup>
import { onMounted, ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    showOneTap: {
        type: Boolean,
        default: true,
    },
    mode: {
        type: String,
        default: 'signin', // 'signin' or 'signup'
    },
});

const googleClientId = ref(import.meta.env.VITE_GOOGLE_CLIENT_ID || '');
const isLoading = ref(false);

// Handle Google credential response
const handleCredentialResponse = async (response) => {
    if (isLoading.value) return;

    isLoading.value = true;

    try {
        // Send the credential to our backend
        router.post(route('socialite.one-tap'), {
            credential: response.credential,
        }, {
            onFinish: () => {
                isLoading.value = false;
            },
            onError: (errors) => {
                console.error('Google One Tap login failed:', errors);
                isLoading.value = false;
            }
        });
    } catch (error) {
        console.error('Error processing Google sign-in:', error);
        isLoading.value = false;
    }
};

// Initialize Google Sign-In
onMounted(() => {
    if (!googleClientId.value) {
        console.warn('Google Client ID not configured. Please set VITE_GOOGLE_CLIENT_ID in your .env file.');
        return;
    }

    // Load Google Sign-In script
    const script = document.createElement('script');
    script.src = 'https://accounts.google.com/gsi/client';
    script.async = true;
    script.defer = true;
    script.onload = () => {
        // Initialize Google One Tap
        if (props.showOneTap && window.google) {
            window.google.accounts.id.initialize({
                client_id: googleClientId.value,
                callback: handleCredentialResponse,
                auto_select: false,
                cancel_on_tap_outside: true,
            });

            // Display One Tap prompt
            window.google.accounts.id.prompt((notification) => {
                if (notification.isNotDisplayed() || notification.isSkippedMoment()) {
                    console.log('One Tap prompt not displayed:', notification.getNotDisplayedReason());
                }
            });
        }

        // Render Sign-In button
        if (window.google) {
            window.google.accounts.id.renderButton(
                document.getElementById('google-signin-button'),
                {
                    theme: 'outline',
                    size: 'large',
                    width: '100%',
                    text: props.mode === 'signup' ? 'signup_with' : 'signin_with',
                    shape: 'rectangular',
                    logo_alignment: 'left',
                }
            );
        }
    };
    document.head.appendChild(script);

    // Make callback available globally
    window.handleCredentialResponse = handleCredentialResponse;
});

// Handle traditional OAuth redirect
const handleOAuthRedirect = () => {
    window.location.href = route('socialite.redirect', { provider: 'google' });
};
</script>

<template>
    <div class="google-signin-container">
        <!-- Google Sign-In Button -->
        <div id="google-signin-button" class="w-full"></div>

        <!-- Fallback button if Google SDK fails to load -->
        <button
            v-if="!googleClientId"
            @click="handleOAuthRedirect"
            class="w-full flex items-center justify-center gap-3 px-4 py-3 border border-gray-300 rounded-lg shadow-sm bg-white hover:bg-gray-50 transition-colors duration-200 text-gray-700 font-medium"
            :disabled="isLoading"
        >
            <svg class="w-5 h-5" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            <span>{{ mode === 'signup' ? 'Sign up with Google' : 'Sign in with Google' }}</span>
        </button>

        <!-- Loading overlay -->
        <div v-if="isLoading" class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center rounded-lg">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
        </div>
    </div>
</template>

<style scoped>
.google-signin-container {
    position: relative;
    min-height: 44px;
}

/* Style the Google button iframe container */
#google-signin-button {
    display: flex;
    justify-content: center;
}
</style>
