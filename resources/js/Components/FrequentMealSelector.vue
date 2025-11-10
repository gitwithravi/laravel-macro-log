<script setup>
import { ref, computed } from 'vue';
import axios from 'axios';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

const emit = defineEmits(['select']);

const frequentMeals = ref([]);
const isLoading = ref(true);
const error = ref('');
const searchQuery = ref('');
const portionMultiplier = ref(1.0);
const selectedMeal = ref(null);

// Fetch frequent meals
const fetchFrequentMeals = async () => {
    isLoading.value = true;
    error.value = '';

    try {
        const response = await axios.get(route('frequent-meals.list'), {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        frequentMeals.value = response.data || [];
    } catch (err) {
        error.value = 'Failed to load frequent meals';
        console.error(err);
    } finally {
        isLoading.value = false;
    }
};

// Fetch on mount
fetchFrequentMeals();

// Filtered meals based on search query
const filteredMeals = computed(() => {
    if (!searchQuery.value.trim()) {
        return frequentMeals.value;
    }

    const query = searchQuery.value.toLowerCase();
    return frequentMeals.value.filter(meal =>
        meal.meal_name.toLowerCase().includes(query)
    );
});

// Select a meal
const selectMeal = (meal) => {
    selectedMeal.value = meal;
};

// Calculate nutrition based on portion multiplier
const calculateNutrition = (meal) => {
    const multiplier = portionMultiplier.value;
    return {
        calories: Math.round(meal.calories * multiplier),
        protein: (meal.protein * multiplier).toFixed(1),
        carbs: (meal.carbs * multiplier).toFixed(1),
        fat: (meal.fat * multiplier).toFixed(1),
    };
};

// Log the selected meal
const logSelectedMeal = () => {
    if (selectedMeal.value) {
        emit('select', {
            meal: selectedMeal.value,
            portionMultiplier: portionMultiplier.value,
        });
    }
};

// Quick portion buttons
const setQuickPortion = (value) => {
    portionMultiplier.value = value;
};
</script>

<template>
    <div class="space-y-4">
        <!-- Search -->
        <div>
            <InputLabel for="search" value="Search frequent meals" />
            <TextInput
                id="search"
                v-model="searchQuery"
                type="text"
                class="mt-2 block w-full"
                placeholder="Search by name..."
            />
        </div>

        <!-- Loading State -->
        <div v-if="isLoading" class="p-8 text-center">
            <svg class="animate-spin h-8 w-8 text-blue-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="mt-2 text-sm text-gray-600">Loading meals...</p>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="p-4 bg-red-50 border border-red-200 rounded-xl">
            <p class="text-sm text-red-600">{{ error }}</p>
        </div>

        <!-- Frequent Meals List -->
        <div v-else-if="filteredMeals.length > 0" class="space-y-2 max-h-64 overflow-y-auto">
            <button
                v-for="meal in filteredMeals"
                :key="meal.id"
                @click="selectMeal(meal)"
                :class="[
                    'w-full text-left p-3 rounded-lg border-2 transition-all',
                    selectedMeal?.id === meal.id
                        ? 'border-blue-500 bg-blue-50'
                        : 'border-gray-200 hover:border-blue-300 bg-white'
                ]"
            >
                <div class="font-medium text-gray-900">{{ meal.meal_name }}</div>
                <div class="mt-1 flex gap-3 text-xs text-gray-600">
                    <span>{{ meal.calories }} cal</span>
                    <span>{{ meal.protein }}g pro</span>
                    <span>{{ meal.carbs }}g carb</span>
                    <span>{{ meal.fat }}g fat</span>
                </div>
            </button>
        </div>

        <!-- Empty State -->
        <div v-else class="p-8 text-center bg-gray-50 rounded-lg">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="mt-2 text-sm text-gray-600">
                {{ searchQuery ? 'No meals found' : 'No frequent meals saved yet' }}
            </p>
            <p v-if="!searchQuery" class="mt-1 text-xs text-gray-500">
                Add frequent meals from the Frequent Meals page
            </p>
        </div>

        <!-- Portion Selector (shown when meal is selected) -->
        <div v-if="selectedMeal" class="space-y-3 p-4 bg-blue-50 rounded-lg border border-blue-200">
            <div>
                <InputLabel for="portion" value="Portion size" />
                <div class="mt-2 flex items-center gap-2">
                    <TextInput
                        id="portion"
                        v-model.number="portionMultiplier"
                        type="number"
                        min="0.1"
                        max="10"
                        step="0.1"
                        class="block w-24"
                    />
                    <span class="text-sm text-gray-600">x</span>
                </div>
            </div>

            <!-- Quick Portion Buttons -->
            <div class="flex gap-2">
                <button
                    @click="setQuickPortion(0.5)"
                    type="button"
                    class="px-3 py-1 text-xs bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
                >
                    0.5x
                </button>
                <button
                    @click="setQuickPortion(1.0)"
                    type="button"
                    class="px-3 py-1 text-xs bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
                >
                    1x
                </button>
                <button
                    @click="setQuickPortion(1.5)"
                    type="button"
                    class="px-3 py-1 text-xs bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
                >
                    1.5x
                </button>
                <button
                    @click="setQuickPortion(2.0)"
                    type="button"
                    class="px-3 py-1 text-xs bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
                >
                    2x
                </button>
            </div>

            <!-- Nutrition Preview -->
            <div class="grid grid-cols-4 gap-2 mt-3">
                <div class="bg-white rounded-lg p-2 text-center border border-orange-200">
                    <div class="text-xs text-orange-700 font-medium mb-1">Cal</div>
                    <div class="text-sm font-bold text-orange-900">{{ calculateNutrition(selectedMeal).calories }}</div>
                </div>
                <div class="bg-white rounded-lg p-2 text-center border border-blue-200">
                    <div class="text-xs text-blue-700 font-medium mb-1">Pro</div>
                    <div class="text-sm font-bold text-blue-900">{{ calculateNutrition(selectedMeal).protein }}g</div>
                </div>
                <div class="bg-white rounded-lg p-2 text-center border border-emerald-200">
                    <div class="text-xs text-emerald-700 font-medium mb-1">Carb</div>
                    <div class="text-sm font-bold text-emerald-900">{{ calculateNutrition(selectedMeal).carbs }}g</div>
                </div>
                <div class="bg-white rounded-lg p-2 text-center border border-purple-200">
                    <div class="text-xs text-purple-700 font-medium mb-1">Fat</div>
                    <div class="text-sm font-bold text-purple-900">{{ calculateNutrition(selectedMeal).fat }}g</div>
                </div>
            </div>

            <!-- Log Button -->
            <button
                @click="logSelectedMeal"
                type="button"
                class="w-full mt-3 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors"
            >
                Log This Meal
            </button>
        </div>
    </div>
</template>
