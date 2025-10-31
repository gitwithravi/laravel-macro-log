<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue';
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import TwoFactorAuthenticationForm from '@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue';

defineProps({
    confirmsTwoFactorAuthentication: Boolean,
    sessions: Array,
});
</script>

<template>
    <AppLayout title="Profile">
        <div class="min-h-screen bg-gray-50 pb-20">
            <!-- Header -->
            <div class="sticky top-0 z-10 bg-white border-b border-gray-200 shadow-sm">
                <div class="max-w-2xl mx-auto px-4 py-4">
                    <h1 class="text-2xl font-bold text-gray-900">Profile Settings</h1>
                </div>
            </div>

            <!-- Content -->
            <div class="max-w-2xl mx-auto px-4 py-6 space-y-4">
                <div v-if="$page.props.jetstream.canUpdateProfileInformation">
                    <UpdateProfileInformationForm :user="$page.props.auth.user" />
                </div>

                <div v-if="$page.props.jetstream.canUpdatePassword">
                    <UpdatePasswordForm />
                </div>

                <div v-if="$page.props.jetstream.canManageTwoFactorAuthentication">
                    <TwoFactorAuthenticationForm
                        :requires-confirmation="confirmsTwoFactorAuthentication"
                    />
                </div>

                <LogoutOtherBrowserSessionsForm :sessions="sessions" />

                <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
                    <DeleteUserForm />
                </template>
            </div>
        </div>
    </AppLayout>
</template>
