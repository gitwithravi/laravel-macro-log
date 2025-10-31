<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    sessions: Array,
});

const confirmingLogout = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmLogout = () => {
    confirmingLogout.value = true;

    setTimeout(() => passwordInput.value.focus(), 250);
};

const logoutOtherBrowserSessions = () => {
    form.delete(route('other-browser-sessions.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingLogout.value = false;

    form.reset();
};
</script>

<template>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200">
        <!-- Header -->
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Browser Sessions</h3>
            <p class="mt-1 text-sm text-gray-600">Manage and log out your active sessions on other browsers and devices.</p>
        </div>

        <!-- Content -->
        <div class="px-4 py-5 sm:p-6">
            <p class="text-sm text-gray-600 mb-5">
                If necessary, you may log out of all of your other browser sessions across all of your devices. Some of your recent sessions are listed below; however, this list may not be exhaustive. If you feel your account has been compromised, you should also update your password.
            </p>

            <!-- Other Browser Sessions -->
            <div v-if="sessions.length > 0" class="space-y-3 mb-5">
                <div v-for="(session, i) in sessions" :key="i" class="flex items-center p-3 rounded-lg bg-gray-50">
                    <div class="flex-shrink-0">
                        <svg v-if="session.agent.is_desktop" class="size-8 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" />
                        </svg>

                        <svg v-else class="size-8 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                        </svg>
                    </div>

                    <div class="ml-3 flex-1 min-w-0">
                        <div class="text-sm font-medium text-gray-900 truncate">
                            {{ session.agent.platform ? session.agent.platform : 'Unknown' }} - {{ session.agent.browser ? session.agent.browser : 'Unknown' }}
                        </div>

                        <div class="text-xs text-gray-500 truncate">
                            {{ session.ip_address }}
                        </div>

                        <div class="text-xs mt-1">
                            <span v-if="session.is_current_device" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                This device
                            </span>
                            <span v-else class="text-gray-500">Last active {{ session.last_active }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <PrimaryButton @click="confirmLogout">
                    Log Out Other Sessions
                </PrimaryButton>

                <ActionMessage :on="form.recentlySuccessful" class="text-sm">
                    Done.
                </ActionMessage>
            </div>
        </div>

        <!-- Log Out Other Devices Confirmation Modal -->
        <teleport to="body">
            <div v-if="confirmingLogout" class="fixed inset-0 z-50 overflow-y-auto" @click.self="closeModal">
                <div class="flex min-h-screen items-end justify-center sm:items-center sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                    <div class="relative bg-white w-full sm:max-w-md sm:rounded-2xl rounded-t-2xl shadow-xl transform transition-all p-6">
                        <div class="text-center mb-6">
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 mb-4">
                                <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Log Out Other Browser Sessions</h3>
                            <p class="text-sm text-gray-600 mb-4">
                                Please enter your password to confirm you would like to log out of your other browser sessions across all of your devices.
                            </p>

                            <div class="mb-6">
                                <TextInput
                                    ref="passwordInput"
                                    v-model="form.password"
                                    type="password"
                                    class="mt-1 block w-full"
                                    placeholder="Password"
                                    autocomplete="current-password"
                                    @keyup.enter="logoutOtherBrowserSessions"
                                />

                                <InputError :message="form.errors.password" class="mt-2" />
                            </div>

                            <div class="flex gap-3">
                                <SecondaryButton @click="closeModal" class="flex-1">
                                    Cancel
                                </SecondaryButton>
                                <PrimaryButton
                                    class="flex-1"
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                    @click="logoutOtherBrowserSessions"
                                >
                                    Log Out
                                </PrimaryButton>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </teleport>
    </div>
</template>
