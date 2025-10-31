<script setup>
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import TextArea from '@/Components/TextArea.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import axios from 'axios';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['close', 'success']);

const form = useForm({
    raw_input: '',
});

const isLoading = ref(false);
const error = ref('');
const textareaRef = ref(null);

// Auto-focus textarea when modal opens
watch(() => props.show, (newValue) => {
    if (newValue) {
        form.reset();
        form.clearErrors();
        error.value = '';
        setTimeout(() => {
            textareaRef.value?.focus();
        }, 100);
    }
});

const submitMeal = async () => {
    if (!form.raw_input.trim()) {
        error.value = 'Please describe what you ate';
        return;
    }

    isLoading.value = true;
    error.value = '';

    try {
        await axios.post(route('meals.store'), {
            raw_input: form.raw_input,
        });

        // Success
        emit('success');
        emit('close');
    } catch (err) {
        if (err.response && err.response.data && err.response.data.error) {
            error.value = err.response.data.error;
        } else {
            error.value = 'Failed to log meal. Please try again.';
        }
    } finally {
        isLoading.value = false;
    }
};

const closeModal = () => {
    if (!isLoading.value) {
        emit('close');
    }
};
</script>

<template>
    <teleport to="body">
        <div
            v-if="show"
            class="fixed inset-0 z-50 overflow-y-auto"
            @click.self="closeModal"
        >
            <div class="flex items-end sm:items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="closeModal"></div>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white rounded-t-3xl sm:rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-5 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-white">Log Meal</h3>
                        </div>
                        <button
                            type="button"
                            @click="closeModal"
                            :disabled="isLoading"
                            class="text-white/80 hover:text-white transition-colors disabled:opacity-50"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <form @submit.prevent="submitMeal" class="px-6 py-6 space-y-5">
                        <div>
                            <InputLabel for="meal_input" value="Describe your meal" />
                            <TextArea
                                id="meal_input"
                                ref="textareaRef"
                                v-model="form.raw_input"
                                class="mt-2 block w-full"
                                rows="5"
                                placeholder="e.g., 2 Ragi roti with 3 boiled eggs"
                                :disabled="isLoading"
                                required
                            />
                            <p class="mt-2 text-xs text-gray-500">
                                Describe your meal in natural language. Be as specific as possible for accurate nutrition tracking.
                            </p>
                            <InputError :message="form.errors.raw_input" class="mt-2" />
                        </div>

                        <!-- Error Message -->
                        <div v-if="error" class="p-4 bg-red-50 border border-red-200 rounded-xl">
                            <div class="flex gap-3">
                                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                <p class="text-sm text-red-600">{{ error }}</p>
                            </div>
                        </div>

                        <!-- Loading State -->
                        <div v-if="isLoading" class="p-4 bg-indigo-50 border border-indigo-200 rounded-xl">
                            <div class="flex gap-3 items-center">
                                <svg class="animate-spin h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <p class="text-sm text-indigo-700 font-medium">Analyzing your meal...</p>
                            </div>
                        </div>

                        <!-- Footer Buttons -->
                        <div class="flex items-center gap-3 justify-end pt-2">
                            <SecondaryButton
                                type="button"
                                @click="closeModal"
                                :disabled="isLoading"
                            >
                                Cancel
                            </SecondaryButton>
                            <PrimaryButton
                                type="submit"
                                :disabled="isLoading || !form.raw_input.trim()"
                                :class="{ 'opacity-50 cursor-not-allowed': isLoading || !form.raw_input.trim() }"
                            >
                                <svg v-if="isLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ isLoading ? 'Logging...' : 'Log Meal' }}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </teleport>
</template>
