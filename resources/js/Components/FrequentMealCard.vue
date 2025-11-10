<script setup>
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    meal: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['edit']);

const showDeleteConfirm = ref(false);

const deleteMeal = () => {
    router.delete(route('frequent-meals.destroy', props.meal.id), {
        preserveScroll: true,
        onSuccess: () => {
            showDeleteConfirm.value = false;
        },
    });
};

const editMeal = () => {
    emit('edit', props.meal);
};
</script>

<template>
    <div class="bg-white rounded-2xl border border-gray-200 p-4 hover:shadow-md transition-shadow">
        <!-- Header -->
        <div class="flex items-start justify-between mb-3">
            <div class="flex-1">
                <h3 class="font-semibold text-gray-900 text-base">{{ meal.meal_name }}</h3>
                <p class="text-xs text-gray-500 mt-1">
                    Saved meal
                </p>
            </div>
            <div class="flex items-center gap-1">
                <!-- Edit Button -->
                <button
                    @click="editMeal"
                    class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                    aria-label="Edit meal"
                    title="Edit meal"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </button>

                <!-- Delete Button -->
                <button
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
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Delete Frequent Meal?</h3>
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
    </div>
</template>
