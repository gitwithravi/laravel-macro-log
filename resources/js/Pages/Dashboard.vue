<script setup>
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import TodaySummary from '@/Components/TodaySummary.vue';
import MealEntryCard from '@/Components/MealEntryCard.vue';
import FloatingActionButton from '@/Components/FloatingActionButton.vue';
import MealLogModal from '@/Components/MealLogModal.vue';

const props = defineProps({
    todayMeals: Array,
    todayTotals: Object,
    activeGoal: Object,
    todayDate: String,
    userTimezone: String,
});

const showMealModal = ref(false);

const openMealModal = () => {
    showMealModal.value = true;
};

const closeMealModal = () => {
    showMealModal.value = false;
};

const onMealLogged = () => {
    // Reload page to get updated data
    router.reload({ preserveScroll: true });
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        weekday: 'long',
        month: 'long',
        day: 'numeric',
        year: 'numeric'
    });
};
</script>

<template>
    <AppLayout title="Dashboard">
        <Head title="Dashboard" />

        <div class="min-h-screen bg-gray-50 pb-24">
            <!-- Header -->
            <div class="sticky top-0 z-10 bg-white border-b border-gray-200 shadow-sm">
                <div class="max-w-4xl mx-auto px-4 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                            <p class="text-sm text-gray-500 mt-0.5">{{ formatDate(todayDate) }}</p>
                        </div>
                        <button
                            @click="openMealModal"
                            class="sm:hidden p-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="max-w-4xl mx-auto px-4 py-6 space-y-6">
                <!-- Today's Summary -->
                <TodaySummary
                    :totals="todayTotals"
                    :active-goal="activeGoal"
                />

                <!-- Meals Section -->
                <div>
                    <!-- Section Header -->
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-gray-900">Today's Meals</h2>
                        <span class="text-sm text-gray-500">{{ todayMeals.length }} {{ todayMeals.length === 1 ? 'meal' : 'meals' }}</span>
                    </div>

                    <!-- Empty State -->
                    <div v-if="todayMeals.length === 0" class="bg-white rounded-2xl border border-gray-200 p-12 text-center">
                        <div class="w-20 h-20 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No meals logged yet</h3>
                        <p class="text-sm text-gray-600 mb-6">Start tracking your nutrition by logging your first meal</p>
                        <button
                            @click="openMealModal"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-medium rounded-xl transition-all shadow-md hover:shadow-lg"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Log Your First Meal
                        </button>
                    </div>

                    <!-- Meals List -->
                    <div v-else class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <MealEntryCard
                            v-for="meal in todayMeals"
                            :key="meal.id"
                            :meal="meal"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Floating Action Button -->
        <FloatingActionButton :on-click="openMealModal" />

        <!-- Meal Log Modal -->
        <MealLogModal
            :show="showMealModal"
            @close="closeMealModal"
            @success="onMealLogged"
        />
    </AppLayout>
</template>
