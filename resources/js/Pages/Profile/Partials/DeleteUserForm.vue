<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    setTimeout(() => passwordInput.value.focus(), 250);
};

const deleteUser = () => {
    form.delete(route('current-user.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.reset();
};
</script>

<template>
    <div class="bg-white rounded-2xl shadow-sm border border-red-200">
        <!-- Header -->
        <div class="px-4 py-5 sm:px-6 border-b border-red-200">
            <h3 class="text-lg font-semibold text-red-900">Delete Account</h3>
            <p class="mt-1 text-sm text-red-700">Permanently delete your account.</p>
        </div>

        <!-- Content -->
        <div class="px-4 py-5 sm:p-6">
            <p class="text-sm text-gray-600 mb-4">
                Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
            </p>

            <DangerButton @click="confirmUserDeletion">
                Delete Account
            </DangerButton>
        </div>

        <!-- Delete Account Confirmation Modal -->
        <teleport to="body">
            <div v-if="confirmingUserDeletion" class="fixed inset-0 z-50 overflow-y-auto" @click.self="closeModal">
                <div class="flex min-h-screen items-end justify-center sm:items-center sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                    <div class="relative bg-white w-full sm:max-w-md sm:rounded-2xl rounded-t-2xl shadow-xl transform transition-all p-6">
                        <div class="text-center mb-6">
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Delete Account</h3>
                            <p class="text-sm text-gray-600 mb-4">
                                Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
                            </p>

                            <div class="mb-6">
                                <TextInput
                                    ref="passwordInput"
                                    v-model="form.password"
                                    type="password"
                                    class="mt-1 block w-full"
                                    placeholder="Password"
                                    autocomplete="current-password"
                                    @keyup.enter="deleteUser"
                                />

                                <InputError :message="form.errors.password" class="mt-2" />
                            </div>

                            <div class="flex gap-3">
                                <SecondaryButton @click="closeModal" class="flex-1">
                                    Cancel
                                </SecondaryButton>
                                <DangerButton
                                    class="flex-1"
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                    @click="deleteUser"
                                >
                                    Delete Account
                                </DangerButton>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </teleport>
    </div>
</template>
