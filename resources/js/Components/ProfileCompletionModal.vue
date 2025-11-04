<script setup>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import DialogModal from './DialogModal.vue';
import InputLabel from './InputLabel.vue';
import TextInput from './TextInput.vue';
import InputError from './InputError.vue';
import PrimaryButton from './PrimaryButton.vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
});

const form = useForm({
    date_of_birth: '',
    gender: '',
    height: '',
    open_api_key: '',
});

const completeProfile = () => {
    form.post(route('profile.update-completion'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};

const isFormValid = computed(() => {
    return form.date_of_birth && form.gender && form.height && form.open_api_key;
});
</script>

<template>
    <DialogModal :show="show" :closeable="false" max-width="2xl">
        <template #title>
            Complete Your Profile
        </template>

        <template #content>
            <p class="text-sm text-gray-600 mb-4">
                To continue using the system, please complete your profile by providing the following information:
            </p>

            <form @submit.prevent="completeProfile" class="space-y-4">
                <!-- Date of Birth -->
                <div>
                    <InputLabel for="date_of_birth" value="Date of Birth" />
                    <TextInput
                        id="date_of_birth"
                        v-model="form.date_of_birth"
                        type="date"
                        class="mt-1 block w-full"
                        required
                        :max="new Date().toISOString().split('T')[0]"
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
                        required
                    >
                        <option value="" disabled>Select your gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                        <option value="prefer_not_to_say">Prefer not to say</option>
                    </select>
                    <InputError :message="form.errors.gender" class="mt-2" />
                </div>

                <!-- Height -->
                <div>
                    <InputLabel for="height" value="Height (in cm)" />
                    <TextInput
                        id="height"
                        v-model="form.height"
                        type="number"
                        class="mt-1 block w-full"
                        required
                        min="0"
                        max="300"
                        step="0.01"
                        placeholder="170.5"
                    />
                    <InputError :message="form.errors.height" class="mt-2" />
                </div>

                <!-- OpenAI API Key -->
                <div>
                    <InputLabel for="open_api_key" value="OpenAI API Key" />
                    <TextInput
                        id="open_api_key"
                        v-model="form.open_api_key"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        placeholder="sk-..."
                    />
                    <InputError :message="form.errors.open_api_key" class="mt-2" />
                    <p class="mt-1 text-xs text-gray-500">
                        You can get your API key from <a href="https://platform.openai.com/api-keys" target="_blank" class="text-indigo-600 hover:text-indigo-800">OpenAI's platform</a>
                    </p>
                </div>
            </form>
        </template>

        <template #footer>
            <PrimaryButton
                @click="completeProfile"
                :disabled="!isFormValid || form.processing"
                :class="{ 'opacity-25': !isFormValid || form.processing }"
            >
                {{ form.processing ? 'Saving...' : 'Complete Profile' }}
            </PrimaryButton>
        </template>
    </DialogModal>
</template>
