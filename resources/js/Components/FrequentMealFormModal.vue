<script setup>
import { ref, watch, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import TextArea from '@/Components/TextArea.vue';
import TextInput from '@/Components/TextInput.vue';
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
    mode: {
        type: String, // 'create' or 'edit'
        required: true,
        validator: (value) => ['create', 'edit'].includes(value),
    },
    meal: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits(['close', 'saved']);

// Form for create mode
const createForm = useForm({
    raw_input: '',
});

// Form for edit mode - initialize with meal data if in edit mode
const editForm = ref({
    meal_name: props.meal?.meal_name || '',
    calories: props.meal?.calories || 0,
    protein: props.meal?.protein || 0,
    carbs: props.meal?.carbs || 0,
    fat: props.meal?.fat || 0,
});

const isLoading = ref(false);
const error = ref('');
const textareaRef = ref(null);
const isOffline = ref(!navigator.onLine);

const isCreateMode = computed(() => props.mode === 'create');
const isEditMode = computed(() => props.mode === 'edit');

// Listen for online/offline events
if (typeof window !== 'undefined') {
    document.addEventListener('app:online', () => {
        isOffline.value = false;
    });

    document.addEventListener('app:offline', () => {
        isOffline.value = true;
    });
}

// Auto-focus when modal opens and populate edit form
watch(() => props.show, (newValue) => {
    if (newValue) {
        if (isCreateMode.value) {
            createForm.reset();
            createForm.clearErrors();
            error.value = '';
            isOffline.value = !navigator.onLine;
            setTimeout(() => {
                textareaRef.value?.focus();
            }, 100);
        } else if (isEditMode.value && props.meal) {
            // Populate edit form with meal data
            editForm.value = {
                meal_name: props.meal.meal_name,
                calories: props.meal.calories,
                protein: props.meal.protein,
                carbs: props.meal.carbs,
                fat: props.meal.fat,
            };
            error.value = '';
        }
    }
});

const submitCreate = async () => {
    if (!createForm.raw_input.trim()) {
        error.value = 'Please describe the meal';
        return;
    }

    if (isOffline.value) {
        error.value = 'Adding frequent meals requires internet connection for AI processing. Please connect to the internet and try again.';
        return;
    }

    isLoading.value = true;
    error.value = '';

    try {
        await axios.post(route('frequent-meals.store'), {
            raw_input: createForm.raw_input,
        });

        emit('saved');
    } catch (err) {
        if (err.response && err.response.data && err.response.data.errors) {
            const errors = err.response.data.errors;
            error.value = errors.raw_input ? errors.raw_input[0] : 'Failed to add frequent meal.';
        } else if (err.response && err.response.data && err.response.data.error) {
            error.value = err.response.data.error;
        } else {
            error.value = 'Failed to add frequent meal. Please try again.';
        }
    } finally {
        isLoading.value = false;
    }
};

const submitEdit = async () => {
    if (!editForm.value.meal_name.trim()) {
        error.value = 'Meal name is required';
        return;
    }

    isLoading.value = true;
    error.value = '';

    try {
        await axios.put(route('frequent-meals.update', props.meal.id), {
            meal_name: editForm.value.meal_name,
            calories: editForm.value.calories,
            protein: editForm.value.protein,
            carbs: editForm.value.carbs,
            fat: editForm.value.fat,
        });

        emit('saved');
    } catch (err) {
        if (err.response && err.response.data && err.response.data.errors) {
            const errors = err.response.data.errors;
            // Get first error message
            const firstError = Object.values(errors)[0];
            error.value = Array.isArray(firstError) ? firstError[0] : firstError;
        } else if (err.response && err.response.data && err.response.data.error) {
            error.value = err.response.data.error;
        } else {
            error.value = 'Failed to update frequent meal. Please try again.';
        }
    } finally {
        isLoading.value = false;
    }
};

const submitForm = () => {
    if (isCreateMode.value) {
        submitCreate();
    } else {
        submitEdit();
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
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-5 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                <svg v-if="isCreateMode" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                <svg v-else class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-white">
                                {{ isCreateMode ? 'Add Frequent Meal' : 'Edit Frequent Meal' }}
                            </h3>
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

                    <!-- Offline Warning Banner (Create mode only) -->
                    <div v-if="isOffline && isCreateMode" class="px-6 py-3 bg-orange-50 border-b border-orange-200">
                        <div class="flex items-center gap-2 text-orange-700">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm font-medium">You're offline. Adding frequent meals requires internet connection.</span>
                        </div>
                    </div>

                    <!-- Body -->
                    <form @submit.prevent="submitForm" class="px-6 py-6 space-y-5">
                        <!-- Create Mode: Meal Description -->
                        <div v-if="isCreateMode">
                            <InputLabel for="meal_input" value="Describe the meal" />
                            <TextArea
                                id="meal_input"
                                ref="textareaRef"
                                v-model="createForm.raw_input"
                                class="mt-2 block w-full"
                                rows="5"
                                placeholder="e.g., 2 Ragi roti with 3 boiled eggs"
                                :disabled="isLoading"
                                required
                            />
                            <p class="mt-2 text-xs text-gray-500">
                                Describe the meal in natural language. AI will calculate the nutrition values.
                            </p>
                            <InputError :message="createForm.errors.raw_input" class="mt-2" />
                        </div>

                        <!-- Edit Mode: Manual Input Fields -->
                        <div v-if="isEditMode" class="space-y-4">
                            <!-- Meal Name -->
                            <div>
                                <InputLabel for="meal_name" value="Meal Name" />
                                <TextInput
                                    id="meal_name"
                                    v-model="editForm.meal_name"
                                    type="text"
                                    class="mt-2 block w-full"
                                    placeholder="e.g., Breakfast Bowl"
                                    :disabled="isLoading"
                                    required
                                />
                            </div>

                            <!-- Nutrition Grid -->
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Calories -->
                                <div>
                                    <InputLabel for="calories" value="Calories" />
                                    <TextInput
                                        id="calories"
                                        v-model.number="editForm.calories"
                                        type="number"
                                        min="0"
                                        step="1"
                                        class="mt-2 block w-full"
                                        :disabled="isLoading"
                                        required
                                    />
                                </div>

                                <!-- Protein -->
                                <div>
                                    <InputLabel for="protein" value="Protein (g)" />
                                    <TextInput
                                        id="protein"
                                        v-model.number="editForm.protein"
                                        type="number"
                                        min="0"
                                        step="0.1"
                                        class="mt-2 block w-full"
                                        :disabled="isLoading"
                                        required
                                    />
                                </div>

                                <!-- Carbs -->
                                <div>
                                    <InputLabel for="carbs" value="Carbs (g)" />
                                    <TextInput
                                        id="carbs"
                                        v-model.number="editForm.carbs"
                                        type="number"
                                        min="0"
                                        step="0.1"
                                        class="mt-2 block w-full"
                                        :disabled="isLoading"
                                        required
                                    />
                                </div>

                                <!-- Fat -->
                                <div>
                                    <InputLabel for="fat" value="Fat (g)" />
                                    <TextInput
                                        id="fat"
                                        v-model.number="editForm.fat"
                                        type="number"
                                        min="0"
                                        step="0.1"
                                        class="mt-2 block w-full"
                                        :disabled="isLoading"
                                        required
                                    />
                                </div>
                            </div>
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

                        <!-- Loading State (Create mode only) -->
                        <div v-if="isLoading && isCreateMode" class="p-4 bg-blue-50 border border-blue-200 rounded-xl">
                            <div class="flex gap-3 items-center">
                                <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <p class="text-sm text-blue-700 font-medium">Analyzing meal...</p>
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
                                :disabled="isLoading"
                                :class="{ 'opacity-50 cursor-not-allowed': isLoading }"
                            >
                                <svg v-if="isLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ isLoading ? (isCreateMode ? 'Adding...' : 'Updating...') : (isCreateMode ? 'Add Meal' : 'Update Meal') }}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </teleport>
</template>
