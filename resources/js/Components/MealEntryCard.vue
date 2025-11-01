<script setup>
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
import InsightModal from './InsightModal.vue';

const props = defineProps({
    meal: {
        type: Object,
        required: true,
    },
    readonly: {
        type: Boolean,
        default: false,
    },
});

const showDeleteConfirm = ref(false);
const showInsightModal = ref(false);
const insightText = ref(null);
const insightLoading = ref(false);
const insightError = ref(null);

const deleteMeal = () => {
    router.delete(route('meals.destroy', props.meal.id), {
        preserveScroll: true,
        onSuccess: () => {
            showDeleteConfirm.value = false;
        },
    });
};

const formatTime = (timeString) => {
    if (!timeString) return '';
    try {
        const date = new Date(`1970-01-01T${timeString}`);
        return date.toLocaleTimeString('en-US', {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });
    } catch {
        return timeString;
    }
};

const fetchInsight = async () => {
    showInsightModal.value = true;
    insightLoading.value = true;
    insightError.value = null;
    insightText.value = null;

    try {
        const response = await fetch(route('meals.insight', props.meal.id), {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error || 'Failed to fetch insight');
        }

        insightText.value = data.insight;
    } catch (error) {
        insightError.value = error.message;
    } finally {
        insightLoading.value = false;
    }
};

const closeInsightModal = () => {
    showInsightModal.value = false;
};
</script>

<template>
    <div class="bg-white rounded-2xl border border-gray-200 p-4 hover:shadow-md transition-shadow">
        <!-- Header -->
        <div class="flex items-start justify-between mb-3">
            <div class="flex-1">
                <h3 class="font-semibold text-gray-900 text-base">{{ meal.meal_name }}</h3>
                <p class="text-xs text-gray-500 mt-1">
                    {{ formatTime(meal.logged_time) }}
                </p>
            </div>
            <div class="flex items-center gap-1">
                <!-- Insight Button -->
                <button
                    @click="fetchInsight"
                    class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                    aria-label="View meal insight"
                    title="View AI insight"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                </button>

                <!-- Delete Button -->
                <button
                    v-if="!readonly"
                    @click="showDeleteConfirm = true"
                    class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                    aria-label="Delete meal"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Nutrition Grid -->
        <div class="grid grid-cols-4 gap-2">
            <!-- Calories -->
            <div class="bg-orange-50 rounded-lg p-2 text-center border border-orange-100">
                <div class="text-xs text-orange-700 font-medium mb-1">Cal</div>
                <div class="text-sm font-bold text-orange-900">{{ meal.calories }}</div>
            </div>

            <!-- Protein -->
            <div class="bg-blue-50 rounded-lg p-2 text-center border border-blue-100">
                <div class="text-xs text-blue-700 font-medium mb-1">Pro</div>
                <div class="text-sm font-bold text-blue-900">{{ meal.protein }}g</div>
            </div>

            <!-- Carbs -->
            <div class="bg-emerald-50 rounded-lg p-2 text-center border border-emerald-100">
                <div class="text-xs text-emerald-700 font-medium mb-1">Carb</div>
                <div class="text-sm font-bold text-emerald-900">{{ meal.carbs }}g</div>
            </div>

            <!-- Fat -->
            <div class="bg-purple-50 rounded-lg p-2 text-center border border-purple-100">
                <div class="text-xs text-purple-700 font-medium mb-1">Fat</div>
                <div class="text-sm font-bold text-purple-900">{{ meal.fat }}g</div>
            </div>
        </div>

        <!-- Delete Confirmation -->
        <teleport to="body">
            <div
                v-if="showDeleteConfirm"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
                @click.self="showDeleteConfirm = false"
            >
                <div class="bg-white rounded-2xl p-6 max-w-sm w-full shadow-xl">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Delete Meal?</h3>
                    <p class="text-sm text-gray-600 mb-6">
                        Are you sure you want to delete "{{ meal.meal_name }}"? This action cannot be undone.
                    </p>
                    <div class="flex gap-3">
                        <button
                            @click="showDeleteConfirm = false"
                            class="flex-1 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors text-sm font-medium"
                        >
                            Cancel
                        </button>
                        <button
                            @click="deleteMeal"
                            class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors text-sm font-medium"
                        >
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </teleport>

        <!-- Insight Modal -->
        <InsightModal
            :show="showInsightModal"
            :meal-name="meal.meal_name"
            :insight="insightText"
            :loading="insightLoading"
            :error="insightError"
            @close="closeInsightModal"
        />
    </div>
</template>
