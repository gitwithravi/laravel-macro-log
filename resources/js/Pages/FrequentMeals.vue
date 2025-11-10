<script setup>
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import FrequentMealCard from '@/Components/FrequentMealCard.vue';
import FrequentMealFormModal from '@/Components/FrequentMealFormModal.vue';

const props = defineProps({
    frequentMeals: Array,
    count: Number,
    maxLimit: Number,
});

const showAddModal = ref(false);
const showEditModal = ref(false);
const editingMeal = ref(null);

const openAddModal = () => {
    showAddModal.value = true;
};

const closeAddModal = () => {
    showAddModal.value = false;
};

const openEditModal = (meal) => {
    editingMeal.value = meal;
    showEditModal.value = true;
};

const closeEditModal = () => {
    showEditModal.value = false;
    editingMeal.value = null;
};

const onMealSaved = () => {
    router.reload({ preserveScroll: true });
    closeAddModal();
    closeEditModal();
};

const canAddMore = props.count < props.maxLimit;
</script>

<template>
    <AppLayout title="Frequent Meals">
        <Head title="Frequent Meals" />

        <div class="min-h-screen bg-gray-50 pb-24">
            <!-- Header -->
            <div class="sticky top-0 z-10 bg-white border-b border-gray-200 shadow-sm">
                <div class="max-w-4xl mx-auto px-4 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Frequent Meals</h1>
                            <p class="text-sm text-gray-500 mt-0.5">{{ count }} / {{ maxLimit }} meals saved</p>
                        </div>
                        <button
                            @click="openAddModal"
                            :disabled="!canAddMore"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white p-2.5 rounded-full shadow-lg hover:shadow-xl transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="max-w-4xl mx-auto px-4 py-6 space-y-6">
                <!-- Empty State -->
                <div v-if="frequentMeals.length === 0" class="bg-white rounded-2xl border border-gray-200 p-12 text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No frequent meals yet</h3>
                    <p class="text-sm text-gray-600 mb-6">Save meals you eat regularly for quick logging</p>
                </div>

                <!-- Meals Grid -->
                <div v-else>
                    <div class="grid grid-cols-1 gap-4">
                        <FrequentMealCard
                            v-for="meal in frequentMeals"
                            :key="meal.id"
                            :meal="meal"
                            @edit="openEditModal"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Modal -->
        <FrequentMealFormModal
            v-if="showAddModal"
            :show="showAddModal"
            :mode="'create'"
            @close="closeAddModal"
            @saved="onMealSaved"
        />

        <!-- Edit Modal -->
        <FrequentMealFormModal
            v-if="showEditModal"
            :show="showEditModal"
            :mode="'edit'"
            :meal="editingMeal"
            @close="closeEditModal"
            @saved="onMealSaved"
        />
    </AppLayout>
</template>
