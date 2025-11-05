<script setup>
import { ref, computed } from 'vue';
import ProgressWidget from './ProgressWidget.vue';
import MealEntryCard from './MealEntryCard.vue';

const props = defineProps({
    dayData: {
        type: Object,
        required: true,
    },
    activeGoal: {
        type: Object,
        default: null,
    },
    isToday: {
        type: Boolean,
        default: false,
    },
    isYesterday: {
        type: Boolean,
        default: false,
    },
});

const isExpanded = ref(false);

const toggleExpand = () => {
    isExpanded.value = !isExpanded.value;
};

const formatDate = computed(() => {
    if (props.isToday) return 'Today';
    if (props.isYesterday) return 'Yesterday';

    try {
        const date = new Date(props.dayData.date);
        return date.toLocaleDateString('en-US', {
            weekday: 'short',
            month: 'short',
            day: 'numeric'
        });
    } catch {
        return props.dayData.date;
    }
});

const hasGoal = computed(() => {
    return props.activeGoal &&
           props.activeGoal.daily_goal_calories !== null &&
           props.activeGoal.daily_goal_calories !== undefined;
});

// Icons for the progress widgets
const icons = {
    calories: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm5.14 5.86c-1.95-1.96-5.33-1.96-7.28 0C8.68 9.04 8 10.5 8 12.14c0 1.65.68 3.11 1.86 4.28l.71-.71c-1-1-1.57-2.36-1.57-3.57 0-1.21.57-2.57 1.57-3.57 1.56-1.56 4.28-1.56 5.85 0 1 1 1.57 2.36 1.57 3.57 0 1.21-.57 2.57-1.57 3.57l.71.71C18.32 15.25 19 13.79 19 12.14c0-1.64-.68-3.1-1.86-4.28z"/></svg>',
    protein: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M19.48,12.35c-1.57-4.08-7.16-4.3-5.81-10.23c0.1-0.44-0.37-0.78-0.75-0.55C9.29,3.71,6.68,8,8.87,13.62 c0.18,0.46-0.36,0.89-0.75,0.59c-1.81-1.37-2-3.34-1.84-4.75c0.06-0.52-0.62-0.77-0.91-0.34C4.69,10.16,4,11.84,4,14.37 c0.38,5.6,5.11,7.32,6.81,7.54c2.43,0.31,5.06-0.14,6.95-1.87C19.84,18.11,20.6,15.03,19.48,12.35z M10.2,17.38 c1.44-0.35,2.18-1.39,2.38-2.31c0.33-1.43-0.96-2.83-0.09-5.09c0.33,1.87,3.27,3.04,3.27,5.08C15.84,17.59,13.1,19.76,10.2,17.38z"/></svg>',
    carbs: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M2,21.5c0,0.28,0.22,0.5,0.5,0.5h19c0.28,0,0.5-0.22,0.5-0.5V20H2V21.5z M12,2c-1.1,0-2,0.9-2,2s0.9,2,2,2s2-0.9,2-2 S13.1,2,12,2z M21.5,9H2.5C1.67,9,1,9.67,1,10.5V19h22v-8.5C23,9.67,22.33,9,21.5,9z M12,13c-1.66,0-3-1.34-3-3h6 C15,11.66,13.66,13,12,13z"/></svg>',
    fat: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12,3L2,12h3v8h14v-8h3L12,3z M12,16.5c-1.93,0-3.5-1.57-3.5-3.5c0-1.93,1.57-3.5,3.5-3.5s3.5,1.57,3.5,3.5 C15.5,14.93,13.93,16.5,12,16.5z M12,11c-1.1,0-2,0.9-2,2s0.9,2,2,2s2-0.9,2-2S13.1,11,12,11z"/></svg>',
};
</script>

<template>
    <div
        @click="toggleExpand"
        class="bg-white rounded-2xl border border-gray-200 p-4 hover:shadow-md transition-all duration-300 cursor-pointer"
        :class="{ 'shadow-md': isExpanded }"
    >
        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-semibold text-gray-900 text-lg">{{ formatDate }}</h3>
                <p class="text-xs text-gray-500 mt-0.5">
                    {{ dayData.meals.length }} {{ dayData.meals.length === 1 ? 'meal' : 'meals' }} logged
                </p>
            </div>
            <div class="flex items-center gap-2">
                <!-- Chevron Icon -->
                <svg
                    class="w-5 h-5 text-gray-400 transition-transform duration-300"
                    :class="{ 'rotate-180': isExpanded }"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>

        <!-- Progress Widgets Grid (Collapsed View) -->
        <div v-if="hasGoal" class="grid grid-cols-1 lg:grid-cols-4 gap-3 mb-4">
            <ProgressWidget
                label="Calories"
                :consumed="dayData.totals.calories"
                :goal="activeGoal.daily_goal_calories"
                unit=""
                color="orange"
                :icon="icons.calories"
            />
            <ProgressWidget
                label="Protein"
                :consumed="dayData.totals.protein"
                :goal="activeGoal.daily_goal_protein"
                unit="g"
                color="blue"
                :icon="icons.protein"
            />
            <ProgressWidget
                label="Carbs"
                :consumed="dayData.totals.carbs"
                :goal="activeGoal.daily_goal_carb"
                unit="g"
                color="emerald"
                :icon="icons.carbs"
            />
            <ProgressWidget
                label="Fat"
                :consumed="dayData.totals.fat"
                :goal="activeGoal.daily_goal_fat"
                unit="g"
                color="purple"
                :icon="icons.fat"
            />
        </div>

        <!-- No Goal State - Show Totals Only -->
        <div v-else class="grid grid-cols-4 gap-2 mb-4">
            <div class="bg-orange-50 rounded-lg p-3 text-center border border-orange-100">
                <div class="text-xs text-orange-700 font-medium mb-1">Calories</div>
                <div class="text-lg font-bold text-orange-900">{{ dayData.totals.calories }}</div>
            </div>
            <div class="bg-blue-50 rounded-lg p-3 text-center border border-blue-100">
                <div class="text-xs text-blue-700 font-medium mb-1">Protein</div>
                <div class="text-lg font-bold text-blue-900">{{ dayData.totals.protein }}g</div>
            </div>
            <div class="bg-emerald-50 rounded-lg p-3 text-center border border-emerald-100">
                <div class="text-xs text-emerald-700 font-medium mb-1">Carbs</div>
                <div class="text-lg font-bold text-emerald-900">{{ dayData.totals.carbs }}g</div>
            </div>
            <div class="bg-purple-50 rounded-lg p-3 text-center border border-purple-100">
                <div class="text-xs text-purple-700 font-medium mb-1">Fat</div>
                <div class="text-lg font-bold text-purple-900">{{ dayData.totals.fat }}g</div>
            </div>
        </div>

        <!-- Expanded View - Meal List -->
        <transition
            enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="opacity-0 max-h-0"
            enter-to-class="opacity-100 max-h-[2000px]"
            leave-active-class="transition-all duration-300 ease-in"
            leave-from-class="opacity-100 max-h-[2000px]"
            leave-to-class="opacity-0 max-h-0"
        >
            <div v-if="isExpanded" class="space-y-3 overflow-hidden" @click.stop>
                <div class="border-t border-gray-200 pt-4 mb-3">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Meal Details</h4>
                </div>
                <MealEntryCard
                    v-for="meal in dayData.meals"
                    :key="meal.id"
                    :meal="meal"
                    :readonly="true"
                />
            </div>
        </transition>
    </div>
</template>
