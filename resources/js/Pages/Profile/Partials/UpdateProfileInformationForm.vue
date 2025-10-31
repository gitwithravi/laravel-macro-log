<script setup>
import { ref, computed } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    user: Object,
});

const form = useForm({
    _method: 'PUT',
    name: props.user.name,
    email: props.user.email,
    date_of_birth: props.user.date_of_birth,
    gender: props.user.gender,
    open_api_key: props.user.open_api_key,
    photo: null,
});

const verificationLinkSent = ref(null);
const photoPreview = ref(null);
const photoInput = ref(null);
const showApiKey = ref(false);

const maskedApiKey = computed(() => {
    if (!form.open_api_key) return '';
    if (showApiKey.value) return form.open_api_key;

    const key = form.open_api_key;
    if (key.length <= 8) return '***';

    // Show first 7 chars and last 4 chars (e.g., "sk-proj...xyz4")
    return `${key.substring(0, 7)}...${key.substring(key.length - 4)}`;
});

const toggleApiKeyVisibility = () => {
    showApiKey.value = !showApiKey.value;
};

const updateProfileInformation = () => {
    if (photoInput.value) {
        form.photo = photoInput.value.files[0];
    }

    form.post(route('user-profile-information.update'), {
        errorBag: 'updateProfileInformation',
        preserveScroll: true,
        onSuccess: () => clearPhotoFileInput(),
    });
};

const sendEmailVerification = () => {
    verificationLinkSent.value = true;
};

const selectNewPhoto = () => {
    photoInput.value.click();
};

const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];

    if (! photo) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };

    reader.readAsDataURL(photo);
};

const deletePhoto = () => {
    router.delete(route('current-user-photo.destroy'), {
        preserveScroll: true,
        onSuccess: () => {
            photoPreview.value = null;
            clearPhotoFileInput();
        },
    });
};

const clearPhotoFileInput = () => {
    if (photoInput.value?.value) {
        photoInput.value.value = null;
    }
};
</script>

<template>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200">
        <form @submit.prevent="updateProfileInformation">
            <!-- Header -->
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Profile Information</h3>
                <p class="mt-1 text-sm text-gray-600">Update your account's profile information and email address.</p>
            </div>

            <!-- Form Content -->
            <div class="px-4 py-5 sm:p-6 space-y-6">
                <!-- Profile Photo -->
                <div v-if="$page.props.jetstream.managesProfilePhotos">
                    <InputLabel value="Photo" />

                    <input
                        id="photo"
                        ref="photoInput"
                        type="file"
                        class="hidden"
                        @change="updatePhotoPreview"
                    >

                    <!-- Current Profile Photo -->
                    <div class="mt-3 flex items-center gap-4">
                        <div v-show="! photoPreview" class="flex-shrink-0">
                            <img :src="user.profile_photo_url" :alt="user.name" class="rounded-full size-20 object-cover ring-4 ring-gray-100">
                        </div>

                        <!-- New Profile Photo Preview -->
                        <div v-show="photoPreview" class="flex-shrink-0">
                            <span
                                class="block rounded-full size-20 bg-cover bg-no-repeat bg-center ring-4 ring-gray-100"
                                :style="'background-image: url(\'' + photoPreview + '\');'"
                            />
                        </div>

                        <div class="flex flex-col gap-2">
                            <SecondaryButton type="button" @click.prevent="selectNewPhoto" class="text-sm">
                                Select New Photo
                            </SecondaryButton>

                            <SecondaryButton
                                v-if="user.profile_photo_path"
                                type="button"
                                @click.prevent="deletePhoto"
                                class="text-sm"
                            >
                                Remove Photo
                            </SecondaryButton>
                        </div>
                    </div>

                    <InputError :message="form.errors.photo" class="mt-2" />
                </div>

                <!-- Name -->
                <div>
                    <InputLabel for="name" value="Name" />
                    <TextInput
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autocomplete="name"
                    />
                    <InputError :message="form.errors.name" class="mt-2" />
                </div>

                <!-- Email -->
                <div>
                    <InputLabel for="email" value="Email" />
                    <TextInput
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="mt-1 block w-full"
                        required
                        autocomplete="username"
                    />
                    <InputError :message="form.errors.email" class="mt-2" />

                    <div v-if="$page.props.jetstream.hasEmailVerification && user.email_verified_at === null">
                        <p class="text-sm mt-2 text-gray-600">
                            Your email address is unverified.

                            <Link
                                :href="route('verification.send')"
                                method="post"
                                as="button"
                                class="underline text-sm text-indigo-600 hover:text-indigo-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                @click.prevent="sendEmailVerification"
                            >
                                Click here to re-send the verification email.
                            </Link>
                        </p>

                        <div v-show="verificationLinkSent" class="mt-2 font-medium text-sm text-green-600">
                            A new verification link has been sent to your email address.
                        </div>
                    </div>
                </div>

                <!-- Date of Birth -->
                <div>
                    <InputLabel for="date_of_birth" value="Date of Birth" />
                    <TextInput
                        id="date_of_birth"
                        v-model="form.date_of_birth"
                        type="date"
                        class="mt-1 block w-full"
                        autocomplete="bday"
                    />
                    <InputError :message="form.errors.date_of_birth" class="mt-2" />
                </div>

                <!-- Gender -->
                <div>
                    <InputLabel for="gender" value="Gender" />
                    <select
                        id="gender"
                        v-model="form.gender"
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    >
                        <option :value="null">Select gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                        <option value="prefer_not_to_say">Prefer not to say</option>
                    </select>
                    <InputError :message="form.errors.gender" class="mt-2" />
                </div>

                <!-- OpenAI API Key -->
                <div>
                    <InputLabel for="open_api_key" value="OpenAI API Key" />
                    <div class="relative">
                        <!-- Masked Display (when key exists and not revealed) -->
                        <div
                            v-if="form.open_api_key && !showApiKey"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 font-mono text-sm text-gray-700 pr-24"
                        >
                            {{ maskedApiKey }}
                        </div>

                        <!-- Editable Input (when revealed or no key set) -->
                        <TextInput
                            v-else
                            id="open_api_key"
                            v-model="form.open_api_key"
                            type="text"
                            class="mt-1 block w-full pr-24"
                            autocomplete="off"
                            placeholder="sk-..."
                        />

                        <!-- Reveal/Hide Button -->
                        <button
                            v-if="form.open_api_key"
                            type="button"
                            @click="toggleApiKeyVisibility"
                            class="absolute right-2 top-1/2 -translate-y-1/2 mt-0.5 px-3 py-1 text-xs font-medium text-gray-600 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 rounded transition-colors"
                        >
                            {{ showApiKey ? 'Hide' : 'Reveal' }}
                        </button>
                    </div>
                    <InputError :message="form.errors.open_api_key" class="mt-2" />
                    <p class="mt-2 text-sm text-gray-500">
                        Your API key will be stored securely and never shared.{{ form.open_api_key ? ' Currently set.' : '' }}
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-4 py-4 sm:px-6 bg-gray-50 rounded-b-2xl flex items-center justify-between">
                <ActionMessage :on="form.recentlySuccessful" class="text-sm">
                    Saved.
                </ActionMessage>

                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Save
                </PrimaryButton>
            </div>
        </form>
    </div>
</template>
