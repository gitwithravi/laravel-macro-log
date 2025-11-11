<script setup>
import { computed, ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import DayHistoryCard from '@/Components/DayHistoryCard.vue';
import HistorySummaryCard from '@/Components/HistorySummaryCard.vue';

const props = defineProps({
    historyData: Object,
    activeGoal: Object,
    startDate: String,
    endDate: String,
    filterType: {
        type: String,
        default: '7',
    },
    summaryData: {
        type: Object,
        required: true,
    },
});

// Filter state
const activeFilter = ref(props.filterType);
const showCustomInputs = ref(false);
const customStartDate = ref('');
const customEndDate = ref('');
const showFilterDropdown = ref(false);

// Toggle filter dropdown
const toggleFilterDropdown = () => {
    showFilterDropdown.value = !showFilterDropdown.value;
};

// Close dropdown when clicking outside
const closeDropdown = () => {
    showFilterDropdown.value = false;
    showCustomInputs.value = false;
};

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

// Filter methods
const applyPresetFilter = (days) => {
    activeFilter.value = days;
    showCustomInputs.value = false;
    showFilterDropdown.value = false;
    router.get(route('history'), { filter_type: days }, {
        preserveState: false,
        preserveScroll: true,
    });
};

const toggleCustomInputs = () => {
    activeFilter.value = 'custom';
    showCustomInputs.value = true;
};

const applyCustomFilter = () => {
    if (!customStartDate.value || !customEndDate.value) {
        alert('Please select both start and end dates');
        return;
    }

    if (new Date(customStartDate.value) > new Date(customEndDate.value)) {
        alert('Start date must be before end date');
        return;
    }

    showFilterDropdown.value = false;

    router.get(route('history'), {
        filter_type: 'custom',
        start_date: customStartDate.value,
        end_date: customEndDate.value,
    }, {
        preserveState: false,
        preserveScroll: true,
    });
};

const getFilterLabel = () => {
    if (activeFilter.value === '7') return 'Last 7 Days';
    if (activeFilter.value === '30') return 'Last 30 Days';
    if (activeFilter.value === '90') return 'Last 90 Days';
    if (activeFilter.value === 'custom') {
        const dayCount = historyDays.value.length;
        return `Custom Range (${dayCount} ${dayCount === 1 ? 'day' : 'days'})`;
    }
    return 'Last 7 Days';
};
</script>

<template>
    <AppLayout title="History">
        <Head title="History" />

        <div class="min-h-screen bg-gray-50 pb-24">
            <!-- Header -->
            <div class="sticky top-0 z-10 bg-white border-b border-gray-200 shadow-sm">
                <div class="max-w-4xl mx-auto px-4 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Meal History</h1>
                            <p class="text-sm text-gray-500 mt-0.5">{{ formatDateRange() }}</p>
                        </div>
                        <div class="flex items-center gap-3 relative">
                            <!-- Calendar Filter Icon -->
                            <button
                                @click="toggleFilterDropdown"
                                class="p-1.5 rounded-lg hover:bg-gray-100 transition-colors"
                                :class="showFilterDropdown ? 'bg-gray-100' : ''"
                            >
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </button>

                            <!-- Clock Icon -->
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>

                            <!-- Filter Dropdown -->
                            <div
                                v-if="showFilterDropdown"
                                @click.stop
                                class="absolute top-full right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-gray-200 p-4 space-y-3 z-20"
                            >
                                <!-- Backdrop to close on click outside -->
                                <div
                                    v-if="showFilterDropdown"
                                    @click="closeDropdown"
                                    class="fixed inset-0 z-10"
                                ></div>

                                <!-- Dropdown Content -->
                                <div class="relative z-20">
                                    <p class="text-xs font-semibold text-gray-700 mb-2 uppercase tracking-wide">Filter by Date</p>

                                    <!-- Preset Filter Buttons -->
                                    <div class="space-y-2">
                                        <button
                                            @click="applyPresetFilter('7')"
                                            :class="[
                                                'w-full px-4 py-2.5 rounded-lg text-sm font-medium transition-colors text-left',
                                                activeFilter === '7'
                                                    ? 'bg-indigo-600 text-white'
                                                    : 'bg-gray-50 text-gray-700 hover:bg-gray-100'
                                            ]"
                                        >
                                            Last 7 Days
                                        </button>
                                        <button
                                            @click="applyPresetFilter('30')"
                                            :class="[
                                                'w-full px-4 py-2.5 rounded-lg text-sm font-medium transition-colors text-left',
                                                activeFilter === '30'
                                                    ? 'bg-indigo-600 text-white'
                                                    : 'bg-gray-50 text-gray-700 hover:bg-gray-100'
                                            ]"
                                        >
                                            Last 30 Days
                                        </button>
                                        <button
                                            @click="applyPresetFilter('90')"
                                            :class="[
                                                'w-full px-4 py-2.5 rounded-lg text-sm font-medium transition-colors text-left',
                                                activeFilter === '90'
                                                    ? 'bg-indigo-600 text-white'
                                                    : 'bg-gray-50 text-gray-700 hover:bg-gray-100'
                                            ]"
                                        >
                                            Last 90 Days
                                        </button>
                                        <button
                                            @click="toggleCustomInputs"
                                            :class="[
                                                'w-full px-4 py-2.5 rounded-lg text-sm font-medium transition-colors text-left',
                                                activeFilter === 'custom'
                                                    ? 'bg-indigo-600 text-white'
                                                    : 'bg-gray-50 text-gray-700 hover:bg-gray-100'
                                            ]"
                                        >
                                            Custom Range
                                        </button>
                                    </div>

                                    <!-- Custom Date Inputs -->
                                    <div v-if="showCustomInputs" class="mt-3 pt-3 border-t border-gray-200 space-y-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1.5">Start Date</label>
                                            <input
                                                type="date"
                                                v-model="customStartDate"
                                                :max="new Date().toISOString().split('T')[0]"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                            />
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1.5">End Date</label>
                                            <input
                                                type="date"
                                                v-model="customEndDate"
                                                :max="new Date().toISOString().split('T')[0]"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                            />
                                        </div>
                                        <button
                                            @click="applyCustomFilter"
                                            class="w-full px-4 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors"
                                        >
                                            Apply Filter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="max-w-4xl mx-auto px-4 py-6 space-y-6">
                <!-- Empty State -->
                <div v-if="!hasData" class="bg-white rounded-2xl border border-gray-200 p-12 text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No meal history yet</h3>
                    <p class="text-sm text-gray-600 mb-6">Your meal history for the selected date range will appear here once you start logging meals</p>
                </div>

                <!-- History List -->
                <div v-else>
                    <!-- Summary Card -->
                    <HistorySummaryCard :summary-data="summaryData" class="mb-6" />

                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-gray-900">{{ getFilterLabel() }}</h2>
                        <span class="text-sm text-gray-500">{{ historyDays.length }} {{ historyDays.length === 1 ? 'day' : 'days' }}</span>
                    </div>

                    <div class="space-y-4">
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
        </div>
    </AppLayout>
</template>
