<script setup>
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import DayHistoryCard from '@/Components/DayHistoryCard.vue';

const props = defineProps({
    historyData: Object,
    activeGoal: Object,
    startDate: String,
    endDate: String,
});

// Convert historyData object to sorted array
const historyDays = computed(() => {
    return Object.entries(props.historyData)
        .map(([date, data]) => ({
            date,
            ...data,
        }))
        .sort((a, b) => new Date(b.date) - new Date(a.date));
});

const hasData = computed(() => historyDays.value.length > 0);

const formatDateRange = () => {
    const start = new Date(props.startDate);
    const end = new Date(props.endDate);

    const startFormatted = start.toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
    });

    const endFormatted = end.toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    });

    return `${startFormatted} - ${endFormatted}`;
};

const isToday = (dateString) => {
    const today = new Date();
    const date = new Date(dateString);
    return date.toDateString() === today.toDateString();
};

const isYesterday = (dateString) => {
    const yesterday = new Date();
    yesterday.setDate(yesterday.getDate() - 1);
    const date = new Date(dateString);
    return date.toDateString() === yesterday.toDateString();
};
</script>

<template>
    <AppLayout title="History">
        <Head title="History" />

        <div class="min-h-screen bg-gray-50 pb-24">
            <!-- Header -->
            <div class="sticky top-0 z-10 bg-white border-b border-gray-200 shadow-sm">
                <div class="max-w-2xl mx-auto px-4 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Meal History</h1>
                            <p class="text-sm text-gray-500 mt-0.5">{{ formatDateRange() }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="max-w-2xl mx-auto px-4 py-6 space-y-6">
                <!-- Empty State -->
                <div v-if="!hasData" class="bg-white rounded-2xl border border-gray-200 p-12 text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No meal history yet</h3>
                    <p class="text-sm text-gray-600 mb-6">Your meal history for the last 7 days will appear here once you start logging meals</p>
                </div>

                <!-- History List -->
                <div v-else class="space-y-4">
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-lg font-bold text-gray-900">Last 7 Days</h2>
                        <span class="text-sm text-gray-500">{{ historyDays.length }} {{ historyDays.length === 1 ? 'day' : 'days' }}</span>
                    </div>

                    <DayHistoryCard
                        v-for="day in historyDays"
                        :key="day.date"
                        :day-data="day"
                        :active-goal="activeGoal"
                        :is-today="isToday(day.date)"
                        :is-yesterday="isYesterday(day.date)"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
