<script setup>
const props = defineProps({
    summaryData: {
        type: Object,
        required: true,
    },
});

const hasData = props.summaryData.daysWithData > 0;
</script>

<template>
    <div v-if="hasData" class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <!-- Header -->
        <div class="mb-4">
            <h3 class="text-lg font-bold text-gray-900">Average Daily Intake</h3>
            <p class="text-sm text-gray-500 mt-0.5">
                Based on {{ summaryData.daysWithData }} {{ summaryData.daysWithData === 1 ? 'day' : 'days' }} with data
            </p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Average Calories -->
            <div class="bg-gradient-to-br from-orange-50 to-orange-100/50 rounded-xl p-4 border border-orange-200/50">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                    </svg>
                    <span class="text-xs font-medium text-orange-700">Avg Calories</span>
                </div>
                <p class="text-2xl font-bold text-orange-900">{{ summaryData.averageCalories }}</p>
                <p class="text-xs text-orange-600 mt-0.5">kcal/day</p>
            </div>

            <!-- Average Protein -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100/50 rounded-xl p-4 border border-blue-200/50">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                    <span class="text-xs font-medium text-blue-700">Avg Protein</span>
                </div>
                <p class="text-2xl font-bold text-blue-900">{{ summaryData.averageProtein }}</p>
                <p class="text-xs text-blue-600 mt-0.5">g/day</p>
            </div>

            <!-- Average Carbs -->
            <div class="bg-gradient-to-br from-emerald-50 to-emerald-100/50 rounded-xl p-4 border border-emerald-200/50">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                    </svg>
                    <span class="text-xs font-medium text-emerald-700">Avg Carbs</span>
                </div>
                <p class="text-2xl font-bold text-emerald-900">{{ summaryData.averageCarbs }}</p>
                <p class="text-xs text-emerald-600 mt-0.5">g/day</p>
            </div>

            <!-- Average Fat -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100/50 rounded-xl p-4 border border-purple-200/50">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span class="text-xs font-medium text-purple-700">Avg Fat</span>
                </div>
                <p class="text-2xl font-bold text-purple-900">{{ summaryData.averageFat }}</p>
                <p class="text-xs text-purple-600 mt-0.5">g/day</p>
            </div>
        </div>
    </div>
</template>
